
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
    | 'body'
    | 'heading'
    | 'editorial'
    // Backward-compatible aliases
    | 'redhat'
    | 'lora'
    | 'prata'

type TextSize =
    | 'xs'
    | 'sm'
    | 'md'
    | 'lg'
    | 'xl'
    | '2xl'

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
    | 'inverse'
    | 'success'
    | 'warning'
    | 'danger'
    | 'info'

type TextAlign = 'left' | 'center' | 'right'

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
        font: 'body',
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
| Typography
|--------------------------------------------------------------------------
*/

const fontClasses: Record<TextFont, string> = {
    body: 'font-body',
    heading: 'font-heading',
    editorial: 'font-editorial',

    // Legacy aliases mapped to actual Alfrik fonts
    redhat: 'font-body',
    lora: 'font-editorial',
    prata: 'font-heading',
}

const sizeClasses: Record<TextSize, string> = {
    xs: 'text-xs',
    sm: 'text-sm',
    md: 'text-base',
    lg: 'text-lg',
    xl: 'text-xl',
    '2xl': 'text-2xl',
}

const weightClasses: Record<TextWeight, string> = {
    light: 'font-light',
    normal: 'font-normal',
    medium: 'font-medium',
    semibold: 'font-semibold',
    bold: 'font-bold',
}

/*
|--------------------------------------------------------------------------
| Theme Colors
|--------------------------------------------------------------------------
*/

const colorClasses: Record<TextColor, string> = {
    default: 'text-foreground',
    muted: 'text-muted-foreground',
    brand: 'text-brand',
    inverse: 'text-content-inverse',
    success: 'text-success',
    warning: 'text-warning',
    danger: 'text-destructive',
    info: 'text-info',
}

/*
|--------------------------------------------------------------------------
| Alignment
|--------------------------------------------------------------------------
*/

const alignClasses: Record<TextAlign, string> = {
    left: 'text-left',
    center: 'text-center',
    right: 'text-right',
}

/*
|--------------------------------------------------------------------------
| Line Height
|--------------------------------------------------------------------------
*/

const leadingClasses: Record<TextLeading, string> = {
    none: 'leading-none',
    tight: 'leading-tight',
    normal: 'leading-normal',
    relaxed: 'leading-relaxed',
    loose: 'leading-loose',
}

/*
|--------------------------------------------------------------------------
| Letter Spacing
|--------------------------------------------------------------------------
*/

const trackingClasses: Record<TextTracking, string> = {
    tighter: 'tracking-tighter',
    tight: 'tracking-tight',
    normal: 'tracking-normal',
    wide: 'tracking-wide',
}

/*
|--------------------------------------------------------------------------
| Line Clamping
|--------------------------------------------------------------------------
*/

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
| Computed Classes
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
        'transition-colors duration-200',
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