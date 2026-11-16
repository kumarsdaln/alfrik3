<script setup lang="ts">
    interface Props {
        value: string | number
        label: string
        description?: string | null
        source?: string | null
        trend?: string | null
        trendLabel?: string | null
        variant?: 'default' | 'compact' | 'featured'
    }

    withDefaults(defineProps<Props>(), {
        description: null,
        source: null,
        trend: null,
        trendLabel: null,
        variant: 'default',
    })
</script>

<template>
    <figure class="min-w-0" :class="{
        'py-5': variant === 'default',
        'py-3': variant === 'compact',
        'border-y border-border py-8 sm:py-10': variant === 'featured',
    }">
        <p class="font-semibold tracking-tight text-foreground tabular-nums" :class="{
            'text-4xl sm:text-5xl': variant === 'default',
            'text-2xl sm:text-3xl': variant === 'compact',
            'text-5xl sm:text-6xl': variant === 'featured',
        }">
            {{ value }}
        </p>

        <figcaption class="mt-3">
            <p class="text-sm font-semibold text-foreground">
                {{ label }}
            </p>

            <p v-if="description" class="mt-1 max-w-md text-sm leading-relaxed text-muted-foreground">
                {{ description }}
            </p>

            <div v-if="trend || trendLabel" class="mt-3 flex flex-wrap items-center gap-2 text-xs">
                <span v-if="trend" class="font-semibold text-foreground">
                    {{ trend }}
                </span>

                <span v-if="trendLabel" class="text-muted-foreground">
                    {{ trendLabel }}
                </span>
            </div>

            <p v-if="source" class="mt-3 text-xs text-muted-foreground">
                Source: {{ source }}
            </p>
        </figcaption>
    </figure>
</template>
