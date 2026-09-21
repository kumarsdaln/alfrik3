<?php

namespace App\Http\Requests\Admin\Survey;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSurveyQuestionOptionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'label' => [
                'required',
                'string',
                'max:255',
            ],

            'value' => [
                'required',
                'string',
                'max:255',
            ],

            'position' => [
                'nullable',
                'integer',
                'min:0',
            ],

            'is_other' => [
                'required',
                'boolean',
            ],
        ];
    }
}