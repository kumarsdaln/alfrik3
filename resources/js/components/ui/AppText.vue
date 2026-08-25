<script setup lang="ts">
import { computed } from 'vue'

type TextSize =
    | 'xs'
    | 'sm'
    | 'md'
    | 'lg'
    | 'xl'

type TextFont =
    | 'redhat'
    | 'lora'
    | 'prata'

type TextWeight =
    | 'light'
    | 'normal'
    | 'medium'
    | 'semibold'
    | 'bold'

type TextColor =
    | 'default'
    | 'muted'
    | 'brand'
    | 'success'
    | 'danger'
    | 'warning'

type TextAlign =
    | 'left'
    | 'center'
    | 'right'

type TextLeading =
    | 'none'
    | 'tight'
    | 'normal'
    | 'relaxed'
    | 'loose'

type TextTracking =
    | 'tighter'
    | 'tight'
    | 'normal'
    | 'wide'

type TextClamp = 1 | 2 | 3 | 4 | 5 | 6

const props = withDefaults(
    defineProps<{
        tag?: keyof HTMLElementTagNameMap
        font?: TextFont
        size?: TextSize
        weight?: TextWeight
        color?: TextColor
        align?: TextAlign
        leading?: TextLeading
        tracking?: TextTracking

        clamp?: TextClamp

        truncate?: boolean
        uppercase?: boolean
        hoverBrand?: boolean
    }>(),
    {
        tag: 'p',
        font: 'redhat',
        size: 'md',
        weight: 'normal',
        color: 'default',
        align: 'left',
        leading: 'normal',
        tracking: 'normal',
        truncate: false,
        uppercase: false,
        hoverBrand: false,
    }
)

const clampClasses: Record<TextClamp, string> = {
    1: 'line-clamp-1',
    2: 'line-clamp-2',
    3: 'line-clamp-3',
    4: 'line-clamp-4',
    5: 'line-clamp-5',
    6: 'line-clamp-6',
}

const classes = computed(() => [
    /* Font */
    {
        redhat: 'font-redhat',
        lora: 'font-lora',
        prata: 'font-prata',
    }[props.font],

    /* Responsive Size */
    {
        xs: 'text-xs sm:text-sm',
        sm: 'text-sm sm:text-base',
        md: 'text-base sm:text-lg',
        lg: 'text-lg sm:text-xl',
        xl: 'text-xl sm:text-2xl',
    }[props.size],

    /* Weight */
    {
        light: 'font-light',
        normal: 'font-normal',
        medium: 'font-medium',
        semibold: 'font-semibold',
        bold: 'font-bold',
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

    /* Align */
    {
        left: 'text-left',
        center: 'text-center',
        right: 'text-right',
    }[props.align],

    /* Leading */
    {
        none: 'leading-none',
        tight: 'leading-tight',
        normal: 'leading-normal',
        relaxed: 'leading-relaxed',
        loose: 'leading-loose',
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

    props.clamp
        ? clampClasses[props.clamp]
        : undefined,

    props.hoverBrand &&
        'transition-colors duration-300 group-hover:text-brand',
])
</script>

<template>
    <component
        :is="tag"
        :class="classes"
    >
        <slot />
    </component>
</template>