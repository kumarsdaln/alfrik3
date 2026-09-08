<script setup lang="ts">
import { computed, ref, watchEffect } from 'vue'
import {
    ArrowUpDown,
    ArrowUp,
    ArrowDown,
} from '@lucide/vue'

import type {
    Sticky,
    TableHeader,
    SortDirection,
} from '@/types/table'

interface Props {
    header?: TableHeader
    sticky?: Sticky
    selectable?: boolean
    isAllSelected?: boolean
    isIndeterminate?: boolean
    sortDirection?: SortDirection | null
}

const props = withDefaults(
    defineProps<Props>(),
    {
        header: () => ({
            key: '',
            label: '',
        }),
        selectable: false,
        isAllSelected: false,
        isIndeterminate: false,
        sortDirection: null,
    }
)

const emit = defineEmits<{
    (e: 'sort', key: string): void
    (e: 'toggleSelectAll', checked: boolean): void
}>()

/*
|--------------------------------------------------------------------------
| Checkbox
|--------------------------------------------------------------------------
*/

const checkbox = ref<HTMLInputElement | null>(null)

watchEffect(() => {
    if (checkbox.value) {
        checkbox.value.indeterminate = props.isIndeterminate
    }
})

/*
|--------------------------------------------------------------------------
| Classes
|--------------------------------------------------------------------------
*/

const headerClasses = computed(() => [
    'px-4 py-4',
    'text-left text-xs font-semibold uppercase tracking-wider',
    'text-gray-700 dark:text-gray-300',
    'border-b border-l border-gray-200 dark:border-white/10',
    'bg-gray-100/95 dark:bg-zinc-900/95',
    'backdrop-blur-xl',

    props.sticky === 'left'
        ? 'sticky left-0 top-0 z-30'
        : '',

    props.sticky === 'right'
        ? 'sticky right-0 top-0 z-30'
        : '',

    props.header.class,
])

/*
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
*/

const sort = (): void => {
    if (!props.header.sortable) {
        return
    }

    emit('sort', props.header.key)
}

const toggleSelectAll = (checked: boolean): void => {
    emit('toggleSelectAll', checked)
}
</script>

<template>
    <!-- Select -->
    <th
        v-if="props.selectable"
        class="sticky left-0 top-0 z-40
               w-[60px] min-w-[60px]
               px-4 py-4
               bg-gray-100/95 dark:bg-zinc-900/95
               backdrop-blur-xl
               border-b border-l border-gray-200 dark:border-white/10"
    >
        <input
            ref="checkbox"
            type="checkbox"
            :checked="props.isAllSelected"
            @change="toggleSelectAll(($event.target as HTMLInputElement).checked)"
            class="w-4 h-4 rounded-md
                   border-gray-300 dark:border-white/10
                   text-brand bg-white dark:bg-zinc-900
                   focus:ring-2 focus:ring-brand/30
                   focus:ring-offset-0
                   transition
                   cursor-pointer"
        />
    </th>

    <!-- Header -->
    <th
        v-else
        :class="headerClasses"
    >
        <button
            type="button"
            :disabled="!props.header.sortable"
            @click="sort"
            class="flex w-full items-center gap-2 text-left select-none"
            :class="
                props.header.sortable
                    ? 'cursor-pointer hover:text-brand transition-colors'
                    : 'cursor-default'
            "
        >
            <span>{{ props.header.label }}</span>

            <ArrowUpDown
                v-if="props.header.sortable && !props.sortDirection"
                class="h-3.5 w-3.5 opacity-50"
            />

            <ArrowUp
                v-else-if="props.sortDirection === 'asc'"
                class="h-3.5 w-3.5 text-brand"
            />

            <ArrowDown
                v-else-if="props.sortDirection === 'desc'"
                class="h-3.5 w-3.5 text-brand"
            />
        </button>
    </th>
</template>