<script setup lang="ts">
import {
    ComboboxItemIndicator,
    type ComboboxItemIndicatorProps,
} from 'reka-ui'

const props = defineProps<ComboboxItemIndicatorProps>()
</script>

<template>
    <ComboboxItemIndicator v-bind="props">
        <slot />
    </ComboboxItemIndicator>
</template>