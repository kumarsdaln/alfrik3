<script setup lang="ts">
    import type { SelectTriggerProps } from 'reka-ui'
    import type { HTMLAttributes } from 'vue'

    import { ChevronDown } from '@lucide/vue'
    import { reactiveOmit } from '@vueuse/core'
    import {
        SelectIcon,
        SelectTrigger,
        useForwardProps,
    } from 'reka-ui'

    import { cn } from '@/lib/utils'

    const props = withDefaults(
        defineProps<
            SelectTriggerProps & {
                class?: HTMLAttributes['class']
                size?: 'sm' | 'default'
            }
        >(),
        {
            size: 'default',
        },
    )

    const delegatedProps = reactiveOmit(
        props,
        'class',
        'size',
    )

    const forwardedProps = useForwardProps(delegatedProps)
</script>

<template>
    <SelectTrigger
        data-slot="select-trigger"
        :data-size="size"
        v-bind="forwardedProps"
        :class="
            cn(
                `
                flex
                w-full
                items-center
                justify-between
                gap-3
                border
                border-border-light
                bg-surface-light
                px-4
                text-sm
                text-content-light
                outline-none
                transition-colors
                duration-200

                hover:border-content-light/60

                focus:border-primary
                focus:ring-1
                focus:ring-primary/30

                data-[placeholder]:text-content-light/50

                disabled:cursor-not-allowed
                disabled:opacity-50

                dark:border-border-dark
                dark:bg-surface-dark
                dark:text-content-dark

                dark:hover:border-content-dark/60

                dark:focus:border-primary

                dark:data-[placeholder]:text-content-dark/50

                data-[size=default]:h-10
                data-[size=sm]:h-9

                [&_svg]:pointer-events-none
                [&_svg]:shrink-0
                [&_svg]:text-content-light/60
                dark:[&_svg]:text-content-dark/60
                [&_svg]:transition-transform
                `,
                props.class,
            )
        "
    >
        <slot />

        <SelectIcon as-child>
            <ChevronDown class="size-4" />
        </SelectIcon>
    </SelectTrigger>
</template>