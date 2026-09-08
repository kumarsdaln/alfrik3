<script setup lang="ts">
    import { computed } from 'vue'
    import type { PaginationLink } from '@/types/pagination'
    import PaginationListItem from './PaginationListItem.vue'

    interface Props {
        links: PaginationLink[]
    }

    const props = defineProps<Props>()

    const pages = computed(() => {
        const seen = new Set<number>()

        return props.links
            .filter(
                (link): link is PaginationLink & { page: number } => {
                    if (link.page === null || link.page === undefined) {
                        return false
                    }

                    if (seen.has(link.page)) {
                        return false
                    }

                    seen.add(link.page)

                    return true
                }
            )
            .sort((a, b) => a.page - b.page)
    })
</script>

<template>
    <div class="flex items-center gap-1">
        <PaginationListItem v-for="link in pages" :key="link.page" :page="link.page" :url="link.url"
            :active="link.active" />
    </div>
</template>