<?php

namespace App\Http\Controllers\Admin\Interview;

use App\Enums\Interview\Status;
use App\Enums\Interview\Type;
use App\Http\Controllers\Controller;
use App\Http\Requests\Interview\StoreInterviewRequest;
use App\Models\Interview\Interview;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;
use Inertia\Inertia;

class InterviewController extends Controller
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
    public function index(Request $request)
    {
        $sortKey = $request->string('sort')->toString();
        $sortDirection = $request->string('direction')->toString() === 'asc'
            ? 'asc'
            : 'desc';

        $sortableColumns = [
            'id',
            'title',
            'interview_type',
            'status',
            'created_at',
            'updated_at',
        ];

        $interviews = Interview::query()
            ->select('id', 'title', 'interview_type', 'created_by', 'status', 'created_at', 'updated_at')
            ->with('createdBy:id,name')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->string('search')->toString();

                $query->where(function ($query) use ($search) {
                    $query
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('interview_type', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->string('status')->toString());
            })
            ->when(
                in_array($sortKey, $sortableColumns, true),
                fn($query) => $query->orderBy($sortKey, $sortDirection),
                fn($query) => $query->orderByDesc('id')
            )
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admin/Interviews/Index', [
            'interviews' => $interviews,
            'statuses' => Status::dropdown(),
            'types' => Type::dropdown()
        ]);
    }

    public function create()
    {

        return Inertia::render('Admin/Interviews/Create', [
            'statuses' => Status::dropdown(),
            'types' => Type::dropdown()
        ]);
    }

    public function store(StoreInterviewRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('thumbnail')) {
            $upload = $this->fileUploadService->upload(
                $request->file('thumbnail'),
                'interviews/thumbnails',
                null,
                'public'
            );

            $data['thumbnail'] = $upload['url'];
        }

        $data['created_by'] = $request->user()->id;

        $interview = Interview::create($data);
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Interview created successfully.')]);
        return to_route('admin.interviews.participants', $interview->id);
    }

    public function show(Interview $interview)
    {

        return Inertia::render('Admin/Interviews/Show', [
            'interview' => $interview->load([
                'participants.user',
                'questions.interviewer',
                'questions.answers.answeredBy',
                'media'
            ])
        ]);
    }

    public function edit(Interview $interview)
    {
        return Inertia::render('Admin/Interviews/Edit', [
            'interview' => $interview,
            'statuses' => Status::dropdown(),
            'types' => Type::dropdown()
        ]);
    }

    public function update(Request $request, Interview $interview)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'interview_type' => ['required', new Enum(Type::class)],
            'status' => ['required', new Enum(Status::class)],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'duration' => ['nullable', 'integer'],
            'published_at' => ['required']
        ]);

        if ($request->hasFile('thumbnail')) {
            $upload = $this->fileUploadService->upload(
                $request->file('thumbnail'),
                'interviews/thumbnails',
                $interview->thumbnail,
                'public'
            );

            $validated['thumbnail'] = $upload['url'];
        } else {
            unset($validated['thumbnail']);
        }

        $interview->update($validated);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Interview updated successfully.')]);
        return to_route('admin.interviews.index');
    }

    public function updateStatus(Request $request, Interview $interview)
    {
        $request->validate(['status' => ['required', new Enum(Status::class)]]);
        $interview->status = $request->status;
        $interview->save();
        return response()->json(['message' => 'Status updated successfully!']);
    }
    public function updateType(Request $request, Interview $interview)
    {
        $request->validate(['interview_type' => ['required', new Enum(Type::class)]]);
        $interview->interview_type = $request->interview_type;
        $interview->save();
        return response()->json(['message' => 'Type updated successfully!']);
    }

    public function destroy(Request $request, Interview $interview)
    {
        $interview->delete();
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Interview deleted successfully.')]);
        return to_route('admin.interviews.index');
    }
}
