<?php

namespace App\Http\Controllers\Admin\Research;

use App\Http\Controllers\Controller;
use App\Models\Research\ResearchArea;
use App\Models\Research\ResearchPaper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ResearchController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $area = $request->query('area');
        $status = $request->query('status');

        $papers = ResearchPaper::query()
            ->with(['area:id,name,slug', 'author:id,name,username'])
            ->when($search, fn ($q) => $q->where('title', 'ilike', "%{$search}%"))
            ->when($area, fn ($q) => $q->where('area_id', $area))
            ->when($status !== null && $status !== '', fn ($q) => $q->where('status', (bool) (int) $status))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Research/Index', [
            'papers' => $papers,
            'areas' => ResearchArea::orderBy('name')->get(['id', 'name', 'slug']),
            'filters' => ['search' => $search, 'area' => $area, 'status' => $status],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Research/Create', [
            'areas' => ResearchArea::orderBy('name')->get(['id', 'name', 'slug']),
            'authors' => $this->authorOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validatePaper($request);

        $data = $this->pullFields($validated);
        $data['slug'] = ResearchPaper::uniqueSlug($validated['title']);
        $data['cover_image'] = $this->storeCover($request);

        if ($file = $this->storeFile($request)) {
            $data = array_merge($data, $file);
        }

        ResearchPaper::create($data);

        return redirect()->route('admin.research.index')->with('success', 'Research paper created.');
    }

    public function edit(ResearchPaper $paper)
    {
        $paper->load(['area:id,name,slug', 'author:id,name,username']);

        return Inertia::render('Admin/Research/Edit', [
            'paper' => $paper,
            'areas' => ResearchArea::orderBy('name')->get(['id', 'name', 'slug']),
            'authors' => $this->authorOptions(),
        ]);
    }

    public function update(Request $request, ResearchPaper $paper)
    {
        $validated = $this->validatePaper($request);

        $data = $this->pullFields($validated);
        $data['slug'] = ResearchPaper::uniqueSlug($validated['title'], $paper->id);

        if ($request->hasFile('cover_image')) {
            $this->deleteFile($paper->cover_image);
            $data['cover_image'] = $this->storeCover($request);
        }

        if ($request->hasFile('file')) {
            $this->deleteFile($paper->file_path);
            $data = array_merge($data, $this->storeFile($request));
        }

        $paper->update($data);

        return redirect()->route('admin.research.index')->with('success', 'Research paper updated.');
    }

    public function updateStatus(Request $request, ResearchPaper $paper)
    {
        $validated = $request->validate(['status' => ['required', 'boolean']]);
        $paper->update(['status' => $validated['status']]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy(ResearchPaper $paper)
    {
        $this->deleteFile($paper->cover_image);
        $this->deleteFile($paper->file_path);
        $paper->delete();

        return redirect()->route('admin.research.index')->with('success', 'Research paper deleted.');
    }

    private function validatePaper(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'abstract' => ['nullable', 'string'],
            'authors' => ['nullable', 'string', 'max:255'],
            'author_id' => ['nullable', 'integer', 'exists:users,id'],
            'area_id' => ['nullable', 'integer', 'exists:research_areas,id'],
            'institution' => ['nullable', 'string', 'max:255'],
            'methodology' => ['nullable', 'string'],
            'doi' => ['nullable', 'string', 'max:255'],
            'citation' => ['nullable', 'string'],
            'keywords' => ['nullable', 'string', 'max:500'],
            'status' => ['nullable', 'boolean'],
            'featured' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx', 'max:51200'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ]);
    }

    private function pullFields(array $v): array
    {
        return [
            'title' => $v['title'],
            'abstract' => $v['abstract'] ?? null,
            'authors' => $v['authors'] ?? null,
            'author_id' => $v['author_id'] ?? null,
            'area_id' => $v['area_id'] ?? null,
            'institution' => $v['institution'] ?? null,
            'methodology' => $v['methodology'] ?? null,
            'doi' => $v['doi'] ?? null,
            'citation' => $v['citation'] ?? null,
            'keywords' => $v['keywords'] ?? null,
            'published_at' => $v['published_at'] ?? null,
            'status' => (bool) ($v['status'] ?? false),
            'featured' => (bool) ($v['featured'] ?? false),
            'meta_title' => $v['meta_title'] ?? null,
            'meta_description' => $v['meta_description'] ?? null,
            'meta_keywords' => $v['meta_keywords'] ?? null,
        ];
    }

    private function authorOptions()
    {
        return User::query()
            ->whereIn('user_type', ['admin', 'alfrik', 'expert', 'staff'])
            ->orderBy('name')
            ->limit(200)
            ->get(['id', 'name', 'username']);
    }

    private function storeCover(Request $request): ?string
    {
        if (! $request->hasFile('cover_image')) {
            return null;
        }

        $image = $request->file('cover_image');
        $name = uniqid('res_').'.'.$image->getClientOriginalExtension();
        Storage::disk('public')->put('images/research/'.$name, file_get_contents($image));

        return '/storage/images/research/'.$name;
    }

    /** @return array{file_path:string,file_size:int,file_type:string}|null */
    private function storeFile(Request $request): ?array
    {
        if (! $request->hasFile('file')) {
            return null;
        }

        $doc = $request->file('file');
        $ext = strtolower($doc->getClientOriginalExtension());
        $name = uniqid('research_').'.'.$ext;
        Storage::disk('public')->put('research/'.$name, file_get_contents($doc));

        return ['file_path' => '/storage/research/'.$name, 'file_size' => $doc->getSize(), 'file_type' => $ext];
    }

    private function deleteFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk('public')->delete(ltrim(str_replace('storage/', '', ltrim($path, '/')), '/'));
    }
}
