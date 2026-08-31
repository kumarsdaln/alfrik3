// resources/js/types/magazine.ts

/*
|--------------------------------------------------------------------------
| Magazine Category
|--------------------------------------------------------------------------
*/

export interface MagazineCategory {
    id: number
    name: string
    slug: string

    icon?: string | null
    description?: string | null

    status: boolean
    position: number

    meta_title?: string | null
    meta_description?: string | null
    meta_keywords?: string | null

    articles_count?: number
    magazines_count?: number
}

/*
|--------------------------------------------------------------------------
| Magazine
|--------------------------------------------------------------------------
*/

export interface Magazine {
    id: number

    title: string
    slug: string
    subtitle?: string | null

    content?: string | null
    cover_image?: string | null

    category_id?: number | null
    author_id?: number | null

    status: boolean
    featured: boolean

    published_at?: string | null

    meta_title?: string | null
    meta_description?: string | null
    meta_keywords?: string | null

    category?: MagazineCategory | null
    author?: MagazineAuthor | null

    issues?: MagazineIssue[]

    created_at?: string
    updated_at?: string
}

/*
|--------------------------------------------------------------------------
| Magazine Issue
|--------------------------------------------------------------------------
*/

export interface MagazineIssue {
    id: number

    magazine_id: number

    title: string
    slug: string
    subtitle?: string | null

    volume?: number | null
    issue_number?: number | null

    cover_date?: string | null
    published_at?: string | null

    description?: string | null
    editor?: string | null

    cover_image?: string | null

    file_path?: string | null
    file_size?: number | null
    file_size_label?: string | null
    file_type?: string | null

    download_count: number

    status: boolean
    featured: boolean

    meta_title?: string | null
    meta_description?: string | null
    meta_keywords?: string | null

    edition_label?: string | null

    magazine?: Magazine | null
    articles?: MagazineArticle[]

    created_at?: string
    updated_at?: string
}

/*
|--------------------------------------------------------------------------
| Magazine Article
|--------------------------------------------------------------------------
*/

export interface MagazineArticle {
    id: number

    issue_id: number
    category_id?: number | null
    author_id?: number | null

    title: string
    slug: string
    subtitle?: string | null

    excerpt?: string | null
    content?: string | null

    cover_image?: string | null

    type: string
    byline?: string | null

    position: number
    featured: boolean

    status: boolean
    published_at?: string | null

    views: number

    reading_time?: number | null
    reading_time_label?: string | null

    meta_title?: string | null
    meta_description?: string | null
    meta_keywords?: string | null

    issue?: MagazineIssue | null
    category?: MagazineCategory | null
    author?: MagazineAuthor | null

    created_at?: string
    updated_at?: string
}

/*
|--------------------------------------------------------------------------
| Magazine Author
|--------------------------------------------------------------------------
*/

export interface MagazineAuthor {
    id: number
    name: string
    username?: string | null
}