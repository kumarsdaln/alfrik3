<script setup lang="ts">
import {
    ComboboxInput,
    type ComboboxInputProps,
} from 'reka-ui'

const props = defineProps<ComboboxInputProps>()
</script>

<template>
    <ComboboxInput v-bind="props" />
</template>