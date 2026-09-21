import { reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'

import type {
    RequestPayload,
    VisitOptions,
} from '@inertiajs/core'

export interface UseInfiniteFiltersOptions<
    T extends RequestPayload,
> {
    url: string
    dataKey: string
    filterKey?: string
    initialFilters: T
    debounce?: number
    visitOptions?: VisitOptions
}

export function useInfiniteFilters<
    T extends RequestPayload,
>(
    options: UseInfiniteFiltersOptions<T>,
) {
    const filters = reactive({
        ...options.initialFilters,
    }) as T

    const debounce =
        options.debounce ?? 500

    let timeout: ReturnType<typeof setTimeout> | undefined

    let isResetting = false

    const visit = () => {
        router.visit(
            options.url,
            {
                data: {
                    ...filters,
                },

                only: [
                    options.dataKey,
                    options.filterKey ?? 'filters',
                ],

                reset: [
                    options.dataKey,
                ],

                preserveState: true,
                preserveScroll: true,
                replace: true,

                ...options.visitOptions,
            },
        )
    }

    const applyFilters = () => {
        clearTimeout(timeout)

        timeout = setTimeout(() => {
            visit()
        }, debounce)
    }

    const resetFilters = () => {
        clearTimeout(timeout)

        isResetting = true

        Object.assign(
            filters,
            options.initialFilters,
        )

        router.visit(
            options.url,
            {
                data: {
                    ...filters,
                },

                only: [
                    options.dataKey,
                    options.filterKey ?? 'filters',
                ],

                reset: [
                    options.dataKey,
                ],

                preserveState: true,
                preserveScroll: true,
                replace: true,

                onFinish: () => {
                    isResetting = false
                },

                ...options.visitOptions,
            },
        )
    }

    watch(
        filters,
        () => {
            if (isResetting) {
                return
            }

            applyFilters()
        },
        {
            deep: true,
        },
    )

    return {
        filters,
        applyFilters,
        resetFilters,
    }
}