<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

interface Props {
    page: number
    url: string | null
    active?: boolean
}

defineProps<Props>()
</script>

<template>
    <Link
        v-if="url"
        :href="url"
        preserve-scroll
        preserve-state
        :aria-current="active ? 'page' : undefined"
        class="inline-flex h-9 min-w-9 items-center justify-center border px-3 text-sm transition hover:bg-muted"
        :class="{
            'bg-primary text-primary-foreground hover:bg-primary/90': active,
        }"
    >
        {{ page }}
    </Link>

    <span
        v-else
        class="inline-flex h-9 min-w-9 items-center justify-center border px-3 text-sm text-muted-foreground"
    >
        {{ page }}
    </span>
</template>