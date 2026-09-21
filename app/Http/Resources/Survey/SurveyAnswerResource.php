<?php

namespace App\Http\Resources\Survey;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SurveyAnswerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,

            'response_id' => $this->response_id,

            'question_id' => $this->question_id,

            'option_id' => $this->option_id,

            'answer_text' => $this->answer_text,

            'answer_number' => $this->answer_number,

            'answer_boolean' => $this->answer_boolean,

            'answer_json' => $this->answer_json,

            'option' => $this->whenLoaded(
                'option',
                fn () => $this->option
                    ? [
                        'id' => $this->option->id,
                        'label' => $this->option->label,
                        'value' => $this->option->value,
                    ]
                    : null
            ),

            'created_at' => $this->created_at?->toISOString(),

            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }
}