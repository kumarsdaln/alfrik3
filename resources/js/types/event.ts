export interface EventCategory {
    id: number
    name: string
    slug: string
}

export interface EventStatus {
    value: 'draft' | 'published' | 'cancelled'
    label: string
    color: string | null
}

export interface EventType {
    value: 'in_person' | 'online' | 'hybrid'
    label: string
    color: string | null
}

export interface EventVisibility {
    value: 'public' | 'private'
    label: string
    color: string | null
}