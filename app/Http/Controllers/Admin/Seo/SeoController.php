<?php

namespace App\Http\Controllers\Admin\Seo;


use App\Actions\Seo\DeleteSeoMetadata;
use App\Actions\Seo\SaveSeoMetadata;
use App\Enums\SEO\SeoLocale;
use App\Enums\SEO\SeoOgType;
use App\Enums\SEO\SeoSchemaType;
use App\Enums\SEO\SeoTwitterCard;
use App\Http\Controllers\Controller;
use App\Http\Requests\Seo\StoreSeoMetadataRequest;
use App\Http\Resources\SeoMetadataResource;
use App\Support\ModelResolver;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class SeoController extends Controller
{
    public function index(
        string $type,
        int $id,
    ): Response {
        $model = ModelResolver::resolve($type, $id);

        abort_unless(
            method_exists($model, 'seo'),
            404
        );

        $seo = $model->seo()
            ->orderBy('locale')
            ->first();

        return Inertia::render('admin/seo/Index', [
            'model' => [
                'type' => $type,
                'id' => $model->id,
                'title' => $this->modelTitle($model),
            ],
            'localeOptions' => SeoLocale::dropdown(),
            'schemaTypeOptions' => SeoSchemaType::dropdown(),
            'ogTypeOptions' => SeoOgType::dropdown(),
            'twitterCardOptions' => SeoTwitterCard::dropdown(),
            'seo' => $seo
                ? new SeoMetadataResource($seo)
                : null,
        ]);
    }

    public function store(
        StoreSeoMetadataRequest $request,
        string $type,
        int $id,
        SaveSeoMetadata $action,
    ): RedirectResponse 
    {
        $model = ModelResolver::resolve($type, $id);

        abort_unless(
            method_exists($model, 'seo'),
            404
        );

        $action->handle(
            model: $model,
            data: $request->validated(),
        );

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('SEO metadata saved successfully.'),
        ]);

        return back();
    }

    public function destroy(
        string $type,
        int $id,
        int $seoId,
        DeleteSeoMetadata $action,
    ): RedirectResponse {
        $model = ModelResolver::resolve($type, $id);

        abort_unless(
            method_exists($model, 'seo'),
            404
        );

        $seo = $model->seo()
            ->whereKey($seoId)
            ->firstOrFail();

        $action->handle($seo);

        Inertia::flash('toast', [
            'type' => 'success',
            'message' => __('SEO metadata deleted successfully.'),
        ]);

        return back();
    }

    private function modelTitle(Model $model): string
    {
        return $model->title
            ?? $model->name
            ?? class_basename($model);
    }
}