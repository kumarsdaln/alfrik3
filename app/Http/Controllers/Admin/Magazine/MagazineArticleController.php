<?php

namespace App\Http\Controllers\Admin\Magazine;

use App\Actions\Magazine\ArchiveMagazineArticle;
use App\Actions\Magazine\CreateMagazineArticle;
use App\Actions\Magazine\DeleteMagazineArticle;
use App\Actions\Magazine\PublishMagazineArticle;
use App\Actions\Magazine\UpdateMagazineArticle;
use App\Enums\Magazine\MagazineArticleStatus;
use App\Enums\Magazine\MagazineArticleType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Magazine\StoreMagazineArticleRequest;
use App\Http\Requests\Magazine\UpdateMagazineArticleRequest;
use App\Http\Resources\Magazine\MagazineArticleResource;
use App\Models\Magazine\Magazine;
use App\Models\Magazine\MagazineArticle;
use App\Models\Magazine\MagazineIssue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class MagazineArticleController extends Controller
{
    public function index(
        Request $request,
        Magazine $magazine,
        MagazineIssue $issue,
    ): Response {
        $articles = $issue->articles()
            ->with([
                'author',
                'categories',
                'tags',
                'media',
            ])
            ->when(
                $request->filled('search'),
                fn ($query) => $query->where(function ($query) use ($request) {
                    $query
                        ->where('title', 'ilike', '%' . $request->search . '%')
                        ->orWhere('subtitle', 'ilike', '%' . $request->search . '%')
                        ->orWhere('excerpt', 'ilike', '%' . $request->search . '%');
                })
            )
            ->when(
                $request->filled('status'),
                fn ($query) => $query->where(
                    'status',
                    $request->string('status')
                )
            )
            ->when(
                $request->filled('type'),
                fn ($query) => $query->where(
                    'type',
                    $request->string('type')
                )
            )
            ->orderBy('position')
            ->orderBy('id')
            ->paginate(20)
            ->withQueryString();

        return Inertia::render('admin/magazines/articles/Index', [
            'magazine' => $magazine,
            'issue' => $issue,
            'articles' => MagazineArticleResource::collection($articles),

            'statusOptions' => $this->statusOptions(),
            'typeOptions' => $this->typeOptions(),

            'filters' => [
                'search' => $request->search,
                'status' => $request->status,
                'type' => $request->type,
            ],
        ]);
    }

    public function create(
        Magazine $magazine,
        MagazineIssue $issue,
    ): Response {
        return Inertia::render('admin/magazines/articles/Create', [
            'magazine' => $magazine,
            'issue' => $issue,

            'statusOptions' => $this->statusOptions(),
            'typeOptions' => $this->typeOptions(),
        ]);
    }

    public function store(
        StoreMagazineArticleRequest $request,
        Magazine $magazine,
        MagazineIssue $issue,
        CreateMagazineArticle $action,
    ): RedirectResponse {
        $action->handle(
            $issue,
            $request->validated()
        );

        return to_route(
            'admin.magazines.issues.articles.index',
            [
                'magazine' => $magazine,
                'issue' => $issue,
            ]
        )->with(
            'success',
            'Magazine article created successfully.'
        );
    }

    public function show(
        Magazine $magazine,
        MagazineIssue $issue,
        MagazineArticle $article,
    ): Response {
        $article->load([
            'author',
            'categories',
            'tags',
            'media',
            'seo',
        ]);

        return Inertia::render('admin/magazines/articles/Show', [
            'magazine' => $magazine,
            'issue' => $issue,
            'article' => new MagazineArticleResource($article),
        ]);
    }

    public function edit(
        Magazine $magazine,
        MagazineIssue $issue,
        MagazineArticle $article,
    ): Response {
        $article->load([
            'author',
            'categories',
            'tags',
            'media',
            'seo',
        ]);

        return Inertia::render('admin/magazines/articles/Edit', [
            'magazine' => $magazine,
            'issue' => $issue,
            'article' => new MagazineArticleResource($article),

            'statusOptions' => $this->statusOptions(),
            'typeOptions' => $this->typeOptions(),
        ]);
    }

    public function update(
        UpdateMagazineArticleRequest $request,
        Magazine $magazine,
        MagazineIssue $issue,
        MagazineArticle $article,
        UpdateMagazineArticle $action,
    ): RedirectResponse {
        $action->handle(
            $article,
            $request->validated()
        );

        return to_route(
            'admin.magazines.issues.articles.index',
            [
                'magazine' => $magazine,
                'issue' => $issue,
            ]
        )->with(
            'success',
            'Magazine article updated successfully.'
        );
    }

    public function destroy(
        Magazine $magazine,
        MagazineIssue $issue,
        MagazineArticle $article,
        DeleteMagazineArticle $action,
    ): RedirectResponse {
        $action->handle($article);

        return to_route(
            'admin.magazines.issues.articles.index',
            [
                'magazine' => $magazine,
                'issue' => $issue,
            ]
        )->with(
            'success',
            'Magazine article deleted successfully.'
        );
    }

    public function publish(
        Magazine $magazine,
        MagazineIssue $issue,
        MagazineArticle $article,
        PublishMagazineArticle $action,
    ): RedirectResponse {
        $action->handle($article);

        return back()->with(
            'success',
            'Magazine article published successfully.'
        );
    }

    public function archive(
        Magazine $magazine,
        MagazineIssue $issue,
        MagazineArticle $article,
        ArchiveMagazineArticle $action,
    ): RedirectResponse {
        $action->handle($article);

        return back()->with(
            'success',
            'Magazine article archived successfully.'
        );
    }

    private function statusOptions(): array
    {
        return collect(MagazineArticleStatus::cases())
            ->map(
                fn (MagazineArticleStatus $status) => [
                    'value' => $status->value,
                    'label' => $status->label(),
                    'color' => $status->color(),
                ]
            )
            ->values()
            ->all();
    }

    private function typeOptions(): array
    {
        return collect(MagazineArticleType::cases())
            ->map(
                fn (MagazineArticleType $type) => [
                    'value' => $type->value,
                    'label' => $type->label(),
                    'color' => $type->color(),
                ]
            )
            ->values()
            ->all();
    }
}