<?php

namespace App\Http\Requests\Admin\Survey;

use App\Enums\Survey\SurveyQuestionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSurveyQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'section_id' => [
                'nullable',
                'integer',
                'exists:survey_sections,id',
            ],

            'question' => [
                'required',
                'string',
                'max:5000',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'type' => [
                'required',
                Rule::enum(SurveyQuestionType::class),
            ],

            'category' => [
                'nullable',
                'string',
                'max:50',
            ],

            'required' => [
                'required',
                'boolean',
            ],

            'position' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'settings' => [
                'nullable',
                'array',
            ],
        ];
    }
}