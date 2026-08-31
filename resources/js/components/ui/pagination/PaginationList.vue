<script setup lang="ts">
import {
    PaginationList as PaginationListPrimitive,
} from 'reka-ui'
</script>

<template>
    <PaginationListPrimitive v-slot="slotProps">
        <slot v-bind="slotProps" />
    </PaginationListPrimitive>
</template>