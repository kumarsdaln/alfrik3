<script setup lang="ts">
import Badge from '@/components/ui/badge/Badge.vue'
import { Category } from '@/types';

withDefaults(
    defineProps<{
        categories: Category[]
        maxVisible?: number
    }>(),
    {
        maxVisible: 3,
    },
)
</script>

<template>
    <div v-if="categories?.length" class="flex flex-wrap items-center gap-1.5">
        <Badge
            v-for="category in categories.slice(0, maxVisible)"
            :key="category.id"
            variant="outline"
        >
            {{ category.name }}
        </Badge>

        <span
            v-if="categories.length > maxVisible"
            class="text-xs text-muted-foreground"
        >
            +{{ categories.length - maxVisible }}
        </span>
    </div>

    <span v-else class="text-sm text-muted-foreground">
        —
    </span>
</template>
