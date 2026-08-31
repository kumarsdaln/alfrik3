<script setup lang="ts">
import {
    ComboboxContent,
    type ComboboxContentProps,
} from 'reka-ui'

const props = defineProps<ComboboxContentProps>()
</script>

<template>
    <ComboboxContent v-bind="props">
        <slot />
    </ComboboxContent>
</template>