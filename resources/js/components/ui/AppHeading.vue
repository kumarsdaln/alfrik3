<script setup lang="ts">
    import { computed } from 'vue'

    type HeadingSize =
        | 'xs'
        | 'sm'
        | 'md'
        | 'lg'
        | 'xl'
        | '2xl'
        | '3xl'
        | '4xl'
        | '5xl'
        | 'hero'
        | 'display'

    const props = withDefaults(
        defineProps<{
            tag?: keyof HTMLElementTagNameMap
            font?: 'prata' | 'redhat' | 'lora'
            size?: HeadingSize
            weight?:
            | 'light'
            | 'normal'
            | 'medium'
            | 'semibold'
            | 'bold'
            | 'black'

            color?:
            | 'default'
            | 'muted'
            | 'brand'
            | 'success'
            | 'danger'
            | 'warning'

            align?: 'left' | 'center' | 'right'
            leading?: 'none' | 'tight' | 'snug' | 'normal' | 'relaxed'
            tracking?: 'tighter' | 'tight' | 'normal' | 'wide'
            clamp?: 1 | 2 | 3 | 4 | 5 | 6
            truncate?: boolean
            uppercase?: boolean
            hoverBrand?: boolean
            class?: string
        }>(),
        {
            tag: 'h2',
            font: 'redhat',
            size: 'xl',
            weight: 'semibold',
            color: 'default',
            align: 'left',
            leading: 'snug',
            tracking: 'tight',
            truncate: false,
            uppercase: false,
            hoverBrand: false,
        }
    )

    const classes = computed(() => [
        /* Font */
        {
            prata: 'font-prata',
            redhat: 'font-redhat',
            lora: 'font-lora'
        }[props.font],

        /* Responsive Sizes */
        {
            xs: 'text-xs sm:text-sm',
            sm: 'text-sm sm:text-base',
            md: 'text-base sm:text-lg',
            lg: 'text-lg sm:text-xl lg:text-2xl',
            xl: 'text-xl sm:text-2xl lg:text-3xl',
            '2xl': 'text-2xl sm:text-3xl lg:text-4xl',
            '3xl': 'text-3xl sm:text-4xl lg:text-5xl',
            '4xl': 'text-4xl sm:text-5xl lg:text-6xl',
            '5xl': 'text-5xl sm:text-6xl lg:text-7xl',
            hero: `
                text-3xl
                sm:text-4xl
                md:text-5xl
                lg:text-6xl
            `,
            display: `
                text-4xl
                sm:text-5xl
                md:text-6xl
                lg:text-7xl
            `,
        }[props.size],

        /* Weight */
        {
            light: 'font-light',
            normal: 'font-normal',
            medium: 'font-medium',
            semibold: 'font-semibold',
            bold: 'font-bold',
            black: 'font-black',
        }[props.weight],

        /* Color */
        {
            default: 'text-content-light dark:text-content-dark',
            muted: 'text-content-lightMuted dark:text-content-darkMuted',
            brand: 'text-brand',
            success: 'text-green-600',
            danger: 'text-red-600',
            warning: 'text-yellow-600',
        }[props.color],

        /* Alignment */
        {
            left: 'text-left',
            center: 'text-center',
            right: 'text-right',
        }[props.align],

        /* Leading */
        {
            none: 'leading-none',
            tight: 'leading-tight',
            snug: 'leading-snug',
            normal: 'leading-normal',
            relaxed: 'leading-relaxed',
        }[props.leading],

        /* Tracking */
        {
            tighter: 'tracking-tighter',
            tight: 'tracking-tight',
            normal: 'tracking-normal',
            wide: 'tracking-wide',
        }[props.tracking],

        props.uppercase && 'uppercase',
        props.truncate && 'truncate',
        props.hoverBrand &&
        'transition-colors duration-300 group-hover:text-brand',
        props.clamp && `line-clamp-${props.clamp}`,
        props.class,
    ])
</script>

<template>
    <component :is="tag" :class="classes">
        <slot />
    </component>
</template>