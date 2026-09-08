<script setup lang="ts">
    import type { SelectItemProps } from 'reka-ui'
    import type { HTMLAttributes } from 'vue'

    import { Check } from '@lucide/vue'
    import { reactiveOmit } from '@vueuse/core'

    import {
        SelectItem,
        SelectItemIndicator,
        SelectItemText,
        useForwardProps,
    } from 'reka-ui'

    import { cn } from '@/lib/utils'

    const props = defineProps<
        SelectItemProps & {
            class?: HTMLAttributes['class']
        }
    >()

    const delegatedProps = reactiveOmit(
        props,
        'class',
    )

    const forwardedProps =
        useForwardProps(delegatedProps)
</script>

<template>
    <SelectItem
        data-slot="select-item"
        v-bind="forwardedProps"
        :class="
            cn(
                `
                relative
                flex
                w-full
                cursor-default
                select-none
                items-center
                gap-3
                px-3
                py-2.5
                pr-9
                font-redhat
                text-sm
                text-content-light
                outline-none
                transition-colors

                hover:bg-primary/5

                focus:bg-primary/5

                data-[highlighted]:bg-primary/5
                data-[highlighted]:text-content-light

                data-[state=checked]:font-medium
                data-[state=checked]:text-primary

                data-[disabled]:pointer-events-none
                data-[disabled]:opacity-40

                dark:text-content-dark
                dark:data-[highlighted]:text-content-dark
                `,
                props.class,
            )
        "
    >
        <SelectItemText>
            <slot />
        </SelectItemText>

        <span
            class="
                absolute
                right-3
                flex
                size-4
                items-center
                justify-center
            "
        >
            <SelectItemIndicator>
                <slot name="indicator-icon">
                    <Check
                        class="
                            size-4
                            text-primary
                        "
                    />
                </slot>
            </SelectItemIndicator>
        </span>
    </SelectItem>
</template>