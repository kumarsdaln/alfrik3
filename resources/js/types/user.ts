import { FormOption } from "./forms"

export interface Position {
    id: number
    name: string
}

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

export interface Permission {
    id: number
    name: string
    slug: string
    description: string | null

    roles_count?: number
    roles?: Role[]

    created_at?: string
    updated_at?: string
}

export interface Role {
    id: number
    name: string
    slug: string
    description: string | null

    users_count?: number
    permissions_count?: number

    users?: User[]
    permissions?: Permission[]

    created_at?: string
    updated_at?: string
}

export interface User {
    id: number

    name: string
    username: string | null
    email: string

    avatar: string | null
    headline: string | null

    country_id: number | null
    position_id: number | null

    is_active: boolean
    status: FormOption

    roles?: Role[]

    email_verified_at?: string | null
    two_factor_enabled?: boolean

    created_at?: string
    updated_at?: string

    [key: string]: unknown
}

export interface Profile {
    id: number

    name: string
    username: string

    avatar: string | null
    email?: string

    headline?: string | null

    position?: Position | null
    country?: Country | null

    languages?: Language[]
    industries?: Industry[]
}