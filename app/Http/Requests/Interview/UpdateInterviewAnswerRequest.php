<?php

namespace App\Http\Requests\Interview;

use App\Enums\Interview\InterviewParticipantRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateInterviewAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $answer = $this->route('answer');
        return [
            'answer' => [
                'required',
                'string',
            ],
             'answered_by' => [
                'nullable',
                'integer',
                Rule::exists('interview_participants', 'user_id')
                    ->where(function ($query) use ($answer) {
                        $query
                            ->where(
                                'interview_id',
                                $answer->question->interview_id
                            )
                            ->where(
                                'role',
                                InterviewParticipantRole::Interviewee->value
                            );
                    }),
            ],
        ];
    }
}