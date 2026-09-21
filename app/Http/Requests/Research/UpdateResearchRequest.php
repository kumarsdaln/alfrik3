<?php

namespace App\Http\Requests\Research;

use App\Enums\Research\ResearchStatus;
use App\Enums\Research\ResearchType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateResearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $research = $this->route('research');

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
                Rule::unique('researches', 'slug')
                    ->ignore($research),
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
                new Enum(ResearchType::class),
            ],

            'status' => [
                'required',
                new Enum(ResearchStatus::class),
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
