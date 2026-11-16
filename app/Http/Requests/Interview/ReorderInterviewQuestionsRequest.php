<?php

namespace App\Http\Requests\Interview;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class ReorderInterviewQuestionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $interview = $this->route('interview');

        return [
            'questions' => [
                'required',
                'array',
                'min:1',
            ],

            'questions.*.id' => [
                'required',
                'integer',
                'distinct',
                Rule::exists('interview_questions', 'id')
                    ->where('interview_id', $interview->id),
            ],

            'questions.*.position' => [
                'required',
                'integer',
                'min:0',
                'distinct',
            ],
        ];
    }
}