<script setup lang="ts">
import {
    ComboboxViewport,
    type ComboboxViewportProps,
} from 'reka-ui'

const props = defineProps<ComboboxViewportProps>()
</script>

<template>
    <ComboboxViewport v-bind="props">
        <slot />
    </ComboboxViewport>
</template>