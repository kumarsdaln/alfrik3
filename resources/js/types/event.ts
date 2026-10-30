import { FormOption } from "./forms"
import { Category, Tag } from "./taxonomy"

export enum EventStatus {
    DRAFT = 'draft',
    PUBLISHED = 'published',
    CANCELLED = 'cancelled'
}

export enum EventType {
    ONLINE = 'online',
    OFFLINE = 'offline',
    HYBRID = 'hybrid'
}

export enum EventVisibility {
    PUBLIC = 'public',
    PRIVATE = 'private'
}

export type EventStatusOption = FormOption<EventStatus>
export type EventTypeOption = FormOption<EventType>
export type EventVisibilityOption = FormOption<EventVisibility>

export interface EventCreator {
    id: number
    name: string
}

export interface EventSession {
    id: number
    event_id: number
    title: string
    description: string | null
    start_time: string
    end_time: string | null
    position: number
}

export interface EventParticipant {
    id: number
    event_id: number
    user_id: number | null
    role: string
    position: number
    user?: {
        id: number
        name: string
    } | null
}

export interface EventTicket {
    id: number
    event_id: number
    name: string
    description: string | null
    price: string | number
    currency: string
    capacity: number | null
    sales_start_at: string | null
    sales_end_at: string | null
    is_visible: boolean
    is_active: boolean
    position: number
}

export interface Event {
    id: number
    title: string
    slug: string
    description: string | null

    event_type: EventTypeOption
    visibility: EventVisibilityOption
    status: EventStatusOption

    start_date: string
    end_date: string | null

    location_name: string | null
    address: string | null
    city: string | null
    state: string | null
    country: string | null
    meeting_url: string | null

    max_attendees: number | null
    created_by: number | null

    creator?: EventCreator | null
    categories?: Category[]
    tags?: Tag[]
    sessions?: EventSession[]
    participants?: EventParticipant[]
    tickets?: EventTicket[]

    created_at: string
    updated_at: string
}

export interface EventFilters {
    search?: string
    status?: EventStatusOption | ''
}

export interface EventFormData {
    title: string
    slug: string
    description: string | null
    event_type: EventTypeOption
    visibility: EventVisibilityOption
    start_date: string
    end_date: string | null
    location_name: string | null
    address: string | null
    city: string | null
    state: string | null
    country: string | null
    meeting_url: string | null
    max_attendees: number | null
    status: EventStatusOption
}