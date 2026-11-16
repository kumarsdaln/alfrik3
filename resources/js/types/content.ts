/**
 * Shared content types (platform content: articles, interviews, features, etc.)
 * Reused by the expert-content management pages and any content display.
 */

export type ContentStatus = 'draft' | 'published'

export interface ContentType {
    id: number
    name: string
    slug: string
}

export interface ContentCategory {
    id: number
    name: string
    slug: string
}

export interface ContentTag {
    id: number
    name: string
    slug?: string
}

export interface Content {
    id: number
    title: string
    subtitle?: string | null
    article?: string | null
    style?: string | null
    image_path?: string | null
    slug?: string | null
    status?: ContentStatus | boolean | number
    type_id?: number | null
    category_id?: number | null
    author_id?: number | null
    meta_title?: string | null
    meta_description?: string | null
    meta_keywords?: string | null
    type?: ContentType | null
    category?: ContentCategory | null
    author?: { id: number; name: string; username?: string | null } | null
    tags?: ContentTag[]
    created_at?: string
    updated_at?: string
}

/** Payload the expert-content form submits. */
export interface ContentFormData {
    title: string
    subtitle: string
    type_id: number | null
    category_id: number | null
    article: string
    image_path: File | string | null
    tags: number[]
    status: ContentStatus
    meta_title: string
    meta_description: string
    meta_keywords: string
}

export type ContentBlockType =
    | 'heading'
    | 'text'
    | 'quote'
    | 'image'
    | 'table'
    | 'statistic'
    | 'chart'
    | 'finding'
    | 'recommendation'

export interface ContentBlock {
    id: string | number
    type: ContentBlockType
    title?: string | null
    description?: string | null
    content: Record<string, unknown>
    position: number
}

export interface ContentBlockDefinition {
    type: ContentBlockType
    label: string
    description: string
    category: 'basic' | 'media' | 'data' | 'research'
}

export const CONTENT_BLOCKS: ContentBlockDefinition[] = [
    {
        type: 'heading',
        label: 'Heading',
        description: 'Add a section heading.',
        category: 'basic',
    },
    {
        type: 'text',
        label: 'Text',
        description: 'Add paragraphs and written content.',
        category: 'basic',
    },
    {
        type: 'quote',
        label: 'Quote',
        description: 'Highlight a quotation.',
        category: 'basic',
    },
    {
        type: 'image',
        label: 'Image',
        description: 'Add an image with caption.',
        category: 'media',
    },
    {
        type: 'table',
        label: 'Table',
        description: 'Present structured information.',
        category: 'data',
    },
    {
        type: 'statistic',
        label: 'Statistic',
        description: 'Highlight an important metric.',
        category: 'data',
    },
    {
        type: 'chart',
        label: 'Chart',
        description: 'Visualize numerical data.',
        category: 'data',
    },
    {
        type: 'finding',
        label: 'Finding',
        description: 'Highlight a research finding.',
        category: 'research',
    },
    {
        type: 'recommendation',
        label: 'Recommendation',
        description: 'Present an actionable recommendation.',
        category: 'research',
    },
]