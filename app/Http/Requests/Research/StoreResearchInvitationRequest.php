<?php

namespace App\Http\Requests\Admin\Research;

use App\Enums\Research\ResearchMemberRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreResearchInvitationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'role' => [
                'required',
                Rule::enum(ResearchMemberRole::class),
            ],

            'expires_at' => [
                'nullable',
                'date',
                'after:now',
            ],
        ];
    }
}
