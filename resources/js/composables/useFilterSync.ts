import { reactive, watch, onUnmounted } from 'vue'
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

type FilterRecord = Record<string, FilterValue>

interface UseFilterSyncOptions<T extends FilterRecord> {
    /**
     * Wayfinder-generated URL.
     *
     * If omitted, the current URL is used.
     */
    url?: string

    /**
     * Initial filter values.
     */
    initialFilters: T

    /**
     * Debounce time in milliseconds.
     */
    debounce?: number

    /**
     * Automatically apply filters when they change.
     */
    autoApply?: boolean

    /**
     * Preserve Inertia component state.
     */
    preserveState?: boolean

    /**
     * Preserve scroll position.
     */
    preserveScroll?: boolean
}

export function useFilterSync<T extends FilterRecord>({
    url,
    initialFilters,
    debounce = 500,
    autoApply = true,
    preserveState = true,
    preserveScroll = true,
}: UseFilterSyncOptions<T>) {
    const filters = reactive({
        ...initialFilters,
    }) as T

    let timeout: ReturnType<typeof setTimeout> | null = null

    /**
     * Remove empty values before sending the request.
     */
    const getCleanedFilters = (): Partial<T> => {
        const cleaned: Partial<T> = {}

        for (const key of Object.keys(filters) as Array<keyof T>) {
            const value = filters[key]

            if (
                value === '' ||
                value === null ||
                value === undefined
            ) {
                continue
            }

            if (Array.isArray(value) && value.length === 0) {
                continue
            }

            cleaned[key] = value
        }

        return cleaned
    }

    /**
     * Set a filter value.
     *
     * The assignment is isolated here because TypeScript cannot
     * safely narrow generic indexed properties after Array.isArray().
     */
    const setFilterValue = (
        key: keyof T,
        value: FilterValue,
    ): void => {
        ;(filters as Record<keyof T, unknown>)[key] = value
    }

    /**
     * Apply filters.
     */
    const applyFilters = (): void => {
        const cleanedFilters = getCleanedFilters()

        router.get(
            url ?? window.location.pathname,
            cleanedFilters,
            {
                preserveState,
                preserveScroll,
                replace: true,
            },
        )
    }

    /**
     * Cancel pending debounced request.
     */
    const clearDebounce = (): void => {
        if (timeout !== null) {
            clearTimeout(timeout)
            timeout = null
        }
    }

    /**
     * Schedule filter application.
     */
    const scheduleApply = (): void => {
        clearDebounce()

        timeout = setTimeout(() => {
            timeout = null
            applyFilters()
        }, debounce)
    }

    /**
     * Reset all filters.
     */
    const resetFilters = (): void => {
        for (const key of Object.keys(filters) as Array<keyof T>) {
            const currentValue = filters[key]

            if (Array.isArray(currentValue)) {
                setFilterValue(key, [])
            } else {
                setFilterValue(key, '')
            }
        }

        clearDebounce()
        applyFilters()
    }

    /**
     * Remove one filter.
     */
    const removeFilter = (key: keyof T): void => {
        const currentValue = filters[key]

        if (Array.isArray(currentValue)) {
            setFilterValue(key, [])
        } else {
            setFilterValue(key, '')
        }

        clearDebounce()
        applyFilters()
    }

    /**
     * Automatically apply filters when changed.
     */
    if (autoApply) {
        watch(
            filters,
            () => {
                scheduleApply()
            },
            {
                deep: true,
            },
        )
    }

    /**
     * Cleanup debounce timer when component is destroyed.
     */
    onUnmounted(() => {
        clearDebounce()
    })

    return {
        filters,
        applyFilters,
        resetFilters,
        removeFilter,
    }
}

export default useFilterSync