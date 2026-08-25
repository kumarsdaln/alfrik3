import { computed, ref } from 'vue'

export type SortDirection = 'asc' | 'desc'

export interface SortState {
    key: string
    direction: SortDirection
}

export function useTableSort(
    initialKey = '',
    initialDirection: SortDirection = 'asc'
) {
    const sort = ref<SortState>({
        key: initialKey,
        direction: initialDirection,
    })

    /**
     * Is any column sorted?
     */
    const hasSort = computed(() => !!sort.value.key)

    /**
     * Is this column currently sorted?
     */
    const isSorted = (key: string): boolean => {
        return sort.value.key === key
    }

    /**
     * Current direction of a column
     */
    const direction = (
        key: string
    ): SortDirection | null => {

        if (!isSorted(key)) {
            return null
        }

        return sort.value.direction
    }

    /**
     * Toggle sorting
     */
    const toggleSort = (
        key: string
    ): SortState => {

        if (sort.value.key === key) {

            sort.value.direction =
                sort.value.direction === 'asc'
                    ? 'desc'
                    : 'asc'

        } else {

            sort.value = {
                key,
                direction: 'asc',
            }

        }

        return sort.value
    }

    /**
     * Set sort manually
     */
    const setSort = (
        key: string,
        direction: SortDirection = 'asc'
    ): void => {

        sort.value = {
            key,
            direction,
        }

    }

    /**
     * Clear sorting
     */
    const clearSort = (): void => {

        sort.value = {
            key: '',
            direction: 'asc',
        }

    }

    return {
        sort,
        hasSort,
        isSorted,
        direction,
        toggleSort,
        setSort,
        clearSort,
    }
}