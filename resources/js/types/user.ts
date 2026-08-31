import type { TableRow } from '@/types/table'

export interface Country {
    id: number
    name: string
    code: string
}

export interface Language {
    id: number
    code: string
    name: string
    native: string
    rtl: boolean
}

export interface Industry {
    id: number
    name: string
}

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
    username: string
    email: string
    is_active: boolean
    roles: Role[]
    created_at: string
    updated_at: string
}

export interface UserStatusOption {
    value: boolean
    label: string
}