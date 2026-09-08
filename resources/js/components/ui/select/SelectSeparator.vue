<script setup lang="ts">
    import type { SelectSeparatorProps } from 'reka-ui'
    import type { HTMLAttributes } from 'vue'

    import { reactiveOmit } from '@vueuse/core'
    import { SelectSeparator } from 'reka-ui'

    import { cn } from '@/lib/utils'

    const props = defineProps<
        SelectSeparatorProps & {
            class?: HTMLAttributes['class']
        }
    >()

    const delegatedProps = reactiveOmit(
        props,
        'class',
    )
</script>

<template>
    <SelectSeparator
        data-slot="select-separator"
        v-bind="delegatedProps"
        :class="
            cn(
                `
                pointer-events-none
                my-1.5
                h-px
                bg-border-light

                dark:bg-border-dark
                `,
                props.class,
            )
        "
    />
</template>