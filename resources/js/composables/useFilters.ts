import { computed, reactive } from 'vue'

export type FilterValue =
    | string
    | number
    | boolean
    | null
    | undefined
    | string[]
    | number[]

export type FilterValues = Record<string, FilterValue>

export function useFilters<T extends FilterValues>(defaults: T) {
    const filters = reactive({ ...defaults }) as T

    const filterCount = computed(() => {
        let count = 0

        Object.values(filters).forEach(value => {
            if (Array.isArray(value)) {
                if (value.length > 0) {
                    count++
                }

                return
            }

            if (
                value !== undefined &&
                value !== null &&
                value !== ''
            ) {
                count++
            }
        })

        return count
    })

    const hasFilters = computed(() => filterCount.value > 0)

    function clearFilters() {
        Object.assign(
            filters,
            Object.fromEntries(
                Object.entries(defaults).map(([key, value]) => [
                    key,
                    Array.isArray(value) ? [...value] : value,
                ]),
            ),
        )
    }

    return {
        filters,
        filterCount,
        hasFilters,
        clearFilters,
    }
}