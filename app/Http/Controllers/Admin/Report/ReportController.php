<?php

namespace App\Http\Controllers\Admin\Report;

use App\Http\Controllers\Controller;
use App\Models\Report\Report;
use App\Models\Report\ReportCategory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $category = $request->query('category');
        $status = $request->query('status');

        $reports = Report::query()
            ->with(['category:id,name,slug', 'author:id,name,username'])
            ->when($search, fn ($q) => $q->where('title', 'ilike', "%{$search}%"))
            ->when($category, fn ($q) => $q->where('category_id', $category))
            ->when($status !== null && $status !== '', fn ($q) => $q->where('status', (bool) (int) $status))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return Inertia::render('Admin/Reports/Index', [
            'reports' => $reports,
            'categories' => ReportCategory::orderBy('name')->get(['id', 'name', 'slug']),
            'filters' => ['search' => $search, 'category' => $category, 'status' => $status],
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Reports/Create', [
            'categories' => ReportCategory::orderBy('name')->get(['id', 'name', 'slug']),
            'authors' => $this->authorOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateReport($request);

        $data = $this->pullFields($validated);
        $data['slug'] = Report::uniqueSlug($validated['title']);
        $data['cover_image'] = $this->storeCover($request);

        if ($file = $this->storeFile($request)) {
            $data = array_merge($data, $file);
        }

        Report::create($data);

        return redirect()->route('admin.reports.index')->with('success', 'Report created successfully.');
    }

    public function edit(Report $report)
    {
        $report->load(['category:id,name,slug', 'author:id,name,username']);

        return Inertia::render('Admin/Reports/Edit', [
            'report' => $report,
            'categories' => ReportCategory::orderBy('name')->get(['id', 'name', 'slug']),
            'authors' => $this->authorOptions(),
        ]);
    }

    public function update(Request $request, Report $report)
    {
        $validated = $this->validateReport($request);

        $data = $this->pullFields($validated);
        $data['slug'] = Report::uniqueSlug($validated['title'], $report->id);

        if ($request->hasFile('cover_image')) {
            $this->deleteFile($report->cover_image);
            $data['cover_image'] = $this->storeCover($request);
        }

        if ($request->hasFile('file')) {
            $this->deleteFile($report->file_path);
            $data = array_merge($data, $this->storeFile($request));
        }

        $report->update($data);

        return redirect()->route('admin.reports.index')->with('success', 'Report updated successfully.');
    }

    public function updateStatus(Request $request, Report $report)
    {
        $validated = $request->validate(['status' => ['required', 'boolean']]);
        $report->update(['status' => $validated['status']]);

        return back()->with('success', 'Status updated.');
    }

    public function destroy(Report $report)
    {
        $this->deleteFile($report->cover_image);
        $this->deleteFile($report->file_path);
        $report->delete();

        return redirect()->route('admin.reports.index')->with('success', 'Report deleted.');
    }

    private function validateReport(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'category_id' => ['nullable', 'integer', 'exists:report_categories,id'],
            'author_id' => ['nullable', 'integer', 'exists:users,id'],
            'report_year' => ['nullable', 'integer', 'min:1990', 'max:2100'],
            'status' => ['nullable', 'boolean'],
            'featured' => ['nullable', 'boolean'],
            'gated' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx', 'max:51200'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'meta_keywords' => ['nullable', 'string', 'max:500'],
        ]);
    }

    private function pullFields(array $v): array
    {
        return [
            'title' => $v['title'],
            'summary' => $v['summary'] ?? null,
            'category_id' => $v['category_id'] ?? null,
            'author_id' => $v['author_id'] ?? null,
            'report_year' => $v['report_year'] ?? null,
            'published_at' => $v['published_at'] ?? null,
            'status' => (bool) ($v['status'] ?? false),
            'featured' => (bool) ($v['featured'] ?? false),
            'gated' => (bool) ($v['gated'] ?? false),
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
        $name = uniqid('rep_').'.'.$image->getClientOriginalExtension();
        Storage::disk('public')->put('images/reports/'.$name, file_get_contents($image));

        return '/storage/images/reports/'.$name;
    }

    /**
     * @return array{file_path:string,file_size:int,file_type:string}|null
     */
    private function storeFile(Request $request): ?array
    {
        if (! $request->hasFile('file')) {
            return null;
        }

        $doc = $request->file('file');
        $ext = strtolower($doc->getClientOriginalExtension());
        $name = uniqid('report_').'.'.$ext;
        Storage::disk('public')->put('reports/'.$name, file_get_contents($doc));

        return [
            'file_path' => '/storage/reports/'.$name,
            'file_size' => $doc->getSize(),
            'file_type' => $ext,
        ];
    }

    private function deleteFile(?string $path): void
    {
        if (! $path) {
            return;
        }

        Storage::disk('public')->delete(ltrim(str_replace('storage/', '', ltrim($path, '/')), '/'));
    }
}
