<script setup lang="ts">
import {
    ComboboxAnchor,
    type ComboboxAnchorProps,
} from 'reka-ui'

const props = defineProps<ComboboxAnchorProps>()
</script>

<template>
   <ComboboxAnchor v-bind="props" class="
            relative
            w-full">
        <slot />
    </ComboboxAnchor>
</template>