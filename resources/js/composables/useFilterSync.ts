import { reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'

type FilterValue =
    | string
    | number
    | boolean
    | null
    | undefined
    | string[]
    | number[]
    | boolean[]

interface UseFilterSyncOptions<T extends Record<string, FilterValue>> {
    routeName?: string
    initialFilters: T
    debounce?: number
    autoApply?: boolean
    preserveState?: boolean
    preserveScroll?: boolean
}

export function useFilterSync<T extends Record<string, FilterValue>>({
    routeName,
    initialFilters,
    debounce = 500,
    autoApply = true,
    preserveState = true,
    preserveScroll = true,
}: UseFilterSyncOptions<T>) {
    const filters = reactive({ ...initialFilters }) as T

    let timeout: ReturnType<typeof setTimeout> | null = null

    const applyFilters = (): void => {
        const cleanedFilters: Partial<T> = {}

        Object.keys(filters).forEach((key) => {
            const value = filters[key as keyof T]

            if (
                value === '' ||
                value === null ||
                value === undefined
            ) {
                return
            }

            if (Array.isArray(value) && value.length === 0) {
                return
            }

            cleanedFilters[key as keyof T] = value
        })

        router.get(
            route(routeName ?? route().current()!),
            cleanedFilters,
            {
                preserveState,
                preserveScroll,
                replace: true,
            }
        )
    }

    const resetFilters = (): void => {
        Object.keys(filters).forEach((key) => {
            const filterKey = key as keyof T
            const value = filters[filterKey]

            if (Array.isArray(value)) {
                ;(filters[filterKey] as FilterValue) = [] as never
            } else {
                ;(filters[filterKey] as FilterValue) = '' as never
            }
        })

        applyFilters()
    }

    const removeFilter = (key: keyof T): void => {
        if (Array.isArray(filters[key])) {
            ;(filters[key] as FilterValue) = [] as never
        } else {
            ;(filters[key] as FilterValue) = '' as never
        }

        applyFilters()
    }

    if (autoApply) {
        watch(
            filters,
            () => {
                if (timeout) {
                    clearTimeout(timeout)
                }

                timeout = setTimeout(applyFilters, debounce)
            },
            {
                deep: true,
            }
        )
    }

    return {
        filters,
        applyFilters,
        resetFilters,
        removeFilter,
    }
}
export default useFilterSync
