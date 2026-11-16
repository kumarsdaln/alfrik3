<?php

namespace App\Http\Requests\Interview;

use App\Enums\Interview\InterviewParticipantRole;
use App\Models\Interview\Interview;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreInterviewAnswerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $question = $this->route('question');

        return [
            'answer' => [
                'required',
                'string',
            ],

            'answered_by' => [
                'nullable',
                'integer',
                Rule::exists('content_participants', 'id')
                    ->where('participantable_type', Interview::class)
                    ->where('participantable_id', $question->interview_id)
                    ->whereIn(
                        'participant_role_id',
                        \App\Models\ParticipantRole::query()
                            ->where('slug', 'interviewee')
                            ->select('id'),
                    ),
            ],

            'position' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }
}