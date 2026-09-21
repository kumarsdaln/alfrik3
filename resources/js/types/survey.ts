import { FormOption } from "./forms"

export enum SurveyStatus {
    Draft = 'draft',
    Published = 'published',
    Closed = 'closed',
    Archived = 'archived',
}

export type SurveyStatusOption = FormOption<SurveyStatus>

export interface Survey {
    id: number

    research_id: number | null

    title: string
    slug: string
    description: string | null

    status: SurveyStatusOption

    anonymous: boolean
    multiple_responses: boolean
    featured: boolean

    starts_at: string | null
    ends_at: string | null

    response_count: number

    created_at: string
    updated_at: string
}

export interface SurveySection {
    id: number

    survey_id: number

    title: string
    description: string | null

    position: number

    created_at: string
    updated_at: string
}

export enum SurveyQuestionType {
    ShortText = 'short_text',
    LongText = 'long_text',
    SingleChoice = 'single_choice',
    MultipleChoice = 'multiple_choice',
    YesNo = 'yes_no',
    Number = 'number',
    Rating = 'rating',
    Scale = 'scale',
    Date = 'date',
}

export type SurveyQuestionTypeOption = FormOption<SurveyQuestionType>
export interface SurveyQuestion {
    id: number
    survey_id: number
    section_id: number | null
    question: string
    description: string | null
    type: SurveyQuestionTypeOption
    category: string | null
    required: boolean
    position: number
    settings: Record<string, unknown> | null
    created_at: string
    updated_at: string
}

export interface SurveyQuestionOption {
    id: number
    question_id: number
    label: string
    value: string
    position: number
    is_other: boolean
    created_at: string
    updated_at: string
}

export interface SurveyQuestion {
    id: number
    survey_id: number
    section_id: number | null
    question: string
    description: string | null
    type: SurveyQuestionTypeOption
    category: string | null
    required: boolean
    position: number
    settings: Record<string, unknown> | null
    options?: SurveyQuestionOption[]
    created_at: string
    updated_at: string
}

export enum SurveyResponseStatus {
    InProgress = 'in_progress',
    Submitted = 'submitted',
    Abandoned = 'abandoned',
}

export type SurveyResponseStatusOption =
    FormOption<SurveyResponseStatus>

export interface SurveyResponse {
    id: number
    survey_id: number
    user_id: number | null
    respondent_name: string | null
    respondent_email: string | null
    respondent_ip: string | null
    user_agent: string | null
    started_at: string | null
    submitted_at: string | null
    status: SurveyResponseStatusOption
    answers?: SurveyAnswer[]
    created_at: string
    updated_at: string
}

export interface SurveyAnswer {
    id: number
    response_id: number
    question_id: number
    option_id: number | null
    answer_text: string | null
    answer_number: number | null
    answer_boolean: boolean | null
    answer_json: unknown[] | Record<string, unknown> | null
    created_at: string
    updated_at: string
}

export interface SurveyResponse {
    id: number
    survey_id: number
    user_id: number | null

    respondent_name: string | null
    respondent_email: string | null

    respondent_ip: string | null
    user_agent: string | null

    started_at: string | null
    submitted_at: string | null

    status: SurveyResponseStatusOption

    answers?: SurveyAnswer[]

    created_at: string
    updated_at: string
}

export interface SurveyResponseAnswer {
    id: number
    question_id: number
    answer_text: string | null
    answer_number: number | string | null
    answer_boolean: boolean | null
    answer_json: number[] | null

    option: {
        id: number
        label: string
        value: string
    } | null

    options?: {
        id: number
        label: string
        value: string
    }[]
}