<script setup lang="ts" generic="T = boolean">
import type {
    SwitchRootEmits,
    SwitchRootProps,
} from 'reka-ui'

import type { HTMLAttributes } from 'vue'

import { reactiveOmit } from '@vueuse/core'

import {
    SwitchRoot,
    SwitchThumb,
    useForwardPropsEmits,
} from 'reka-ui'

import { cn } from '@/lib/utils'

const props = defineProps<
    SwitchRootProps<T> & {
        class?: HTMLAttributes['class']
    }
>()

const emits = defineEmits<SwitchRootEmits<T>>()

const delegatedProps = reactiveOmit(props, 'class')

const forwarded = useForwardPropsEmits(
    delegatedProps,
    emits,
)
</script>

<template>
    <SwitchRoot
        data-slot="switch"
        v-bind="forwarded"
        :class="
            cn(
                `
                    peer
                    inline-flex
                    h-6
                    w-11
                    shrink-0
                    cursor-pointer
                    items-center
                    rounded-full
                    border
                    border-border-light
                    bg-muted
                    p-0
                    outline-none
                    transition-colors
                    duration-200
                    ease-out

                    focus-visible:border-primary
                    focus-visible:ring-2
                    focus-visible:ring-primary/30

                    data-[state=checked]:border-primary
                    data-[state=checked]:bg-primary

                    disabled:cursor-not-allowed
                    disabled:opacity-50

                    dark:border-border-dark
                    dark:bg-muted-dark
                    dark:data-[state=checked]:border-primary
                    dark:data-[state=checked]:bg-primary
                `,
                props.class,
            )
        "
    >
        <SwitchThumb
            data-slot="switch-thumb"
            :class="
                `
                    pointer-events-none
                    block
                    size-4
                    translate-x-0.5
                    rounded-full
                    bg-white
                    shadow-md
                    ring-0
                    transition-transform
                    duration-200
                    ease-out

                    data-[state=checked]:translate-x-5
                `
            "
        />
    </SwitchRoot>
</template>