<?php

namespace App\Http\Requests\Tag;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateTagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:150'],
            'slug' => [
                'required',
                'string',
                'max:160',
                Rule::unique('tags', 'slug')
                    ->ignore($this->route('tag')->id),
            ],
            'description' => ['nullable', 'string'],
            'status' => ['required', 'boolean'],
        ];
    }
}