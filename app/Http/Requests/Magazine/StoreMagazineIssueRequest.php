<?php

namespace App\Http\Requests\Magazine;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMagazineIssueRequest extends FormRequest
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
                'unique:magazine_issues,slug',
            ],

            'subtitle' => [
                'nullable',
                'string',
                'max:255',
            ],

            'volume' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'issue_number' => [
                'nullable',
                'integer',
                'min:1',
            ],

            'cover_date' => [
                'nullable',
                'date',
            ],

            'published_at' => [
                'nullable',
                'date',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'editor' => [
                'nullable',
                'string',
                'max:255',
            ],

            'status' => [
                'required',
                'string',
                'in:draft,published,archived',
            ],

            'featured' => [
                'boolean',
            ],
        ];
    }
}