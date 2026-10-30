
import type { Pagination } from '@/types'

/**
 * Participant role managed by administrators.
 */
export interface ParticipantRole {
    id: number
    name: string
    slug: string
    description: string | null
    is_active: boolean
    position: number
    content_participants_count?: number
    created_at: string
    updated_at: string
}

/**
 * Supported content types for participant assignments.
 */
export type ParticipantableType =
    | 'research'
    | 'interview'
    | 'report'
    | 'magazine'
    | 'survey'
    | 'event'

/**
 * User who participates in content.
 */
export interface ParticipantUser {
    id: number
    name: string
    email?: string
    avatar?: string | null
}

/**
 * Participant assignment connecting a user and a role
 * to a specific content record.
 */
export interface ContentParticipant {
    id: number
    user_id: number
    participant_role_id: number
    participantable_type: ParticipantableType
    participantable_id: number
    position: number
    is_primary: boolean
    contribution: string | null

    user: ParticipantUser
    role: ParticipantRole

    created_at: string
    updated_at: string
}

/**
 * Participant role filters.
 */
export interface ParticipantRoleFilters {
    search?: string
    status?: 'active' | 'inactive' | ''
}

/**
 * Participant role index statistics.
 */
export interface ParticipantRoleStats {
    total: number
    active: number
    inactive: number
}

/**
 * Props for the participant role index page.
 */
export interface ParticipantRoleIndexProps {
    roles: Pagination<ParticipantRole>
    filters: ParticipantRoleFilters
    stats: ParticipantRoleStats
}

/**
 * Data submitted when creating or updating a participant role.
 */
export interface ParticipantRoleFormData {
    name: string
    slug: string
    description: string | null
    position: number
    is_active: boolean
}

/**
 * Data submitted when assigning a participant to content.
 */
export interface ContentParticipantFormData {
    user_id: number
    participant_role_id: number
    position: number
    is_primary: boolean
    contribution: string | null
}

/**
 * Props for managing participants on a content detail page.
 */
export interface ContentParticipantsProps {
    type: ParticipantableType
    contentId: number
    participants: ContentParticipant[]
    availableRoles: ParticipantRole[]
}