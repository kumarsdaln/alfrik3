<?php

namespace App\Http\Requests\Interview;

use App\Enums\Interview\InterviewParticipantRole;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreInterviewQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'question' => [
                'required',
                'string',
            ],

            'position' => [
                'nullable',
                'integer',
                'min:0',
            ],
            'asked_by' => [
                'nullable',
                'integer',
                Rule::exists('interview_participants', 'user_id')
                    ->where(
                        fn($query) => $query
                            ->where('interview_id', $this->route('interview')->id)
                            ->where('role', InterviewParticipantRole::Interviewer->value)
                    ),
            ],
        ];
    }
}