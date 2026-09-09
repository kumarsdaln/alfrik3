export type FilterValue =
    | string
    | number
    | boolean
    | null
    | undefined
    | string[]
    | number[]

export type FilterValues = Record<string, FilterValue>

export type FilterKey<T> = keyof T

export interface UseFiltersOptions<T extends FilterValues> {
    url: string
    searchKey?: FilterKey<T>
    debounce?: number
    preserveState?: boolean
    preserveScroll?: boolean
    replace?: boolean
}