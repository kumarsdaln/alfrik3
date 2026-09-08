<script setup lang="ts">
    import { computed } from 'vue'
    import { Link } from '@inertiajs/vue3'
    import type { PaginationMeta, PaginationLink } from '@/types/pagination'
    import { PaginationPrev, PaginationNext, PaginationList } from './pagination'

    interface Props {
        meta: PaginationMeta
    }

    const props = defineProps<Props>()

    const previousLink = computed(() =>
        props.meta.links.find(link =>
            link.label.toLowerCase().includes('previous')
        )
    )

    const nextLink = computed(() =>
        props.meta.links.find(link =>
            link.label.toLowerCase().includes('next')
        )
    )
</script>

<template>
    <div v-if="meta.total > 0"
        class="flex flex-col gap-4 border-t px-4 py-4 sm:flex-row sm:items-center sm:justify-between">
        <!-- Results -->
        <p class="text-sm text-muted-foreground">
            Showing
            <span class="font-medium text-foreground">
                {{ meta.from }}
            </span>
            to
            <span class="font-medium text-foreground">
                {{ meta.to }}
            </span>
            of
            <span class="font-medium text-foreground">
                {{ meta.total }}
            </span>
            results
        </p>

        <!-- Pagination -->
        <nav v-if="meta.last_page > 1" class="flex items-center gap-1" aria-label="Pagination">
            <!-- Previous -->
            <PaginationPrev :url="previousLink?.url" />

            <!-- Pages -->
            <PaginationList :links="meta.links" />

            <!-- Next -->
            <PaginationNext :url="nextLink?.url" />
        </nav>
    </div>
</template>