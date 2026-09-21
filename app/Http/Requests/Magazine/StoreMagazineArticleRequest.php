<?php

namespace App\Http\Requests\Magazine;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMagazineArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:magazine_articles,slug',
            ],

            'subtitle' => [
                'nullable',
                'string',
                'max:255',
            ],

            'excerpt' => [
                'nullable',
                'string',
            ],

            'content' => [
                'nullable',
                'string',
            ],

            'author_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'type' => [
                'required',
                'string',
                'in:article,interview,opinion,feature,news',
            ],

            'byline' => [
                'nullable',
                'string',
                'max:255',
            ],

            'position' => [
                'integer',
                'min:0',
            ],

            'featured' => [
                'boolean',
            ],

            'status' => [
                'required',
                'string',
                'in:draft,published,archived',
            ],

            'published_at' => [
                'nullable',
                'date',
            ],

            'reading_time' => [
                'nullable',
                'integer',
                'min:1',
            ],
        ];
    }
}