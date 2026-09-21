<?php

namespace App\Http\Requests\Magazine;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateMagazineRequest extends FormRequest
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
                Rule::unique('magazines', 'slug')
                    ->ignore($this->route('magazine')),
            ],

            'subtitle' => [
                'nullable',
                'string',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'author_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'status' => [
                'required',
                'string',
                'in:draft,published,archived',
            ],

            'featured' => [
                'boolean',
            ],

            'published_at' => [
                'nullable',
                'date',
            ],
        ];
    }
}