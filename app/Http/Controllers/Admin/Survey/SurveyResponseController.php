<?php

namespace App\Http\Controllers\Admin\Survey;

use App\Actions\Survey\DeleteSurveyResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Survey\SurveyAnswerResource;
use App\Http\Resources\Survey\SurveyResponseResource;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveyResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class SurveyResponseController extends Controller
{
    public function index(
        Request $request,
        Survey $survey,
    ): Response {
        $responses = $survey->responses()
            ->with('user')
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->string('search')->toString();

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('respondent_name', 'like', "%{$search}%")
                            ->orWhere('respondent_email', 'like', "%{$search}%");
                    });
                }
            )
            ->when(
                $request->filled('status'),
                fn($query) => $query->where(
                    'status',
                    $request->string('status')->toString()
                )
            )
            ->latest('submitted_at')
            ->latest('id')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('admin/survey/Responses', [
            'survey' => [
                'id' => $survey->id,
                'title' => $survey->title,
            ],

            'responses' => SurveyResponseResource::collection(
                $responses
            ),

            'filters' => [
                'search' => $request->string('search')->toString(),
                'status' => $request->string('status')->toString(),
            ],

            'stats' => [
                'total' => $survey->responses()->count(),

                'submitted' => $survey->responses()
                    ->where('status', 'submitted')
                    ->count(),

                'in_progress' => $survey->responses()
                    ->where('status', 'in_progress')
                    ->count(),

                'abandoned' => $survey->responses()
                    ->where('status', 'abandoned')
                    ->count(),
            ],
        ]);
    }

    public function show(
        Survey $survey,
        SurveyResponse $response,
    ): Response {
        abort_unless(
            $response->survey_id === $survey->id,
            404
        );

        $survey->load([
            'questions',
        ]);

        $response->load([
            'answers.option',
        ]);

        return Inertia::render(
            'admin/survey/response/Show',
            [
                'survey' => [
                    'id' => $survey->id,
                    'title' => $survey->title,
                    'slug' => $survey->slug,
                ],
                'response' => new SurveyResponseResource($response),
                'questions' => SurveyQuestionResource::collection(
                    $survey->questions
                ),
            ]
        );
    }

    public function destroy(
        Survey $survey,
        SurveyResponse $response,
        DeleteSurveyResponse $action,
    ): RedirectResponse {
        abort_unless(
            $response->survey_id === $survey->id,
            404
        );

        $action->handle($response);

        return back()->with(
            'success',
            'Survey response deleted successfully.'
        );
    }
}
