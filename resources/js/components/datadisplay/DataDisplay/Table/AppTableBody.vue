<script setup lang="ts" generic="T extends TableRow">
    import AppTableCell from './AppTableCell.vue'
    import AppTableRow from './AppTableRow.vue'

    import type {
        RowId,
        TableColumn,
        TableRow,
    } from '@/types/table'

    interface Props {
        data: T[]
        columns: TableColumn[]

        selectable?: boolean
        selectedRows?: RowId[]

        primaryKey?: string

        stickyActions?: boolean
    }

    const props = withDefaults(
        defineProps<Props>(),
        {
            selectable: false,
            selectedRows: () => [],
            primaryKey: 'id',
            stickyActions: true,
        }
    )

    const emit = defineEmits<{
        (e: 'toggleRowSelection', id: RowId): void
    }>()

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    const getRowKey = (
        row: TableRow,
    ): RowId => row[props.primaryKey] as RowId
</script>

<template>
    <tbody>
        <AppTableRow v-for="(row, index) in data" :key="getRowKey(row)" :row="row" :index="index" :columns="columns"
            :primary-key="primaryKey" :selectable="selectable" :selected-rows="selectedRows"
            :sticky-actions="stickyActions" @toggleRowSelection="emit('toggleRowSelection', $event)">
            <!-- Forward All Slots -->
            <template #cells="{ row, index }">

                <AppTableCell v-for="column in columns" :key="column.key" :column="column" :row="row" :index="index">
                    <template v-if="column.slot" #[column.slot]="slotProps">
                        <slot :name="column.slot" v-bind="slotProps" />
                    </template>

                </AppTableCell>

            </template>

            <template #actions="{ row, index }">
                <slot name="threedot" :data="row" :index="index" />
            </template>
        </AppTableRow>
    </tbody>
</template>