<script setup lang="ts">
import {
    PaginationLast
} from 'reka-ui'

import { ChevronsRight } from '@lucide/vue'
</script>

<template>
    <PaginationLast
        class="
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
        "
    >
        <slot>
            <ChevronsRight class="h-4 w-4" />
        </slot>
    </PaginationLast>
</template>