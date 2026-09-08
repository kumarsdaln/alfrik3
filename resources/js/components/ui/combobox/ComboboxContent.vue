<script setup lang="ts">
import {
    ComboboxContent,
    type ComboboxContentProps,
} from 'reka-ui'

const props = defineProps<ComboboxContentProps>()
</script>

<template>
    <ComboboxContent v-bind="props" class="
            z-50
            mt-1
            overflow-hidden
            border
            border-border
            bg-surface
            text-content
            shadow-sm
        ">
        <slot />
    </ComboboxContent>
</template>