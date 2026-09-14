<?php

namespace App\Http\Requests\Interview;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ReorderInterviewQuestionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'questions' => [
                'required',
                'array',
                'min:1',
            ],

            'questions.*.id' => [
                'required',
                'integer',
                'exists:interview_questions,id',
            ],

            'questions.*.position' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
    }
}