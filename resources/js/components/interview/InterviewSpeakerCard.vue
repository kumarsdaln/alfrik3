<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ArrowUpRight } from '@lucide/vue'

import Avatar from '@/components/profile/Avatar.vue'

interface Props {
    name?: string | null
    avatar?: string | null
    role?: string
    variant?: 'default' | 'answer'
    href?: string
}

const props = withDefaults(defineProps<Props>(), {
    name: null,
    avatar: null,
    role: 'Speaker',
    variant: 'default',
    href: '#',
})

const isAnswer = computed(() => props.variant === 'answer')

const displayName = computed(() => props.name?.trim() || 'Unknown User')
</script>

<template>
    <Link
        :href="href"
        class="
            group
            inline-flex
            min-w-0
            items-center
            gap-3
            rounded-lg
            py-2
            pr-3
            transition-colors
            duration-200
            hover:bg-muted/50
            focus-visible:outline-none
            focus-visible:ring-2
            focus-visible:ring-ring
            focus-visible:ring-offset-2
        "
    >
        <!-- Avatar -->

        <Avatar
            :image="avatar"
            :name="displayName"
            size="size-11"
            text-size="text-sm"
            class="
                shrink-0
                transition-transform
                duration-200
                group-hover:scale-[1.03]
            "
        />


        <!-- Information -->

        <div class="min-w-0">
            <div
                class="
                    text-[10px]
                    font-medium
                    uppercase
                    tracking-[0.16em]
                    text-muted-foreground
                "
            >
                {{ role }}
            </div>

            <div class="mt-0.5 flex min-w-0 items-center gap-1.5">
                <span
                    class="
                        truncate
                        text-sm
                        font-medium
                        text-foreground
                        transition-colors
                        duration-200
                        group-hover:text-primary
                    "
                >
                    {{ displayName }}
                </span>

                <ArrowUpRight
                    class="
                        size-3.5
                        shrink-0
                        text-muted-foreground
                        opacity-0
                        transition-all
                        duration-200
                        group-hover:translate-x-0.5
                        group-hover:-translate-y-0.5
                        group-hover:text-foreground
                        group-hover:opacity-100
                    "
                    :stroke-width="1.75"
                />
            </div>
        </div>
    </Link>
</template>