<?php

namespace App\Http\Requests\Research;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SaveResearchMethodologyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'method' => [
                'required',
                'string',
                'max:100',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'research_design' => [
                'nullable',
                'string',
                'max:100',
            ],

            'data_collection_method' => [
                'nullable',
                'string',
                'max:100',
            ],

            'sample_size' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'population' => [
                'nullable',
                'string',
            ],

            'geography' => [
                'nullable',
                'string',
            ],

            'start_date' => [
                'nullable',
                'date',
            ],

            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],

            'limitations' => [
                'nullable',
                'string',
            ],
        ];
    }
}