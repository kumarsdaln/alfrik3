<?php
namespace App\Services;

use App\Models\Survey\Survey;
use App\Models\Survey\SurveyAnswer;

class SurveyResultService
{
    /**
     * Aggregate survey answers into per-question result summaries.
     *
     * @return array{total_responses:int, questions:array<int,array<string,mixed>>}
     */
    public function aggregate(Survey $survey): array
    {
        $survey->loadMissing(['questions.options']);

        $totalResponses = $survey->responses()->count();
        $questions = [];

        foreach ($survey->questions as $q) {
            $answers = SurveyAnswer::where('question_id', $q->id);

            $base = [
                'question_id' => $q->id,
                'question' => $q->question,
                'type' => $q->type,
                'total_answers' => (clone $answers)->count(),
            ];

            if (in_array($q->type, ['single_choice', 'multiple_choice'], true)) {
                $counts = SurveyAnswer::where('question_id', $q->id)
                    ->selectRaw('option_id, COUNT(*) as c')
                    ->groupBy('option_id')
                    ->pluck('c', 'option_id');

                $sum = $counts->sum() ?: 1;
                $base['options'] = $q->options->map(fn ($o) => [
                    'id' => $o->id,
                    'label' => $o->label,
                    'count' => (int) ($counts[$o->id] ?? 0),
                    'percentage' => round((($counts[$o->id] ?? 0) / $sum) * 100, 1),
                ])->values()->all();
            } elseif ($q->type === 'rating') {
                $max = (int) ($q->settings['max'] ?? 5);
                $values = SurveyAnswer::where('question_id', $q->id)->pluck('value_text')->map(fn ($v) => (int) $v);
                $distribution = [];
                for ($i = 1; $i <= $max; $i++) {
                    $distribution[] = ['value' => $i, 'count' => $values->filter(fn ($v) => $v === $i)->count()];
                }
                $base['max'] = $max;
                $base['average'] = $values->count() ? round($values->avg(), 2) : 0;
                $base['distribution'] = $distribution;
            } else { // text
                $base['responses'] = SurveyAnswer::where('question_id', $q->id)
                    ->whereNotNull('value_text')
                    ->latest('id')
                    ->limit(100)
                    ->pluck('value_text')
                    ->all();
            }

            $questions[] = $base;
        }

        return ['total_responses' => $totalResponses, 'questions' => $questions];
    }
}