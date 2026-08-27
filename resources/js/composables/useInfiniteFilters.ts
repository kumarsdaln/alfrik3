import { reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'
import { RequestPayload } from '@inertiajs/core'

import type { VisitOptions } from '@inertiajs/core'
import type { RouteParams } from '@/lib/route'

export interface UseInfiniteFiltersOptions<
    T extends RequestPayload,
> {
    route: string
    /** Params for routes that take segments, e.g. content.type expects a slug. */
    routeParams?: RouteParams
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

    let timeout: ReturnType<
        typeof setTimeout
    >

    let isResetting = false

    // Apply
    const applyFilters = (): void => {

        router.visit(
            route(options.route, options.routeParams),
            {
                data: {
                    ...filters,
                },
                only: [
                    options.dataKey,
                    options.filterKey ??
                    'filters',
                ],

                reset: [
                    options.dataKey,
                ],

                preserveScroll: true,
                preserveState: true,
                replace: true,
                ...options.visitOptions,
            }
        )
    }

    // Reset
    const resetFilters = (): void => {
        isResetting = true
        clearTimeout(timeout)
        Object.assign(
            filters,
            options.initialFilters,
        )
        router.visit(
            route(options.route, options.routeParams),
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

                preserveScroll: true,
                preserveState: true,
                replace: true,
                onFinish: () => {
                    isResetting = false
                },
                ...options.visitOptions,
            }
        )
    }

    // Watch
    watch(
        () => ({ ...filters }),
        () => {
            if ( isResetting) {return}
            clearTimeout(timeout)
            timeout = setTimeout(applyFilters,debounce,)
        },
        {
            deep: true,
        }
    )

    return {
        filters,
        applyFilters,
        resetFilters,
    }
}