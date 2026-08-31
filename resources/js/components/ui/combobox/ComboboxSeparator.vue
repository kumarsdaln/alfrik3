<script setup lang="ts">
import {
    ComboboxSeparator,
    type ComboboxSeparatorProps,
} from 'reka-ui'

const props = defineProps<ComboboxSeparatorProps>()
</script>

<template>
    <ComboboxSeparator
        v-bind="props"
        class="my-1 h-px bg-border"
    />
</template>