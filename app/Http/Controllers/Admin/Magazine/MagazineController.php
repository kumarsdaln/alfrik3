<?php

namespace App\Http\Controllers\Admin\Magazine;

use App\Actions\Magazine\ArchiveMagazine;
use App\Actions\Magazine\CreateMagazine;
use App\Actions\Magazine\DeleteMagazine;
use App\Actions\Magazine\PublishMagazine;
use App\Actions\Magazine\UpdateMagazine;
use App\Enums\Magazine\MagazineStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Magazine\StoreMagazineRequest;
use App\Http\Requests\Magazine\UpdateMagazineRequest;
use App\Http\Resources\Magazine\MagazineResource;
use App\Models\Magazine\Magazine;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MagazineController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index(Request $request): Response
    {
        $magazines = Magazine::query()
            ->with([
                'participants.user',
                'participants.role',
                'media',
                'categories:id,name,slug',
                'tags:id,name,slug',
            ])
            ->when(
                $request->filled('search'),
                function (Builder $query) use ($request) {
                    $search = $request->string('search')->trim()->toString();

                    $query->where(function (Builder $query) use ($search) {
                        $query
                            ->where('title', 'like', "%{$search}%")
                            ->orWhere('slug', 'like', "%{$search}%")
                            ->orWhere('subtitle', 'like', "%{$search}%")
                            ->orWhere('description', 'like', "%{$search}%");
                    });
                }
            )
            ->when(
                $request->filled('status'),
                fn (Builder $query) => $query->where(
                    'status',
                    $request->input('status'),
                )
            )
            ->latest()
            ->paginate(20)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [
            'total' => Magazine::query()->count(),

            'published' => Magazine::query()
                ->where('status', MagazineStatus::Published)
                ->count(),

            'draft' => Magazine::query()
                ->where('status', MagazineStatus::Draft)
                ->count(),

            'this_month' => Magazine::query()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
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

    /*
    |--------------------------------------------------------------------------
    | Create
    |--------------------------------------------------------------------------
    */

    public function create(): Response
    {
        return Inertia::render('admin/magazines/Create', [
            'statusOptions' => MagazineStatus::dropdown(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Store
    |--------------------------------------------------------------------------
    */

    public function store(
        StoreMagazineRequest $request,
        CreateMagazine $action,
    ): RedirectResponse {
        $action->handle($request->validated());

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Magazine created successfully.'),
        ]);

        return to_route('admin.magazines.index');
    }

    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit(Magazine $magazine): Response
    {
        $magazine->load([
            'participants.user',
            'participants.role',
            'media',
            'categories:id,name,slug',
            'tags:id,name,slug',
            'seo',
        ]);

        return Inertia::render('admin/magazines/Edit', [
            'magazine' => MagazineResource::make($magazine),

            'statusOptions' => MagazineStatus::dropdown(),

            'breadcrumbs' => [
                [
                    'label' => 'Magazines',
                    'href' => route('admin.magazines.index'),
                ],
                [
                    'label' => $magazine->title,
                    'href' => route('admin.magazines.edit', $magazine),
                ],
            ],
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Update
    |--------------------------------------------------------------------------
    */

    public function update(
        UpdateMagazineRequest $request,
        Magazine $magazine,
        UpdateMagazine $action,
    ): RedirectResponse {
        $action->handle(
            $magazine,
            $request->validated(),
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Magazine updated successfully.'),
        ]);

        return to_route('admin.magazines.index');
    }

    /*
    |--------------------------------------------------------------------------
    | Destroy
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Magazine $magazine,
        DeleteMagazine $action,
    ): RedirectResponse {
        $action->handle($magazine);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Magazine deleted successfully.'),
        ]);

        return to_route('admin.magazines.index');
    }

    /*
    |--------------------------------------------------------------------------
    | Publish
    |--------------------------------------------------------------------------
    */

    public function publish(
        Magazine $magazine,
        PublishMagazine $action,
    ): RedirectResponse {
        $action->handle($magazine);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Magazine published successfully.'),
        ]);

        return back();
    }

    /*
    |--------------------------------------------------------------------------
    | Archive
    |--------------------------------------------------------------------------
    */

    public function archive(
        Magazine $magazine,
        ArchiveMagazine $action,
    ): RedirectResponse {
        $action->handle($magazine);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('Magazine archived successfully.'),
        ]);

        return back();
    }
}