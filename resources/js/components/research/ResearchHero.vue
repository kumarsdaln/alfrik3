<script setup lang="ts">
    import { Link } from '@inertiajs/vue3'
    import { ArrowRight } from '@lucide/vue'

    import AppHeading from '@/Components/Ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'

    import type { ResearchPaper } from '@/types'
import { MetaInfo } from '../ui/metainfo'

    defineProps<{
        research: ResearchPaper
        researchUrl: string
        year?: string | number | null
    }>()
</script>

<template>
    <section class="border-b border-border-light dark:border-border-dark">
        <div class="py-10 sm:py-14 lg:py-16">
            <div class="
                    grid
                    items-center
                    gap-8
                    lg:grid-cols-[1.1fr_0.9fr]
                    lg:gap-14
                ">
                <!-- Cover -->

                <Link :href="researchUrl" class="group block overflow-hidden bg-muted">
                    <div class="relative overflow-hidden">
                        <img v-if="research.cover_image" :src="research.cover_image" :alt="research.title" class="
                                block
                                h-auto
                                w-full
                                object-cover
                                transition-transform
                                duration-700
                                ease-out
                                group-hover:scale-[1.02]
                            " />

                        <div v-else class="
                                flex
                                aspect-[16/10]
                                w-full
                                items-center
                                justify-center
                                bg-surface-light
                                dark:bg-surface-dark
                            ">
                            <AppText size="xs" weight="bold" uppercase tracking="wide" color="muted">
                                Research Paper
                            </AppText>
                        </div>
                    </div>
                </Link>

                <!-- Content -->

                <div class="flex flex-col justify-center">

                    <!-- Area -->

                    <AppText v-if="research.area" tag="p" size="xs" weight="semibold" tracking="wide" uppercase
                        color="muted" class="mb-3">
                        {{ research.area.name }}
                    </AppText>

                    <!-- Title -->

                    <Link :href="researchUrl" class="group">
                        <AppHeading tag="h2" font="prata" size="3xl" weight="normal" leading="tight" hover-brand>
                            {{ research.title }}
                        </AppHeading>
                    </Link>

                    <!-- Authors -->

                    <AppText v-if="research.authors" tag="p" font="lora" size="sm" color="muted" class="mt-4 italic">
                        {{ research.authors }}
                    </AppText>

                    <!-- Abstract -->

                    <AppText v-if="research.abstract" tag="p" font="lora" color="muted" leading="relaxed" :clamp="3"
                        class="mt-5 max-w-xl">
                        {{ research.abstract }}
                    </AppText>

                    <!-- Meta -->
                    <MetaInfo class="mt-4"
                    :items="[
                        ...(research.institution
                            ? [
                                {
                                    value: research.institution,
                                },
                            ]
                            : []),

                        ...(year
                            ? [
                                {
                                    value: String(year),
                                },
                            ]
                            : []),
                    ]" />

                    <!-- Read Link -->

                    <div class="mt-7">
                        <Link :href="researchUrl" class="
                                inline-flex
                                items-center
                                gap-2
                                text-sm
                                font-semibold
                                text-content-light
                                transition-all
                                duration-300
                                hover:gap-3
                                hover:text-brand
                                dark:text-content-dark
                            ">
                            Read study

                            <ArrowRight :size="16" :stroke-width="1.8" />
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>