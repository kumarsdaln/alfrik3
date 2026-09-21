<?php

namespace App\Http\Requests\Seo;

use App\Enums\SEO\SeoLocale;
use App\Enums\SEO\SeoOgType;
use App\Enums\SEO\SeoSchemaType;
use App\Enums\SEO\SeoTwitterCard;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class StoreSeoMetadataRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'canonical_url' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'indexable' => [
                'required',
                'boolean',
            ],

            'followable' => [
                'required',
                'boolean',
            ],

            'og_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'og_description' => [
                'nullable',
                'string',
            ],

            'og_type' => [
                'nullable',
                new Enum(SeoOgType::class),
            ],

            'og_image_url' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'twitter_card' => [
                'nullable',
                new Enum(SeoTwitterCard::class),
            ],

            'twitter_title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'twitter_description' => [
                'nullable',
                'string',
            ],

            'twitter_image_url' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'locale' => [
                'required',
                new Enum(SeoLocale::class),
            ],

            'schema_type' => [
                'nullable',
                new Enum(SeoSchemaType::class),
            ],
        ];
    }
}