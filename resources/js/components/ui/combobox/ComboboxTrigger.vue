<script setup lang="ts">
import {
    ComboboxTrigger,
    type ComboboxTriggerProps,
} from 'reka-ui'

const props = defineProps<ComboboxTriggerProps>()
</script>

<template>
    <ComboboxTrigger v-bind="props" class="
            flex
            shrink-0
            items-center
            justify-center
            text-content/50
            outline-none
            transition-colors

            hover:text-content

            focus-visible:text-primary

            disabled:pointer-events-none
            disabled:opacity-50
        ">
        <slot />
    </ComboboxTrigger>
</template>