<?php

namespace App\Http\Controllers\Admin\Interview;

use App\Actions\Interview\SyncInterviewTaxonomy;
use App\Http\Controllers\Controller;
use App\Http\Requests\Interview\SyncInterviewTaxonomyRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\TagResource;
use App\Models\Category;
use App\Models\Interview\Interview;
use App\Models\Tag;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InterviewTaxonomyController extends Controller
{
    public function index(
        Request $request,
        Interview $interview,
    ): Response {
        $categories = Category::query()
            ->where('status', true)
            ->when(
                $request->filled('category_search'),
                function ($query) use ($request) {
                    $search = $request
                        ->string('category_search')
                        ->trim()
                        ->toString();

                    $query->where(
                        'name',
                        'ilike',
                        "%{$search}%"
                    );
                }
            )
            ->orderBy('name')
            ->paginate(20, ['*'], 'categories_page')
            ->withQueryString();

        $tags = Tag::query()
            ->where('status', true)
            ->when(
                $request->filled('tag_search'),
                function ($query) use ($request) {
                    $search = $request
                        ->string('tag_search')
                        ->trim()
                        ->toString();

                    $query->where(
                        'name',
                        'ilike',
                        "%{$search}%"
                    );
                }
            )
            ->orderBy('name')
            ->paginate(20, ['*'], 'tags_page')
            ->withQueryString();

        $interview->load([
            'categories',
            'tags',
        ]);

        return Inertia::render(
            'admin/interview/taxonomy/Index',
            [
                'interview' => [
                    'id' => $interview->id,
                    'title' => $interview->title,
                ],

                'categories' => CategoryResource::collection(
                    $categories
                ),

                'tags' => TagResource::collection(
                    $tags
                ),

                'selectedCategoryIds' => $interview
                    ->categories
                    ->pluck('id')
                    ->values(),

                'selectedTagIds' => $interview
                    ->tags
                    ->pluck('id')
                    ->values(),

                'breadcrumbs' => BreadcrumbBuilder::make()
                    ->admin()
                    ->add('Interviews', route('admin.interviews.index'))
                    ->add($interview->title, route('admin.interviews.edit', $interview))
                    ->add('Taxonomy')
                    ->toArray()   
            ]
        );
    }

    public function update(
        SyncInterviewTaxonomyRequest $request,
        Interview $interview,
        SyncInterviewTaxonomy $action,
    ): RedirectResponse {
        $action->handle(
            $interview,
            $request->validated('category_ids', []),
            $request->validated('tag_ids', []),
        );

        return back()->with(
            'success',
            'Categories and tags updated successfully.'
        );
    }
}