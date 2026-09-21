<?php

namespace App\Http\Requests\Admin\Research;

use Illuminate\Foundation\Http\FormRequest;

class SyncResearchFindingQuestionsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'question_ids' => ['array'],
            'question_ids.*' => [
                'integer',
                'distinct',
                'exists:research_questions,id',
            ],
        ];
    }
}