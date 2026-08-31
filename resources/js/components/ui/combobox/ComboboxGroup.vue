<script setup lang="ts">
import {
    ComboboxGroup,
    type ComboboxGroupProps,
} from 'reka-ui'

const props = defineProps<ComboboxGroupProps>()
</script>

<template>
    <ComboboxGroup v-bind="props">
        <slot />
    </ComboboxGroup>
</template>