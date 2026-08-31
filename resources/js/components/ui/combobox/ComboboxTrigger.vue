<script setup lang="ts">
import {
    ComboboxTrigger,
    type ComboboxTriggerProps,
} from 'reka-ui'

const props = defineProps<ComboboxTriggerProps>()
</script>

<template>
    <ComboboxTrigger v-bind="props">
        <slot />
    </ComboboxTrigger>
</template>