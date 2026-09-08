<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Interview\Interview;
use App\Models\Magazine\Magazine;
use App\Models\Report\Report;
use App\Models\Research\ResearchArea;
use App\Models\Research\ResearchPaper;
use App\Models\Survey\Survey;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends Controller
{
    /**
     * Display the Alfrik public homepage.
     */
    public function __invoke(): Response
    {
        /*
        |--------------------------------------------------------------------------
        | Editor's Choice
        |--------------------------------------------------------------------------
        |
        | Prefer explicitly featured research/report content.
        | Fall back to the latest published research if nothing is featured.
        |
        */

        $featuredResearch = ResearchPaper::query()
            ->published()
            ->featured()
            ->with([
                'area',
                'author',
            ])
            ->latest('published_at')
            ->first();

        $featuredReport = Report::query()
            ->published()
            ->featured()
            ->with([
                'category',
                'author',
            ])
            ->latest('published_at')
            ->first();

        $featured = $featuredReport ?? $featuredResearch;

        /*
        |--------------------------------------------------------------------------
        | Latest Research
        |--------------------------------------------------------------------------
        */

        $research = ResearchPaper::query()
            ->published()
            ->with([
                'area',
                'author',
            ])
            ->latest('published_at')
            ->take(4)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Latest Reports
        |--------------------------------------------------------------------------
        */

        $reports = Report::query()
            ->published()
            ->with([
                'category',
                'author',
            ])
            ->latest('published_at')
            ->take(4)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Latest Surveys
        |--------------------------------------------------------------------------
        */

        $surveys = Survey::query()
            ->published()
            ->with([
                'author',
            ])
            ->latest('published_at')
            ->take(3)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Latest Magazines
        |--------------------------------------------------------------------------
        */

        $magazines = Magazine::query()
            ->published()
            ->with([
                'category',
                'author',
            ])
            ->latest('published_at')
            ->take(3)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Latest Interviews
        |--------------------------------------------------------------------------
        */

        $interviews = Interview::query()
            ->with([
                'participants.user',
            ])
            ->whereIn('status', ['published'])
            ->latest('published_at')
            ->take(3)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Upcoming Events
        |--------------------------------------------------------------------------
        */

        $events = Event::query()
            ->published()
            ->public()
            ->upcoming()
            ->with([
                'categories',
            ])
            ->orderBy('start_date')
            ->take(3)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Areas of Work
        |--------------------------------------------------------------------------
        */

        $areas = ResearchArea::query()
            ->withCount([
                'papers' => fn ($query) => $query->published(),
            ])
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
                'description',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Latest from Alfrik
        |--------------------------------------------------------------------------
        |
        | Keep the individual content collections separate because each model
        | has different fields and relationships. The frontend can combine
        | them into the editorial feed.
        |
        */

        return Inertia::render('Welcome', [
            /*
            | Editor's Choice
            */
            'featured' => $featured,

            /*
            | Individual content streams
            */
            'research' => $research,
            'reports' => $reports,
            'surveys' => $surveys,
            'magazines' => $magazines,
            'interviews' => $interviews,
            'events' => $events,

            /*
            | Discovery
            */
            'areas' => $areas,
        ]);
    }
}