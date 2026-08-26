/**
 * Reports module — downloadable industry/insight reports.
 */

export interface ReportCategory {
    id: number
    name: string
    slug: string
    description?: string | null
    reports_count?: number
    published_count?: number
}

export interface ReportAuthor {
    id: number
    name: string
    username?: string | null
}

export interface Report {
    id: number
    title: string
    slug: string
    summary?: string | null
    cover_image?: string | null
    file_path?: string | null
    file_size?: number | null
    file_size_label?: string | null
    file_type?: string | null
    category_id?: number | null
    author_id?: number | null
    report_year?: number | null
    published_at?: string | null
    status?: boolean
    featured?: boolean
    gated?: boolean
    download_count?: number
    meta_title?: string | null
    meta_description?: string | null
    meta_keywords?: string | null
    created_at?: string | null
    category?: ReportCategory | null
    author?: ReportAuthor | null
}

export interface ReportFilters {
    category?: string | null
    search?: string | null
    status?: string | number | null
}
