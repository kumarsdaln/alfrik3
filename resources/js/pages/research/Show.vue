<script setup lang="ts">
    import { Head } from '@inertiajs/vue3'
    import { ref } from 'vue'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppButton from '@/components/ui/AppButton.vue'
    import AppBadge from '@/components/ui/AppBadge.vue'
    import ResearchCard from '@/components/research/ResearchCard.vue'
    import { show as researchShow, download as researchDownload } from '@/routes/research'
    import type { ResearchPaper } from '@/types'

    const props = defineProps<{
        paper: ResearchPaper
        related?: ResearchPaper[]
        meta_data?: { meta_title?: string; meta_description?: string; meta_keywords?: string }
    }>()

    const year = props.paper.published_at ? new Date(props.paper.published_at).getFullYear() : ''
    const keywordList = props.paper.keywords ? props.paper.keywords.split(',').map(k => k.trim()).filter(Boolean) : []

    const copied = ref(false)
    async function copyCitation() {
        if (!props.paper.citation) return
        await navigator.clipboard?.writeText(props.paper.citation)
        copied.value = true
        setTimeout(() => (copied.value = false), 2000)
    }
</script>

<template>

    <Head :title="meta_data?.meta_title || paper.title">
        <meta name="description" :content="meta_data?.meta_description ?? paper.abstract ?? ''" />
        <meta name="keywords" :content="meta_data?.meta_keywords ?? paper.keywords ?? ''" />
    </Head>

    <div class="container mx-auto px-4 py-10 grid lg:grid-cols-3 gap-10">
        <div class="lg:col-span-2">
            <AppText v-if="paper.area" tag="p" font="redhat" size="xs" weight="bold" tracking="wide" uppercase
                color="brand" class="mb-3">{{ paper.area.name }}</AppText>
            <AppHeading tag="h1" font="prata" size="4xl" weight="bold" leading="tight" class="mb-4">
                {{ paper.title }}</AppHeading>
            <AppText v-if="paper.authors" tag="p" font="lora" class="mb-2 italic">{{ paper.authors }}</AppText>
            <div class="flex flex-wrap items-center gap-3 mb-8">
                <AppText v-if="paper.institution" tag="span" font="redhat" size="sm" color="muted">
                    {{ paper.institution }}</AppText>
                <AppText v-if="paper.institution && year" tag="span" size="sm" color="muted">·</AppText>
                <AppText v-if="year" tag="span" font="redhat" size="sm" color="muted">{{ year }}</AppText>
                <AppText v-if="paper.doi" tag="span" font="redhat" size="sm" color="muted">· DOI:
                    <a :href="`https://doi.org/${paper.doi}`" target="_blank" rel="noopener"
                        class="text-brand hover:underline">{{ paper.doi }}</a>
                </AppText>
            </div>

            <div v-if="paper.abstract" class="mb-8">
                <AppHeading tag="h2" font="prata" size="lg" weight="bold" class="mb-2">Abstract</AppHeading>
                <AppText tag="p" font="lora" color="muted" leading="relaxed" class="whitespace-pre-line">
                    {{ paper.abstract }}</AppText>
            </div>

            <div v-if="paper.methodology" class="mb-8">
                <AppHeading tag="h2" font="prata" size="lg" weight="bold" class="mb-2">Methodology</AppHeading>
                <AppText tag="p" font="lora" color="muted" leading="relaxed" class="whitespace-pre-line">
                    {{ paper.methodology }}</AppText>
            </div>

            <div v-if="keywordList.length" class="flex flex-wrap gap-2 mb-8">
                <AppBadge v-for="(k, i) in keywordList" :key="i" variant="default" size="md">{{ k }}</AppBadge>
            </div>

            <div v-if="paper.citation"
                class="rounded-xl bg-surface-light dark:bg-surface-dark border border-border-light dark:border-border-dark p-5">
                <div class="flex items-center justify-between mb-2">
                    <AppHeading tag="h3" font="prata" size="md" weight="bold">How to cite</AppHeading>
                    <button @click="copyCitation" class="text-xs font-semibold text-brand hover:underline">
                        {{ copied ? 'Copied!' : 'Copy' }}</button>
                </div>
                <AppText tag="p" size="sm" color="muted" leading="relaxed" class="font-mono">{{ paper.citation }}
                </AppText>
            </div>
        </div>

        <!-- Download sidebar -->
        <aside class="lg:col-span-1">
            <div
                class="sticky top-24 rounded-2xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-6">
                <img v-if="paper.cover_image" :src="paper.cover_image" :alt="paper.title"
                    class="w-full rounded-xl mb-5" />
                <AppHeading tag="h3" font="prata" size="md" weight="bold" class="mb-4">Full paper</AppHeading>
                <dl class="space-y-2 mb-6">
                    <div v-if="paper.file_type" class="flex justify-between">
                        <AppText tag="dt" font="redhat" size="sm" color="muted">Format</AppText>
                        <AppText tag="dd" font="redhat" size="sm" weight="medium" uppercase>{{ paper.file_type }}
                        </AppText>
                    </div>
                    <div v-if="paper.file_size_label" class="flex justify-between">
                        <AppText tag="dt" font="redhat" size="sm" color="muted">Size</AppText>
                        <AppText tag="dd" font="redhat" size="sm" weight="medium">{{ paper.file_size_label }}</AppText>
                    </div>
                    <div class="flex justify-between">
                        <AppText tag="dt" font="redhat" size="sm" color="muted">Downloads</AppText>
                        <AppText tag="dd" font="redhat" size="sm" weight="medium">{{ paper.download_count ?? 0 }}
                        </AppText>
                    </div>
                </dl>
                <AppButton v-if="paper.file_path" :href="researchDownload(paper.slug).url" external variant="primary"
                    full-width>
                    <template #icon-left>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                        </svg>
                    </template>
                    Download Paper
                </AppButton>
                <AppText v-else tag="p" font="redhat" size="sm" color="muted">File coming soon.</AppText>
            </div>
        </aside>
    </div>

    <section v-if="related && related.length"
        class="container mx-auto px-4 py-12 border-t border-border-light dark:border-border-dark">
        <AppHeading tag="h2" font="prata" size="3xl" weight="semibold" class="mb-8">Related research</AppHeading>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <ResearchCard v-for="p in related" :key="p.id" :paper="p" :href="researchShow(p.slug).url" compact />
        </div>
    </section>
</template>
