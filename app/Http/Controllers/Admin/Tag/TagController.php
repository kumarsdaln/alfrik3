<?php

namespace App\Http\Controllers\Admin\Tag;

use App\Actions\Tag\CreateTag;
use App\Actions\Tag\DeleteTag;
use App\Actions\Tag\UpdateTag;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tag\StoreTagRequest;
use App\Http\Requests\Tag\UpdateTagRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TagController extends Controller
{
    public function index(Request $request): Response
    {
        $tags = Tag::query()
            ->when(
                $request->filled('search'),
                function ($query) use ($request) {
                    $search = $request->string('search')->trim()->toString();

                    $query->where(function ($query) use ($search) {
                        $query
                            ->where('name', 'ilike', "%{$search}%")
                            ->orWhere('slug', 'ilike', "%{$search}%");
                    });
                }
            )
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where(
                    'status',
                    $request->string('status')->toString() === '1'
                )
            )
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('admin/tag/Index', [
            'tags' => TagResource::collection($tags),

            'filters' => [
                'search' => $request->string('search')->toString(),
                'status' => $request->string('status')->toString(),
            ],

            'stats' => [
                'total' => Tag::count(),
                'active' => Tag::where('status', true)->count(),
                'inactive' => Tag::where('status', false)->count(),
            ],

            'breadcrumbs' => BreadcrumbBuilder::make()
                             ->admin()
                             ->add('Tags')
                             ->toArray()
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/tag/Create', [
            'breadcrumbs' => BreadcrumbBuilder::make()
                             ->admin()
                             ->add('Tags', route('admin.tags.index'))
                             ->add('Create')
                             ->toArray()
        ]);
    }

    public function store(
        StoreTagRequest $request,
        CreateTag $action,
    ): RedirectResponse {
        $action->handle($request->validated());

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag created successfully.');
    }

    public function edit(Tag $tag): Response
    {
        return Inertia::render('admin/tag/Edit', [
            'tag' => TagResource::make($tag),
            'breadcrumbs' => BreadcrumbBuilder::make()
                             ->admin()
                             ->add('Tags', route('admin.tags.index'))
                             ->add("{$tag->name} - Edit")
                             ->toArray()
        ]);
    }

    public function update(
        UpdateTagRequest $request,
        Tag $tag,
        UpdateTag $action,
    ): RedirectResponse {
        $action->handle($tag, $request->validated());

        return redirect()
            ->route('admin.tags.index')
            ->with('success', 'Tag updated successfully.');
    }

    public function destroy(
        Tag $tag,
        DeleteTag $action,
    ): RedirectResponse {
        $action->handle($tag);

        return back()->with('success', 'Tag deleted successfully.');
    }
}