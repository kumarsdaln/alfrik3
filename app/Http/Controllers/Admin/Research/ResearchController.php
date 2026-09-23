<?php

namespace App\Http\Controllers\Admin\Research;

use App\Actions\Research\CreateResearch;
use App\Actions\Research\UpdateResearch;
use App\Enums\Research\ResearchStatus;
use App\Enums\Research\ResearchType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Research\StoreResearchRequest;
use App\Http\Requests\Research\UpdateResearchRequest;
use App\Http\Resources\Research\ResearchResource;
use App\Models\Research\Research;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ResearchController extends Controller
{
    public function index(Request $request): Response
    {
        $researches = Research::query()
            ->with('author')
            ->when(
                $request->filled('search'),
                fn($query) => $query->where(function ($query) use ($request) {
                    $query
                        ->where(
                            'title',
                            'ilike',
                            '%' . $request->search . '%'
                        )
                        ->orWhere(
                            'slug',
                            'ilike',
                            '%' . $request->search . '%'
                        );
                })
            )
            ->when(
                $request->filled('status'),
                fn($query) => $query->where(
                    'status',
                    $request->status
                )
            )
            ->when(
                $request->filled('type'),
                fn($query) => $query->where(
                    'type',
                    $request->type
                )
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        /*
    |--------------------------------------------------------------------------
    | Stats
    |--------------------------------------------------------------------------
    */

        $stats = [
            'total' => Research::count(),

            'draft' => Research::where(
                'status',
                ResearchStatus::Draft
            )->count(),

            'published' => Research::where(
                'status',
                ResearchStatus::Published
            )->count(),

            'featured' => Research::where(
                'featured',
                true
            )->count(),
        ];

        return Inertia::render('admin/research/Index', [
            'researches' => ResearchResource::collection(
                $researches
            ),

            'stats' => $stats,

            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
                'type' => $request->type,
            ],

            'statusOptions' => ResearchStatus::dropdown(),

            'typeOptions' => ResearchType::dropdown(),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/research/Create', [
            'statusOptions' => ResearchStatus::dropdown(),
            'typeOptions' => ResearchType::dropdown(),
            'authors' => User::query()
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function store(
        StoreResearchRequest $request,
        CreateResearch $action,
    ): RedirectResponse {
        $research = $action->handle(
            $request->validated()
        );
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Research created.')]);

        return to_route(
            'admin.research.show',
            $research
        );
    }

    public function show(Research $research): Response
    {
        $research->load('author');

        return Inertia::render('admin/research/Show', [
            'research' => new ResearchResource($research),
        ]);
    }

    public function edit(Research $research): Response
    {
        $research->load('author');

        return Inertia::render('admin/research/Edit', [
            'research' => new ResearchResource($research),
            'statusOptions' => ResearchStatus::dropdown(),
            'typeOptions' => ResearchType::dropdown(),
            'authors' => User::query()
                ->orderBy('name')
                ->get(['id', 'name']),
        ]);
    }

    public function update(
        UpdateResearchRequest $request,
        Research $research,
        UpdateResearch $action,
    ): RedirectResponse {
        $action->handle(
            research: $research,
            data: $request->validated(),
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Research updated.')]);

        return to_route(
            'admin.research.show',
            $research
        );
    }

    public function destroy(Research $research): RedirectResponse
    {
        $research->delete();
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Research destroyed.')]);
        return to_route('admin.research.index');
    }
}
