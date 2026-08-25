<script setup lang="ts" generic="T extends TableRow">
import { computed } from 'vue'

import {
    formatDate,
    formatDateTime,
} from '@/utils/dateUtils'

import type {
    TableColumn,
    TableRow,
} from '@/types/table'

interface Props {
    column: TableColumn
    row: T

    index?: number

    bordered?: boolean
    truncate?: boolean
}

const props = withDefaults(
    defineProps<Props>(),
    {
        index: 0,
        bordered: true,
        truncate: false,
    }
)

/*
|--------------------------------------------------------------------------
| Computed
|--------------------------------------------------------------------------
*/

const cellValue = computed(
    () => props.row[props.column.key]
)

const isTruthy = computed(
    () => Boolean(cellValue.value)
)

const cellClasses = computed(() => [
    'px-4 py-4 text-sm align-middle',
    'text-gray-700 dark:text-gray-200',

    props.bordered &&
        'border-b border-l border-gray-100 dark:border-white/5',

    props.truncate &&
        'max-w-[220px] truncate',

    props.column.class,
])
</script>

<template>
    <td :class="cellClasses">

        <!-- Custom Slot -->
        <template v-if="column.slot">
            <slot
                :name="column.slot"
                :data="row"
                :index="index"
            />
        </template>

        <!-- Image -->
        <img
            v-else-if="column.type === 'image'"
            :src="String(cellValue ?? '')"
            alt=""
            class="w-10 h-10 rounded-xl object-cover border border-gray-200 dark:border-white/10"
        >

        <!-- Badge -->
        <span
            v-else-if="column.type === 'badge'"
            class="inline-flex items-center rounded-full
                   bg-gray-100 px-2.5 py-1
                   text-xs font-medium
                   dark:bg-white/5"
        >
            {{ cellValue }}
        </span>

        <!-- Boolean -->
        <span
            v-else-if="column.type === 'boolean'"
            :class="[
                'inline-flex items-center rounded-full px-2.5 py-1 text-xs font-medium',
                isTruthy
                    ? 'bg-green-100 text-green-700 dark:bg-green-500/10 dark:text-green-400'
                    : 'bg-red-100 text-red-700 dark:bg-red-500/10 dark:text-red-400',
            ]"
        >
            {{ isTruthy ? 'Active' : 'Inactive' }}
        </span>

        <!-- Date -->
        <template v-else-if="column.type === 'date'">
            {{ formatDate(cellValue) }}
        </template>

        <!-- DateTime -->
        <template v-else-if="column.type === 'datetime'">
            {{ formatDateTime(cellValue) }}
        </template>

        <!-- HTML -->
        <div
            v-else-if="column.type === 'html'"
            v-html="String(cellValue ?? '')"
        />

        <!-- Default -->
        <template v-else>
            {{ cellValue }}
        </template>

    </td>
</template>