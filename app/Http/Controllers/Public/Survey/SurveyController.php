<?php

namespace App\Http\Controllers\Public\Survey;

use App\Actions\Survey\CreateSurveyResponse;
use App\Actions\Survey\SubmitSurveyResponse;
use App\Http\Controllers\Controller;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class SurveyController extends Controller
{
    public function index(): Response
    {
        $surveys = Survey::query()
            ->where('status', 'published')
            ->where(function ($query) {
                $query
                    ->whereNull('starts_at')
                    ->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query
                    ->whereNull('ends_at')
                    ->orWhere('ends_at', '>=', now());
            })
            ->withCount([
                'questions',
                'responses',
            ])
            ->orderByDesc('starts_at')
            ->orderByDesc('created_at')
            ->get([
                'id',
                'title',
                'slug',
                'description',
                'starts_at',
                'ends_at',
                'created_at',
            ]);

        return Inertia::render('surveys/Index', [
            'surveys' => $surveys,

            'breadcrumbs' => [
                [
                    'label' => 'Home',
                    'href' => '/',
                ],
                [
                    'label' => 'Surveys',
                ],
            ],
        ]);
    }

    public function show(
        Request $request,
        Survey $survey,
    ): Response {
        abort_unless($this->isLive($survey), 404);

        $survey->load([
            'sections.questions.options',
            'questions' => fn($query) => $query
                ->whereNull('section_id')
                ->orderBy('position'),
            'questions.options',
        ]);

        return Inertia::render('surveys/Show', [
            'survey' => $this->surveyPayload($survey),

            'isOpen' => $survey->isOpen(),

            'hasResponded' => $this->hasResponded(
                $request,
                $survey
            ),
        ]);
    }

    public function submit(
        Request $request,
        Survey $survey,
    ): RedirectResponse {
        if (! $this->isLive($survey)) {
            return back()->withErrors([
                'survey' => 'This survey is no longer accepting responses.',
            ]);
        }

        if (
            ! $survey->anonymous &&
            ! $request->user()
        ) {
            return redirect()->route('login');
        }

        if (
            ! $survey->multiple_responses &&
            $this->hasResponded($request, $survey)
        ) {
            return redirect()->route('surveys.thank-you', [
                'survey' => $survey->slug,
            ]);
        }

        $survey->load([
            'questions.options',
            'sections.questions.options',
        ]);

        $questions = $survey->questions
            ->merge(
                $survey->sections->flatMap(
                    fn($section) => $section->questions
                )
            )
            ->unique('id')
            ->values();

        $answers = $request->input('answers', []);

        if (! is_array($answers)) {
            return back()->withErrors([
                'answers' => 'Invalid survey response.',
            ]);
        }

        $errors = [];

        foreach ($questions as $question) {
            $questionId = $question->id;

            $answer = $answers[$questionId] ?? null;

            /*
        |--------------------------------------------------------------------------
        | Required validation
        |--------------------------------------------------------------------------
        */

            if (
                $question->required &&
                $this->isEmptyAnswer($answer)
            ) {
                $errors["answers.$questionId"] =
                    'This question is required.';

                continue;
            }

            if ($this->isEmptyAnswer($answer)) {
                continue;
            }

            /*
        |--------------------------------------------------------------------------
        | Type validation
        |--------------------------------------------------------------------------
        */

            switch ($question->type->value) {
                case 'short_text':
                case 'long_text':

                    if (! is_string($answer)) {
                        $errors["answers.$questionId"] =
                            'Please provide a valid text answer.';
                    }

                    break;

                case 'single_choice':

                    if (! is_numeric($answer)) {
                        $errors["answers.$questionId"] =
                            'Please select a valid option.';

                        break;
                    }

                    $optionExists = $question->options
                        ->contains(
                            fn($option) =>
                            $option->id === (int) $answer
                        );

                    if (! $optionExists) {
                        $errors["answers.$questionId"] =
                            'The selected option is invalid.';
                    }

                    break;

                case 'multiple_choice':

                    if (! is_array($answer)) {
                        $errors["answers.$questionId"] =
                            'Please select valid options.';

                        break;
                    }

                    $validOptionIds = $question->options
                        ->pluck('id')
                        ->map(fn($id) => (int) $id)
                        ->all();

                    foreach ($answer as $optionId) {
                        if (
                            ! is_numeric($optionId) ||
                            ! in_array(
                                (int) $optionId,
                                $validOptionIds,
                                true
                            )
                        ) {
                            $errors["answers.$questionId"] =
                                'One or more selected options are invalid.';

                            break;
                        }
                    }

                    break;

                case 'yes_no':

                    if (
                        ! is_bool($answer) &&
                        ! in_array($answer, [0, 1, '0', '1'], true)
                    ) {
                        $errors["answers.$questionId"] =
                            'Please select Yes or No.';
                    }

                    break;

                case 'number':

                    if (! is_numeric($answer)) {
                        $errors["answers.$questionId"] =
                            'Please enter a valid number.';

                        break;
                    }

                    $this->validateNumericRange(
                        $question,
                        $answer,
                        $errors
                    );

                    break;

                case 'rating':
                case 'scale':

                    if (! is_numeric($answer)) {
                        $errors["answers.$questionId"] =
                            'Please select a valid value.';

                        break;
                    }

                    $this->validateNumericRange(
                        $question,
                        $answer,
                        $errors
                    );

                    break;

                case 'date':

                    if (
                        ! is_string($answer) ||
                        ! $this->isValidDate($answer)
                    ) {
                        $errors["answers.$questionId"] =
                            'Please provide a valid date.';
                    }

                    break;

                default:

                    $errors["answers.$questionId"] =
                        'Unsupported question type.';
            }
        }

        if ($errors !== []) {
            return back()
                ->withInput()
                ->withErrors($errors);
        }

        /*
    |--------------------------------------------------------------------------
    | Store response
    |--------------------------------------------------------------------------
    */

        $response = DB::transaction(function () use (
            $request,
            $survey,
            $questions,
            $answers,
        ) {
            $response = app(CreateSurveyResponse::class)->handle([
                'survey_id' => $survey->id,
                'user_id' => $request->user()?->id,
                'respondent_name' => $request->user()?->name,
                'respondent_email' => $request->user()?->email,
                'respondent_ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'started_at' => now(),
                'status' => SurveyResponseStatus::InProgress,
            ]);

            foreach ($questions as $question) {
                $questionId = $question->id;

                if (! array_key_exists($questionId, $answers)) {
                    continue;
                }

                $answer = $answers[$questionId];

                if ($this->isEmptyAnswer($answer)) {
                    continue;
                }

                $payload = $this->buildAnswerPayload(
                    $question,
                    $answer,
                );

                app(CreateSurveyAnswer::class)->handle([
                    'response_id' => $response->id,
                    'question_id' => $questionId,
                    ...$payload,
                ]);
            }

            app(SubmitSurveyResponse::class)->handle($response);

            $survey->increment('response_count');

            return $response;
        });

        if (! $survey->multiple_responses) {
            session()->put(
                "survey.responded.{$survey->id}",
                true,
            );
        }

        return redirect()->route('surveys.thank-you', [
            'survey' => $survey->slug,
        ]);
    }

    private function validateAndNormalizeAnswers(
        Request $request,
        Survey $survey,
    ): array {
        $answers = [];

        foreach ($survey->questions as $question) {
            $value = $request->input(
                "answers.{$question->id}"
            );

            $filled = $this->isFilled($value);

            /*
             * Required question.
             */
            if (
                $question->required &&
                ! $filled
            ) {
                abort(
                    422,
                    "Question {$question->id} is required."
                );
            }

            if (! $filled) {
                continue;
            }

            $answers[] = $this->normalizeAnswer(
                $question,
                $value
            );
        }

        return $answers;
    }

    private function normalizeAnswer(
        SurveyQuestion $question,
        mixed $value,
    ): array {
        return match ($question->type->value) {
            'short_text',
            'long_text' => [
                'question_id' => $question->id,
                'option_id' => null,
                'answer_text' => mb_substr(
                    (string) $value,
                    0,
                    5000
                ),
            ],

            'single_choice' => $this->singleChoiceAnswer(
                $question,
                $value
            ),

            'multiple_choice' => $this->multipleChoiceAnswer(
                $question,
                $value
            ),

            'yes_no' => $this->booleanAnswer(
                $question,
                $value
            ),

            'number' => $this->numberAnswer(
                $question,
                $value
            ),

            'rating',
            'scale' => $this->numericAnswer(
                $question,
                $value
            ),

            'date' => $this->dateAnswer(
                $question,
                $value
            ),

            default => abort(
                422,
                'Unsupported survey question type.'
            ),
        };
    }

    private function singleChoiceAnswer(
        SurveyQuestion $question,
        mixed $value,
    ): array {
        $optionId = (int) $value;

        $valid = $question->options
            ->contains('id', $optionId);

        if (! $valid) {
            abort(
                422,
                'Invalid option selected.'
            );
        }

        return [
            'question_id' => $question->id,
            'option_id' => $optionId,
            'answer_text' => null,
        ];
    }

    private function multipleChoiceAnswer(
        SurveyQuestion $question,
        mixed $value,
    ): array {
        if (! is_array($value)) {
            abort(
                422,
                'Invalid multiple-choice answer.'
            );
        }

        $optionIds = collect($value)
            ->map(fn($id) => (int) $id)
            ->unique()
            ->values();

        $validOptionIds = $question->options
            ->pluck('id');

        if (
            $optionIds->diff($validOptionIds)->isNotEmpty()
        ) {
            abort(
                422,
                'One or more selected options are invalid.'
            );
        }

        return [
            'question_id' => $question->id,
            'option_id' => null,
            'answer_json' => $optionIds->values()->all(),
        ];
    }

    private function booleanAnswer(
        SurveyQuestion $question,
        mixed $value,
    ): array {
        if (
            ! is_bool($value) &&
            ! in_array($value, [
                true,
                false,
                'true',
                'false',
                '1',
                '0',
                1,
                0,
            ], true)
        ) {
            abort(
                422,
                'Invalid yes/no answer.'
            );
        }

        return [
            'question_id' => $question->id,
            'option_id' => null,
            'answer_boolean' => filter_var(
                $value,
                FILTER_VALIDATE_BOOLEAN
            ),
        ];
    }

    private function numberAnswer(
        SurveyQuestion $question,
        mixed $value,
    ): array {
        if (! is_numeric($value)) {
            abort(
                422,
                'Invalid number.'
            );
        }

        return [
            'question_id' => $question->id,
            'option_id' => null,
            'answer_number' => $value,
        ];
    }

    private function numericAnswer(
        SurveyQuestion $question,
        mixed $value,
    ): array {
        if (! is_numeric($value)) {
            abort(
                422,
                'Invalid numeric answer.'
            );
        }

        $number = (float) $value;

        $min = (float) (
            $question->settings['min'] ?? 1
        );

        $max = (float) (
            $question->settings['max'] ??
            (
                $question->type->value === 'rating'
                ? 5
                : 10
            )
        );

        if (
            $number < $min ||
            $number > $max
        ) {
            abort(
                422,
                "Answer must be between {$min} and {$max}."
            );
        }

        return [
            'question_id' => $question->id,
            'option_id' => null,
            'answer_number' => $number,
        ];
    }

    private function isEmptyAnswer(mixed $answer): bool
    {
        if (is_array($answer)) {
            return count($answer) === 0;
        }

        return $answer === null ||
            $answer === '';
    }
    private function validateNumericRange(
        SurveyQuestion $question,
        mixed $answer,
        array &$errors,
    ): void {
        $value = (float) $answer;

        $min = $question->settings['min'] ?? null;
        $max = $question->settings['max'] ?? null;

        if ($min !== null && $value < (float) $min) {
            $errors["answers.{$question->id}"] =
                "The value must be at least {$min}.";

            return;
        }

        if ($max !== null && $value > (float) $max) {
            $errors["answers.{$question->id}"] =
                "The value must not exceed {$max}.";
        }
    }

    private function isValidDate(string $date): bool
    {
        $parsed = \DateTime::createFromFormat(
            'Y-m-d',
            $date,
        );

        return $parsed !== false &&
            $parsed->format('Y-m-d') === $date;
    }

    private function buildAnswerPayload(
        SurveyQuestion $question,
        mixed $answer,
    ): array {
        return match ($question->type->value) {
            'short_text',
            'long_text',
            'date' => [
                'answer_text' => (string) $answer,
            ],

            'single_choice' => [
                'option_id' => (int) $answer,
            ],

            'multiple_choice' => [
                'answer_json' => collect($answer)
                    ->map(fn($id) => (int) $id)
                    ->values()
                    ->all(),
            ],

            'yes_no' => [
                'answer_boolean' => filter_var(
                    $answer,
                    FILTER_VALIDATE_BOOLEAN,
                ),
            ],

            'number',
            'rating',
            'scale' => [
                'answer_number' => $answer,
            ],

            default => [
                'answer_text' => (string) $answer,
            ],
        };
    }

    private function dateAnswer(
        SurveyQuestion $question,
        mixed $value,
    ): array {
        if (
            ! is_string($value) ||
            ! preg_match(
                '/^\d{4}-\d{2}-\d{2}$/',
                $value
            )
        ) {
            abort(
                422,
                'Invalid date.'
            );
        }

        return [
            'question_id' => $question->id,
            'option_id' => null,
            'answer_text' => $value,
        ];
    }

    private function isFilled(mixed $value): bool
    {
        if (is_array($value)) {
            return count($value) > 0;
        }

        return $value !== null &&
            $value !== '';
    }

    private function isLive(Survey $survey): bool
    {
        if ($survey->status !== SurveyStatus::Published) {
            return false;
        }

        if (
            $survey->starts_at &&
            $survey->starts_at->isFuture()
        ) {
            return false;
        }

        if (
            $survey->ends_at &&
            $survey->ends_at->isPast()
        ) {
            return false;
        }

        return true;
    }

    private function hasResponded(
        Request $request,
        Survey $survey,
    ): bool {
        if ($request->user()) {
            return $survey->responses()
                ->where('user_id', $request->user()->id)
                ->where('status', SurveyResponseStatus::Submitted)
                ->exists();
        }

        return session()->boolean(
            "survey.responded.{$survey->id}"
        );
    }

    private function surveyPayload(
        Survey $survey,
    ): array {
        return [
            'id' => $survey->id,
            'title' => $survey->title,
            'slug' => $survey->slug,
            'description' => $survey->description,

            'anonymous' => $survey->anonymous,

            'multiple_responses' => $survey->multiple_responses,

            'starts_at' => $survey->starts_at?->toISOString(),

            'ends_at' => $survey->ends_at?->toISOString(),

            'sections' => $survey->sections
                ->map(fn($section) => [
                    'id' => $section->id,
                    'title' => $section->title,
                    'description' => $section->description,
                    'position' => $section->position,

                    'questions' => $section->questions
                        ->map(
                            fn($question) =>
                            $this->questionPayload($question)
                        )
                        ->values()
                        ->all(),
                ])
                ->values()
                ->all(),

            'unsectioned_questions' => $survey->questions
                ->map(
                    fn($question) =>
                    $this->questionPayload($question)
                )
                ->values()
                ->all(),
        ];
    }

    private function questionPayload(
        SurveyQuestion $question,
    ): array {
        return [
            'id' => $question->id,

            'question' => $question->question,

            'description' => $question->description,

            'type' => [
                'value' => $question->type->value,
                'label' => $question->type->label(),
            ],

            'required' => $question->required,

            'position' => $question->position,

            'settings' => $question->settings,

            'options' => $question->options
                ->map(fn($option) => [
                    'id' => $option->id,
                    'label' => $option->label,
                    'value' => $option->value,
                    'position' => $option->position,
                    'is_other' => $option->is_other,
                ])
                ->values()
                ->all(),
        ];
    }

    public function thankYou(
        Survey $survey,
    ): Response {
        return Inertia::render('surveys/ThankYou', [
            'survey' => [
                'id' => $survey->id,
                'title' => $survey->title,
            ],
        ]);
    }
}
