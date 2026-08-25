<?php

namespace App\Http\Controllers\Admin\Survey;

use App\Http\Controllers\Controller;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyAnswer;
use App\Models\Survey\SurveyOption;
use App\Models\Survey\SurveyQuestion;
use App\Services\SurveyResultService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $surveys = Survey::query()
            ->withCount('questions', 'responses')
            ->when($search, fn ($q) => $q->where('title', 'ilike', "%{$search}%"))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Surveys/Index', [
            'surveys' => $surveys,
            'filters' => ['search' => $search],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Surveys/Create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateSurvey($request);

        $survey = DB::transaction(function () use ($validated, $request) {
            $survey = Survey::create(array_merge($this->meta($validated), [
                'slug' => Survey::uniqueSlug($validated['title']),
                'author_id' => auth()->id(),
            ]));

            $this->syncQuestions($survey, $request->input('questions', []));

            return $survey;
        });

        return redirect()->route('admin.surveys.edit', $survey->id)->with('success', 'Survey created.');
    }

    public function edit(Survey $survey)
    {
        $survey->load(['questions.options']);

        return Inertia::render('Admin/Surveys/Edit', ['survey' => $survey]);
    }

    public function update(Request $request, Survey $survey)
    {
        $validated = $this->validateSurvey($request);

        DB::transaction(function () use ($survey, $validated, $request) {
            $survey->update(array_merge($this->meta($validated), [
                'slug' => Survey::uniqueSlug($validated['title'], $survey->id),
            ]));

            $this->syncQuestions($survey, $request->input('questions', []));
        });

        return back()->with('success', 'Survey saved.');
    }

    public function updateStatus(Request $request, Survey $survey)
    {
        $validated = $request->validate(['status' => ['required', 'boolean']]);
        $survey->update(['status' => $validated['status']]);

        return back()->with('success', 'Status updated.');
    }

    public function results(Survey $survey)
    {
        return Inertia::render('Admin/Surveys/Results', [
            'survey' => $survey->only('id', 'title', 'slug', 'description', 'show_results'),
            'results' => app(SurveyResultService::class)->aggregate($survey),
        ]);
    }

    public function destroy(Survey $survey)
    {
        DB::transaction(function () use ($survey) {
            $questionIds = $survey->questions()->pluck('id');
            SurveyAnswer::whereIn('question_id', $questionIds)->delete();
            SurveyOption::whereIn('question_id', $questionIds)->delete();
            $survey->questions()->delete();
            $survey->responses()->delete();
            $survey->delete();
        });

        return redirect()->route('admin.surveys.index')->with('success', 'Survey deleted.');
    }

    private function validateSurvey(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'status' => ['nullable', 'boolean'],
            'allow_anonymous' => ['nullable', 'boolean'],
            'one_response_per_user' => ['nullable', 'boolean'],
            'show_results' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'closes_at' => ['nullable', 'date'],
            'questions' => ['nullable', 'array'],
            'questions.*.id' => ['nullable', 'integer'],
            'questions.*.question' => ['required', 'string', 'max:1000'],
            'questions.*.type' => ['required', 'in:single_choice,multiple_choice,text,rating'],
            'questions.*.required' => ['nullable', 'boolean'],
            'questions.*.settings' => ['nullable', 'array'],
            'questions.*.options' => ['nullable', 'array'],
            'questions.*.options.*.id' => ['nullable', 'integer'],
            'questions.*.options.*.label' => ['required', 'string', 'max:255'],
        ]);
    }

    private function meta(array $v): array
    {
        return [
            'title' => $v['title'],
            'description' => $v['description'] ?? null,
            'status' => (bool) ($v['status'] ?? false),
            'allow_anonymous' => (bool) ($v['allow_anonymous'] ?? true),
            'one_response_per_user' => (bool) ($v['one_response_per_user'] ?? true),
            'show_results' => (bool) ($v['show_results'] ?? false),
            'published_at' => $v['published_at'] ?? null,
            'closes_at' => $v['closes_at'] ?? null,
        ];
    }

    /**
     * Diff-sync the builder's questions + options against the DB.
     */
    private function syncQuestions(Survey $survey, array $questions): void
    {
        $keptQuestionIds = [];

        foreach (array_values($questions) as $qIndex => $qData) {
            $isChoice = in_array($qData['type'], ['single_choice', 'multiple_choice'], true);

            $attrs = [
                'survey_id' => $survey->id,
                'question' => $qData['question'],
                'type' => $qData['type'],
                'required' => (bool) ($qData['required'] ?? false),
                'position' => $qIndex,
                'settings' => $qData['settings'] ?? null,
            ];

            // Update in place only if the id belongs to this survey; else create.
            $question = ! empty($qData['id'])
                ? $survey->questions()->find($qData['id'])
                : null;
            if ($question) {
                $question->update($attrs);
            } else {
                $question = SurveyQuestion::create($attrs);
            }
            $keptQuestionIds[] = $question->id;

            // Options only apply to choice questions.
            $keptOptionIds = [];
            if ($isChoice) {
                foreach (array_values($qData['options'] ?? []) as $oIndex => $oData) {
                    $oAttrs = ['question_id' => $question->id, 'label' => $oData['label'], 'position' => $oIndex];
                    $option = ! empty($oData['id'])
                        ? $question->options()->find($oData['id'])
                        : null;
                    if ($option) {
                        $option->update($oAttrs);
                    } else {
                        $option = SurveyOption::create($oAttrs);
                    }
                    $keptOptionIds[] = $option->id;
                }
            }

            // Remove options that were deleted in the builder.
            $staleOptions = $question->options()->whereNotIn('id', $keptOptionIds ?: [0])->pluck('id');
            if ($staleOptions->isNotEmpty()) {
                SurveyAnswer::whereIn('option_id', $staleOptions)->delete();
                SurveyOption::whereIn('id', $staleOptions)->delete();
            }
        }

        // Remove questions dropped in the builder (and their options + answers).
        $staleQuestions = $survey->questions()->whereNotIn('id', $keptQuestionIds ?: [0])->pluck('id');
        if ($staleQuestions->isNotEmpty()) {
            SurveyAnswer::whereIn('question_id', $staleQuestions)->delete();
            SurveyOption::whereIn('question_id', $staleQuestions)->delete();
            SurveyQuestion::whereIn('id', $staleQuestions)->delete();
        }
    }
}
