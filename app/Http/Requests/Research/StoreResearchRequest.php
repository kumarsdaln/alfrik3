<?php

namespace App\Http\Requests\Research;

use App\Enums\Research\ResearchStatus as ResearchResearchStatus;
use App\Enums\Research\ResearchType as ResearchResearchType;
use App\Enums\ResearchStatus;
use App\Enums\ResearchType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Enum;

class StoreResearchRequest extends FormRequest
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

            'slug' => [
                'required',
                'string',
                'max:255',
                'unique:researches,slug',
            ],

            'subtitle' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'summary' => [
                'nullable',
                'string',
            ],

            'type' => [
                'required',
                new Enum(ResearchResearchType::class),
            ],

            'status' => [
                'required',
                new Enum(ResearchResearchStatus::class),
            ],

            'author_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'featured' => [
                'required',
                'boolean',
            ],

            'published_at' => [
                'nullable',
                'date',
            ],
        ];
    }
}