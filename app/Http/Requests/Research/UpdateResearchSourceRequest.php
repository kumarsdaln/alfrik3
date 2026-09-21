<?php

namespace App\Http\Requests\Research;

use App\Enums\Research\ResearchSourceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class UpdateResearchSourceRequest extends FormRequest
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

            'source_type' => [
                'required',
                new Enum(ResearchSourceType::class),
            ],

            'author' => [
                'nullable',
                'string',
                'max:255',
            ],

            'publisher' => [
                'nullable',
                'string',
                'max:255',
            ],

            'url' => [
                'nullable',
                'url',
                'max:2048',
            ],

            'published_at' => [
                'nullable',
                'date',
            ],

            'citation' => [
                'nullable',
                'string',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'position' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }
}