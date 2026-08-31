<script setup lang="ts">
    defineProps<{
        value: string | number
    }>()
</script>

<template>
    <div class="
            mt-3
            font-redhat
            text-3xl
            font-semibold
            leading-none
            text-content-light
            dark:text-content-dark
        ">
        {{ value }}
    </div>
</template>