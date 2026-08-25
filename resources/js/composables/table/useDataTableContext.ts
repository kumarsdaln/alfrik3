import { inject } from 'vue'

import { DataTableKey } from './tableContext'

export function useDataTableContext() {
    const table = inject(DataTableKey)
    if (!table) {
        throw new Error(
            '[DataTable] Missing DataTable provider.'
        )
    }
    return table
}