<?php

namespace App\Http\Controllers\Admin\Magazine;

use App\Http\Controllers\Controller;
use App\Models\Magazine\Magazine;
use App\Models\Magazine\MagazineCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class MagazineController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $status = $request->query('status');

        $magazines = Magazine::query()
            ->with(['category:id,name,slug', 'author:id,name,username'])
            ->when($search, fn ($q) => $q->where('title', 'ilike', "%{$search}%"))
            ->when($category, fn ($q) => $q->where('category_id', $category))
            ->when($status !== null && $status !== '', fn ($q) => $q->where('status', (bool) (int) $status))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Magazine/Index', [
            'magazines' => $magazines,
            'categories' => MagazineCategory::orderBy('name')->get(['id', 'name', 'slug']),
            'filters' => [
                'search' => $search,
                'category' => $category,
                'status' => $status,
            ],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Magazine/Create', [
            'categories' => MagazineCategory::orderBy('name')->get(['id', 'name', 'slug']),
            'authors' => $this->authorOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateMagazine($request);

        $data = $this->pullFields($validated);
        $data['slug'] = Magazine::uniqueSlug($validated['title']);
        $data['content'] = $this->buildContent($request);
        $data['cover_image'] = $this->storeCover($request) ?? null;
        $data['status'] = (bool) ($validated['status'] ?? false);

        Magazine::create($data);

        return redirect()
            ->route('admin.magazine.index')
            ->with('success', 'Magazine issue created successfully.');
    }

    public function edit(Magazine $magazine)
    {
        $magazine->load('category:id,name,slug');

        return Inertia::render('Admin/Magazine/Edit', [
            'magazine' => array_merge($magazine->toArray(), [
                'sections' => $magazine->sections,
            ]),
            'categories' => MagazineCategory::orderBy('name')->get(['id', 'name', 'slug']),
            'authors' => $this->authorOptions(),
        ]);
    }

    public function update(Request $request, Magazine $magazine)
    {
        $validated = $this->validateMagazine($request);

        $data = $this->pullFields($validated);
        $data['slug'] = Magazine::uniqueSlug($validated['title'], $magazine->id);
        $data['content'] = $this->buildContent($request);
        $data['status'] = (bool) ($validated['status'] ?? false);

        if ($request->hasFile('cover_image')) {
            $this->deleteCover($magazine->cover_image);
            $data['cover_image'] = $this->storeCover($request);
        }

        $magazine->update($data);

        return redirect()
            ->route('admin.magazine.index')
            ->with('success', 'Magazine issue updated successfully.');
    }

    public function updateStatus(Request $request, Magazine $magazine)
    {
        $validated = $request->validate(['status' => ['required', 'boolean']]);
        $magazine->update(['status' => $validated['status']]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy(Magazine $magazine)
    {
        $this->deleteCover($magazine->cover_image);
        $magazine->delete();

        return redirect()
            ->route('admin.magazine.index')
            ->with('success', 'Magazine issue deleted.');
    }

    private function validateMagazine(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:magazine_categories,id'],
            'author_id' => ['nullable', 'integer', 'exists:users,id'],
            'status' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
            'sections' => ['nullable', 'array'],
            'sections.*.section' => ['nullable', 'string', 'max:255'],
            'sections.*.content' => ['nullable', 'string'],
        ]);
    }

    /**
     * Users eligible to be credited as an issue author.
     */
    private function authorOptions()
    {
        return User::query()
            ->where(fn ($q) => $q->whereIn('user_type', ['admin', 'alfrik', 'staff'])->orWhereHas('expert'))
            ->orderBy('name')
            ->limit(200)
            ->get(['id', 'name', 'username']);
    }

    private function pullFields(array $validated): array
    {
        return [
            'title' => $validated['title'],
            'subtitle' => $validated['subtitle'] ?? null,
            'category_id' => $validated['category_id'] ?? null,
            'author_id' => $validated['author_id'] ?? null,
            'published_at' => $validated['published_at'] ?? null,
            'meta_title' => $validated['meta_title'] ?? null,
            'meta_description' => $validated['meta_description'] ?? null,
            'meta_keywords' => $validated['meta_keywords'] ?? null,
        ];
    }

    /**
     * Assemble the sectioned content JSON stored in `magazine.content`.
     */
    private function buildContent(Request $request): string
    {
        $sections = collect($request->input('sections', []))
            ->filter(fn ($s) => filled($s['section'] ?? null) || filled($s['content'] ?? null))
            ->map(fn ($s) => [
                'section' => $s['section'] ?? '',
                'content' => $s['content'] ?? '',
            ])
            ->values()
            ->all();

        return json_encode(['sections' => $sections], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    private function storeCover(Request $request): ?string
    {
        if (! $request->hasFile('cover_image')) {
            return null;
        }

        $image = $request->file('cover_image');
        $fileName = uniqid('mag_').'.'.$image->getClientOriginalExtension();
        Storage::disk('public')->put('images/magazine/'.$fileName, file_get_contents($image));

        return '/storage/images/magazine/'.$fileName;
    }

    private function deleteCover(?string $path): void
    {
        if (! $path) {
            return;
        }

        $relative = ltrim(str_replace('storage/', '', ltrim($path, '/')), '/');
        Storage::disk('public')->delete($relative);
    }
}
