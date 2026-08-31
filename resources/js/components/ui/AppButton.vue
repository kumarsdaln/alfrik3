<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'

import {
    Plus,
    Save,
    Check,
    Pencil,
    Trash2,
    Eye,
    Download,
    Filter,
    ArrowLeft,
    ArrowRight,
    X,
    Loader2,
} from '@lucide/vue'

import { Button } from '@/components/ui/button'

type ButtonVariant =
    | 'primary'
    | 'secondary'
    | 'submit'
    | 'save'
    | 'add'
    | 'edit'
    | 'delete'
    | 'view'
    | 'export'
    | 'filter'
    | 'cancel'
    | 'back'
    | 'outline'
    | 'ghost'
    | 'link'

type ButtonSize =
    | 'xs'
    | 'sm'
    | 'md'
    | 'lg'
    | 'xl'

type Rounded =
    | 'none'
    | 'sm'
    | 'md'
    | 'lg'
    | 'xl'
    | 'full'

export type Method =
    | 'get'
    | 'post'
    | 'put'
    | 'patch'
    | 'delete'

export type UrlMethodPair = {
    url: string
    method: Method
    component?: string | Record<string, string>
}

interface Props {
    href?: string | UrlMethodPair | null
    method?: Method
    external?: boolean
    variant?: ButtonVariant
    size?: ButtonSize
    rounded?: Rounded
    type?: 'button' | 'submit' | 'reset'
    loading?: boolean
    disabled?: boolean
    fullWidth?: boolean
    iconOnly?: boolean
    autoIcon?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    href: null,
    method: 'get',
    external: false,
    variant: 'primary',
    size: 'md',
    rounded: 'full',
    type: 'button',
    loading: false,
    disabled: false,
    fullWidth: false,
    iconOnly: false,
    autoIcon: false,
})

/*
|--------------------------------------------------------------------------
| URL
|--------------------------------------------------------------------------
*/

const resolvedHref = computed(() => {
    if (!props.href) {
        return undefined
    }

    if (typeof props.href === 'string') {
        return props.href
    }

    return props.href.url
})

/*
|--------------------------------------------------------------------------
| Component
|--------------------------------------------------------------------------
*/

const componentTag = computed(() => {
    if (!resolvedHref.value) {
        return 'button'
    }

    if (props.external) {
        return 'a'
    }

    return Link
})

/*
|--------------------------------------------------------------------------
| Variant
|--------------------------------------------------------------------------
|
| AppButton keeps our existing API while using the new Button underneath.
|
*/

const variantClasses = computed(() => {
    const variants: Record<ButtonVariant, string> = {
        primary: `
            border-brand
            bg-brand
            text-white
            shadow-sm
            hover:bg-brand
            hover:text-white
            hover:brightness-110
        `,

        secondary: `
            border-zinc-900
            bg-zinc-900
            text-white
            hover:bg-zinc-800
            hover:text-white
            dark:border-white
            dark:bg-white
            dark:text-zinc-900
            dark:hover:bg-zinc-100
        `,

        submit: `
            border-brand
            bg-brand
            text-white
            hover:bg-brand
            hover:text-white
            hover:brightness-110
        `,

        save: `
            border-emerald-600
            bg-emerald-600
            text-white
            hover:bg-emerald-700
            hover:text-white
        `,

        add: `
            border-emerald-200
            bg-emerald-50
            text-emerald-700
            hover:bg-emerald-100
            hover:text-emerald-700
            dark:border-emerald-500/20
            dark:bg-emerald-500/10
            dark:text-emerald-400
        `,

        edit: `
            border-amber-200
            bg-amber-50
            text-amber-700
            hover:bg-amber-100
            hover:text-amber-700
            dark:border-amber-500/20
            dark:bg-amber-500/10
            dark:text-amber-400
        `,

        delete: `
            border-red-200
            bg-red-50
            text-red-700
            hover:bg-red-100
            hover:text-red-700
            dark:border-red-500/20
            dark:bg-red-500/10
            dark:text-red-400
        `,

        view: `
            border-sky-200
            bg-sky-50
            text-sky-700
            hover:bg-sky-100
            hover:text-sky-700
            dark:border-sky-500/20
            dark:bg-sky-500/10
            dark:text-sky-400
        `,

        export: `
            border-violet-200
            bg-violet-50
            text-violet-700
            hover:bg-violet-100
            hover:text-violet-700
            dark:border-violet-500/20
            dark:bg-violet-500/10
            dark:text-violet-400
        `,

        filter: `
            border-zinc-200
            bg-white
            text-zinc-700
            hover:bg-zinc-50
            hover:text-zinc-900
            dark:border-zinc-700
            dark:bg-zinc-900
            dark:text-zinc-200
            dark:hover:bg-zinc-800
        `,

        cancel: `
            border-zinc-200
            bg-zinc-100
            text-zinc-700
            hover:bg-zinc-200
            hover:text-zinc-900
            dark:border-zinc-700
            dark:bg-zinc-800
            dark:text-zinc-300
            dark:hover:bg-zinc-700
        `,

        back: `
            border-zinc-200
            bg-transparent
            text-zinc-700
            hover:bg-zinc-100
            hover:text-zinc-900
            dark:border-zinc-700
            dark:text-zinc-300
            dark:hover:bg-zinc-800
        `,

        outline: `
            border-brand
            bg-transparent
            text-brand
            hover:bg-brand
            hover:text-white
        `,

        ghost: `
            border-transparent
            bg-transparent
            text-zinc-700
            hover:bg-zinc-100
            hover:text-zinc-900
            dark:text-zinc-200
            dark:hover:bg-zinc-800
        `,

        link: `
            border-transparent
            bg-transparent
            p-0
            text-brand
            shadow-none
            hover:bg-transparent
            hover:text-brand
            hover:underline
        `,
    }

    return variants[props.variant]
})

