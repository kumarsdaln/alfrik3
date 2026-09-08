<script setup lang="ts">
    import type { SelectLabelProps } from 'reka-ui'
    import type { HTMLAttributes } from 'vue'

    import { SelectLabel } from 'reka-ui'

    import { cn } from '@/lib/utils'

    const props = defineProps<
        SelectLabelProps & {
            class?: HTMLAttributes['class']
        }
    >()
</script>

<template>
    <SelectLabel
        data-slot="select-label"
        :class="
            cn(
                `
                px-3
                pb-1.5
                pt-2
                font-redhat
                text-[11px]
                font-bold
                uppercase
                tracking-[0.12em]
                text-content-light/50

                dark:text-content-dark/50
                `,
                props.class,
            )
        "
    >
        <slot />
    </SelectLabel>
</template>