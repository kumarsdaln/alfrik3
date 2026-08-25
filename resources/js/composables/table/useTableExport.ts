import { router } from '@inertiajs/vue3'
import { toast } from 'vue-sonner'

import type { RowId, TableFilters } from '@/types/table'

interface ExportOptions {
    filename?: string
    filters?: TableFilters
    onlySelected?: boolean
    onSuccess?: () => void
    onError?: () => void
}

export function useTableExport() {

    const exportRows = (
        url: string,
        selectedRows: RowId[],
        options: ExportOptions = {}
    ): void => {
        router.post(
            url,
            {
                ids: options.onlySelected
                    ? selectedRows
                    : [],
                filters:
                    options.filters ?? {},
            },
            {
                preserveScroll: true,
                onSuccess: () => {
                    toast.success(
                        'Export started.'
                    )
                    options.onSuccess?.()

                },
                onError: () => {
                    toast.error(
                        'Unable to export.'
                    )
                    options.onError?.()
                },
            }
        )

    }

    return {
        exportRows,
    }
}