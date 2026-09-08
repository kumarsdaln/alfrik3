import { Option } from "./forms"

export enum InterviewType {
    VIDEO = 'video',
    AUDIO = 'audio',
    WRITTEN = 'written',
}

export enum InterviewStatus {
    DRAFT = 'draft',
    PUBLISHED = 'published',
}

export type InterviewTypeOption = Option<InterviewType>
export type InterviewStatusOption = Option<InterviewStatus>

export interface Interview {
    id: number
    title: string
    slug: string
    description: string | null
    interview_type: InterviewTypeOption
    status: InterviewStatusOption
    thumbnail: string | null
    duration: string
    published_at: string
    created_at: string
}