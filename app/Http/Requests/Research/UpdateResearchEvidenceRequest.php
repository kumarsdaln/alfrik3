<?php

namespace App\Http\Requests\Research;

use App\Enums\Research\ResearchEvidenceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateResearchEvidenceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'type' => [
                'required',
                Rule::enum(ResearchEvidenceType::class),
            ],
            'reference_id' => [
                'nullable',
                'integer',
                'min:1',
            ],
            'title' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'description' => [
                'nullable',
                'string',
            ],
            'citation' => [
                'nullable',
                'string',
            ],
            'position' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }
}