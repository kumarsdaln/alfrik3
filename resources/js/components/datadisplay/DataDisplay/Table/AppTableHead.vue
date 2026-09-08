<script setup lang="ts">
import AppTableHeader from './AppTableHeader.vue'

import type {
    SortState,
    TableHeader,
} from '@/types/table'

interface Props {
    headers: TableHeader[]
    sort?: SortState | null

    selectable?: boolean
    isAllSelected?: boolean
    isIndeterminate?: boolean

    stickyActions?: boolean
}

const props = withDefaults(
    defineProps<Props>(),
    {
        sort: null,
        selectable: false,
        isAllSelected: false,
        isIndeterminate: false,
        stickyActions: true,
    }
)

const emit = defineEmits<{
    (e: 'sort', key: string): void
    (e: 'toggleSelectAll', checked: boolean): void
}>()

/*
|--------------------------------------------------------------------------
| Constants
|--------------------------------------------------------------------------
*/

const actionsHeader: TableHeader = {
    key: '__actions__',
    label: '',
}

const sortDirectionFor = (header: TableHeader) =>
    props.sort?.key === header.key
        ? props.sort.direction
        : null
</script>

<template>
    <thead
        class="sticky top-0 z-20
               bg-gray-100/90
               backdrop-blur-xl
               dark:bg-zinc-900/90"
    >
        <tr>

            <!-- Select -->
            <AppTableHeader
                v-if="selectable"
                selectable
                :is-all-selected="isAllSelected"
                :is-indeterminate="isIndeterminate"
                @toggleSelectAll="emit('toggleSelectAll', $event)"
            />

            <!-- Headers -->
            <AppTableHeader
                v-for="header in headers"
                :key="header.key"
                :header="header"
                :sort-direction="sortDirectionFor(header)"
                @sort="emit('sort', $event)"
            />

            <!-- Actions -->
            <AppTableHeader
                v-if="stickyActions"
                :header="actionsHeader"
                sticky="right"
            />

        </tr>
    </thead>
</template>
