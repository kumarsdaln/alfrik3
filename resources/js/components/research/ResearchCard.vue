
<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ArrowRight, Gem } from '@lucide/vue'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import { MetaInfo } from '@/components/ui/metainfo'

import type { Research } from '@/types'

const props = withDefaults(
    defineProps<{
        research: Research
        href: string
        compact?: boolean
    }>(),
    {
        compact: false,
    },
)

/*
|--------------------------------------------------------------------------
| Media
|--------------------------------------------------------------------------
*/

const coverMedia = computed(() => {
    const media = props.research.media ?? []

    return (
        media.find(item => item.collection === 'cover') ??
        media.find(item => item.collection === 'default') ??
        media.find(item => item.url)
    )
})

/*
|--------------------------------------------------------------------------
| Research Information
|--------------------------------------------------------------------------
*/

const researchType = computed(() =>
    props.research.type?.label ??
    props.research.type?.value ??
    'Research'
)

const summary = computed(() =>
    props.research.summary || props.research.description
)

const categories = computed(() =>
    props.research.categories ?? []
)

const year = computed(() => {
    if (!props.research.published_at) return ''

    return new Intl.DateTimeFormat('en-IN', {
        year: 'numeric',
    }).format(new Date(props.research.published_at))
})

/*
|--------------------------------------------------------------------------
| Metadata
|--------------------------------------------------------------------------
*/

const metaItems = computed(() => [
    ...(year.value ? [{ value: year.value }] : []),

    ...(props.research.methodology?.geography
        ? [{ value: props.research.methodology.geography }]
        : []),
])
</script>

