import {
    computed,
    reactive,
} from 'vue'

import type {
    TableFilters,
} from '@/types/table'

export interface UseTableFiltersOptions<
    T extends TableFilters,
> {
    initialFilters: T
}

export function useTableFilters<
    T extends TableFilters,
>(
    options: UseTableFiltersOptions<T>,
) {

    //Filters
    const filters = reactive({
        ...options.initialFilters,
    }) as T

    //Has Filters
    const hasFilters = computed(() => {

        return Object.values(filters).some(value => {

            if (
                value === '' ||
                value === null ||
                value === undefined
            ) {
                return false
            }

            if (Array.isArray(value)) {
                return value.length > 0
            }

            return true

        })

    })

    //Cleaned Filters
    const cleanedFilters = computed<T>(() => {

        const result = {} as T

        for (const [key, value] of Object.entries(filters)) {

            if (
                value === '' ||
                value === null ||
                value === undefined
            ) {
                continue
            }

            if (
                Array.isArray(value) &&
                value.length === 0
            ) {
                continue
            }

            result[key as keyof T] = value as T[keyof T]

        }

        return result

    })

    //Reset
    const resetFilters = (): void => {

        for (const key of Object.keys(filters)) {

            const filterKey = key as keyof T
            const value = filters[filterKey]

                ; (filters as Record<string, unknown>)[key] =
                    Array.isArray(value)
                        ? []
                        : ''

        }

    }

    //Remove
    const removeFilter = (
        key: keyof T,
    ): void => {

        const value = filters[key]

            ; (filters as Record<string, unknown>)[key as string] =
                Array.isArray(value)
                    ? []
                    : ''

    }

    //Return
    return {
        filters,
        hasFilters,
        cleanedFilters,
        resetFilters,
        removeFilter,
    }

}