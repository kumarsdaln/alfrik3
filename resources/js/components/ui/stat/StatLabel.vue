<script setup lang="ts">
    defineProps<{
        label: string
    }>()
</script>

<template>
    <span class="
            font-redhat
            text-[11px]
            font-bold
            uppercase
            tracking-[0.16em]
            text-content-light-muted
            dark:text-content-dark-muted
        ">
        {{ label }}
    </span>
</template>