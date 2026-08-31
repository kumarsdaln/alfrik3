<?php

namespace App\Http\Controllers\Public\Magazine;

use App\Http\Controllers\Controller;
use App\Models\Magazine\Magazine;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MagazineController extends Controller
{
    /**
     * Display the magazine landing page.
     */
    public function index(Request $request): Response
    {
        $magazines = Magazine::query()
            ->published()
            ->with([
                'category:id,name,slug',
            ])
            ->withCount([
                'issues as published_issues_count' => fn ($query) => $query
                    ->published(),
            ])
            ->when(
                $request->filled('search'),
                fn ($query) => $query->where(function ($query) use ($request) {
                    $search = $request->string('search');

                    $query
                        ->where('title', 'ilike', "%{$search}%")
                        ->orWhere('subtitle', 'ilike', "%{$search}%");
                })
            )
            ->when(
                $request->filled('category'),
                fn ($query) => $query->whereHas(
                    'category',
                    fn ($query) => $query->where(
                        'slug',
                        $request->string('category')
                    )
                )
            )
            ->latest('published_at')
            ->paginate(12)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Featured Magazine
        |--------------------------------------------------------------------------
        */

        $featured = Magazine::query()
            ->published()
            ->featured()
            ->with([
                'category:id,name,slug',
            ])
            ->withCount([
                'issues as published_issues_count' => fn ($query) => $query
                    ->published(),
            ])
            ->latest('published_at')
            ->first();

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        $categories = \App\Models\Magazine\MagazineCategory::query()
            ->active()
            ->orderBy('position')
            ->orderBy('name')
            ->get([
                'id',
                'name',
                'slug',
                'icon',
            ]);

        return Inertia::render('magazine/Index', [
            'magazines' => $magazines,

            'featured' => $featured,

            'categories' => $categories,

            'qfilters' => [
                'search' => $request->input('search'),
                'category' => $request->input('category'),
            ],

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Magazine')
                ->toArray(),
        ]);
    }

    /**
     * Display a magazine.
     */
    public function show(Magazine $magazine): Response
    {
        abort_unless(
            $magazine->status &&
            (
                ! $magazine->published_at ||
                $magazine->published_at->isPast()
            ),
            404
        );

        $magazine->load([
            'category:id,name,slug',

            'issues' => fn ($query) => $query
                ->published()
                ->withCount([
                    'articles as published_articles_count' => fn ($query) => $query
                        ->published(),
                ])
                ->latest('published_at'),
        ]);

        return Inertia::render('magazine/Show', [
            'magazine' => $magazine,

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add(
                    'Magazine',
                    route('magazine.index')
                )
                ->add($magazine->title)
                ->toArray(),

            'meta_data' => [
                'meta_title' => $magazine->meta_title
                    ?: $magazine->title . ' — Alfrik',

                'meta_description' => $magazine->meta_description
                    ?: $magazine->subtitle,

                'meta_keywords' => $magazine->meta_keywords,
            ],
        ]);
    }
}