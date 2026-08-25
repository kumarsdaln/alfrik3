<script setup>
    import { computed } from 'vue'

    import {
        statTrendVariants,
    } from './stats.variants'

    const props = defineProps({
        type: {
            type: String,
            default: 'positive',
        }
    })

    const trendClass = computed(() => {
        return [
            'mt-3',
            'inline-flex items-center gap-1',
            'text-sm font-medium',

            statTrendVariants[props.type],
        ]
    })
</script>

<template>
    <div :class="trendClass">
        <slot />
    </div>
</template>