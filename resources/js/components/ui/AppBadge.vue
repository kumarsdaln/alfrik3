<script setup lang="ts">
    import { computed } from 'vue'

    type BadgeVariant =
        | 'default'
        | 'primary'
        | 'success'
        | 'warning'
        | 'danger'
        | 'info'

    type BadgeSize =
        | 'sm'
        | 'md'
        | 'lg'

    interface Props {
        variant?: BadgeVariant
        size?: BadgeSize
        rounded?: boolean
        outlined?: boolean
        dot?: boolean
        bgColor?: string
        textColor?: string
        borderColor?: string
    }

    const props = withDefaults(
        defineProps<Props>(),
        {
            variant: 'default',
            size: 'md',
            rounded: true,
            outlined: false,
            dot: false,
        },
    )

    const variantClasses = computed(() => {

        if (props.bgColor || props.textColor || props.borderColor) {
            return ''
        }

        const variants = {
            default:
                'bg-gray-100 text-gray-700 border border-gray-200 dark:bg-white/10 dark:text-gray-300 dark:border-white/10',
            primary:
                'bg-brand/10 text-brand border border-brand/20',
            success:
                'bg-green-100 text-green-700 border border-green-200 dark:bg-green-500/10 dark:text-green-400 dark:border-green-500/20',
            warning:
                'bg-yellow-100 text-yellow-700 border border-yellow-200 dark:bg-yellow-500/10 dark:text-yellow-400 dark:border-yellow-500/20',
            danger:
                'bg-red-100 text-red-700 border border-red-200 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20',
            info:
                'bg-sky-100 text-sky-700 border border-sky-200 dark:bg-sky-500/10 dark:text-sky-400 dark:border-sky-500/20',
        }

        return variants[props.variant]
    })

    const sizeClasses = computed(() => ({
        sm: 'px-2 py-0.5 text-xs',
        md: 'px-3 py-1 text-xs',
        lg: 'px-4 py-1.5 text-sm',
    }[props.size]))

    const customStyle = computed(() => ({
        backgroundColor:
            props.outlined
                ? 'transparent'
                : props.bgColor,
        color: props.textColor,
        borderColor: props.borderColor,

    }))
</script>

<template>

    <span :class="[
        'inline-flex items-center gap-2 border font-medium transition-all duration-200',
        rounded
            ? 'rounded-full'
            : 'rounded-lg',
        sizeClasses,
        variantClasses,
    ]" :style="customStyle">

        <!-- Dot -->

        <span v-if="dot" class="h-2 w-2 rounded-full" :style="{
            backgroundColor:
                textColor ||
                borderColor ||
                'currentColor'
        }" />

        <!-- Left Icon -->
        <slot name="icon" />
        <slot />

        <!-- Right Icon -->
        <slot name="suffix" />
    </span>
</template>