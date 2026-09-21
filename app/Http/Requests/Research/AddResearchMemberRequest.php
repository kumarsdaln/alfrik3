<?php

namespace App\Http\Requests\Research;

use App\Enums\Research\ResearchMemberRole;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddResearchMemberRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'role' => [
                'required',
                Rule::enum(ResearchMemberRole::class),
            ],
        ];
    }
}
