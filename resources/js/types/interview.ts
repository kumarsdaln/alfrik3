
import type { FormOption } from './forms'
import type { Media } from './media'
import type { Participant } from './participant'
import type { SeoMetadata } from './seo'
import { Category, Tag } from './taxonomy'

export enum InterviewType {
    VIDEO = 'video',
    AUDIO = 'audio',
    WRITTEN = 'written',
}

export enum InterviewStatus {
    DRAFT = 'draft',
    PUBLISHED = 'published',
}

export type InterviewTypeOption = FormOption<InterviewType>
export type InterviewStatusOption = FormOption<InterviewStatus>

export interface InterviewAnswer {
    id: number
    answer: string
    position: number
    answered_by: Participant | null
}

export interface InterviewQuestion {
    id: number
    question: string
    position: number
    asked_by: Participant | null
    answers?: InterviewAnswer[]

    interview?: {
        id: number
        title: string
    }
}

export interface Interview {
    id: number

    // Publication details
    title: string
    slug: string
    description: string | null
    introduction: string | null
    conclusion: string | null

    // Publication settings
    interview_type: InterviewTypeOption
    status: InterviewStatusOption
    published_at: string | null

    // Participants and conversation
    participants: Participant[]
    questions: InterviewQuestion[]

    // Classification
    categories?: Category[]
    tags?: Tag[]

    // Integrations
    media?: Media[]
    seo?: SeoMetadata

    created_at: string
    updated_at: string
}
