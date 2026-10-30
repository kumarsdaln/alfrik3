<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'

import type { Report } from '@/types'

defineProps<{
    report: Report
    reportUrl: string
    coverUrl: string
    dateLabel: string
}>()
</script>

<template>
    <section class="border-b border-border-light dark:border-border-dark">
        <div class="container mx-auto px-4 py-10 sm:px-6 sm:py-14 lg:px-8 lg:py-16">
            <div
                class="
                    grid
                    gap-8
                    lg:grid-cols-[1.1fr_0.9fr]
                    lg:items-center
                    lg:gap-14
                "
            >
                <!-- ================================================== -->
                <!-- Cover -->
                <!-- ================================================== -->

                <Link
                    :href="reportUrl"
                    class="group block overflow-hidden bg-muted"
                >
                    <div class="aspect-[16/10] overflow-hidden">
                        <img
                            :src="coverUrl"
                            :alt="report.title"
                            class="
                                h-full
                                w-full
                                object-cover
                                transition-transform
                                duration-700
                                group-hover:scale-[1.02]
                            "
                        />
                    </div>
                </Link>


                <!-- ================================================== -->
                <!-- Content -->
                <!-- ================================================== -->

                <div class="flex flex-col justify-center">
                    <!-- Type -->

                    <AppText
                        v-if="report.type"
                        tag="p"
                        size="xs"
                        weight="semibold"
                        tracking="wide"
                        uppercase
                        color="muted"
                        class="mb-4"
                    >
                        {{ report.type.label }}
                    </AppText>


                    <!-- Title -->

                    <Link
                        :href="reportUrl"
                        class="group/title block"
                    >
                        <AppHeading
                            tag="h1"
                            font="prata"
                            size="3xl"
                            weight="normal"
                            leading="tight"
                            hover-brand
                            class="sm:text-4xl"
                        >
                            {{ report.title }}
                        </AppHeading>
                    </Link>


                    <!-- Subtitle -->

                    <AppText
                        v-if="report.subtitle"
                        tag="p"
                        font="lora"
                        size="sm"
                        leading="relaxed"
                        class="mt-4 max-w-xl"
                    >
                        {{ report.subtitle }}
                    </AppText>


                    <!-- Summary -->

                    <AppText
                        v-if="report.summary"
                        tag="p"
                        font="lora"
                        color="muted"
                        leading="relaxed"
                        :clamp="3"
                        class="mt-4 max-w-xl"
                    >
                        {{ report.summary }}
                    </AppText>


                    <!-- Meta -->

                    <div
                        class="
                            mt-6
                            flex
                            flex-wrap
                            items-center
                            gap-x-4
                            gap-y-2
                        "
                    >
                        <AppText
                            v-if="dateLabel"
                            tag="span"
                            size="xs"
                            color="muted"
                        >
                            {{ dateLabel }}
                        </AppText>

                        <span
                            v-if="dateLabel && report.type"
                            class="text-border-light dark:text-border-dark"
                            aria-hidden="true"
                        >
                            /
                        </span>

                        <AppText
                            v-if="report.type"
                            tag="span"
                            size="xs"
                            color="muted"
                        >
                            {{ report.type.label }}
                        </AppText>
                    </div>


                    <!-- Action -->

                    <div class="mt-8">
                        <Link
                            :href="reportUrl"
                            class="
                                inline-flex
                                h-10
                                items-center
                                justify-center
                                bg-black
                                px-5
                                text-sm
                                font-medium
                                text-white
                                transition-colors
                                duration-200
                                hover:bg-brand
                                dark:bg-white
                                dark:text-black
                                dark:hover:bg-brand
                                dark:hover:text-white
                            "
                        >
                            Read report
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>