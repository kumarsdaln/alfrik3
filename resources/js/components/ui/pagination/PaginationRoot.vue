<script setup lang="ts">
    import {
        PaginationRoot,
        type PaginationRootProps,
    } from 'reka-ui'

    const props = defineProps<PaginationRootProps>()
</script>

<template>
    <PaginationRoot v-bind="props" class="flex items-center">
        <slot />
    </PaginationRoot>
</template>