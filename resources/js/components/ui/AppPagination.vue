<script setup lang="ts">
import {
    PaginationRoot,
    PaginationList,
    PaginationListItem,
    PaginationPrev,
    PaginationNext,
    PaginationFirst,
    PaginationLast,
    PaginationEllipsis,
} from '@/components/ui/pagination'

interface PaginationMeta {
    current_page: number
    last_page: number
    from: number | null
    to: number | null
    total: number
    per_page: number
}

const props = withDefaults(
    defineProps<{
        meta: PaginationMeta
        showEdges?: boolean
        siblingCount?: number
        hideSummary?: boolean
    }>(),
    {
        showEdges: false,
        siblingCount: 1,
        hideSummary: false,
    },
)

const emit = defineEmits<{
    pageChange: [page: number]
}>()

const changePage = (page: number) => {
    if (
        page < 1 ||
        page > props.meta.last_page ||
        page === props.meta.current_page
    ) {
        return
    }

    emit('pageChange', page)
}
</script>

<template>
    <div
        v-if="meta.last_page > 1"
        class="
            flex
            flex-col
            gap-5
            border-t
            border-border-light
            pt-5
            dark:border-border-dark
            sm:flex-row
            sm:items-center
            sm:justify-between
        "
    >
        <div
            v-if="!hideSummary"
            class="
                shrink-0
                font-redhat
                text-xs
                text-content-light/60
                dark:text-content-dark/60
            "
        >
            <template v-if="meta.from !== null && meta.to !== null">
                Showing
                <span
                    class="
                        font-semibold
                        text-content-light
                        dark:text-content-dark
                    "
                >
                    {{ meta.from }}–{{ meta.to }}
                </span>
                of
                <span
                    class="
                        font-semibold
                        text-content-light
                        dark:text-content-dark
                    "
                >
                    {{ meta.total }}
                </span>
            </template>

            <template v-else>
                {{ meta.total }} results
            </template>
        </div>

        <PaginationRoot
            :page="meta.current_page"
            :total="meta.total"
            :items-per-page="meta.per_page"
            :sibling-count="siblingCount"
            :show-edges="showEdges"
            @update:page="changePage"
        >
            <PaginationList v-slot="{ items }">
                <PaginationFirst
                    v-if="showEdges"
                    aria-label="Go to first page"
                />

                <PaginationPrev
                    aria-label="Go to previous page"
                />

                <template
                    v-for="(item, index) in items"
                    :key="index"
                >
                    <PaginationListItem
                        v-if="item.type === 'page'"
                        :value="item.value"
                    >
                        {{ item.value }}
                    </PaginationListItem>

                    <PaginationEllipsis
                        v-else
                        :index="index"
                    />
                </template>

                <PaginationNext
                    aria-label="Go to next page"
                />

                <PaginationLast
                    v-if="showEdges"
                    aria-label="Go to last page"
                />
            </PaginationList>
        </PaginationRoot>
    </div>
</template>