import { computed, reactive, watch } from 'vue'
import { router } from '@inertiajs/vue3'

import type { FilterKey, FilterValues, UseFiltersOptions } from '@/types'

function cloneValue<T>(value: T): T {
    if (Array.isArray(value)) {
        return [...value] as T
    }

    if (value !== null && typeof value === 'object') {
        return { ...value }
    }

    return value
}

function isActive(value: unknown): boolean {
    if (Array.isArray(value)) {
        return value.length > 0
    }

    return value !== undefined &&
        value !== null &&
        value !== ''
}

export function useFilters<T extends FilterValues>(
    defaults: T,
    options: UseFiltersOptions<T>,
) {
    const {
        url,
        debounce = 500,
        preserveState = true,
        preserveScroll = true,
        replace = true,
    } = options

    /**
     * Keep an independent copy of the initial filters.
     */
    const initialFilters = Object.fromEntries(
        Object.entries(defaults).map(([key, value]) => [
            key,
            cloneValue(value),
        ]),
    ) as T

    /**
     * Reactive filters.
     */
    const filters = reactive({
        ...initialFilters,
    }) as T

    /**
     * Active filter keys.
     */
    const activeFilters = computed<FilterKey<T>[]>(() =>
        Object.entries(filters)
            .filter(([, value]) => isActive(value))
            .map(([key]) => key as FilterKey<T>),
    )

    /**
     * Number of active filters.
     */
    const filterCount = computed(() => activeFilters.value.length)

    /**
     * Whether any filter is active.
     */
    const hasFilters = computed(() => filterCount.value > 0)

    /**
     * Check whether a filter is active.
     */
    function hasFilter(key: FilterKey<T>): boolean {
        return isActive(filters[key])
    }

    /**
     * Build query parameters.
     *
     * Empty values are removed from the request.
     */
    function getQueryParams(): Partial<T> {
        return Object.fromEntries(
            Object.entries(filters).filter(([, value]) =>
                isActive(value),
            ),
        ) as Partial<T>
    }

    /**
     * Apply filters.
     */
    function applyFilters(): void {
        router.get(
            url,
            getQueryParams(),
            {
                preserveState,
                preserveScroll,
                replace,
            },
        )
    }

    /**
     * Reset a single filter.
     */
    function resetFilter<K extends FilterKey<T>>(key: K): void {
        filters[key] = cloneValue(initialFilters[key]) as T[K]
    }

    /**
     * Clear all filters.
     */
    function clearFilters(): void {
        for (const key of Object.keys(initialFilters) as FilterKey<T>[]) {
            filters[key] = cloneValue(
                initialFilters[key],
            ) as T[typeof key]
        }

        applyFilters()
    }

    /**
     * Automatically apply filters after the user
     * stops typing.
     *
     * The first filter is treated as the search field.
     */
    let debounceTimer: ReturnType<typeof setTimeout> | undefined

    function applyDebounced(): void {
        if (debounceTimer) {
            clearTimeout(debounceTimer)
        }

        debounceTimer = setTimeout(() => {
            applyFilters()
        }, debounce)
    }

    /**
     * Watch the first filter (normally `search`).
     *
     * Other filters are intentionally not watched here,
     * so they can be applied through the Apply button.
     */
    const searchKey = Object.keys(filters)[0] as FilterKey<T>

    watch(
        () => filters[searchKey],
        () => {
            applyDebounced()
        },
    )

    return {
        filters,
        activeFilters,
        filterCount,
        hasFilters,
        hasFilter,
        applyFilters,
        clearFilters,
        resetFilter,
    }
}