<?php

namespace App\Http\Requests\Interview;

use App\Models\Interview\Interview;
use App\Models\ParticipantRole;
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
        /** @var Interview $interview */
        $interview = $this->route('interview');

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
                Rule::exists('content_participants', 'id')
                    ->where('participantable_type', $interview->getMorphClass())
                    ->where('participantable_id', $interview->id)
                    ->whereIn(
                        'participant_role_id',
                        ParticipantRole::query()
                            ->where('slug', 'interviewer')
                            ->select('id'),
                    ),
            ],
        ];
    }
}
