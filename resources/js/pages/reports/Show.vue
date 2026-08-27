<script setup lang="ts">
    import { Head } from '@inertiajs/vue3'
    import { computed } from 'vue'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppButton from '@/components/ui/AppButton.vue'
    import ReportCard from '@/Components/Reports/ReportCard.vue'
    import { show as reportShow, download as reportDownload } from '@/routes/reports'
    import { login } from '@/routes'
    import type { Report } from '@/types'

    const props = defineProps<{
        report: Report
        related?: Report[]
        canDownload: boolean
        meta_data?: { meta_title?: string; meta_description?: string; meta_keywords?: string }
    }>()


    const dateLabel = computed(() => {
        const d = props.report.published_at || props.report.created_at
        return d ? new Date(d).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : ''
    })
    const cover = props.report.cover_image || '/frontend/images/placeholder.jpg'
</script>

<template>

    <Head :title="meta_data?.meta_title || report.title">
        <meta name="description" :content="meta_data?.meta_description ?? report.summary ?? ''" />
        <meta name="keywords" :content="meta_data?.meta_keywords ?? ''" />
    </Head>

    <div class="container mx-auto px-4 py-10 grid lg:grid-cols-3 gap-10">
        <!-- Main -->
        <div class="lg:col-span-2">
            <AppText v-if="report.category" tag="p" font="redhat" size="xs" weight="bold" tracking="wide" uppercase
                color="brand" class="mb-3">
                {{ report.category.name }}
            </AppText>
            <AppHeading tag="h1" font="prata" size="4xl" weight="bold" leading="tight" class="mb-4">
                {{ report.title }}
            </AppHeading>
            <div class="flex flex-wrap items-center gap-3 mb-8">
                <AppText v-if="report.author" tag="span" size="sm" weight="medium">By {{ report.author.name }}</AppText>
                <AppText v-if="report.author" tag="span" size="sm" color="muted">·</AppText>
                <AppText tag="span" size="sm" color="muted">{{ dateLabel }}</AppText>
                <AppText v-if="report.report_year" tag="span" size="sm" color="muted">· {{ report.report_year }} edition
                </AppText>
            </div>

            <img :src="cover" :alt="report.title" class="w-full rounded-2xl mb-8" />

            <div v-if="report.summary" class="max-w-none">
                <AppHeading tag="h2" font="prata" size="xl" weight="semibold" class="mb-4">About this report
                </AppHeading>
                <AppText tag="p" font="lora" size="lg" color="muted" leading="relaxed" class="whitespace-pre-line">{{
                    report.summary }}</AppText>
            </div>
        </div>

        <!-- Download sidebar -->
        <aside class="lg:col-span-1">
            <div
                class="sticky top-24 rounded-2xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-6">
                <AppHeading tag="h3" font="prata" size="lg" weight="semibold" class="mb-4">Download</AppHeading>
                <dl class="space-y-2 mb-6">
                    <div v-if="report.file_type" class="flex justify-between">
                        <AppText tag="dt" size="sm" color="muted">Format</AppText>
                        <AppText tag="dd" size="sm" weight="medium" class="uppercase">{{ report.file_type }}</AppText>
                    </div>
                    <div v-if="report.file_size_label" class="flex justify-between">
                        <AppText tag="dt" size="sm" color="muted">Size</AppText>
                        <AppText tag="dd" size="sm" weight="medium">{{ report.file_size_label }}</AppText>
                    </div>
                    <div class="flex justify-between">
                        <AppText tag="dt" size="sm" color="muted">Downloads</AppText>
                        <AppText tag="dd" size="sm" weight="medium">{{ report.download_count ?? 0 }}</AppText>
                    </div>
                </dl>

                <template v-if="report.file_path">
                    <AppButton v-if="canDownload" :href="reportDownload(report.slug).url" external variant="primary"
                        full-width>
                        <template #icon-left>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                            </svg>
                        </template>
                        Download Report
                    </AppButton>
                    <div v-else class="text-center">
                        <AppText tag="p" size="sm" color="muted" align="center" class="mb-3">This report is available to
                            members.</AppText>
                        <AppButton :href="login().url" variant="secondary" full-width>Log in to download</AppButton>
                    </div>
                </template>
                <AppText v-else tag="p" size="sm" color="muted">File coming soon.</AppText>
            </div>
        </aside>
    </div>

    <!-- Related -->
    <section v-if="related && related.length"
        class="container mx-auto px-4 py-12 border-t border-border-light dark:border-border-dark">
        <AppHeading tag="h2" font="prata" size="3xl" weight="semibold" class="mb-8">Related reports</AppHeading>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <ReportCard v-for="r in related" :key="r.id" :report="r" :href="reportShow(r.slug).url"
                :download-href="reportDownload(r.slug).url" />
        </div>
    </section>
</template>
