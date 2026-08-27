import type { TableRow } from '@/types/table'

export interface Role extends TableRow {
    id: number,
    name: string,
    slug: string,
    description: string,
}

export interface Permission extends TableRow {
    id: number,
    name: string,
    slug: string,
    description: string,
}

export interface User extends TableRow {
    id: number
    name: string
    email: string
    status: string
    roles: Role[]
    created_at: string
    updated_at: string
}

export interface UserStatusOption {
    value: boolean
    label: string
}