<script setup lang="ts">
    import type { Component } from 'vue'

    defineProps<{
        icon: Component
    }>()
</script>

<template>
    <div class="
            flex
            h-9
            w-9
            shrink-0
            items-center
            justify-center
            border
            border-border-light
            text-content-light-muted
            dark:border-border-dark
            dark:text-content-dark-muted
        ">
        <component :is="icon" class="h-4 w-4" />
    </div>
</template>