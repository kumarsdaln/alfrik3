<?php

namespace App\Http\Requests\Survey;

use App\Enums\Survey\SurveyStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'research_id' => [
                'nullable',
                'integer',
                'exists:researches,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:surveys,slug',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'status' => [
                'required',
                Rule::enum(SurveyStatus::class),
            ],

            'anonymous' => [
                'required',
                'boolean',
            ],

            'multiple_responses' => [
                'required',
                'boolean',
            ],

            'featured' => [
                'required',
                'boolean',
            ],

            'starts_at' => [
                'nullable',
                'date',
            ],

            'ends_at' => [
                'nullable',
                'date',
                'after_or_equal:starts_at',
            ],
        ];
    }
}
