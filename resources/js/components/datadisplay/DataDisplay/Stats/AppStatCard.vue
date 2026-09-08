<script setup>
    import { computed } from 'vue'

    import {
        statVariants,
    } from './stats.variants'

    const props = defineProps({
        variant: {
            type: String,
            default: 'default',
        }
    })

    const statClass = computed(() => {
        return [
            'rounded-3xl',
            'p-5',
            'transition-all duration-200',

            statVariants[props.variant],
        ]
    })
</script>

<template>
    <div :class="statClass">
        <div class="
                flex items-start justify-between
                gap-4
            ">
            <!-- Content -->
            <div class="min-w-0 flex-1">
                <slot />
            </div>

            <!-- Icon -->
            <div v-if="$slots.icon" class="
                    flex h-12 w-12
                    items-center justify-center
                    rounded-2xl

                    bg-neutral-100
                    dark:bg-neutral-800
                ">
                <slot name="icon" />
            </div>
        </div>
    </div>
</template>