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

export interface Role {
    id: number,
    name: string,
    slug: string,
    description: string,
}

export interface Permission {
    id: number,
    name: string,
    slug: string,
    description: string,
}

export type User = {
    id: number;
    name: string;
    email: string;
    avatar?: string;
    email_verified_at?: string | null;
    is_active?: boolean
    roles?: Role[]
    two_factor_enabled?: boolean;
    created_at?: string;
    updated_at?: string;
    [key: string]: unknown;
};

export interface UserStatusOption {
    value: boolean
    label: string
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