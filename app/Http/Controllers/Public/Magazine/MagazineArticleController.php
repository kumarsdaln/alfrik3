<?php

namespace App\Http\Controllers\Public\Magazine;

use App\Http\Controllers\Controller;
use App\Models\Magazine\MagazineArticle;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class MagazineArticleController extends Controller
{
    /**
     * Display a magazine article.
     */
    public function show(MagazineArticle $article): InertiaResponse
    {
        /*
        |--------------------------------------------------------------------------
        | Publishing Check
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $article->status->value === 'published' &&
            (
                ! $article->published_at ||
                $article->published_at->isPast()
            ),
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Article Relationships
        |--------------------------------------------------------------------------
        */

        $article->load([
            'issue' => fn ($query) => $query
                ->select([
                    'id',
                    'magazine_id',
                    'title',
                    'slug',
                    'subtitle',
                    'volume',
                    'issue_number',
                    'cover_date',
                    'published_at',
                    'description',
                ])
                ->with([
                    'magazine' => fn ($query) => $query
                        ->select([
                            'id',
                            'title',
                            'slug',
                        ])
                        ->with([
                            'categories:id,name,slug',
                        ]),
                ]),

            'author:id,name',

            'categories:id,name,slug',

            'media',

            'seo',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Related Articles
        |--------------------------------------------------------------------------
        |
        | Related articles belong to the same issue.
        |
        */

        $related = MagazineArticle::query()
            ->published()
            ->where('id', '!=', $article->id)
            ->where('issue_id', $article->issue_id)
            ->with([
                'author:id,name',
                'categories:id,name,slug',
                'media',
            ])
            ->orderBy('position')
            ->take(4)
            ->get([
                'id',
                'issue_id',
                'author_id',
                'title',
                'slug',
                'subtitle',
                'excerpt',
                'content',
                'type',
                'byline',
                'position',
                'featured',
                'status',
                'published_at',
                'views',
                'reading_time',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Increment Views
        |--------------------------------------------------------------------------
        */

        $article->increment('views');

        /*
        |--------------------------------------------------------------------------
        | Breadcrumbs
        |--------------------------------------------------------------------------
        */

        $breadcrumbs = BreadcrumbBuilder::make()
            ->home()
            ->add(
                'Magazine',
                route('magazine.index')
            )
            ->add(
                $article->issue->magazine->title,
                route(
                    'magazine.show',
                    $article->issue->magazine->slug
                )
            )
            ->add(
                $article->issue->title,
                route(
                    'magazine.issues.show',
                    [
                        'magazine' => $article->issue->magazine->slug,
                        'issue' => $article->issue->slug,
                    ]
                )
            )
            ->add($article->title)
            ->toArray();

        /*
        |--------------------------------------------------------------------------
        | SEO
        |--------------------------------------------------------------------------
        */

        $seo = $article->seo->first();

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return Inertia::render('magazine/articles/Show', [
            'article' => $article,

            'related' => $related,

            'breadcrumbs' => $breadcrumbs,

            'meta_data' => [
                'meta_title' => $seo?->title
                    ?: $article->title . ' — ' .
                        $article->issue->magazine->title,

                'meta_description' => $seo?->description
                    ?: $article->excerpt,

                'canonical_url' => $seo?->canonical_url,

                'indexable' => $seo?->indexable ?? true,

                'followable' => $seo?->followable ?? true,

                'og_title' => $seo?->og_title
                    ?: $seo?->title
                    ?: $article->title,

                'og_description' => $seo?->og_description
                    ?: $seo?->description
                    ?: $article->excerpt,

                'og_type' => $seo?->og_type,

                'og_image_url' => $seo?->og_image_url,

                'twitter_card' => $seo?->twitter_card,

                'twitter_title' => $seo?->twitter_title
                    ?: $seo?->title
                    ?: $article->title,

                'twitter_description' => $seo?->twitter_description
                    ?: $seo?->description
                    ?: $article->excerpt,

                'twitter_image_url' => $seo?->twitter_image_url,

                'locale' => $seo?->locale,

                'schema_type' => $seo?->schema_type,
            ],
        ]);
    }
}