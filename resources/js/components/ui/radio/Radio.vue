<script setup lang="ts">
    import type {
        RadioGroupItemEmits,
        RadioGroupItemProps,
    } from 'reka-ui'

    import type { HTMLAttributes } from 'vue'

    import { reactiveOmit } from '@vueuse/core'

    import {
        RadioGroupIndicator,
        RadioGroupItem,
        useForwardPropsEmits,
    } from 'reka-ui'

    import { cn } from '@/lib/utils'

    const props = defineProps<
        RadioGroupItemProps & {
            class?: HTMLAttributes['class']
        }
    >()

    const emits = defineEmits<RadioGroupItemEmits>()

    const delegatedProps = reactiveOmit(props, 'class')

    const forwarded = useForwardPropsEmits(
        delegatedProps,
        emits,
    )
</script>

<template>
    <RadioGroupItem data-slot="radio" v-bind="forwarded" :class="cn(
        `
                peer
                grid
                size-4
                shrink-0
                cursor-pointer
                place-items-center
                rounded-full
                border
                border-border-light
                bg-transparent
                outline-none

                transition-colors
                duration-200

                focus-visible:border-primary
                focus-visible:ring-2
                focus-visible:ring-primary/30

                data-[state=checked]:border-primary

                disabled:cursor-not-allowed
                disabled:opacity-50

                dark:border-border-dark
                `,
        props.class,
    )
        ">
        <RadioGroupIndicator data-slot="radio-indicator" class="
                size-2
                rounded-full
                bg-primary
            " />

        <slot />
    </RadioGroupItem>
</template>