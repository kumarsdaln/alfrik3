<?php

namespace App\Http\Requests\Media;

use App\Enums\Media\MediaCollection;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'collection' => [
                'required',
                'string',
                Rule::enum(MediaCollection::class),
            ],

            'name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'alt' => [
                'nullable',
                'string',
                'max:255',
            ],

            'metadata' => [
                'nullable',
                'array',
            ],
        ];
    }
}