<script setup lang="ts">
    defineProps<{
        description: string
    }>()
</script>

<template>
    <p class="
            mt-4
            border-t
            border-border-light
            pt-3
            font-redhat
            text-xs
            text-content-light-muted
            dark:border-border-dark
            dark:text-content-dark-muted
        ">
        {{ description }}
    </p>
</template>