export interface SeoMetadata {
    id: number

    title?: string | null
    description?: string | null

    canonical_url?: string | null

    indexable: boolean
    followable: boolean

    og_title?: string | null
    og_description?: string | null
    og_type?: string | null
    og_image_url?: string | null

    twitter_card?: string | null
    twitter_title?: string | null
    twitter_description?: string | null
    twitter_image_url?: string | null

    locale: string
    schema_type?: string | null

    created_at?: string
    updated_at?: string
}