<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import { MetaInfo } from '@/components/ui/metainfo'

import type { Research } from '@/types'

withDefaults(
    defineProps<{
        research: Research
        href: string
        compact?: boolean
    }>(),
    {
        compact: false,
    },
)

const yearOf = (date?: string | null): string => {
    return date ? String(new Date(date).getFullYear()) : ''
}
</script>

<template>
    <!--
    |--------------------------------------------------------------------------
    | Compact — Related Research
    |--------------------------------------------------------------------------
    -->

    <Link
        v-if="compact"
        :href="href"
        class="
            group
            flex
            h-full
            flex-col
            border
            border-border-light
            bg-surface-light
            p-6
            transition-colors
            duration-300
            dark:border-border-dark
            dark:bg-surface-dark
        "
    >
        <!-- Type -->

        <div v-if="research.type" class="
                mb-5
                flex
                items-center
                gap-2
            ">
            <AppText tag="span" size="xs" weight="bold" tracking="wide" uppercase>
                {{ research.type.label ?? research.type.value ?? research.type }}
            </AppText>
        </div>

        <!-- Title -->

        <AppHeading
            tag="h3"
            font="prata"
            size="lg"
            weight="normal"
            leading="tight"
            :clamp="3"
        >
            {{ research.title }}
        </AppHeading>

        <!-- Subtitle -->

        <AppText
            v-if="research.subtitle"
            tag="p"
            font="lora"
            size="xs"
            color="muted"
            leading="relaxed"
            :clamp="2"
            class="mt-4 italic"
        >
            {{ research.subtitle }}
        </AppText>

        <!-- Bottom Meta -->

        <div
            class="
                mt-auto
                flex
                items-center
                gap-3
                border-t
                border-border-light
                pt-5
                dark:border-border-dark
            "
            :class="research.subtitle ? 'mt-6' : ''"
        >
            <MetaInfo
                :items="[
                    ...(yearOf(research.published_at)
                        ? [{ value: yearOf(research.published_at) }]
                        : []),
                ]"
            />

            <span
                class="
                    ml-auto
                    shrink-0
                    text-xs
                    font-semibold
                    transition-transform
                    duration-300
                    group-hover:translate-x-1
                "
            >
                Read →
            </span>
        </div>
    </Link>

    <!--
    |--------------------------------------------------------------------------
    | Full Research Card
    |--------------------------------------------------------------------------
    -->

    <Link
        v-else
        :href="href"
        class="
            group
            grid
            overflow-hidden
            border-y
            border-border-light
            bg-surface-light
            transition-colors
            duration-300
            dark:border-border-dark
            dark:bg-surface-dark
        "
    >
        <!-- Content -->
        <div
            class="
                flex
                min-w-0
                flex-col
                px-6
                py-7
                sm:px-8
                sm:py-8
                lg:px-10
            "
        >
            <!-- Type -->

            <div v-if="research.type" class="
                    mb-4
                    flex
                    items-center
                    gap-2
                ">
                <AppText tag="span" size="xs" weight="bold" tracking="wide" color="brand" uppercase>
                    {{ research.type.label ?? research.type.value ?? research.type }}
                </AppText>
            </div>

            <!-- Title -->
            <AppHeading
                tag="h3"
                font="prata"
                size="2xl"
                weight="normal"
                leading="tight"
                :clamp="3"
            >
                {{ research.title }}
            </AppHeading>

            <!-- Subtitle -->
            <AppText
                v-if="research.subtitle"
                tag="p"
                font="lora"
                size="sm"
                color="muted"
                leading="relaxed"
                :clamp="2"
                class="mt-3 italic"
            >
                {{ research.subtitle }}
            </AppText>

            <!-- Summary -->
            <AppText
                v-if="research.summary"
                tag="p"
                font="lora"
                size="sm"
                color="muted"
                leading="relaxed"
                :clamp="3"
                class="mt-4 max-w-3xl"
            >
                {{ research.summary }}
            </AppText>

            <!-- Metadata -->
            <div
                class="
                    mt-7
                    flex
                    items-center
                    border-t
                    border-border-light
                    pt-5
                "
            >
                <MetaInfo
                    :items="[
                        ...(yearOf(research.published_at)
                            ? [{ value: yearOf(research.published_at) }]
                            : []),
                    ]"
                />

                <span
                    class="
                        ml-auto
                        shrink-0
                        text-xs
                        font-semibold
                        tracking-wide
                        transition-transform
                        duration-300
                        group-hover:translate-x-1
                    "
                >
                    Read study →
                </span>
            </div>
        </div>
    </Link>
</template>