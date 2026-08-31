<script setup lang="ts">
    import {
        PaginationListItem
    } from 'reka-ui'

    interface Props {
        value: number
    }

    defineProps<Props>()
</script>

<template>
    <PaginationListItem :value="value" class="
            inline-flex
            h-9
            min-w-9
            items-center
            justify-center
            border
            border-border-light
            px-2
            font-redhat
            text-xs
            font-semibold
            text-content-light
            transition-colors

            hover:border-brand
            hover:text-brand

            data-[selected]:border-brand
            data-[selected]:bg-brand
            data-[selected]:text-white

            dark:border-border-dark
            dark:text-content-dark

            dark:data-[selected]:text-white
        ">
        <slot>
            {{ value }}
        </slot>
    </PaginationListItem>
</template>