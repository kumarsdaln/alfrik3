<?php

namespace App\Http\Requests\Magazine;

use App\Enums\Magazine\MagazineStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreMagazineRequest extends FormRequest
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
                'unique:magazines,slug',
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
                Rule::enum(MagazineStatus::class),
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