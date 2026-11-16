<?php

namespace App\Http\Requests\Interview;

use App\Enums\Interview\InterviewStatus;
use App\Enums\Interview\InterviewType;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateInterviewRequest extends FormRequest
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
                Rule::unique('interviews', 'slug')
                    ->ignore($this->route('interview')),
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'introduction' => [
                'nullable',
                'string',
            ],

            'conclusion' => [
                'nullable',
                'string',
            ],

            'interview_type' => [
                'required',
                Rule::enum(InterviewType::class),
            ],

            'status' => [
                'required',
                Rule::enum(InterviewStatus::class),
            ],

            'published_at' => [
                'nullable',
                'date',
            ],
        ];
    }
}