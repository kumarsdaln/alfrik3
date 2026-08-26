/**
 * Research module — academic-style research papers & studies.
 */

export interface ResearchArea {
    id: number
    name: string
    slug: string
    description?: string | null
    papers_count?: number
    published_count?: number
}

export interface ResearchAuthorUser {
    id: number
    name: string
    username?: string | null
}

export interface ResearchPaper {
    id: number
    title: string
    slug: string
    abstract?: string | null
    authors?: string | null
    author_id?: number | null
    area_id?: number | null
    institution?: string | null
    methodology?: string | null
    doi?: string | null
    citation?: string | null
    keywords?: string | null
    cover_image?: string | null
    file_path?: string | null
    file_size?: number | null
    file_size_label?: string | null
    file_type?: string | null
    published_at?: string | null
    status?: boolean
    featured?: boolean
    download_count?: number
    meta_title?: string | null
    meta_description?: string | null
    meta_keywords?: string | null
    created_at?: string | null
    area?: ResearchArea | null
    author?: ResearchAuthorUser | null
}

export interface ResearchFilters {
    area?: string | null
    search?: string | null
    status?: string | number | null
}
