<script setup lang="ts">
    import { Link } from '@inertiajs/vue3'
    import { ArrowDownToLine, LockKeyhole } from '@lucide/vue'

    import AppHeading from '@/Components/Ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'
    import type { Report } from '@/types'

    defineProps<{
        report: Report
        reportUrl: string
        downloadUrl: string
        coverUrl: string
        dateLabel: string
    }>()
</script>

<template>
    <section class="border-b border-border-light dark:border-border-dark">
        <div class="py-10 sm:py-14 lg:py-16">
            <div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr] lg:gap-14">

                <!-- Image -->

                <Link :href="reportUrl" class="group block overflow-hidden bg-muted">
                    <div class="aspect-[16/10] overflow-hidden">
                        <img :src="coverUrl" :alt="report.title" class="
                                h-full
                                w-full
                                object-cover
                                transition-transform
                                duration-700
                                group-hover:scale-[1.02]
                            " />
                    </div>
                </Link>

                <!-- Content -->

                <div class="flex flex-col justify-center">

                    <!-- Gated Label -->

                    <div v-if="report.gated" class="mb-5 flex items-center gap-2">
                        <LockKeyhole :size="13" :stroke-width="1.8" class="text-brand" />

                        <AppText tag="span" size="xs" weight="bold" tracking="wide" uppercase color="brand">
                            Gated Report
                        </AppText>
                    </div>

                    <!-- Category -->

                    <AppText v-if="report.category" tag="p" size="xs" weight="semibold" tracking="wide" uppercase
                        color="muted" class="mb-4">
                        {{ report.category.name }}
                    </AppText>

                    <!-- Title -->

                    <Link :href="reportUrl" class="group/title">
                        <AppHeading tag="h2" font="prata" size="3xl" weight="normal" leading="tight" hover-brand>
                            {{ report.title }}
                        </AppHeading>
                    </Link>

                    <!-- Summary -->

                    <AppText v-if="report.summary" font="lora" color="muted" leading="relaxed" clamp="3"
                        class="mt-5 max-w-xl">
                        {{ report.summary }}
                    </AppText>

                    <!-- Meta -->

                    <div class="
                            mt-6
                            flex
                            flex-wrap
                            items-center
                            gap-x-4
                            gap-y-2
                        ">
                        <AppText tag="span" size="xs" color="muted">
                            {{ dateLabel }}
                        </AppText>

                        <span class="text-border-light dark:text-border-dark" aria-hidden="true">
                            /
                        </span>

                        <AppText tag="span" size="xs" color="muted">
                            {{ report.report_year }}
                        </AppText>
                    </div>

                    <!-- Actions -->

                    <div class="mt-8 flex flex-wrap items-center gap-6">

                        <!-- Read Report -->

                        <Link :href="reportUrl" class="
                                inline-flex
                                h-10
                                items-center
                                justify-center
                                !bg-black
                                px-5
                                text-sm
                                font-medium
                                !text-white
                                transition-colors
                                duration-200
                                hover:!bg-brand
                                dark:!bg-white
                                dark:!text-black
                                dark:hover:!bg-brand
                                dark:hover:!text-white
                            ">
                            Read report
                        </Link>

                        <!-- Download -->

                        <a :href="downloadUrl" class="
                                group/download
                                inline-flex
                                h-10
                                items-center
                                gap-2
                                border
                                border-black
                                px-4
                                text-sm
                                font-medium
                                !text-black
                                transition-all
                                duration-200
                                hover:bg-black
                                hover:!text-white
                                dark:border-white
                                dark:!text-white
                                dark:hover:bg-white
                                dark:hover:!text-black
                            ">
                            <ArrowDownToLine :size="15" :stroke-width="1.8" class="
                                    transition-transform
                                    duration-200
                                    group-hover/download:translate-y-0.5
                                " />

                            <span>
                                {{ report.gated ? 'Get report' : 'Download' }}
                            </span>
                        </a>

                    </div>
                </div>
            </div>
        </div>
    </section>
</template>