<script setup lang="ts">
import {
    ComboboxCancel,
    type ComboboxCancelProps,
} from 'reka-ui'

const props = defineProps<ComboboxCancelProps>()
</script>

<template>
    <ComboboxCancel v-bind="props">
        <slot />
    </ComboboxCancel>
</template>