<?php

namespace App\Http\Controllers\Public\Magazine;

use App\Http\Controllers\Controller;
use App\Models\Magazine\Magazine;
use App\Models\Magazine\MagazineIssue;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Inertia\Inertia;
use Inertia\Response;

class MagazineIssueController extends Controller
{
    /**
     * Display a magazine issue.
     */
    public function show(
        Magazine $magazine,
        MagazineIssue $issue
    ): Response {
        /*
        |--------------------------------------------------------------------------
        | Ensure the issue belongs to this magazine
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $issue->magazine_id === $magazine->id,
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Publishing Check
        |--------------------------------------------------------------------------
        */

        abort_unless(
            $issue->status->value === 'published' &&
            (
                ! $issue->published_at ||
                $issue->published_at->isPast()
            ),
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Load Magazine
        |--------------------------------------------------------------------------
        */

        $magazine->load([
            'categories:id,name,slug',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Load Issue
        |--------------------------------------------------------------------------
        */

        $issue->load([
            'magazine:id,title,slug',
            'media',
            'seo',

            'articles' => fn ($query) => $query
                ->published()
                ->with([
                    'author:id,name',
                    'categories:id,name,slug',
                    'media',
                ]),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Related Issues
        |--------------------------------------------------------------------------
        */

        $related = MagazineIssue::query()
            ->published()
            ->where('magazine_id', $magazine->id)
            ->where('id', '!=', $issue->id)
            ->latest('published_at')
            ->take(3)
            ->with([
                'media',
            ])
            ->get([
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
                'editor',
                'file_path',
                'file_size',
                'file_type',
                'download_count',
                'status',
                'featured',
            ]);

        /*
        |--------------------------------------------------------------------------
        | SEO
        |--------------------------------------------------------------------------
        */

        $seo = $issue->seo->first();

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return Inertia::render('magazine/issues/Show', [
            'magazine' => $magazine,

            'issue' => $issue,

            'related' => $related,

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add(
                    'Magazine',
                    route('magazine.index')
                )
                ->add(
                    $magazine->title,
                    route(
                        'magazine.show',
                        $magazine->slug
                    )
                )
                ->add($issue->title)
                ->toArray(),

            'meta_data' => [
                'meta_title' => $seo?->title
                    ?: $issue->title . ' — ' . $magazine->title,

                'meta_description' => $seo?->description
                    ?: $issue->description,

                'canonical_url' => $seo?->canonical_url,

                'indexable' => $seo?->indexable ?? true,

                'followable' => $seo?->followable ?? true,

                'og_title' => $seo?->og_title
                    ?: $seo?->title
                    ?: $issue->title,

                'og_description' => $seo?->og_description
                    ?: $seo?->description
                    ?: $issue->description,

                'og_type' => $seo?->og_type,

                'og_image_url' => $seo?->og_image_url,

                'twitter_card' => $seo?->twitter_card,

                'twitter_title' => $seo?->twitter_title
                    ?: $seo?->title
                    ?: $issue->title,

                'twitter_description' => $seo?->twitter_description
                    ?: $seo?->description
                    ?: $issue->description,

                'twitter_image_url' => $seo?->twitter_image_url,

                'locale' => $seo?->locale,

                'schema_type' => $seo?->schema_type,
            ],
        ]);
    }
}