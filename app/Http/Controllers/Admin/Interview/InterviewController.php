<?php

namespace App\Http\Controllers\Admin\Interview;

use App\Actions\Interview\AddInterviewParticipant;
use App\Actions\Interview\CreateInterview;
use App\Actions\Interview\DeleteInterview;
use App\Actions\Interview\RemoveInterviewParticipant;
use App\Actions\Interview\UpdateInterview;
use App\Enums\Interview\InterviewStatus;
use App\Enums\Interview\InterviewType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Interview\StoreInterviewParticipantRequest;
use App\Http\Requests\Interview\StoreInterviewRequest;
use App\Http\Requests\Interview\UpdateInterviewRequest;
use App\Http\Resources\Interview\InterviewResource;
use App\Models\Interview\Interview;
use App\Models\Interview\InterviewParticipant;
use App\Support\Breadcrumbs\BreadcrumbBuilder;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class InterviewController extends Controller
{
    public function index(Request $request): Response
    {
        $search = $request->query('search');
        $type = $request->query('type');
        $status = $request->query('status');
        $category = $request->query('category');
        $tags = $request->query('tags');

        $interviews = Interview::query()
            ->with([
                'creator',
                'participants.user',
                'categories',
                'tags',
            ])
            ->when(
                $search,
                fn($query, $search) => $query->where(function ($query) use ($search) {
                    $query
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                }),
            )
            ->when(
                filled($type),
                fn($query) => $query->where('interview_type', $type),
            )
            ->when(
                filled($status),
                fn($query) => $query->where('status', $status),
            )
            ->when(
                filled($category),
                fn($query) => $query->whereHas(
                    'categories',
                    fn($query) => $query->where('slug', $category),
                ),
            )
            ->when(
                filled($tags),
                fn($query) => $query->whereHas(
                    'tags',
                    fn($query) => $query->where('slug', $tags),
                ),
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            'total' => Interview::count(),
            'published' => Interview::where('status', true)->count(),
            'draft' => Interview::where('status', false)->count(),
            'this_month' => Interview::whereBetween('created_at', [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ])->count(),
        ];

        return Inertia::render('admin/interview/Index', [
            'interviews' => InterviewResource::collection($interviews),

            'typeOptions' => InterviewType::dropdown(),
            'statusOptions' => InterviewStatus::dropdown(),

            'filters' => [
                'search' => $search,
                'type' => $type,
                'status' => $status,
                'category' => $category,
                'tags' => $tags,
            ],

            'stats' => $stats,

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Interviews')
                ->toArray(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/interview/Create', [
            'typeOptions' => InterviewType::dropdown(),
            'statusOptions' => InterviewStatus::dropdown(),

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Interviews', route('admin.interviews.index'))
                ->add('Create Interview')
                ->toArray(),
        ]);
    }

    public function store(
        StoreInterviewRequest $request,
        CreateInterview $action,
    ) {
        $interview = $action->handle(
            $request->validated()
        );

        Inertia::flash('success', 'Interview created successfully.');

        return to_route(
            'admin.interviews.edit',
            $interview
        );
    }

    public function show(Interview $interview): Response
    {
        $interview->load([
            'creator',
            'participants.user',
            'questions.asker',
            'questions.answers.answerer',
            'media',
            'categories',
            'tags',
            'seo',
        ]);

        return Inertia::render('admin/interview/Show', [
            'interview' => new InterviewResource($interview),

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Interviews', route('admin.interviews.index'))
                ->add($interview->title)
                ->toArray(),
        ]);
    }

    public function edit(Interview $interview): Response
    {
        $interview->load([
            'creator',
            'participants.user',
            'questions.asker',
            'questions.answers.answerer',
            'media',
            'categories',
            'tags',
            'seo',
        ]);

        return Inertia::render('admin/interview/Edit', [
            'interview' => new InterviewResource($interview),

            'typeOptions' => InterviewType::dropdown(),
            'statusOptions' => InterviewStatus::dropdown(),

            'breadcrumbs' => BreadcrumbBuilder::make()
                ->home()
                ->add('Interviews', route('admin.interviews.index'))
                ->add($interview->title)
                ->toArray(),
        ]);
    }

    public function update(
        UpdateInterviewRequest $request,
        Interview $interview,
        UpdateInterview $action,
    ) {
        $action->handle(
            $interview,
            $request->validated()
        );

        Inertia::flash('success', 'Interview updated successfully.');

        return to_route(
            'admin.interviews.edit',
            $interview
        );
    }

    public function destroy(
        Interview $interview,
        DeleteInterview $action,
    ) {
        $action->handle($interview);

        Inertia::flash('success', 'Interview deleted successfully.');

        return to_route('admin.interviews.index');
    }


    public function addParticipant(
        StoreInterviewParticipantRequest $request,
        Interview $interview,
        AddInterviewParticipant $action,
    ) {
        $action->handle(
            $interview,
            $request->validated(),
        );

        Inertia::flash(
            'success',
            'Participant added successfully.',
        );

        return to_route(
            'admin.interviews.edit',
            $interview,
        );
    }

    public function removeParticipant(
        InterviewParticipant $participant,
        RemoveInterviewParticipant $action,
    ) {
        $action->handle($participant);

        Inertia::flash(
            'success',
            'Participant removed successfully.',
        );

        return to_route(
            'admin.interviews.edit',
            $participant->interview,
        );
    }
}
