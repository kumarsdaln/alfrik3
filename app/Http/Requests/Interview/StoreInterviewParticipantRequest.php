<?php

namespace App\Http\Requests\Interview;

use App\Enums\Interview\InterviewParticipantRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreInterviewParticipantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $interview = $this->route('interview');

        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',

                Rule::unique('interview_participants', 'user_id')
                    ->where(
                        fn($query) => $query
                            ->where('interview_id', $interview->id)
                            ->where('role', $this->input('role'))
                    ),
            ],

            'role' => [
                'required',
                'string',
                Rule::enum(InterviewParticipantRole::class),
            ],
        ];
    }
}
