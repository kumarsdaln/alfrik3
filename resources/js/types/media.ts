export interface Media {
    id: number
    collection: string
    name: string
    file_name: string
    mime_type: string
    extension: string | null
    size: number
    disk: string
    url: string
    alt: string | null
    metadata: Record<string, unknown> | null
    created_at: string
    updated_at: string
}