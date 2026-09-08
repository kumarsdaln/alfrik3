<script setup lang="ts">
import {
    ComboboxInput,
    type ComboboxInputProps,
} from 'reka-ui'

const props = defineProps<ComboboxInputProps>()
</script>

<template>
    <ComboboxInput v-bind="props" class="
            w-full
            bg-transparent
            text-sm
            text-content
            outline-none

            placeholder:text-content/50

            disabled:cursor-not-allowed
            disabled:opacity-50
        "/>
</template>