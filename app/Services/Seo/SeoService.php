<?php

namespace App\Services\Seo;

use App\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Model;

class SeoService
{
    public function __construct(
        protected StructuredDataService $structuredDataService,
    ) {
    }

    public function generate(
        Model $model,
        string $url,
        ?string $title = null,
        ?string $description = null,
        ?string $image = null,
        ?string $locale = null,
        array $structuredData = [],
    ): array {
        $locale ??= config('app.locale', 'en');

        $seo = $this->resolveMetadata(
            model: $model,
            locale: $locale,
        );

        $resolvedTitle = $seo?->title ?: $title;

        $resolvedDescription = $seo?->description ?: $description;

        $resolvedImage = $seo?->og_image_url ?: $image;

        $canonical = $seo?->canonical_url ?: $url;

        return [
            'title' => $this->clean($resolvedTitle),

            'description' => $this->clean($resolvedDescription),

            'canonical' => $canonical,

            'robots' => $this->resolveRobots($seo),

            'locale' => $locale,

            'og' => [
                'title' => $this->clean(
                    $seo?->og_title ?: $resolvedTitle
                ),

                'description' => $this->clean(
                    $seo?->og_description ?: $resolvedDescription
                ),

                'type' => $seo?->og_type ?: 'website',

                'url' => $canonical,

                'image' => $resolvedImage,
            ],

            'twitter' => [
                'card' => $seo?->twitter_card
                    ?: 'summary_large_image',

                'title' => $this->clean(
                    $seo?->twitter_title ?: $resolvedTitle
                ),

                'description' => $this->clean(
                    $seo?->twitter_description
                    ?: $resolvedDescription
                ),

                'image' => $seo?->twitter_image_url
                    ?: $resolvedImage,
            ],

            'schema_type' => $seo?->schema_type,

            'structured_data' => $this->structuredData(
                seo: $seo,
                data: $structuredData,
            ),
        ];
    }

    protected function structuredData(
        ?SeoMetadata $seo,
        array $data,
    ): array {
        if (!$seo?->schema_type) {
            return [];
        }

        return $this->structuredDataService->build(
            schemaType: $seo->schema_type,
            data: $data,
        );
    }

    protected function resolveMetadata(
        Model $model,
        string $locale,
    ): ?SeoMetadata {
        if (!method_exists($model, 'seo')) {
            return null;
        }

        return $model->seo()
            ->where('locale', $locale)
            ->first();
    }

    protected function resolveRobots(
        ?SeoMetadata $seo,
    ): string {
        if (!$seo) {
            return 'index, follow';
        }

        return sprintf(
            '%s, %s',
            $seo->indexable ? 'index' : 'noindex',
            $seo->followable ? 'follow' : 'nofollow',
        );
    }

    protected function clean(
        ?string $value,
    ): ?string {
        if (!filled($value)) {
            return null;
        }

        return trim(strip_tags($value));
    }
}