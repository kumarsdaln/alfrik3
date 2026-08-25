import { computed, ref } from 'vue'

import type { TableColumn } from '@/types/table'

export function useTableColumns(
    initialColumns: TableColumn[]
) {

    const columns = ref<TableColumn[]>([
        ...initialColumns
    ])

    const hiddenColumns = ref<string[]>([])

    /**
     * Visible columns
     */
    const visibleColumns = computed(() => {

        return columns.value.filter(
            column =>
                !hiddenColumns.value.includes(column.key)
        )

    })

    /**
     * Hide column
     */
    const hideColumn = (
        key: string
    ): void => {

        if (
            hiddenColumns.value.includes(key)
        ) {
            return
        }

        hiddenColumns.value.push(key)

    }

    /**
     * Show column
     */
    const showColumn = (
        key: string
    ): void => {

        hiddenColumns.value =
            hiddenColumns.value.filter(
                column => column !== key
            )

    }

    /**
     * Toggle
     */
    const toggleColumn = (
        key: string
    ): void => {
        if (
            hiddenColumns.value.includes(key)
        ) {
            showColumn(key)
        } else {
            hideColumn(key)
        }

    }

    /**
     * Reset
     */
    const resetColumns = (): void => {

        hiddenColumns.value = []

        columns.value = [
            ...initialColumns
        ]

    }

    return {
        columns,
        visibleColumns,
        hiddenColumns,
        hideColumn,
        showColumn,
        toggleColumn,
        resetColumns,
    }

}