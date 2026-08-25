<?php

namespace App\Http\Controllers\Public\Magazine;

use App\Http\Controllers\Controller;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use App\Models\Magazine\Magazine;
use App\Models\Magazine\MagazineCategory;
use Illuminate\Http\Request;
use Inertia\Inertia;

class MagazineController extends Controller
{
    public function index(Request $request)
    {
        $activeCategory = $request->query('category');
        $search = trim((string) $request->query('search', ''));

        $columns = [
            'id', 'title', 'subtitle', 'cover_image', 'slug', 'category_id',
            'author_id', 'published_at', 'created_at',
        ];

        $query = Magazine::query()
            ->published()
            ->with(['category:id,name,slug', 'author:id,name,username'])
            ->when($activeCategory, fn ($q) => $q->whereHas(
                'category',
                fn ($c) => $c->where('slug', $activeCategory)
            ))
            ->when($search !== '', fn ($q) => $q->where(function ($w) use ($search) {
                $w->where('title', 'ilike', "%{$search}%")
                    ->orWhere('subtitle', 'ilike', "%{$search}%");
            }))
            ->orderByDesc('published_at')
            ->orderByDesc('created_at');

        // Featured = newest published issue (only on the unfiltered landing view).
        $featured = null;
        if (! $activeCategory && $search === '') {
            $featured = Magazine::published()
                ->with(['category:id,name,slug', 'author:id,name,username'])
                ->orderByDesc('published_at')
                ->orderByDesc('created_at')
                ->first($columns);
        }

        $categories = MagazineCategory::query()
            ->withCount(['magazines as published_count' => fn ($q) => $q->where('status', true)])
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'icon']);

        return Inertia::render('Magazine/Index', [
            'magazines' => Inertia::scroll(fn () => $query->paginate(12, $columns)->withQueryString()),
            'featured' => $featured,
            'categories' => $categories,
            'qfilters' => [
                'category' => $activeCategory,
                'search' => $search,
            ],
            'breadcrumbs' => BreadcrumbBuilder::make()->home()->add('Magazine')->toArray(),
        ]);
    }

    public function view(string $category, string $slug)
    {
        $magazine = Magazine::query()
            ->published()
            ->with(['category:id,name,slug', 'author:id,name,username'])
            ->where('slug', $slug)
            ->firstOrFail();

        // Related issues from the same category (fallback to latest others).
        $related = Magazine::query()
            ->published()
            ->with('category:id,name,slug')
            ->where('id', '!=', $magazine->id)
            ->when(
                $magazine->category_id,
                fn ($q) => $q->where('category_id', $magazine->category_id),
                fn ($q) => $q->orderByDesc('created_at'),
            )
            ->latest('created_at')
            ->take(4)
            ->get(['id', 'title', 'subtitle', 'cover_image', 'slug', 'category_id']);

        return Inertia::render('Magazine/Show', [
            'magazine' => $magazine,
            'related' => $related,
            'breadcrumbs' => BreadcrumbBuilder::make()->home()
                ->add('Magazine', route('magazine.index'))
                ->add($magazine->title)
                ->toArray(),
            'meta_data' => [
                'meta_title' => $magazine->meta_title,
                'meta_description' => $magazine->meta_description,
                'meta_keywords' => $magazine->meta_keywords,
                'image_path' => $magazine->cover_image,
                'type' => 'Article',
            ],
        ]);
    }
}
