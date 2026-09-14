import type { FormOption } from './forms'

export enum InterviewType {
    VIDEO = 'video',
    AUDIO = 'audio',
    WRITTEN = 'written',
}

export enum InterviewStatus {
    DRAFT = 'draft',
    PUBLISHED = 'published',
}

export enum InterviewParticipantRole {
    INTERVIEWER = 'interviewer',
    INTERVIEWEE = 'interviewee',
}

export type InterviewTypeOption = FormOption<InterviewType>
export type InterviewStatusOption = FormOption<InterviewStatus>
export type InterviewParticipantRoleOption = FormOption<InterviewParticipantRole>

export interface InterviewParticipant {
    id: number
    role: FormOption<InterviewParticipantRole>
    user: {
        id: number
        name: string
        email: string
    }
}

export interface InterviewAnswer {
    id: number
    answer: string
    answered_by: {
        id: number
        name: string
    } | null
}

export interface InterviewQuestion {
    id: number
    question: string
    position: number
    asked_by: {
        id: number
        name: string
    } | null
    answers: InterviewAnswer[]

    interview?: {
        id: number
        title: string
    }
}

export interface InterviewCategory {
    id: number
    name: string
    slug: string
    description: string | null
    status: boolean
    sort_order: number
}

export interface InterviewTag {
    id: number
    name: string
    slug: string
    description: string | null
    status: boolean
}

export interface InterviewMedia {
    id: number
    collection: string
    name: string
    file_name: string
    mime_type: string
    extension: string | null
    size: number
    disk: string
    path: string
    alt: string | null
    metadata: Record<string, unknown> | null
    created_at: string
    updated_at: string
}

export interface InterviewSeo {
    id: number
    title: string | null
    description: string | null
    canonical_url: string | null
    indexable: boolean
    followable: boolean
    og_title: string | null
    og_description: string | null
    og_type: string | null
    og_image_url: string | null
    twitter_card: string | null
    twitter_title: string | null
    twitter_description: string | null
    twitter_image_url: string | null
    locale: string
    schema_type: string | null
}

export interface Interview {
    id: number
    title: string
    slug: string
    description: string | null

    interview_type: InterviewTypeOption
    status: InterviewStatusOption

    published_at: string | null
    views_count: number

    created_at: string
    updated_at: string

    creator: {
        id: number
        name: string
        email: string
    } | null

    participants: InterviewParticipant[]
    questions: InterviewQuestion[]

    categories: InterviewCategory[]
    tags: InterviewTag[]

    media: InterviewMedia[]
    seo: InterviewSeo[]
}