/*
|--------------------------------------------------------------------------
| Size
|--------------------------------------------------------------------------
*/

const sizeClasses = computed(() => {
    if (props.iconOnly) {
        return {
            xs: 'h-8 w-8 p-0',
            sm: 'h-9 w-9 p-0',
            md: 'h-10 w-10 p-0',
            lg: 'h-11 w-11 p-0',
            xl: 'h-12 w-12 p-0',
        }[props.size]
    }

    return {
        xs: 'h-8 px-3 text-xs',
        sm: 'h-9 px-4 text-sm',
        md: 'h-10 px-5 text-sm',
        lg: 'h-11 px-6 text-base',
        xl: 'h-12 px-7 text-lg',
    }[props.size]
})

/*
|--------------------------------------------------------------------------
| Rounded
|--------------------------------------------------------------------------
*/

const roundedClasses = computed(() => ({
    none: 'rounded-none',
    sm: 'rounded',
    md: 'rounded-md',
    lg: 'rounded-lg',
    xl: 'rounded-xl',
    full: 'rounded-full',
}[props.rounded]))

/*
|--------------------------------------------------------------------------
| Icons
|--------------------------------------------------------------------------
*/

const iconMap = {
    primary: Check,
    secondary: Check,
    submit: Check,
    save: Save,
    add: Plus,
    edit: Pencil,
    delete: Trash2,
    view: Eye,
    export: Download,
    filter: Filter,
    cancel: X,
    back: ArrowLeft,
    outline: ArrowRight,
    ghost: undefined,
    link: ArrowRight,
}

const CurrentIcon = computed(() => {
    if (props.loading) {
        return Loader2
    }

    if (!props.autoIcon) {
        return null
    }

    return iconMap[props.variant]
})

/*
|--------------------------------------------------------------------------
| Method
|--------------------------------------------------------------------------
*/

const resolvedMethod = computed(() => {
    if (
        props.href &&
        typeof props.href !== 'string'
    ) {
        return props.href.method
    }

    return props.method
})
</script>

<template>
    <Button
        :as="componentTag"
        :as-child="false"
        :href="resolvedHref"
        :method="!external ? resolvedMethod : undefined"
        :target="external ? '_blank' : undefined"
        :rel="external ? 'noopener noreferrer' : undefined"
        :type="!resolvedHref ? type : undefined"
        :disabled="disabled || loading"
        :aria-busy="loading"
        :class="[
            sizeClasses,
            roundedClasses,
            variantClasses,
            fullWidth ? 'w-full' : '',
            iconOnly ? 'shrink-0' : '',
        ]"
    >
        <!-- Loading -->
        <Loader2
            v-if="loading"
            class="h-4 w-4 shrink-0 animate-spin"
        />

        <!-- Custom Left Icon -->
        <template v-else-if="$slots['icon-left']">
            <slot name="icon-left" />
        </template>

        <!-- Auto Icon -->
        <component
            v-else-if="CurrentIcon"
            :is="CurrentIcon"
            class="h-4 w-4 shrink-0"
        />

        <!-- Label -->
        <span
            v-if="!iconOnly"
            class="truncate"
        >
            <slot />
        </span>

        <!-- Custom Right Icon -->
        <slot
            v-if="$slots['icon-right']"
            name="icon-right"
        />
    </Button>
</template>