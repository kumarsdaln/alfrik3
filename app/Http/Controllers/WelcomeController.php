<?php

namespace App\Http\Controllers;

use App\Models\Event\Event;
use App\Models\Interview\Interview;
use App\Models\Magazine\Magazine;
use App\Models\Report\Report;
use App\Models\Research\Research;
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
        */

        $featuredResearch = Research::query()
            ->where('status', 'published')
            ->where('featured', true)
            ->with([
                'author',
                'categories',
                'media',
            ])
            ->latest('published_at')
            ->first();

        $featuredReport = Report::query()
            ->published()
            ->featured()
            ->with([
                'categories',
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

        $research = Research::query()
            ->where('status', 'published')
            ->with([
                'categories',
                'author',
                'media',
            ])
            ->latest('published_at')
            ->take(4)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Research Categories
        |--------------------------------------------------------------------------
        |
        | Derive discovery categories from the research records loaded above.
        | No ResearchArea or ResearchPaper model is required.
        |
        */

        $categories = $research
            ->pluck('categories')
            ->flatten(1)
            ->unique('id')
            ->values();

        /*
        |--------------------------------------------------------------------------
        | Latest Reports
        |--------------------------------------------------------------------------
        */

        $reports = Report::query()
            ->published()
            ->with([
                'categories',
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
            ->with('author')
            ->latest()
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
                'categories',
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
            ->where('status', 'published')
            ->with([
                'participants.user',
            ])
            ->latest('published_at')
            ->take(3)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Upcoming Public Events
        |--------------------------------------------------------------------------
        */

        /*
        |--------------------------------------------------------------------------
        | Render Homepage
        |--------------------------------------------------------------------------
        */

        return Inertia::render('Welcome', [
            'featured' => $featured,
            'research' => $research,
            'reports' => $reports,
            'surveys' => $surveys,
            'magazines' => $magazines,
            'interviews' => $interviews,
            'categories' => $categories,
        ]);
    }
}