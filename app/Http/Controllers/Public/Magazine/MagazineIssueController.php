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
            $issue->status &&
                (
                    ! $issue->published_at ||
                    $issue->published_at->isPast()
                ),
            404
        );

        /*
        |--------------------------------------------------------------------------
        | Load Issue
        |--------------------------------------------------------------------------
        */

        $issue->load([
            'magazine:id,category_id,title,slug',
            'magazine.category:id,name,slug',

            'articles' => fn($query) => $query
                ->published()
                ->with([
                    'author:id,name,username',
                ])
                ->ordered(),
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
                'cover_image',
                'featured',
            ]);

        /*
        |--------------------------------------------------------------------------
        | Response
        |--------------------------------------------------------------------------
        */

        return Inertia::render('magazine/issues/Show', [
            'magazine' => $magazine->load([
                'category:id,name,slug',
            ]),

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
                'meta_title' => $issue->meta_title
                    ?: $issue->title . ' — ' . $magazine->title,

                'meta_description' => $issue->meta_description
                    ?: $issue->description,

                'meta_keywords' => $issue->meta_keywords,
            ],
        ]);
    }
}
