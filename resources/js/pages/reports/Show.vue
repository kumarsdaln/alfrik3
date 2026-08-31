<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import {
    ArrowDownToLine,
    CalendarDays,
    FileText,
    LockKeyhole,
} from '@lucide/vue'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import ReportCard from '@/components/reports/ReportCard.vue'

import {
    show as reportShow,
    download as reportDownload,
} from '@/routes/reports'
import { login } from '@/routes'

import type { Report } from '@/types'
import { MetaInfo } from '@/components/ui/metainfo'

interface Props {
    report: Report
    related?: Report[]
    canDownload: boolean
    meta_data?: {
        meta_title?: string
        meta_description?: string
        meta_keywords?: string
    }
}

const props = defineProps<Props>()

const dateLabel = computed(() => {
    const date =
        props.report.published_at ||
        props.report.created_at

    if (!date) {
        return ''
    }

    return new Date(date).toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    })
})

const cover = computed(
    () =>
        props.report.cover_image ||
        '/frontend/images/placeholder.jpg',
)

const reportUrl = computed(
    () => reportShow(props.report.slug).url,
)

const downloadUrl = computed(
    () => reportDownload(props.report.slug).url,
)
</script>

<template>
    <Head :title="meta_data?.meta_title || report.title">
        <meta
            name="description"
            :content="
                meta_data?.meta_description ??
                report.summary ??
                ''
            "
        />

        <meta
            name="keywords"
            :content="meta_data?.meta_keywords ?? ''"
        />
    </Head>


    <!--
    |--------------------------------------------------------------------------
    | Report Header
    |--------------------------------------------------------------------------
    -->

    <section
        class="
            border-b
            border-border-light
            dark:border-border-dark
        "
    >
        <div
            class="
                container
                mx-auto
                px-4
                pb-4
                pt-10
                sm:pb-4
                sm:pt-14
                lg:pb-4
                lg:pt-16
            "
        >

            <!-- Category -->
            <AppText
                v-if="report.category"
                tag="p"
                size="xs"
                weight="bold"
                tracking="wide"
                uppercase
                color="primary"
                class="mb-2"
            >
                {{ report.category.name }}
            </AppText>


            <!-- Title -->

            <AppHeading
                tag="h1"
                font="prata"
                size="5xl"
                weight="normal"
                leading="tight"
                class="max-w-5xl"
            >
                {{ report.title }}
            </AppHeading>


            <!-- Summary -->
            <AppText
                v-if="report.summary"
                font="lora"
                size="lg"
                color="muted"
                leading="relaxed"
                class="mt-2 max-w-3xl"
            >
                {{ report.summary }}
            </AppText>


            <!-- Meta -->
            <MetaInfo class="mt-4"
             :items="[
                {
                    label: 'By',
                    value: report.author.name,
                },
                {
                    value: dateLabel,
                    icon: CalendarDays,
                },
                {
                    value: `${report.report_year} edition`,
                },
            ]" />

        </div>
    </section>


    <!--
    |--------------------------------------------------------------------------
    | Report Content
    |--------------------------------------------------------------------------
    -->

    <main>
        <div
            class="
                container
                mx-auto
                px-4
                py-10
                sm:py-14
                lg:py-16
            "
        >

            <div
                class="
                    grid
                    gap-10
                    lg:grid-cols-[minmax(0,1fr)_320px]
                    lg:gap-14
                "
            >

                <!-- Main Content -->

                <article class="min-w-0">

                    <!-- Cover -->

                    <Link
                        :href="reportUrl"
                        class="group block overflow-hidden"
                    >
                        <div
                            class="
                                aspect-[16/10]
                                overflow-hidden
                                bg-muted
                            "
                        >
                            <img
                                :src="cover"
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


                    <!-- About -->

                    <section
                        v-if="report.summary"
                        class="
                            mt-10
                            max-w-3xl
                            sm:mt-14
                        "
                    >
                        <AppHeading
                            tag="h2"
                            font="prata"
                            size="2xl"
                            weight="normal"
                            leading="tight"
                        >
                            About this report
                        </AppHeading>

                        <AppText
                            tag="p"
                            font="lora"
                            size="lg"
                            color="muted"
                            leading="relaxed"
                            class="mt-5 whitespace-pre-line"
                        >
                            {{ report.summary }}
                        </AppText>
                    </section>

                </article>


                <!-- Download -->

                <aside>
                    <div
                        class="
                            sticky
                            top-24
                            border-t
                            border-border-light
                            pt-5
                            dark:border-border-dark
                        "
                    >

                        <!-- Gated -->
                        <div v-if="report.gated" class="mb-5 flex items-center gap-2">
                            <LockKeyhole :size="14" :stroke-width="1.8" class="text-primary" />

                            <AppText tag="span" size="xs" weight="bold" tracking="wide" uppercase color="primary">
                                Gated Report
                            </AppText>
                        </div>


                        <!-- Heading -->
                        <AppHeading
                            tag="h2"
                            font="prata"
                            size="xl"
                            weight="normal"
                        >
                            Get the report
                        </AppHeading>


                        <AppText
                            size="sm"
                            color="muted"
                            leading="relaxed"
                            class="mt-3"
                        >
                            Download the complete report and
                            explore the full research.
                        </AppText>


                        <!-- Details -->

                        <dl class="
                                mt-6
                                divide-y
                                divide-border-light
                                border-y
                                border-border-light
                                dark:divide-border-dark
                                dark:border-border-dark
                            ">
                            <div v-if="report.file_type" class="
                                    flex
                                    items-center
                                    justify-between
                                    py-3
                                ">
                                <AppText tag="dt" size="xs" color="muted">
                                    Format
                                </AppText>

                                <AppText tag="dd" size="xs" weight="medium" uppercase>
                                    {{ report.file_type }}
                                </AppText>
                            </div>


                            <div v-if="report.file_size_label" class="
                                    flex
                                    items-center
                                    justify-between
                                    py-3
                                ">
                                <AppText tag="dt" size="xs" color="muted">
                                    Size
                                </AppText>

                                <AppText tag="dd" size="xs" weight="medium">
                                    {{ report.file_size_label }}
                                </AppText>
                            </div>


                            <div class="
                                    flex
                                    items-center
                                    justify-between
                                    py-3
                                ">
                                <AppText tag="dt" size="xs" color="muted">
                                    Downloads
                                </AppText>

                                <AppText tag="dd" size="xs" weight="medium">
                                    {{ report.download_count ?? 0 }}
                                </AppText>
                            </div>
                        </dl>


                        <!-- Actions -->

                        <div class="mt-6">

                            <template v-if="report.file_path">

                                <!-- Can download -->
                                <a v-if="canDownload" :href="downloadUrl" class="
                                        group
                                        inline-flex
                                        h-11
                                        w-full
                                        items-center
                                        justify-center
                                        gap-2
                                        !bg-black
                                        px-5
                                        text-sm
                                        font-medium
                                        !text-white
                                        transition-colors
                                        duration-200
                                        hover:!bg-primary
                                        dark:!bg-white
                                        dark:!text-black
                                        dark:hover:!bg-primary
                                        dark:hover:!text-white
                                    ">
                                    <ArrowDownToLine :size="16" :stroke-width="1.8" class="
                                            transition-transform
                                            duration-200
                                            group-hover:translate-y-0.5
                                        " />

                                    Download report
                                </a>


                                <!-- Requires login -->
                                <div v-else>
                                    <AppText tag="p" size="sm" color="muted" leading="relaxed" class="mb-4">
                                        This report is available
                                        to members.
                                    </AppText>

                                    <Link :href="login().url" class="
                                            inline-flex
                                            h-11
                                            w-full
                                            items-center
                                            justify-center
                                            border
                                            border-black
                                            px-5
                                            text-sm
                                            font-medium
                                            !text-black
                                            transition-colors
                                            duration-200
                                            hover:bg-black
                                            hover:!text-white
                                            dark:border-white
                                            dark:!text-white
                                            dark:hover:bg-white
                                            dark:hover:!text-black
                                        ">
                                        Log in to download
                                    </Link>
                                </div>

                            </template>


                            <!-- No file -->
                            <div v-else class="
                                    flex
                                    items-center
                                    gap-2
                                    border
                                    border-dashed
                                    border-border-light
                                    p-4
                                    dark:border-border-dark
                                ">
                                <FileText :size="16" :stroke-width="1.7" class="shrink-0 text-muted" />

                                <AppText size="sm" color="muted">
                                    File coming soon.
                                </AppText>
                            </div>

                        </div>

                    </div>
                </aside>

            </div>
        </div>
    </main>


    <!--
    |--------------------------------------------------------------------------
    | Related Reports
    |--------------------------------------------------------------------------
    -->
    <section v-if="related?.length" class="
            border-t
            border-border-light
            dark:border-border-dark
        ">
        <div class="
                container
                mx-auto
                px-4
                py-12
                sm:py-14
                lg:py-16
            ">

            <div class="mb-8">
                <AppText tag="p" size="xs" weight="bold" tracking="wide" uppercase color="primary" class="mb-3">
                    Continue reading
                </AppText>

                <AppHeading tag="h2" font="prata" size="3xl" weight="normal">
                    Related reports
                </AppHeading>
            </div>


            <div class="
                    grid
                    grid-cols-1
                    gap-x-8
                    gap-y-12
                    sm:grid-cols-2
                    lg:grid-cols-3
                    lg:gap-x-10
                ">
                <ReportCard v-for="relatedReport in related" :key="relatedReport.id" 
                    :report="relatedReport"
                    :href="reportShow(relatedReport.slug).url" 
                    :download-href="reportDownload(relatedReport.slug).url" />
            </div>

        </div>
    </section>
</template>