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
        $search = trim(
            (string) $request->query('search', '')
        );

        /*
        |--------------------------------------------------------------------------
        | Magazines
        |--------------------------------------------------------------------------
        */

        $magazines = Magazine::query()
            ->published()
            ->with([
                'author:id,name',
            ])
            ->withCount([
                'issues as published_issues_count' => fn ($query) => $query
                    ->published(),
            ])
            ->when(
                $search !== '',
                fn ($query) => $query->where(function ($query) use ($search) {
                    $query
                        ->where(
                            'title',
                            'ilike',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'subtitle',
                            'ilike',
                            "%{$search}%"
                        )
                        ->orWhere(
                            'description',
                            'ilike',
                            "%{$search}%"
                        );
                })
            )
            ->orderByDesc('published_at')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Featured Magazine
        |--------------------------------------------------------------------------
        */

        $featured = null;

        if ($search === '') {
            $featured = Magazine::query()
                ->published()
                ->featured()
                ->with([
                    'author:id,name',
                ])
                ->withCount([
                    'issues as published_issues_count' => fn ($query) => $query
                        ->published(),
                ])
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->first();

            /*
            |--------------------------------------------------------------------------
            | Fallback to latest published magazine
            |--------------------------------------------------------------------------
            */

            if (! $featured) {
                $featured = Magazine::query()
                    ->published()
                    ->with([
                        'author:id,name',
                    ])
                    ->withCount([
                        'issues as published_issues_count' => fn ($query) => $query
                            ->published(),
                    ])
                    ->orderByDesc('published_at')
                    ->orderByDesc('created_at')
                    ->first();
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return Inertia::render('magazine/Index', [
            'magazines' => $magazines,

            'featured' => $featured,

            'qfilters' => [
                'search' => $search,
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
        // abort_unless(
        //     $this->isLive($magazine),
        //     404
        // );

        /*
        |--------------------------------------------------------------------------
        | Magazine
        |--------------------------------------------------------------------------
        */

        $magazine->load([
            'author:id,name',

            'issues' => fn ($query) => $query
                ->published()
                ->withCount([
                    'articles as published_articles_count' => fn ($query) => $query
                        ->published(),
                ])
                ->orderByDesc('published_at')
                ->orderByDesc('created_at'),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

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

            /*
            |--------------------------------------------------------------------------
            | SEO
            |--------------------------------------------------------------------------
            */

            'meta_data' => [
                'meta_title' => $magazine->title
                    . ' — Alfrik',

                'meta_description' => $magazine->subtitle
                    ?: $magazine->description,

                'canonical_url' => null,

                'og_title' => $magazine->title,

                'og_description' => $magazine->subtitle
                    ?: $magazine->description,

                'og_image' => null,

                'twitter_title' => $magazine->title,

                'twitter_description' => $magazine->subtitle
                    ?: $magazine->description,

                'twitter_image' => null,
            ],
        ]);
    }

    /**
     * Determine whether a magazine is publicly available.
     */
    private function isLive(Magazine $magazine): bool
    {
        return $magazine->status === 'published'
            && (
                $magazine->published_at === null
                || $magazine->published_at->lte(now())
            );
    }
}