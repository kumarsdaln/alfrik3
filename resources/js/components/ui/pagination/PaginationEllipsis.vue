<script setup lang="ts">
    import {
        PaginationEllipsis
    } from 'reka-ui'
</script>

<template>
    <PaginationEllipsis class="
            inline-flex
            h-9
            min-w-9
            items-center
            justify-center
            px-2
            font-redhat
            text-xs
            text-content-light/40
            dark:text-content-dark/40
        ">
        <slot>
            …
        </slot>
    </PaginationEllipsis>
</template>