<?php

namespace App\Http\Controllers\Public\Survey;

use App\Enums\Survey\SurveyResponseStatus;
use App\Enums\Survey\SurveyStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\Survey\SurveyResource;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyQuestion;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SurveyController extends Controller
{
    public function index(): Response
    {
        $surveys = Survey::query()
            ->where('status', SurveyStatus::Published)
            ->withCount('questions')
            ->orderByDesc('featured')
            ->orderByDesc('created_at')
            ->get([
                'id',
                'title',
                'slug',
                'description',
                'anonymous',
                'multiple_responses',
                'featured',
                'starts_at',
                'ends_at',
                'response_count',
                'created_at',
            ]);

        return Inertia::render('surveys/Index', [
            'surveys' => SurveyResource::collection($surveys),

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Surveys')
                ->toArray(),

            'seoMetadata' => [
                'title' => 'Surveys — Alfrik',
                'description' => 'Share your perspective and take part in Alfrik community surveys, research, and conversations.',
            ],
        ]);
    }

    public function show(Request $request, Survey $survey): Response
    {
        // abort_unless($this->isLive($survey), 404);

        $survey->load([
            'sections' => fn ($query) => $query
                ->with([
                    'questions' => fn ($query) => $query
                        ->with('options')
                        ->orderBy('position'),
                ])
                ->orderBy('position'),

            'questions' => fn ($query) => $query
                ->whereNull('section_id')
                ->with('options')
                ->orderBy('position'),
        ]);

        return Inertia::render('surveys/Show', [
            'survey' => $this->surveyPayload($survey),

            'isOpen' => $this->isLive($survey),

            'hasResponded' => $this->hasResponded(
                $request,
                $survey
            ),

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Surveys', route('surveys.index'))
                ->add($survey->title)
                ->toArray(),

            'seoMetadata' => $survey->seoMetadata ?? [
                'title' => $survey->title . ' — Alfrik',
                'description' => $survey->description,
            ],  
        ]);
    }

    public function submit(
        Request $request,
        Survey $survey,
    ): RedirectResponse {
        abort_unless($this->isLive($survey), 404);

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

        // Response creation / answer validation should remain
        // in the existing Survey actions.
        //
        // Keep your existing submit implementation here.
        //
        // The important part for the Vue pages is that the endpoint
        // accepts:
        //
        // answers[question_id]
        //
        // and for multiple choice:
        //
        // answers[question_id][]

        return redirect()->route('surveys.thank-you', [
            'survey' => $survey->slug,
        ]);
    }

    public function thankYou(Survey $survey): Response
    {
        return Inertia::render('surveys/ThankYou', [
            'survey' => new SurveyResource($survey),
        ]);
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

    private function surveyPayload(Survey $survey): array
    {
        return [
            'id' => $survey->id,
            'title' => $survey->title,
            'slug' => $survey->slug,
            'description' => $survey->description,
            'anonymous' => $survey->anonymous,
            'multiple_responses' => $survey->multiple_responses,
            'featured' => $survey->featured,
            'starts_at' => $survey->starts_at?->toISOString(),
            'ends_at' => $survey->ends_at?->toISOString(),
            'response_count' => $survey->response_count,

            'sections' => $survey->sections
                ->map(fn ($section) => [
                    'id' => $section->id,
                    'title' => $section->title,
                    'description' => $section->description,
                    'position' => $section->position,

                    'questions' => $section->questions
                        ->map(fn ($question) => $this->questionPayload($question))
                        ->values()
                        ->all(),
                ])
                ->values()
                ->all(),

            'unsectioned_questions' => $survey->questions
                ->map(fn ($question) => $this->questionPayload($question))
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
                ->map(fn ($option) => [
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
}