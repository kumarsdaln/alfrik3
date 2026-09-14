<?php

namespace App\Http\Requests\Media;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'file' => [
                'required',
                'file',
                'max:51200', // 50 MB
            ],

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
        ];
    }
}