<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

import AppHeading from '@/Components/Ui/AppHeading.vue'
import AppText from '@/Components/Ui/AppText.vue'
import { MetaInfo } from '@/Components/Ui/MetaInfo'

import type { ResearchPaper } from '@/types'

withDefaults(
    defineProps<{
        paper: ResearchPaper
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
        <!-- Area -->

        <div
            v-if="paper.area"
            class="
                mb-5
                flex
                items-center
                gap-2
            "
        >
            <span
                class="h-px w-6"
                aria-hidden="true"
            />

            <AppText
                tag="span"
                size="xs"
                weight="bold"
                tracking="wide"
                uppercase
            >
                {{ paper.area.name }}
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
            {{ paper.title }}
        </AppHeading>

        <!-- Authors -->

        <AppText
            v-if="paper.authors"
            tag="p"
            font="lora"
            size="xs"
            color="muted"
            leading="relaxed"
            :clamp="2"
            class="mt-4 italic"
        >
            {{ paper.authors }}
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
            :class="paper.authors ? 'mt-6' : ''"
        >
            <MetaInfo
                :items="[
                    ...(paper.institution
                        ? [{ value: paper.institution }]
                        : []),

                    ...(yearOf(paper.published_at)
                        ? [{ value: yearOf(paper.published_at) }]
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
            sm:grid-cols-[200px_1fr]
        "
    >
        <!-- Cover -->

        <div
            v-if="paper.cover_image"
            class="
                relative
                overflow-hidden
                bg-muted
                sm:border-r
                sm:border-border-light
                dark:sm:border-border-dark
            "
        >
            <div class="aspect-[4/3] h-full sm:aspect-auto">
                <img
                    :src="paper.cover_image"
                    :alt="paper.title"
                    class="
                        h-full
                        w-full
                        object-cover
                        transition-transform
                        duration-700
                        ease-out
                        group-hover:scale-[1.025]
                    "
                />
            </div>
        </div>

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
            <!-- Area -->

            <div
                v-if="paper.area"
                class="
                    mb-4
                    flex
                    items-center
                    gap-2
                "
            >
                <span
                    class="h-px w-7"
                    aria-hidden="true"
                />

                <AppText
                    tag="span"
                    size="xs"
                    weight="bold"
                    tracking="wide"
                    uppercase
                >
                    {{ paper.area.name }}
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
                {{ paper.title }}
            </AppHeading>

            <!-- Authors -->

            <AppText
                v-if="paper.authors"
                tag="p"
                font="lora"
                size="sm"
                color="muted"
                leading="relaxed"
                :clamp="2"
                class="mt-3 italic"
            >
                {{ paper.authors }}
            </AppText>

            <!-- Abstract -->

            <AppText
                v-if="paper.abstract"
                tag="p"
                font="lora"
                size="sm"
                color="muted"
                leading="relaxed"
                :clamp="3"
                class="mt-4 max-w-3xl"
            >
                {{ paper.abstract }}
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
                    dark:border-border-dark
                "
            >
                <MetaInfo
                    :items="[
                        ...(paper.institution
                            ? [{ value: paper.institution }]
                            : []),

                        ...(yearOf(paper.published_at)
                            ? [{ value: yearOf(paper.published_at) }]
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