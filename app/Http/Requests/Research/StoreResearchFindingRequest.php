<?php

namespace App\Http\Requests\Research;

use App\Enums\Research\ResearchFindingType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class StoreResearchFindingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'summary' => [
                'nullable',
                'string',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'type' => [
                'required',
                new Enum(ResearchFindingType::class),
            ],

            'confidence' => [
                'nullable',
                'numeric',
                'min:0',
                'max:100',
            ],

            'position' => [
                'nullable',
                'integer',
                'min:0',
            ],
        ];
    }
}
