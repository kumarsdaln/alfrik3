/**
 * Shared magazine types (digital editorial issues + categories).
 * Reused by the public magazine reader, the landing grid, and the admin
 * management screens.
 */

export interface MagazineCategory {
    id: number
    name: string
    slug: string
    icon?: string | null
    description?: string | null
    meta_title?: string | null
    meta_description?: string | null
    meta_keywords?: string | null
    magazines_count?: number
    published_count?: number
}

/** Minimal author shape credited on an issue. */
export interface MagazineAuthor {
    id: number
    name: string
    username?: string | null
}

/** A single readable section within an issue. */
export interface MagazineSection {
    section: string
    content: string
    subsections?: { title: string }[]
}

export interface Magazine {
    id: number
    title: string
    subtitle?: string | null
    cover_image?: string | null
    slug: string
    content?: string | null
    category_id?: number | null
    author_id?: number | null
    status?: boolean
    published_at?: string | null
    reading_minutes?: number
    meta_title?: string | null
    meta_description?: string | null
    meta_keywords?: string | null
    created_at?: string | null
    updated_at?: string | null
    category?: MagazineCategory | null
    author?: MagazineAuthor | null
    /** Decoded sections, present on the edit/show payloads. */
    sections?: MagazineSection[]
}

/** Form shape used by the admin create/edit section repeater. */
export interface MagazineSectionForm {
    section: string
    content: string
}

export interface MagazineFilters {
    category?: string | null
    search?: string | null
    status?: string | number | null
}
