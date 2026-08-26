/**
 * Surveys module — builder (survey → questions → options) and response results.
 */

export type SurveyQuestionType = 'single_choice' | 'multiple_choice' | 'text' | 'rating'

export interface SurveyOption {
    id?: number
    question_id?: number
    label: string
    position?: number
}

export interface SurveyQuestion {
    id?: number
    survey_id?: number
    question: string
    type: SurveyQuestionType
    required?: boolean
    position?: number
    settings?: { max?: number } | null
    options?: SurveyOption[]
}

export interface Survey {
    id: number
    title: string
    slug: string
    description?: string | null
    status?: boolean
    published_at?: string | null
    closes_at?: string | null
    allow_anonymous?: boolean
    one_response_per_user?: boolean
    show_results?: boolean
    author_id?: number | null
    questions_count?: number
    responses_count?: number
    created_at?: string | null
    questions?: SurveyQuestion[]
}

/** Aggregated result shapes returned by SurveyResultService. */
export interface SurveyOptionResult {
    id: number
    label: string
    count: number
    percentage: number
}

export interface SurveyQuestionResult {
    question_id: number
    question: string
    type: SurveyQuestionType
    total_answers: number
    options?: SurveyOptionResult[]
    max?: number
    average?: number
    distribution?: { value: number; count: number }[]
    responses?: string[]
}

export interface SurveyResults {
    total_responses: number
    questions: SurveyQuestionResult[]
}

/** Builder form model (create/edit). */
export interface SurveyFormQuestion {
    id?: number
    question: string
    type: SurveyQuestionType
    required: boolean
    settings: { max: number }
    options: { id?: number; label: string }[]
}
