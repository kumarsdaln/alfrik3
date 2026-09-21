<?php

namespace App\Http\Controllers\Admin\Magazine;

use App\Actions\Magazine\ArchiveMagazine;
use App\Actions\Magazine\CreateMagazine;
use App\Actions\Magazine\DeleteMagazine;
use App\Actions\Magazine\PublishMagazine;
use App\Actions\Magazine\UpdateMagazine;
use App\Http\Controllers\Controller;
use App\Http\Requests\Magazine\StoreMagazineRequest;
use App\Http\Requests\Magazine\UpdateMagazineRequest;
use App\Models\Magazine\Magazine;
use App\Models\User;
use App\Enums\Magazine\MagazineStatus;
use App\Http\Resources\Magazine\MagazineResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MagazineController extends Controller
{
    public function index(Request $request): Response
    {
        $magazines = Magazine::query()
            ->with([
                'author:id,name,username,avatar',
                'media',
                'categories:id,name,slug',
                'tags:id,name,slug',
            ])
            ->when(
                $request->filled('search'),
                fn($query) => $query->where(function ($query) use ($request) {
                    $search = $request->input('search');

                    $query
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%");
                })
            )
            ->when(
                $request->filled('status'),
                fn($query) => $query->where(
                    'status',
                    $request->input('status')
                )
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $stats = [
            [
                'label' => 'Total Magazines',
                'value' => Magazine::count(),
            ],
            [
                'label' => 'Published',
                'value' => Magazine::where(
                    'status',
                    MagazineStatus::Published
                )->count(),
            ],
            [
                'label' => 'Draft',
                'value' => Magazine::where(
                    'status',
                    MagazineStatus::Draft
                )->count(),
            ],
            [
                'label' => 'Archived',
                'value' => Magazine::where(
                    'status',
                    MagazineStatus::Archived
                )->count(),
            ],
            [
                'label' => 'Featured',
                'value' => Magazine::where('featured', true)->count(),
            ],
        ];

        return Inertia::render('admin/magazines/Index', [
            'magazines' => MagazineResource::collection($magazines),

            'stats' => $stats,

            'filters' => [
                'search' => $request->input('search'),
                'status' => $request->input('status'),
            ],

            'statusOptions' => MagazineStatus::dropdown(),

            'breadcrumbs' => [
                [
                    'label' => 'Magazines',
                    'href' => route('admin.magazines.index'),
                ],
            ],
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('admin/magazines/Create', [
            'authors' => User::query()
                ->select([
                    'id',
                    'name',
                    'username',
                    'avatar',
                ])
                ->orderBy('name')
                ->get(),

            'statusOptions' => MagazineStatus::dropdown(),
        ]);
    }

    public function store(
        StoreMagazineRequest $request,
        CreateMagazine $action,
    ): RedirectResponse {
        $action->handle($request->validated());
        Inertia::flash('toast', ['type' => 'success', 'message' => __('Magazine created successfully.')]);
        return to_route('admin.magazines.index');
    }

    public function edit(Magazine $magazine): Response
    {
        $magazine->load([
            'author:id,name,username,avatar',
            'media',
            'categories:id,name,slug',
            'tags:id,name,slug',
            'seo',
        ]);

        return Inertia::render('admin/magazines/Edit', [
            'magazine' => $magazine,
            'authors' => User::query()
                ->select([
                    'id',
                    'name',
                    'username',
                    'avatar',
                ])
                ->orderBy('name')
                ->get(),

            'statuses' => collect(MagazineStatus::cases())
                ->map(fn(MagazineStatus $status) => [
                    'value' => $status->value,
                    'label' => str($status->value)->headline()->toString(),
                ])
                ->values(),
        ]);
    }

    public function update(
        UpdateMagazineRequest $request,
        Magazine $magazine,
        UpdateMagazine $action,
    ): RedirectResponse {
        $action->handle(
            $magazine,
            $request->validated(),
        );

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Magazine updated successfully.')]);

        return to_route('admin.magazines.index');
    }

    public function destroy(
        Magazine $magazine,
        DeleteMagazine $action,
    ): RedirectResponse {
        $action->handle($magazine);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Magazine deleted successfully.')]);

        return to_route('admin.magazines.index');
    }

    public function publish(
        Magazine $magazine,
        PublishMagazine $action,
    ): RedirectResponse {
        $action->handle($magazine);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Magazine published successfully.')]);

        return back();
    }

    public function archive(
        Magazine $magazine,
        ArchiveMagazine $action,
    ): RedirectResponse {
        $action->handle($magazine);

        Inertia::flash('toast', ['type' => 'success', 'message' => __('Magazine archived successfully.')]);

        return back();
    }
}
