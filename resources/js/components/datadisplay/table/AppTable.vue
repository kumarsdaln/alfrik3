<script setup lang="ts" generic="T extends TableRow">
import { computed } from 'vue'

import AppTableHead from './AppTableHead.vue'
import AppTableBody from './AppTableBody.vue'
import AppTableEmpty from './AppTableEmpty.vue'
import AppTableLoading from './AppTableLoading.vue'

import type {
    RowId,
    SortState,
    TableHeader,
    TableColumn,
    TableRow,
} from '@/types/table'

interface Props {
    headers: TableHeader[]
    columns: TableColumn[]
    data: T[]

    loading?: boolean
    selectable?: boolean
    stickyActions?: boolean

    selectedRows?: RowId[]
    sort?: SortState | null

    isAllSelected?: boolean
    isIndeterminate?: boolean

    primaryKey?: string
}

const props = withDefaults(
    defineProps<Props>(),
    {
        loading: false,
        selectable: false,
        stickyActions: true,
        selectedRows: () => [],
        sort: null,
        isAllSelected: false,
        isIndeterminate: false,
        primaryKey: 'id',
    }
)

const emit = defineEmits<{
    (e: 'sort', key: string): void
    (e: 'toggleSelectAll', checked: boolean): void
    (e: 'toggleRowSelection', id: RowId): void
}>()

/*
|--------------------------------------------------------------------------
| Computed
|--------------------------------------------------------------------------
*/

const slotColumns = computed(() =>
    props.columns.filter(column => !!column.slot)
)

const emptyColspan = computed(() =>
    props.columns.length +
    (props.selectable ? 1 : 0) +
    (props.stickyActions ? 1 : 0)
)
</script>

<template>
    <table class="w-full border-separate border-spacing-0">

        <!-- Header -->
        <AppTableHead
            :headers="headers"
            :sort="sort"
            :selectable="selectable"
            :sticky-actions="stickyActions"
            :is-all-selected="isAllSelected"
            :is-indeterminate="isIndeterminate"
            @sort="emit('sort', $event)"
            @toggleSelectAll="emit('toggleSelectAll', $event)"
        />

        <!-- Body -->
        <AppTableLoading
            v-if="loading"
            :colspan="emptyColspan"
        />

        <AppTableBody
            v-else-if="data.length"
            :data="data"
            :columns="columns"
            :selectable="selectable"
            :sticky-actions="stickyActions"
            :selected-rows="selectedRows"
            :primary-key="primaryKey"
            @toggleRowSelection="emit('toggleRowSelection', $event)"
        >
            <!-- Dynamic Column Slots -->
            <template
                v-for="column in slotColumns"
                :key="column.key"
                #[column.slot!]="slotProps"
            >
                <slot
                    :name="column.slot!"
                    v-bind="slotProps"
                />
            </template>

            <!-- Actions -->
            <template #threedot="slotProps">
                <slot
                    name="threedot"
                    v-bind="slotProps"
                />
            </template>
        </AppTableBody>

        <!-- Empty -->
        <AppTableEmpty
            v-else-if="!loading"
            :colspan="emptyColspan"
        />
    </table>
</template>
