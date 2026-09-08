<script setup lang="ts" generic="T extends TableRow">
import { computed } from 'vue'

import type {
    RowId,
    TableColumn,
    TableRow,
} from '@/types/table'

interface Props {
    row: T
    columns: TableColumn[]

    index?: number
    primaryKey?: string

    selectable?: boolean
    selectedRows?: RowId[]

    stickyActions?: boolean
}

const props = withDefaults(
    defineProps<Props>(),
    {
        index: 0,
        primaryKey: 'id',
        selectable: false,
        selectedRows: () => [],
        stickyActions: true,
    }
)

const emit = defineEmits<{
    (e: 'toggleRowSelection', id: RowId): void
}>()

/*
|--------------------------------------------------------------------------
| Computed
|--------------------------------------------------------------------------
*/

const rowId = computed(
    () => props.row[props.primaryKey] as RowId
)

const isSelected = computed(
    () => props.selectedRows.includes(rowId.value)
)

const rowClasses = computed(() => [

    'group transition-colors duration-200',

    isSelected.value
        ? 'bg-brand/10 dark:bg-brand/10'
        : 'odd:bg-white even:bg-gray-50/40 hover:bg-gray-50 dark:odd:bg-transparent dark:even:bg-white/[0.01] dark:hover:bg-white/[0.03]',

])

const checkboxCellClasses = computed(() => [

    'sticky left-0 z-10',

    'px-4 py-4',

    'border-b border-gray-100 dark:border-white/5',

    isSelected.value
        ? 'bg-brand/10 dark:bg-brand/10'
        : 'bg-white dark:bg-[#080808] group-hover:bg-gray-50 dark:group-hover:bg-white/[0.03]',

])

const actionsCellClasses = computed(() => [

    'sticky right-0 z-10',

    'px-4 py-4',

    'border-b border-gray-100 dark:border-white/5',

    isSelected.value
        ? 'bg-brand/10 dark:bg-brand/10'
        : 'bg-white dark:bg-[#080808] group-hover:bg-gray-50 dark:group-hover:bg-white/[0.03]',

    'shadow-[-8px_0_12px_-8px_rgba(0,0,0,0.15)]',

])

/*
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
*/

const toggleSelection = (): void => {
    emit('toggleRowSelection', rowId.value)
}
</script>

<template>
    <tr :class="rowClasses">

        <!-- Select -->
        <td
            v-if="selectable"
            :class="checkboxCellClasses"
        >
            <input
                type="checkbox"
                :checked="isSelected"
                @change="toggleSelection"
                class="h-4 w-4
                       cursor-pointer
                       rounded-md
                       border-gray-300
                       bg-white
                       text-brand
                       transition
                       focus:ring-2
                       focus:ring-brand/30
                       focus:ring-offset-0
                       dark:border-white/10
                       dark:bg-zinc-900"
            >
        </td>

        <!-- Cells -->
        <slot
            name="cells"
            :row="row"
            :index="index"
            :selected="isSelected"
            :row-id="rowId"
        />

        <!-- Actions -->
        <td
            v-if="stickyActions"
            :class="actionsCellClasses"
        >
            <slot
                name="actions"
                :row="row"
                :index="index"
                :selected="isSelected"
                :row-id="rowId"
            />
        </td>

    </tr>
</template>