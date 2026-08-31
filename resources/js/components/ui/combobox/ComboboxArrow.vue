<script setup lang="ts">
import {
    ComboboxArrow,
    type ComboboxArrowProps,
} from 'reka-ui'

const props = defineProps<ComboboxArrowProps>()
</script>

<template>
    <ComboboxArrow v-bind="props" />
</template>