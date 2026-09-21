<?php

namespace App\Http\Controllers\Admin\Survey;

use App\Actions\Survey\CreateSurveySection;
use App\Actions\Survey\DeleteSurveySection;
use App\Actions\Survey\UpdateSurveySection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Survey\StoreSurveySectionRequest;
use App\Http\Requests\Admin\Survey\UpdateSurveySectionRequest;
use App\Http\Resources\Survey\SurveySectionResource;
use App\Models\Survey\Survey;
use App\Models\Survey\SurveySection;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SurveySectionController extends Controller
{
    public function index(
        Survey $survey,
    ): Response {
        $sections = $survey->sections()
            ->orderBy('position')
            ->get();

        return Inertia::render(
            'admin/survey/sections/Index',
            [
                'survey' => [
                    'id' => $survey->id,
                    'title' => $survey->title,
                ],

                'sections' => SurveySectionResource::collection(
                    $sections
                ),
            ]
        );
    }

    public function store(
        StoreSurveySectionRequest $request,
        Survey $survey,
        CreateSurveySection $action,
    ): RedirectResponse {
        $data = $request->validated();

        if (! array_key_exists('position', $data)) {
            $data['position'] = $survey->sections()->count();
        }

        $action->handle(
            $survey,
            $data
        );

        return back()->with(
            'success',
            'Survey section created successfully.'
        );
    }

    public function edit(
        Survey $survey,
        SurveySection $section,
    ): Response {
        $this->ensureSectionBelongsToSurvey(
            $survey,
            $section
        );

        return Inertia::render(
            'admin/survey/sections/Edit',
            [
                'survey' => [
                    'id' => $survey->id,
                    'title' => $survey->title,
                ],

                'section' => new SurveySectionResource(
                    $section
                ),
            ]
        );
    }

    public function update(
        UpdateSurveySectionRequest $request,
        Survey $survey,
        SurveySection $section,
        UpdateSurveySection $action,
    ): RedirectResponse {
        $this->ensureSectionBelongsToSurvey(
            $survey,
            $section
        );

        $action->handle(
            $section,
            $request->validated()
        );

        return redirect()
            ->route(
                'admin.survey.sections.index',
                $survey
            )
            ->with(
                'success',
                'Survey section updated successfully.'
            );
    }

    public function destroy(
        Survey $survey,
        SurveySection $section,
        DeleteSurveySection $action,
    ): RedirectResponse {
        $this->ensureSectionBelongsToSurvey(
            $survey,
            $section
        );

        $action->handle($section);

        return back()->with(
            'success',
            'Survey section deleted successfully.'
        );
    }

    private function ensureSectionBelongsToSurvey(
        Survey $survey,
        SurveySection $section,
    ): void {
        abort_unless(
            $section->survey_id === $survey->id,
            404
        );
    }
}
