<script setup lang="ts">
import {
    ComboboxLabel,
    type ComboboxLabelProps,
} from 'reka-ui'

const props = defineProps<ComboboxLabelProps>()
</script>

<template>
    <ComboboxLabel
        v-bind="props"
        class="px-3 py-2 text-xs font-medium text-muted-foreground"
    >
        <slot />
    </ComboboxLabel>
</template>