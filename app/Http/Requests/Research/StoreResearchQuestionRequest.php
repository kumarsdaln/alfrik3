<?php

namespace App\Http\Requests\Research;

use App\Enums\Research\ResearchQuestionType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class StoreResearchQuestionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'question' => [
                'required',
                'string',
                'max:1000',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'type' => [
                'required',
                new Enum(ResearchQuestionType::class),
            ],

            'position' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }
}
