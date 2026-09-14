<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

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
                'max:50',
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