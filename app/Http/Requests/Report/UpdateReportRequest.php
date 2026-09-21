<?php

namespace App\Http\Requests\Report;

use App\Enums\Report\ReportStatus;
use App\Enums\Report\ReportType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $report = $this->route('report');

        return [
            'research_id' => [
                'nullable',
                'integer',
                'exists:researches,id',
            ],

            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('reports', 'slug')
                    ->ignore($report?->id),
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
                Rule::enum(ReportType::class),
            ],

            'status' => [
                'required',
                Rule::enum(ReportStatus::class),
            ],

            'author_id' => [
                'nullable',
                'integer',
                'exists:users,id',
            ],

            'featured' => [
                'boolean',
            ],

            'published_at' => [
                'nullable',
                'date',
            ],

            'report_date' => [
                'nullable',
                'date',
            ],
        ];
    }
}