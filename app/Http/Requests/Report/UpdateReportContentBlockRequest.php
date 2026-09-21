<?php

namespace App\Http\Requests\Admin\Report;

use App\Enums\Report\ReportContentBlockType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReportContentBlockRequest extends FormRequest
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
                Rule::enum(ReportContentBlockType::class),
            ],

            'title' => [
                'nullable',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'content' => [
                'nullable',
                'array',
            ],

            'position' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
    }
}