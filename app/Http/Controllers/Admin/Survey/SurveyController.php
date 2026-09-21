<?php

namespace App\Http\Controllers\Admin\Survey;

use App\Actions\Survey\CreateSurvey;
use App\Actions\Survey\DeleteSurvey;
use App\Actions\Survey\UpdateSurvey;
use App\Enums\Survey\SurveyResponseStatus;
use App\Enums\Survey\SurveyStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Survey\StoreSurveyRequest;
use App\Http\Requests\Survey\UpdateSurveyRequest;
use App\Http\Resources\Admin\Survey\SurveyResponseResource;
use App\Http\Resources\Survey\SurveyResource;
use App\Models\Research\Research;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyAnswer;
use App\Models\Survey\SurveyQuestion;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SurveyController extends Controller
{
    public function index(
        Request $request,
    ): Response {
        $query = Survey::query()
            ->with('research');

        if ($request->filled('search')) {
            $search = $request->string('search');

            $query->where(function ($query) use ($search) {
                $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->string('status')
            );
        }

        if ($request->filled('research_id')) {
            $query->where(
                'research_id',
                $request->integer('research_id')
            );
        }

        $surveys = $query
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return Inertia::render(
            'admin/survey/Index',
            [
                'surveys' => SurveyResource::collection($surveys),

                'filters' => [
                    'search' => $request->input('search'),
                    'status' => $request->input('status'),
                    'research_id' => $request->input('research_id'),
                ],

                'statusOptions' => SurveyStatus::dropdown(),

                'stats' => [
                    'total' => Survey::count(),

                    'published' => Survey::where(
                        'status',
                        SurveyStatus::Published
                    )->count(),

                    'draft' => Survey::where(
                        'status',
                        SurveyStatus::Draft
                    )->count(),

                    'closed' => Survey::where(
                        'status',
                        SurveyStatus::Closed
                    )->count(),
                ],
            ]
        );
    }

    public function create(): Response
    {
        return Inertia::render(
            'admin/survey/Create',
            [
                'statusOptions' => SurveyStatus::dropdown(),

                'researches' => Research::query()
                    ->orderBy('title')
                    ->get([
                        'id',
                        'title',
                    ]),
            ]
        );
    }

    public function store(
        StoreSurveyRequest $request,
        CreateSurvey $action,
    ): RedirectResponse {
        $survey = $action->handle(
            $request->validated()
        );

        return redirect()
            ->route(
                'admin.survey.show',
                $survey
            )
            ->with(
                'success',
                'Survey created successfully.'
            );
    }

    public function show(
        Survey $survey,
    ): Response {
        $survey->load('research');

        return Inertia::render(
            'admin/survey/Show',
            [
                'survey' => new SurveyResource($survey),
            ]
        );
    }

    public function edit(
        Survey $survey,
    ): Response {
        $survey->load('research');

        return Inertia::render(
            'admin/survey/Edit',
            [
                'survey' => new SurveyResource($survey),

                'statusOptions' => SurveyStatus::dropdown(),

                'researches' => Research::query()
                    ->orderBy('title')
                    ->get([
                        'id',
                        'title',
                    ]),
            ]
        );
    }

    public function update(
        UpdateSurveyRequest $request,
        Survey $survey,
        UpdateSurvey $action,
    ): RedirectResponse {
        $action->handle(
            $survey,
            $request->validated()
        );

        return redirect()
            ->route(
                'admin.survey.show',
                $survey
            )
            ->with(
                'success',
                'Survey updated successfully.'
            );
    }

    public function destroy(
        Survey $survey,
        DeleteSurvey $action,
    ): RedirectResponse {
        $action->handle($survey);

        return redirect()
            ->route('admin.survey.index')
            ->with(
                'success',
                'Survey deleted successfully.'
            );
    }

    public function analytics(Survey $survey): Response
    {
        $survey->load([
            'questions.options',
            'sections.questions.options',
        ]);

        $responses = $survey->responses();

        $totalResponses = (clone $responses)->count();

        $submittedResponses = (clone $responses)
            ->where('status', SurveyResponseStatus::Submitted)
            ->count();

        $inProgressResponses = (clone $responses)
            ->where('status', SurveyResponseStatus::InProgress)
            ->count();

        $abandonedResponses = (clone $responses)
            ->where('status', SurveyResponseStatus::Abandoned)
            ->count();

        $completionRate = $totalResponses > 0
            ? round(
                ($submittedResponses / $totalResponses) * 100,
                1
            )
            : 0;

        $recentResponses = $survey->responses()
            ->latest()
            ->limit(10)
            ->get();

        $questions = $survey->questions
            ->merge(
                $survey->sections->flatMap(
                    fn($section) => $section->questions
                )
            )
            ->unique('id')
            ->values();

        $questionAnalytics = $questions->map(
            function (SurveyQuestion $question) {
                $answers = SurveyAnswer::query()
                    ->where('question_id', $question->id)
                    ->whereHas(
                        'response',
                        fn($query) => $query
                            ->where('survey_id', $this->survey->id)
                            ->where(
                                'status',
                                SurveyResponseStatus::Submitted
                            )
                    )
                    ->get();

                return [
                    'id' => $question->id,
                    'question' => $question->question,
                    'type' => [
                        'value' => $question->type->value,
                        'label' => $question->type->label(),
                    ],
                    'total_answers' => $answers->count(),
                    'options' => $question->options
                        ->map(function ($option) use ($answers, $question) {
                            $count = $answers->sum(
                                function ($answer) use ($option, $question) {
                                    if (
                                        $question->type->value === 'multiple_choice'
                                    ) {
                                        return collect($answer->answer_json ?? [])
                                            ->contains((int) $option->id)
                                            ? 1
                                            : 0;
                                    }

                                    return (int) (
                                        $answer->option_id === $option->id
                                    );
                                }
                            );

                            return [
                                'id' => $option->id,
                                'label' => $option->label,
                                'value' => $option->value,
                                'count' => $count,
                            ];
                        })
                        ->values(),
                    'numbers' => $answers
                        ->pluck('answer_number')
                        ->filter(fn($value) => $value !== null)
                        ->values(),
                ];
            }
        );

        return Inertia::render('admin/survey/Analytics', [
            'survey' => new SurveyResource($survey),
            'stats' => [
                'total_responses' => $totalResponses,
                'submitted_responses' => $submittedResponses,
                'in_progress_responses' => $inProgressResponses,
                'abandoned_responses' => $abandonedResponses,
                'completion_rate' => $completionRate,
            ],
            'questions' => $questionAnalytics,
            'recent_responses' => SurveyResponseResource::collection(
                $recentResponses
            ),
        ]);
    }
}
