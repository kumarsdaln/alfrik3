<?php

namespace App\Http\Controllers\Public\Survey;

use App\Http\Controllers\Controller;
use App\Http\Resources\Survey\SurveyResource;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyAnswer;
use App\Models\Survey\SurveyResponse;
use App\Services\SurveyResultService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class SurveyController extends Controller
{
    public function index(): Response
    {
        $surveys = Survey::query()
            ->open()
            ->withCount([
                'questions',
                'responses',
            ])
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->get([
                'id',
                'title',
                'slug',
                'description',
                'closes_at',
                'published_at',
                'created_at',
            ]);

        return Inertia::render('surveys/Index', [
            'surveys' => SurveyResource::collection($surveys),

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Surveys')
                ->toArray(),
        ]);
    }

    public function show(Request $request, Survey $survey)
    {
        abort_unless($this->isLive($survey), 404);

        $survey->load([
            'questions.options',
        ]);

        return Inertia::render('surveys/Show', [
            'survey' => $survey,
            'isOpen' => $survey->isOpen(),
            'hasResponded' => $this->hasResponded($request, $survey),

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Surveys', route('surveys.index'))
                ->add($survey->title)
                ->toArray(),
        ]);
    }

    public function submit(Request $request, Survey $survey)
    {
        abort_unless($this->isLive($survey), 404);
        abort_unless($survey->isOpen(), 403, 'This survey is closed.');

        if (! $survey->allow_anonymous && ! auth()->check()) {
            return redirect()->guest(route('login'));
        }

        if ($survey->one_response_per_user && $this->hasResponded($request, $survey)) {
            return back()->with('error', 'You have already responded to this survey.');
        }

        $survey->load('questions.options');

        // Validate required questions and collect normalized answers.
        $answers = [];
        foreach ($survey->questions as $q) {
            $value = $request->input("answers.{$q->id}");
            $filled = is_array($value) ? count($value) > 0 : ($value !== null && $value !== '');

            if ($q->required && ! $filled) {
                return back()->withErrors(["answers.{$q->id}" => 'This question is required.'])->withInput();
            }
            if (! $filled) {
                continue;
            }

            $validOptionIds = $q->options->pluck('id')->all();

            if ($q->type === 'single_choice') {
                if (in_array((int) $value, $validOptionIds, true)) {
                    $answers[] = ['question_id' => $q->id, 'option_id' => (int) $value, 'value_text' => null];
                }
            } elseif ($q->type === 'multiple_choice') {
                foreach ((array) $value as $optId) {
                    if (in_array((int) $optId, $validOptionIds, true)) {
                        $answers[] = ['question_id' => $q->id, 'option_id' => (int) $optId, 'value_text' => null];
                    }
                }
            } elseif ($q->type === 'rating') {
                $max = (int) ($q->settings['max'] ?? 5);
                $rating = max(1, min($max, (int) $value));
                $answers[] = ['question_id' => $q->id, 'option_id' => null, 'value_text' => (string) $rating];
            } else { // text
                $answers[] = ['question_id' => $q->id, 'option_id' => null, 'value_text' => mb_substr((string) $value, 0, 2000)];
            }
        }

        DB::transaction(function () use ($survey, $request, $answers) {
            $response = SurveyResponse::create([
                'survey_id' => $survey->id,
                'user_id' => auth()->id(),
                'session_token' => auth()->check() ? null : $request->session()->getId(),
                'ip_address' => $request->ip(),
            ]);

            foreach ($answers as $a) {
                SurveyAnswer::create(array_merge($a, ['response_id' => $response->id]));
            }
        });

        // Remember completion for anonymous dedup fallback.
        $request->session()->put("survey_done_{$survey->id}", true);

        if ($survey->show_results) {
            return redirect()->route('surveys.results', $survey->slug)->with('success', 'Thank you for responding!');
        }

        return redirect()->route('surveys.index')->with('success', 'Thank you for responding!');
    }

    public function results(Survey $survey)
    {
        abort_unless($this->isLive($survey), 404);
        abort_unless($survey->show_results, 403, 'Results are not public for this survey.');

        return Inertia::render('surveys/Results', [
            'survey' => $survey->only('id', 'title', 'slug', 'description'),
            'results' => app(SurveyResultService::class)->aggregate($survey),
            'breadcrumbs' => BreadcrumbBuilder::make()->home()
                ->add('surveys', route('surveys.index'))
                ->add($survey->title, route('surveys.show', $survey->slug))
                ->add('Results')
                ->toArray(),
        ]);
    }

    private function isLive(Survey $survey): bool
    {
        return $survey->status && (is_null($survey->published_at) || $survey->published_at->lte(now()));
    }

    private function hasResponded(Request $request, Survey $survey): bool
    {
        if (auth()->check()) {
            return $survey->responses()->where('user_id', auth()->id())->exists();
        }

        if ($request->session()->get("survey_done_{$survey->id}")) {
            return true;
        }

        return $survey->responses()->where('session_token', $request->session()->getId())->exists();
    }
}
