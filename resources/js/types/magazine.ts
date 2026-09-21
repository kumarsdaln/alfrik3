import { FormOption } from './forms'
import { Media } from './media'
import { SeoMetadata } from './seo'
import type { Category, Tag } from './taxonomy'
import type { Profile } from './user'

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
    description?: string | null

    author_id?: number | null

    status: MagazineStatusOption
    featured: boolean

    published_at?: string | null

    author?: Profile | null

    categories?: Category[]
    tags?: Tag[]
    media?: Media[]
    seo?: SeoMetadata[]

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

    download_count: number

    status: MagazineIssueStatusOption
    featured: boolean

    magazine?: Magazine | null

    media?: Media[]
    seo?: SeoMetadata[]

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
    author_id?: number | null

    title: string
    slug: string
    subtitle?: string | null

    excerpt?: string | null
    content?: string | null

    type: MagazineArticleTypeOption
    byline?: string | null

    position: number
    featured: boolean

    status: MagazineArticleStatusOption
    published_at?: string | null

    views: number

    reading_time?: number | null
    reading_time_label?: string | null

    issue?: MagazineIssue | null
    author?: Profile | null

    categories?: Category[]
    tags?: Tag[]
    media?: Media[]
    seo?: SeoMetadata[]

    created_at?: string
    updated_at?: string
}

/*
|--------------------------------------------------------------------------
| Magazine Status
|--------------------------------------------------------------------------
*/

export enum MagazineStatus {
    DRAFT = 'draft',
    PUBLISHED = 'published',
    ARCHIVED = 'archived',
}
export type MagazineStatusOption = FormOption<MagazineStatus>

/*
|--------------------------------------------------------------------------
| Magazine Issue Status
|--------------------------------------------------------------------------
*/

export enum MagazineIssueStatus {
    DRAFT = 'draft',
    PUBLISHED = 'published',
    ARCHIVED = 'archived',
}
export type MagazineIssueStatusOption = FormOption<MagazineIssueStatus>

/*
|--------------------------------------------------------------------------
| Magazine Article Status
|--------------------------------------------------------------------------
*/

export enum MagazineArticleStatus {
    DRAFT = 'draft',
    PUBLISHED = 'published',
    ARCHIVED = 'archived',
}
export type MagazineArticleStatusOption = FormOption<MagazineArticleStatus>

/*
|--------------------------------------------------------------------------
| Magazine Article Type
|--------------------------------------------------------------------------
*/

export enum MagazineArticleType {
    ARTICLE = 'article',
    INTERVIEW = 'interview',
    OPINION = 'opinion',
    FEATURE = 'feature',
    NEWS = 'news',
}
export type MagazineArticleTypeOption = FormOption<MagazineArticleType>