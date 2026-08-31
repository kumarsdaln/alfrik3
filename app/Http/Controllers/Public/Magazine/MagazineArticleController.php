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
            $article->status &&
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
                    'volume',
                    'issue_number',
                    'cover_date',
                    'published_at',
                ])
                ->with([
                    'magazine' => fn ($query) => $query
                        ->select([
                            'id',
                            'category_id',
                            'title',
                            'slug',
                        ])
                        ->with([
                            'category:id,name,slug',
                        ]),
                ]),

            'author:id,name,username',
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
                'author:id,name,username',
            ])
            ->ordered()
            ->take(4)
            ->get([
                'id',
                'issue_id',
                'author_id',
                'title',
                'slug',
                'subtitle',
                'excerpt',
                'cover_image',
                'type',
                'byline',
                'published_at',
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
        | Response
        |--------------------------------------------------------------------------
        */

        return Inertia::render('magazine/articles/Show', [
            'article' => $article,

            'related' => $related,

            'breadcrumbs' => $breadcrumbs,

            'meta_data' => [
                'meta_title' => $article->meta_title
                    ?: $article->title . ' — ' .
                        $article->issue->magazine->title,

                'meta_description' => $article->meta_description
                    ?: $article->excerpt,

                'meta_keywords' => $article->meta_keywords,
            ],
        ]);
    }
}