<script setup lang="ts">
    import type { HTMLAttributes, TextareaHTMLAttributes } from 'vue'
    import { reactiveOmit } from '@vueuse/core'

    import { cn } from '@/lib/utils'

    interface Props extends /* @vue-ignore */ TextareaHTMLAttributes {
        class?: HTMLAttributes['class']
    }

    const props = defineProps<Props>()

    const delegatedProps = reactiveOmit(props, 'class')
</script>

<template>
    <textarea
        v-bind="delegatedProps"
        data-slot="textarea"
        :class="
            cn(
                `
                    flex
                    min-h-32
                    w-full
                    resize-y
                    border
                    border-border-light
                    bg-surface-light
                    px-4
                    py-3

                    font-redhat
                    text-sm
                    leading-6
                    text-content-light

                    outline-none
                    transition-colors
                    duration-200

                    placeholder:text-content-lightMuted

                    hover:border-content-lightMuted

                    focus:border-primary

                    disabled:cursor-not-allowed
                    disabled:bg-surface-lightMuted
                    disabled:opacity-50

                    dark:border-border-dark
                    dark:bg-surface-dark
                    dark:text-content-dark

                    dark:placeholder:text-content-darkMuted

                    dark:hover:border-content-darkMuted

                    dark:focus:border-primary

                    dark:disabled:bg-surface-darkMuted

                    aria-invalid:border-destructive
                    aria-invalid:focus:border-destructive
                `,
                props.class,
            )
        "
    ></textarea>
</template>