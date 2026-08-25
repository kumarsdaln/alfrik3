import type { TableRow } from '@/types/table'

export interface UserRole extends TableRow {
    id: number
    name: string
    slug: string
}

export interface User extends TableRow {
    id: number
    name: string
    email: string
    status: string
    roles: UserRole[]
    created_at: string
    updated_at: string
}

export interface PaginatedUsers {
    data: User[]
    total: number
    current_page: number
    last_page: number
    per_page: number
    prev_page_url: string | null
    next_page_url: string | null
}

export interface UserStatusOption {
    value: string
    label: string
}

export interface UserIndexProps {
    users: PaginatedUsers
}