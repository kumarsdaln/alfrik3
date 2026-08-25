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
    export type Method = 'get' | 'post' | 'put' | 'patch' | 'delete';

    export type UrlMethodPair = {
        url: string;
        method: Method;
        component?: string | Record<string, string>;
    };

    interface Props {
        href?: string | UrlMethodPair
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

    const props = withDefaults(
        defineProps < Props > (),
        {
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
        }
    )

    const componentTag = computed(() => {
        if (props.href && props.external) {
            return 'a'
        }
        if (props.href) {
            return Link
        }
        return 'button'
    })

    const baseClasses = `
        inline-flex
        items-center
        justify-center
        gap-2
        
        font-medium
        
        transition-all
        duration-300
        
        select-none
        
        focus:outline-none
        focus:ring-2
        focus:ring-brand
        focus:ring-offset-2
        
        disabled:pointer-events-none
        disabled:opacity-60
        
        active:scale-95
        `

    const widthClasses = computed(() =>
        props.fullWidth
            ? 'w-full'
            : ''
    )

    const roundedClasses = computed(() => ({
        none: '',
        sm: 'rounded',
        md: 'rounded-md',
        lg: 'rounded-lg',
        xl: 'rounded-xl',
        full: 'rounded-full',
    }[props.rounded]))

    const sizeClasses = computed(() => ({

        xs: props.iconOnly
            ? 'h-8 w-8 text-xs'
            : 'px-3 py-1.5 text-xs',

        sm: props.iconOnly
            ? 'h-9 w-9 text-sm'
            : 'px-4 py-2 text-sm',

        md: props.iconOnly
            ? 'h-10 w-10 text-sm'
            : 'px-5 py-2.5 text-sm',

        lg: props.iconOnly
            ? 'h-11 w-11 text-base'
            : 'px-6 py-3 text-base',

        xl: props.iconOnly
            ? 'h-12 w-12 text-lg'
            : 'px-7 py-3.5 text-lg',

    }[props.size]))

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
    | Variants
    |--------------------------------------------------------------------------
    */

    const variantClasses = computed(() => ({

        /* ------------------------------------------------------------------
         | Primary
         * ------------------------------------------------------------------*/

        primary: `
        bg-brand
        border border-brand

        text-white

        hover:brightness-110

        shadow-sm
    `,

        secondary: `
        bg-zinc-900
        dark:bg-white

        border border-zinc-900
        dark:border-white

        text-white
        dark:text-zinc-900

        hover:opacity-90
    `,

        /* ------------------------------------------------------------------
         | Actions
         * ------------------------------------------------------------------*/

        submit: `
        bg-brand
        border border-brand

        text-white

        hover:brightness-110
    `,

        save: `
        bg-emerald-600
        border border-emerald-600

        text-white

        hover:bg-emerald-700
    `,

        add: `
        bg-emerald-50
        dark:bg-emerald-500/10

        border border-emerald-200
        dark:border-emerald-500/20

        text-emerald-700
        dark:text-emerald-400

        hover:bg-emerald-100
        dark:hover:bg-emerald-500/20
    `,

        edit: `
        bg-amber-50
        dark:bg-amber-500/10

        border border-amber-200
        dark:border-amber-500/20

        text-amber-700
        dark:text-amber-400

        hover:bg-amber-100
        dark:hover:bg-amber-500/20
    `,

        delete: `
        bg-red-50
        dark:bg-red-500/10

        border border-red-200
        dark:border-red-500/20

        text-red-700
        dark:text-red-400

        hover:bg-red-100
        dark:hover:bg-red-500/20
    `,

        view: `
        bg-sky-50
        dark:bg-sky-500/10

        border border-sky-200
        dark:border-sky-500/20

        text-sky-700
        dark:text-sky-400

        hover:bg-sky-100
        dark:hover:bg-sky-500/20
    `,

        export: `
        bg-violet-50
        dark:bg-violet-500/10

        border border-violet-200
        dark:border-violet-500/20

        text-violet-700
        dark:text-violet-400

        hover:bg-violet-100
        dark:hover:bg-violet-500/20
    `,

        filter: `
        bg-white
        dark:bg-zinc-900

        border border-zinc-200
        dark:border-zinc-700

        text-zinc-700
        dark:text-zinc-200

        hover:bg-zinc-50
        dark:hover:bg-zinc-800
    `,

        cancel: `
        bg-zinc-100
        dark:bg-zinc-800

        border border-zinc-200
        dark:border-zinc-700

        text-zinc-700
        dark:text-zinc-300

        hover:bg-zinc-200
        dark:hover:bg-zinc-700
    `,

        back: `
        bg-transparent

        border border-zinc-200
        dark:border-zinc-700

        text-zinc-700
        dark:text-zinc-300

        hover:bg-zinc-100
        dark:hover:bg-zinc-800
    `,

        /* ------------------------------------------------------------------
         | Generic
         * ------------------------------------------------------------------*/

        outline: `
        bg-transparent

        border border-brand

        text-brand

        hover:bg-brand
        hover:text-white
    `,

        ghost: `
        bg-transparent

        border border-transparent

        text-zinc-700
        dark:text-zinc-200

        hover:bg-zinc-100
        dark:hover:bg-zinc-800
    `,

        link: `
        bg-transparent

        border-transparent

        p-0

        text-brand

        shadow-none

        hover:underline
    `,

    }[props.variant]))

</script>
<template>
    <component 
        :is="componentTag" 
        :href="href" 
        :method="href && !external ? method : undefined"
        :target="external ? '_blank' : undefined"
        :rel="external ? 'noopener noreferrer' : undefined" 
        :type="!href ? type : undefined"
        :disabled="disabled || loading" 
        :aria-busy="loading" 
        :class="[
            baseClasses,
            sizeClasses,
            roundedClasses,
            variantClasses,
            widthClasses,
        ]">
        <!-- Loading -->
        <Loader2 v-if="loading" class="h-4 w-4 animate-spin" />

        <!-- Custom Left Icon -->
        <template v-else-if="$slots['icon-left']">
            <slot name="icon-left" />
        </template>

        <!-- Auto Icon -->
        <component v-else-if="CurrentIcon" 
            :is="CurrentIcon" 
            class="h-4 w-4 shrink-0" 
            :class="{'animate-spin': loading,}" 
        />

        <!-- Label -->
        <span v-if="!iconOnly" class="truncate">
            <slot />
        </span>

        <!-- Custom Right Icon -->
        <slot v-if="$slots['icon-right']" name="icon-right" />
    </component>
</template>