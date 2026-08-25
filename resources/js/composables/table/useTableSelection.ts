import { computed, ref, type Ref } from 'vue'

import type {
    RowId,
    TableRow,
} from '@/types/table'

export function useTableSelection<T extends TableRow>(
    data: Readonly<Ref<T[]>>,
    primaryKey: string
) {

    const selectedRows = ref<RowId[]>([])

    const currentPageIds = computed<RowId[]>(() =>
        data.value.map(
            row => row[primaryKey] as RowId
        )
    )

    const isAllSelected = computed(() =>
        currentPageIds.value.length > 0 &&
        currentPageIds.value.every(id =>
            selectedRows.value.includes(id)
        )
    )

    const isIndeterminate = computed(() => {

        const selectedCount =
            currentPageIds.value.filter(id =>
                selectedRows.value.includes(id)
            ).length

        return (
            selectedCount > 0 &&
            selectedCount < currentPageIds.value.length
        )

    })

    const toggleSelectAll = (
        checked: boolean
    ): void => {

        if (checked) {

            selectedRows.value = [
                ...new Set([
                    ...selectedRows.value,
                    ...currentPageIds.value,
                ]),
            ]

            return
        }

        selectedRows.value =
            selectedRows.value.filter(
                id =>
                    !currentPageIds.value.includes(id)
            )

    }

    const toggleRowSelection = (
        rowId: RowId
    ): void => {

        const index =
            selectedRows.value.indexOf(rowId)

        if (index > -1) {

            selectedRows.value.splice(index, 1)

        } else {

            selectedRows.value.push(rowId)

        }

    }

    const clearSelection = (): void => {
        selectedRows.value = []
    }

    return {
        selectedRows,
        currentPageIds,
        isAllSelected,
        isIndeterminate,
        toggleSelectAll,
        toggleRowSelection,
        clearSelection,
    }
}