export interface Category {
    id: number
    name: string
    slug: string
    description?: string | null
    status?: boolean
    sort_order?: number
    parent:Category
}

export interface Tag {
    id: number
    name: string
    slug: string
    description?: string | null
    status?: boolean
}