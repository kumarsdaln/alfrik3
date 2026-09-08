<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ChevronLeft } from '@lucide/vue'

interface Props {
    url?: string | null
}

defineProps<Props>()
</script>

<template>
    <Link
        v-if="url"
        :href="url"
        preserve-scroll
        preserve-state
        aria-label="Previous page"
        class="inline-flex h-9 w-9 items-center justify-center border transition hover:bg-muted"
    >
        <ChevronLeft class="h-4 w-4" />
    </Link>

    <span
        v-else
        aria-disabled="true"
        aria-label="Previous page"
        class="inline-flex h-9 w-9 items-center justify-center border text-muted-foreground"
    >
        <ChevronLeft class="h-4 w-4" />
    </span>
</template>