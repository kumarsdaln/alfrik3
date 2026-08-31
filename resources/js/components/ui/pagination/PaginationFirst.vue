<script setup lang="ts">
    import {
        PaginationFirst
    } from 'reka-ui'

    import { ChevronsLeft } from '@lucide/vue'
</script>

<template>
    <PaginationFirst class="
            inline-flex
            h-9
            w-9
            items-center
            justify-center
            border
            border-border-light
            text-content-light
            transition-colors

            hover:border-brand
            hover:text-brand

            disabled:pointer-events-none
            disabled:opacity-30

            dark:border-border-dark
            dark:text-content-dark
        ">
        <slot>
            <ChevronsLeft class="h-4 w-4" />
        </slot>
    </PaginationFirst>
</template>