<template>
    <!--
    |--------------------------------------------------------------------------
    | Compact Research Card
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
            overflow-hidden
            border
            border-border-light
            bg-surface-light
            transition-colors
            duration-300
            hover:border-primary/40
            dark:border-border-dark
            dark:bg-surface-dark
        "
    >
        <!-- Cover -->
        <div class="relative overflow-hidden bg-muted">
            <img
                v-if="coverMedia"
                :src="coverMedia.url"
                :alt="coverMedia.alt || research.title"
                class="
                    aspect-[16/9]
                    w-full
                    object-cover
                    transition-transform
                    duration-500
                    group-hover:scale-[1.03]
                "
                loading="lazy"
            />

            <div
                v-else
                class="flex aspect-[16/9] items-center justify-center"
            >
                <AppText size="xs" color="muted">
                    Research publication
                </AppText>
            </div>

            <div
                v-if="research.featured"
                class="absolute left-3 top-3"
            >
                <Badge variant="secondary">
                    <Gem :size="12" />
                    Featured research
                </Badge>
            </div>
        </div>

        <!-- Content -->
        <div class="flex flex-1 flex-col p-5 sm:p-6">

            <AppText
                tag="p"
                size="xs"
                color="brand"
                weight="semibold"
                uppercase
                tracking="wide"
            >
                {{ researchType }}
            </AppText>

            <div
                v-if="categories.length"
                class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1"
            >
                <template
                    v-for="(category, index) in categories"
                    :key="category.id"
                >
                    <AppText tag="span" size="xs" color="muted">
                        {{ category.name }}
                    </AppText>

                    <span
                        v-if="index < categories.length - 1"
                        class="text-border-light"
                    >
                        /
                    </span>
                </template>
            </div>

            <AppHeading
                tag="h3"
                font="prata"
                size="lg"
                weight="normal"
                leading="tight"
                :clamp="3"
                class="mt-3 transition-colors group-hover:text-primary"
            >
                {{ research.title }}
            </AppHeading>

            <AppText
                v-if="research.subtitle"
                tag="p"
                font="lora"
                size="xs"
                color="muted"
                leading="relaxed"
                :clamp="2"
                class="mt-3 italic"
            >
                {{ research.subtitle }}
            </AppText>

            <div
                class="
                    mt-auto
                    flex
                    items-center
                    gap-3
                    border-t
                    border-border-light
                    pt-4
                    dark:border-border-dark
                "
                :class="research.subtitle ? 'mt-5' : 'mt-6'"
            >
                <MetaInfo :items="metaItems" />

                <span
                    class="
                        ml-auto
                        inline-flex
                        shrink-0
                        items-center
                        gap-1.5
                        text-xs
                        font-semibold
                        text-foreground
                        transition-colors
                        group-hover:text-primary
                    "
                >
                    Read study
                    <ArrowRight
                        class="
                            h-3.5
                            w-3.5
                            transition-transform
                            duration-200
                            group-hover:translate-x-1
                        "
                    />
                </span>
            </div>
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
            hover:border-primary/40
            dark:border-border-dark
            dark:bg-surface-dark
            lg:grid-cols-[0.9fr_1.1fr]
        "
    >
        <!-- Cover Image -->
        <div class="relative min-h-0 overflow-hidden bg-muted">
            <img
                v-if="coverMedia"
                :src="coverMedia.url"
                :alt="coverMedia.alt || research.title"
                class="
                    aspect-[16/10]
                    h-full
                    w-full
                    object-cover
                    transition-transform
                    duration-700
                    group-hover:scale-[1.03]
                    lg:aspect-auto
                    lg:min-h-[340px]
                "
                loading="lazy"
            />

            <div
                v-else
                class="
                    flex
                    min-h-[220px]
                    h-full
                    items-center
                    justify-center
                    lg:min-h-[340px]
                "
            >
                <AppText size="sm" color="muted">
                    Research publication
                </AppText>
            </div>

            <!-- Featured Badge -->
            <div
                v-if="research.featured"
                class="absolute left-4 top-4"
            >
                <Badge variant="secondary">
                    <Gem :size="12" />
                    Featured research
                </Badge>
            </div>
        </div>

        <!-- Research Information -->
        <div
            class="
                flex
                min-w-0
                flex-col
                justify-center
                px-6
                py-7
                sm:px-8
                sm:py-9
                lg:px-10
                lg:py-10
            "
        >
            <!-- Type and Categories -->
            <div>
                <AppText
                    tag="p"
                    size="xs"
                    color="brand"
                    weight="semibold"
                    uppercase
                    tracking="wide"
                >
                    {{ researchType }}
                </AppText>

                <div
                    v-if="categories.length"
                    class="mt-2 flex flex-wrap items-center gap-x-2 gap-y-1"
                >
                    <template
                        v-for="(category, index) in categories"
                        :key="category.id"
                    >
                        <AppText
                            tag="span"
                            size="xs"
                            color="muted"
                        >
                            {{ category.name }}
                        </AppText>

                        <span
                            v-if="index < categories.length - 1"
                            class="text-border-light dark:text-border-dark"
                        >
                            /
                        </span>
                    </template>
                </div>
            </div>

            <!-- Title -->
            <AppHeading
                tag="h3"
                font="prata"
                size="2xl"
                weight="normal"
                leading="tight"
                :clamp="3"
                class="mt-4 transition-colors group-hover:text-primary"
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
                v-if="summary"
                tag="p"
                font="lora"
                size="sm"
                color="muted"
                leading="relaxed"
                :clamp="3"
                class="mt-4 max-w-2xl"
            >
                {{ summary }}
            </AppText>

            <!-- Metadata and CTA -->
            <div
                class="
                    mt-7
                    flex
                    flex-wrap
                    items-center
                    gap-4
                    border-t
                    border-border-light
                    pt-5
                    dark:border-border-dark
                "
            >
                <MetaInfo :items="metaItems" />

                <span
                    class="
                        ml-auto
                        inline-flex
                        shrink-0
                        items-center
                        gap-2
                        text-xs
                        font-semibold
                        tracking-wide
                        text-foreground
                        transition-colors
                        group-hover:text-primary
                    "
                >
                    Read full study

                    <ArrowRight
                        class="
                            h-4
                            w-4
                            transition-transform
                            duration-200
                            group-hover:translate-x-1
                        "
                    />
                </span>
            </div>
        </div>
    </Link>
</template>
