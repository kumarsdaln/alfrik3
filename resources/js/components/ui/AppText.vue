<script setup lang="ts">
import { computed } from 'vue'

type TextTag =
    | 'p'
    | 'span'
    | 'div'
    | 'label'
    | 'strong'
    | 'em'
    | 'small'
    | 'dd'
    | 'dt'
    | 'h1'
    | 'h2'
    | 'h3'
    | 'h4'
    | 'h5'
    | 'h6'

type TextFont =
    | 'redhat'
    | 'lora'
    | 'prata'

type TextSize =
    | 'xs'
    | 'sm'
    | 'md'
    | 'lg'
    | 'xl'

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

interface Props {
    tag?: TextTag
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
}

const props = withDefaults(
    defineProps<Props>(),
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
    },
)


/*
|--------------------------------------------------------------------------
| Class Maps
|--------------------------------------------------------------------------
*/

const fontClasses: Record<TextFont, string> = {
    redhat: 'font-redhat',
    lora: 'font-lora',
    prata: 'font-prata',
}

const sizeClasses: Record<TextSize, string> = {
    xs: 'text-xs',
    sm: 'text-sm',
    md: 'text-base',
    lg: 'text-lg',
    xl: 'text-xl',
}

const weightClasses: Record<TextWeight, string> = {
    light: 'font-light',
    normal: 'font-normal',
    medium: 'font-medium',
    semibold: 'font-semibold',
    bold: 'font-bold',
}

const colorClasses: Record<TextColor, string> = {
    default: 'text-content-light dark:text-content-dark',
    muted: 'text-content-lightMuted dark:text-content-darkMuted',
    brand: 'text-brand',
    success: 'text-green-600 dark:text-green-500',
    danger: 'text-red-600 dark:text-red-500',
    warning: 'text-yellow-600 dark:text-yellow-500',
}

const alignClasses: Record<TextAlign, string> = {
    left: 'text-left',
    center: 'text-center',
    right: 'text-right',
}

const leadingClasses: Record<TextLeading, string> = {
    none: 'leading-none',
    tight: 'leading-tight',
    normal: 'leading-normal',
    relaxed: 'leading-relaxed',
    loose: 'leading-loose',
}

const trackingClasses: Record<TextTracking, string> = {
    tighter: 'tracking-tighter',
    tight: 'tracking-tight',
    normal: 'tracking-normal',
    wide: 'tracking-wide',
}

const clampClasses: Record<TextClamp, string> = {
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