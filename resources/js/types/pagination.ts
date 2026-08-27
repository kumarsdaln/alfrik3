export interface Pagination<T> {
    data: T[]
    total: number
    current_page: number
    last_page: number
    per_page: number
    prev_page_url: string | null
    next_page_url: string | null
}