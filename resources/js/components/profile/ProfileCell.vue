<script setup lang="ts">
import { computed } from 'vue'

import type { Profile } from '@/types'

import Avatar from './Avatar.vue'

type ProfileSize = 'xs' | 'sm' | 'md' | 'lg'
type ProfileVariant = 'default' | 'compact' | 'large'

interface Props {
    profile: Profile

    size?: ProfileSize
    variant?: ProfileVariant

    showEmail?: boolean
    showPosition?: boolean

    clickable?: boolean

    truncate?: boolean
}

const props = withDefaults(
    defineProps<Props>(),
    {
        size: 'md',
        variant: 'default',
        showEmail: false,
        showPosition: true,
        clickable: false,
        truncate: true,
    }
)

const avatarSize = computed(() => {
    if (props.variant === 'compact') {
        return 'size-8'
    }

    if (props.variant === 'large') {
        return 'size-14'
    }

    return {
        xs: 'size-7',
        sm: 'size-8',
        md: 'size-10',
        lg: 'size-12',
    }[props.size]
})

const avatarTextSize = computed(() => {
    if (props.variant === 'compact') {
        return 'text-sm'
    }

    if (props.variant === 'large') {
        return 'text-xl'
    }

    return {
        xs: 'text-xs',
        sm: 'text-sm',
        md: 'text-base',
        lg: 'text-lg',
    }[props.size]
})

const nameClass = computed(() => {
    if (props.variant === 'large') {
        return 'text-base font-semibold'
    }

    if (props.variant === 'compact') {
        return 'text-xs font-medium'
    }

    return 'text-sm font-medium'
})

const secondaryClass = computed(() => {
    if (props.variant === 'large') {
        return 'text-sm text-muted-foreground'
    }

    return 'text-xs text-muted-foreground'
})
</script>

<template>
    <div
        class="flex min-w-0 items-center gap-3"
        :class="{
            'cursor-pointer': clickable,
        }"
    >
        <!-- Avatar -->
        <Avatar
            :image="profile.avatar"
            :name="profile.name"
            :size="avatarSize"
            :text-size="avatarTextSize"
            rounded="full"
        />

        <!-- Information -->
        <div class="min-w-0 flex-1">
            <!-- Name -->
            <div
                class="text-foreground"
                :class="[
                    nameClass,
                    {
                        truncate,
                    },
                ]"
                :title="profile.name"
            >
                {{ profile.name }}
            </div>

            <!-- Position -->
            <div
                v-if="showPosition && profile.position"
                class="mt-0.5"
                :class="[
                    secondaryClass,
                    {
                        truncate,
                    },
                ]"
                :title="profile.position.name"
            >
                {{ profile.position.name }}
            </div>

            <!-- Email -->
            <div
                v-if="showEmail && profile.email"
                class="mt-0.5"
                :class="[
                    secondaryClass,
                    {
                        truncate,
                    },
                ]"
                :title="profile.email"
            >
                {{ profile.email }}
            </div>
        </div>

        <!-- Optional slot -->
        <div
            v-if="$slots.actions"
            class="shrink-0"
        >
            <slot name="actions" />
        </div>
    </div>
</template>