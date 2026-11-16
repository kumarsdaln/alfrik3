<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue'
import { Tag } from '@/types';

withDefaults(
    defineProps<{
        tags: Tag[]
        maxVisible?: number
    }>(),
    {
        maxVisible: 3,
    },
)
</script>

<template>
    <div v-if="tags?.length" class="flex flex-wrap items-center gap-1.5">
        <Badge
            v-for="tag in tags.slice(0, maxVisible)"
            :key="tag.id"
            variant="secondary"
        >
            {{ tag.name }}
        </Badge>

        <span
            v-if="tags.length > maxVisible"
            class="text-xs text-muted-foreground"
        >
            +{{ tags.length - maxVisible }}
        </span>
    </div>

    <span v-else class="text-sm text-muted-foreground">
        —
    </span>
</template>
