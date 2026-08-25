import {
    computed,
    type ComputedRef,
    type Ref,
} from 'vue'

import { useTableSelection } from './useTableSelection'
import { useTableSort } from './useTableSort'
import {
    useTableSearch,
    type UseTableSearchOptions,
} from './useTableSearch'
import { useTableFilters } from './useTableFilters'
import { useTablePreferences } from './useTablePreferences'
import { useTableVirtualScroll } from './useTableVirtualScroll'

import type {
    TableColumn,
    TableFilters,
    TableRow,
} from '@/types/table'


/*
|--------------------------------------------------------------------------
| Options
|--------------------------------------------------------------------------
*/

export interface UseDataTableOptions<
    T extends TableRow,
    F extends TableFilters = TableFilters,
> {
    data: Readonly<Ref<T[]>>
    columns: Readonly<Ref<readonly TableColumn[]>>
    primaryKey?: string
    storageKey?: string
    filters?: F
    search?: UseTableSearchOptions
    virtualScroll?: {
        enabled?: boolean
        rowHeight?: number
        containerHeight?: number
        overscan?: number
    }
}


/*
|--------------------------------------------------------------------------
| Return
|--------------------------------------------------------------------------
*/

export interface UseDataTableReturn<
    T extends TableRow,
    F extends TableFilters = TableFilters,
> {
    rows: Readonly<Ref<T[]>>
    columns: ComputedRef<TableColumn[]>
    selection: ReturnType<
        typeof useTableSelection<T>
    >
    sorting: ReturnType<typeof useTableSort>
    searching: ReturnType<typeof useTableSearch>
    filtering: ReturnType<typeof useTableFilters<F>>
    preferences:
        ReturnType<typeof useTablePreferences> | null
    virtualScroll:
        ReturnType<typeof useTableVirtualScroll> | null
    clear(): void

    refresh(): void
}


/*
|--------------------------------------------------------------------------
| Composable
|--------------------------------------------------------------------------
*/

export function useDataTable<
    T extends TableRow,
    F extends TableFilters = TableFilters,
>(
    options: UseDataTableOptions<T, F>,
): UseDataTableReturn<T, F> {

    /*
    |--------------------------------------------------------------------------
    | Selection
    |--------------------------------------------------------------------------
    */

    const selection = useTableSelection<T>(
        options.data,
        options.primaryKey ?? 'id',
    )


    /*
    |--------------------------------------------------------------------------
    | Sorting
    |--------------------------------------------------------------------------
    */

    const sorting = useTableSort()


    /*
    |--------------------------------------------------------------------------
    | Searching
    |--------------------------------------------------------------------------
    */

    const searching = useTableSearch(
        options.search,
    )


    /*
    |--------------------------------------------------------------------------
    | Filtering
    |--------------------------------------------------------------------------
    */

    const filtering = useTableFilters<F>({
        initialFilters:
            options.filters ??
            ({} as F),
    })


    /*
    |--------------------------------------------------------------------------
    | Preferences
    |--------------------------------------------------------------------------
    */

    const preferences = options.storageKey
        ? useTablePreferences(
            options.storageKey,
        )
        : null


    /*
    |--------------------------------------------------------------------------
    | Virtual Scroll
    |--------------------------------------------------------------------------
    */

    const virtualScroll =
        options.virtualScroll?.enabled
            ? useTableVirtualScroll({
                items: options.data,
                rowHeight:
                    options.virtualScroll.rowHeight,
                containerHeight:
                    options.virtualScroll.containerHeight,
                overscan:
                    options.virtualScroll.overscan,
            })
            : null


    /*
    |--------------------------------------------------------------------------
    | Visible Columns
    |--------------------------------------------------------------------------
    */

    const columns = computed<TableColumn[]>(
        () => {
            if (!preferences) {
                return [
                    ...options.columns.value,
                ]
            }

            return options.columns.value.filter(
                column =>
                    !preferences.hiddenColumns.value
                        .includes(column.key),
            )
        },
    )


    /*
    |--------------------------------------------------------------------------
    | Clear
    |--------------------------------------------------------------------------
    */

    const clear = (): void => {
        selection.clearSelection()
        filtering.resetFilters()
        sorting.clearSort()
        searching.clear()
    }


    /*
    |--------------------------------------------------------------------------
    | Refresh
    |--------------------------------------------------------------------------
    */

    const refresh = (): void => {
        selection.clearSelection()
    }


    /*
    |--------------------------------------------------------------------------
    | Return
    |--------------------------------------------------------------------------
    */

    return {
        rows: options.data,
        columns,
        selection,
        sorting,
        searching,
        filtering,
        preferences,
        virtualScroll,
        clear,
        refresh,
    }
}