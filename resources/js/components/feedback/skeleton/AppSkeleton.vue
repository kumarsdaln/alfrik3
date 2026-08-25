<script setup>
    import { computed } from 'vue'

    import {
        skeletonVariants,
        skeletonRounded,
    } from './skeleton.variants'

    const props = defineProps({
        width: {
            type: String,
            default: 'w-full',
        },

        height: {
            type: String,
            default: 'h-4',
        },

        rounded: {
            type: String,
            default: 'md',
        },

        animated: {
            type: Boolean,
            default: true,
        }
    })

    const skeletonClass = computed(() => {
        return [
            skeletonVariants.default,

            skeletonRounded[props.rounded],

            props.width,

            props.height,

            props.animated &&
            'animate-pulse',
        ]
    })
</script>

<template>
    <div :class="skeletonClass" aria-hidden="true" />
</template>