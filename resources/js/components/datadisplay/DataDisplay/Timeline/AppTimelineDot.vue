<script setup>
    import { computed } from 'vue'

    import {
        timelineDotVariants,
    } from './timeline.variants'

    const props = defineProps({
        variant: {
            type: String,
            default: 'primary',
        }
    })

    const dotClass = computed(() => {
        return [
            'relative z-10',
            'mt-2',
            'h-3 w-3',
            'shrink-0',
            'rounded-full',

            timelineDotVariants[props.variant],
        ]
    })
</script>

<template>
    <div class="relative flex flex-col items-center">
        <!-- Dot -->
        <div :class="dotClass" />

        <!-- Line -->
        <div class="
                mt-1
                w-px
                flex-1
                bg-neutral-200

                dark:bg-neutral-800
            " />
    </div>
</template>