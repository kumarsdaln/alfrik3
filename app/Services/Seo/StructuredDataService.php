<?php

namespace App\Services\Seo;

use App\Enums\SeoSchemaType;
use App\Services\Seo\Schemas\ArticleSchema;
use App\Services\Seo\Schemas\OrganizationSchema;
use App\Services\Seo\Schemas\PersonSchema;
use App\Services\Seo\Schemas\ReportSchema;
use App\Services\Seo\Schemas\SchemaBuilder;
use App\Services\Seo\Schemas\ScholarlyArticleSchema;
use App\Services\Seo\Schemas\WebPageSchema;
use App\Services\Seo\Schemas\WebSiteSchema;
use InvalidArgumentException;

class StructuredDataService
{
    public function __construct(
        protected WebPageSchema $webPageSchema,
        protected ArticleSchema $articleSchema,
        protected ReportSchema $reportSchema,
        protected ScholarlyArticleSchema $scholarlyArticleSchema,
        protected PersonSchema $personSchema,
        protected OrganizationSchema $organizationSchema,
        protected WebSiteSchema $webSiteSchema,
    ) {}

    public function build(
        string $schemaType,
        array $data,
    ): array {
        $builder = $this->resolveBuilder($schemaType);
        return [
            '@context' => 'https://schema.org',
            '@graph' => [
                $builder->build($data),
            ],
        ];
    }

    protected function resolveBuilder(
        string $schemaType,
    ): SchemaBuilder {
        return match ($schemaType) {
            SeoSchemaType::WebPage->value => $this->webPageSchema,
            SeoSchemaType::Article->value => $this->articleSchema,
            SeoSchemaType::Report->value => $this->reportSchema,
            SeoSchemaType::ScholarlyArticle->value => $this->scholarlyArticleSchema,
            SeoSchemaType::Person->value => $this->personSchema,
            SeoSchemaType::Organization->value => $this->organizationSchema,
            SeoSchemaType::WebSite->value => $this->webSiteSchema,

            default => throw new InvalidArgumentException(
                "Unsupported SEO schema type [{$schemaType}]."
            ),
        };
    }
}
