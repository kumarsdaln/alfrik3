<?php

namespace App\Http\Requests\Survey;

use App\Enums\Survey\SurveyStatus;
use App\Models\Survey;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSurveyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Survey $survey */
        $survey = $this->route('survey');

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
                Rule::unique('surveys', 'slug')
                    ->ignore($survey),
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