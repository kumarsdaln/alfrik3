import type { InjectionKey } from 'vue'

import type {
    TableRow,
    TableFilters,
} from '@/types/table'

import type {
    UseDataTableReturn,
} from './useDataTable'

export const DataTableKey =
    Symbol('DataTable') as InjectionKey<
        UseDataTableReturn<
            TableRow,
            TableFilters
        >
    >