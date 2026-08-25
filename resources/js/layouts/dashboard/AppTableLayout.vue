<script
    setup
    lang="ts"
    generic="T extends TableRow"
>
import { toRef } from 'vue'

import PageHeader from '@/components/dashboard/PageHeader.vue'

import AppTable from '@/components/datadisplay/table/AppTable.vue'
import AppTablePagination from '@/components/datadisplay/table/AppTablePagination.vue'
import AppTableBulkActions from '@/components/datadisplay/table/AppTableBulkActions.vue'

import { useDataTable } from '@/composables/table/useDataTable'

import type {
    RowId,
    TableColumn,
    TableFilters,
    TableHeader,
    TableRow,
} from '@/types/table'


/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/

interface Props {
    title?: string
    headers: TableHeader[]
    columns: TableColumn[]
    data: T[]
    loading?: boolean
    selectable?: boolean
    stickyActions?: boolean
    primaryKey?: string
    total?: number
    perPage?: number
    currentPage?: number
    lastPage?: number
    prevPageUrl?: string | null
    nextPageUrl?: string | null
    filters?: TableFilters
}


const props = withDefaults(
    defineProps<Props>(),
    {
        title: 'Entries',
        loading: false,
        selectable: false,
        stickyActions: true,
        primaryKey: 'id',
        total: 0,
        perPage: 0,
        currentPage: 1,
        lastPage: 1,
        prevPageUrl: null,
        nextPageUrl: null,
        filters: () => ({}),
    },
)


/*
|--------------------------------------------------------------------------
| Emits
|--------------------------------------------------------------------------
*/

const emit = defineEmits<{
    sort: [key: string]
    bulkExport: [ids: RowId[]]
    bulkDelete: [ids: RowId[]]
}>()


/*
|--------------------------------------------------------------------------
| Table
|--------------------------------------------------------------------------
*/

const table = useDataTable({
    data: toRef(props, 'data'),
    columns: toRef(props, 'columns'),
    primaryKey: props.primaryKey,
    filters: props.filters,
})


/*
|--------------------------------------------------------------------------
| Bulk Actions
|--------------------------------------------------------------------------
*/

const handleBulkExport = (): void => {
    emit(
        'bulkExport',
        table.selection.selectedRows.value,
    )
}


const handleBulkDelete = (): void => {
    emit(
        'bulkDelete',
        table.selection.selectedRows.value,
    )
}


/*
|--------------------------------------------------------------------------
| Sorting
|--------------------------------------------------------------------------
*/

const handleSort = (
    key: string,
): void => {
    table.sorting.toggleSort(key)

    emit('sort', key)
}
</script>


<template>
    <div
        class="
            flex
            h-full
            flex-col
            overflow-hidden
            border
            border-gray-200
            bg-white
            shadow-xl
            shadow-black/5
            dark:border-white/10
            dark:bg-[#080808]
        "
    >
        <!-- Page Header -->

        <PageHeader
            :title="`Manage ${title}`"
        >
            <template #header-actions>
                <slot name="header" />
            </template>
        </PageHeader>


        <!-- Filters -->

        <div
            v-if="$slots.filter"
            class="
                border-b
                border-gray-200
                bg-gray-50/70
                p-4
                backdrop-blur-xl
                dark:border-white/10
                dark:bg-white/[0.02]
            "
        >
            <slot name="filter" />
        </div>


        <!-- Bulk Actions -->

        <AppTableBulkActions
            v-if="selectable"
            :selected-rows="
                table.selection.selectedRows.value
            "
            @clear="
                table.selection.clearSelection
            "
            @export="handleBulkExport"
            @delete="handleBulkDelete"
        />


        <!-- Table -->

        <div
            class="
                min-h-0
                flex-1
                overflow-auto
            "
        >
            <AppTable
                :headers="headers"
                :columns="table.columns.value"
                :data="table.rows.value"
                :loading="loading"
                :selectable="selectable"
                :sticky-actions="stickyActions"
                :selected-rows="
                    table.selection.selectedRows.value
                "
                :sort="
                    table.sorting.sort.value
                "
                :is-all-selected="
                    table.selection.isAllSelected.value
                "
                :is-indeterminate="
                    table.selection.isIndeterminate.value
                "
                :primary-key="primaryKey"
                @sort="handleSort"
                @toggleSelectAll="
                    table.selection.toggleSelectAll
                "
                @toggleRowSelection="
                    table.selection.toggleRowSelection
                "
            >
                <!-- Forward all named slots -->

                <template
                    v-for="(_, slotName) in $slots"
                    :key="String(slotName)"
                    #[slotName]="scope"
                >
                    <slot
                        :name="slotName"
                        v-bind="scope"
                    />
                </template>
            </AppTable>
        </div>


        <!-- Pagination -->

        <AppTablePagination
            :data-length="
                table.rows.value.length
            "
            :total="total"
            :per-page="perPage"
            :current-page="currentPage"
            :last-page="lastPage"
            :prev-page-url="prevPageUrl"
            :next-page-url="nextPageUrl"
            :filters="filters"
        />
    </div>
</template>