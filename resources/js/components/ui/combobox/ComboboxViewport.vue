<script setup lang="ts">
import {
    ComboboxViewport,
    type ComboboxViewportProps,
} from 'reka-ui'

const props = defineProps<ComboboxViewportProps>()
</script>

<template>
    <ComboboxViewport v-bind="props" class="
            max-h-64
            overflow-y-auto
            bg-surface
            p-1
        ">
        <slot />
    </ComboboxViewport>
</template>