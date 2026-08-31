<script setup lang="ts">
import { computed } from 'vue'

type HeadingTag =
    | 'h1'
    | 'h2'
    | 'h3'
    | 'h4'
    | 'h5'
    | 'h6'
    | 'div'
    | 'span'

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

type HeadingFont =
    | 'prata'
    | 'redhat'
    | 'lora'

type HeadingWeight =
    | 'light'
    | 'normal'
    | 'medium'
    | 'semibold'
    | 'bold'
    | 'black'

type HeadingColor =
    | 'default'
    | 'muted'
    | 'brand'
    | 'success'
    | 'danger'
    | 'warning'

type HeadingAlign =
    | 'left'
    | 'center'
    | 'right'

type HeadingLeading =
    | 'none'
    | 'tight'
    | 'snug'
    | 'normal'
    | 'relaxed'

type HeadingTracking =
    | 'tighter'
    | 'tight'
    | 'normal'
    | 'wide'

type HeadingClamp = 1 | 2 | 3 | 4 | 5 | 6

interface Props {
    tag?: HeadingTag
    font?: HeadingFont
    size?: HeadingSize
    weight?: HeadingWeight
    color?: HeadingColor
    align?: HeadingAlign
    leading?: HeadingLeading
    tracking?: HeadingTracking
    clamp?: HeadingClamp
    truncate?: boolean
    uppercase?: boolean
    hoverBrand?: boolean
    class?: string
}

const props = withDefaults(
    defineProps<Props>(),
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
    },
)


/*
|--------------------------------------------------------------------------
| Class Maps
|--------------------------------------------------------------------------
*/

const fontClasses: Record<HeadingFont, string> = {
    prata: 'font-prata',
    redhat: 'font-redhat',
    lora: 'font-lora',
}

const sizeClasses: Record<HeadingSize, string> = {
    xs: 'text-xs sm:text-sm',

    sm: 'text-sm sm:text-base',

    md: 'text-base sm:text-lg',

    lg: 'text-lg sm:text-xl lg:text-2xl',

    xl: 'text-xl sm:text-2xl lg:text-3xl',

    '2xl': 'text-2xl sm:text-3xl lg:text-4xl',

    '3xl': 'text-3xl sm:text-4xl lg:text-5xl',

    '4xl': 'text-4xl sm:text-5xl lg:text-6xl',

    '5xl': 'text-5xl sm:text-6xl lg:text-7xl',

    hero: [
        'text-3xl',
        'sm:text-4xl',
        'md:text-5xl',
        'lg:text-6xl',
    ].join(' '),

    display: [
        'text-4xl',
        'sm:text-5xl',
        'md:text-6xl',
        'lg:text-7xl',
    ].join(' '),
}

const weightClasses: Record<HeadingWeight, string> = {
    light: 'font-light',
    normal: 'font-normal',
    medium: 'font-medium',
    semibold: 'font-semibold',
    bold: 'font-bold',
    black: 'font-black',
}

const colorClasses: Record<HeadingColor, string> = {
    default: 'text-content-light dark:text-content-dark',
    muted: 'text-content-lightMuted dark:text-content-darkMuted',
    brand: 'text-brand',
    success: 'text-green-600 dark:text-green-500',
    danger: 'text-red-600 dark:text-red-500',
    warning: 'text-yellow-600 dark:text-yellow-500',
}

const alignClasses: Record<HeadingAlign, string> = {
    left: 'text-left',
    center: 'text-center',
    right: 'text-right',
}

const leadingClasses: Record<HeadingLeading, string> = {
    none: 'leading-none',
    tight: 'leading-tight',
    snug: 'leading-snug',
    normal: 'leading-normal',
    relaxed: 'leading-relaxed',
}

const trackingClasses: Record<HeadingTracking, string> = {
    tighter: 'tracking-tighter',
    tight: 'tracking-tight',
    normal: 'tracking-normal',
    wide: 'tracking-wide',
}

const clampClasses: Record<HeadingClamp, string> = {
    1: 'line-clamp-1',
    2: 'line-clamp-2',
    3: 'line-clamp-3',
    4: 'line-clamp-4',
    5: 'line-clamp-5',
    6: 'line-clamp-6',
}


/*
|--------------------------------------------------------------------------
| Classes
|--------------------------------------------------------------------------
*/

const classes = computed(() => [
    fontClasses[props.font],
    sizeClasses[props.size],
    weightClasses[props.weight],
    colorClasses[props.color],
    alignClasses[props.align],
    leadingClasses[props.leading],
    trackingClasses[props.tracking],

    props.uppercase && 'uppercase',

    props.truncate && 'truncate',

    props.clamp && clampClasses[props.clamp],

    props.hoverBrand && [
        'transition-colors',
        'duration-200',
        'group-hover:text-brand',
    ],

    props.class,
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