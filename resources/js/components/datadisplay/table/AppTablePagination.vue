<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import {
    ChevronLeft,
    ChevronRight,
} from '@lucide/vue'

import type {
    TableFilters,
} from '@/types/table'

import { useTablePagination } from '@/composables/table/useTablePagination'

interface Props {
    dataLength?: number
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
        dataLength: 0,
        total: 0,
        perPage: 0,

        currentPage: 1,
        lastPage: 1,

        prevPageUrl: null,
        nextPageUrl: null,

        filters: () => ({}),
    }
)

/*
|--------------------------------------------------------------------------
| Computed
|--------------------------------------------------------------------------
*/

const {
    hasPrevious,
    hasNext,
    generateUrl,
} = useTablePagination({
    currentPage: computed(() => props.currentPage),
    lastPage: computed(() => props.lastPage),
    prevPageUrl: computed(() => props.prevPageUrl),
    nextPageUrl: computed(() => props.nextPageUrl),
    filters: computed(() => props.filters),
})

const pageSize = computed(() => {

    if (props.perPage > 0) {
        return props.perPage
    }

    return props.dataLength

})

const from = computed(() => {

    if (!props.total) {
        return 0
    }

    return (
        (props.currentPage - 1) *
        pageSize.value +
        1
    )

})

const to = computed(() => {

    if (!props.total) {
        return 0
    }

    return Math.min(

        from.value +
        props.dataLength -
        1,

        props.total

    )

})

</script>

<template>

    <div
        class="flex items-center justify-between
               border-t border-gray-200
               bg-gray-50/70
               px-6 py-4
               backdrop-blur-xl
               dark:border-white/10
               dark:bg-white/[0.02]"
    >

        <!-- Information -->
        <div class="text-sm text-gray-500">

            Showing

            <span class="font-semibold text-gray-900 dark:text-white">
                {{ from }}
            </span>

            -

            <span class="font-semibold text-gray-900 dark:text-white">
                {{ to }}
            </span>

            of

            <span class="font-semibold text-gray-900 dark:text-white">
                {{ total }}
            </span>

            entries

        </div>

        <!-- Navigation -->
        <div class="flex items-center gap-2">

            <Link
                :href="generateUrl(prevPageUrl)"
                class="pagination-button"
                :class="{
                    'pointer-events-none opacity-40': !hasPrevious,
                }"
            >
                <ChevronLeft class="h-4 w-4" />
            </Link>

            <div>
                {{ currentPage }}
                /
                {{ lastPage }}
            </div>

            <Link
                :href="generateUrl(nextPageUrl)"
                class="pagination-button"
                :class="{
                    'pointer-events-none opacity-40': !hasNext,
                }"
            >
                <ChevronRight class="h-4 w-4" />
            </Link>

        </div>

    </div>

</template>