<script setup lang="ts">
import { computed, ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import { Copy, Check, Download, ExternalLink } from '@lucide/vue'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import ResearchCard from '@/components/research/ResearchCard.vue'
import { Button } from '@/components/ui/button'

import {
    show as researchShow,
    download as researchDownload,
} from '@/routes/research'

import type { ResearchPaper } from '@/types'
import { MetaInfo } from '@/Components/Ui/MetaInfo'
import { Badge } from '@/components/ui/badge'

const props = defineProps<{
    paper: ResearchPaper
    related?: ResearchPaper[]
    meta_data?: {
        meta_title?: string
        meta_description?: string
        meta_keywords?: string
    }
}>()

/*
|--------------------------------------------------------------------------
| Computed
|--------------------------------------------------------------------------
*/

const year = computed(() =>
    props.paper.published_at
        ? new Date(props.paper.published_at).getFullYear()
        : '',
)

const keywordList = computed(() =>
    props.paper.keywords
        ? props.paper.keywords
              .split(',')
              .map((keyword) => keyword.trim())
              .filter(Boolean)
        : [],
)

/*
|--------------------------------------------------------------------------
| Citation
|--------------------------------------------------------------------------
*/

const copied = ref(false)

async function copyCitation() {
    if (!props.paper.citation) {
        return
    }

    try {
        await navigator.clipboard.writeText(props.paper.citation)

        copied.value = true

        setTimeout(() => {
            copied.value = false
        }, 2000)
    } catch {
        copied.value = false
    }
}

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

const cover = computed(
    () => props.paper.cover_image || '/frontend/images/placeholder.jpg',
)
</script>

<template>
    <Head :title="meta_data?.meta_title || paper.title">
        <meta
            name="description"
            :content="meta_data?.meta_description ?? paper.abstract ?? ''"
        />

        <meta
            name="keywords"
            :content="meta_data?.meta_keywords ?? paper.keywords ?? ''"
        />
    </Head>

    <!--
    |--------------------------------------------------------------------------
    | Article Header
    |--------------------------------------------------------------------------
    -->

    <header class="border-b border-border-light dark:border-border-dark">
        <div class="container mx-auto px-4 pb-10 pt-10 sm:pb-14 sm:pt-14">
            <!-- Area -->

            <AppText
                v-if="paper.area"
                tag="p"
                size="xs"
                weight="bold"
                tracking="wide"
                uppercase
                color="primary"
                class="mb-4"
            >
                {{ paper.area.name }}
            </AppText>

            <!-- Title -->

            <AppHeading
                tag="h1"
                font="prata"
                size="4xl"
                weight="normal"
                leading="tight"
                class="max-w-5xl"
            >
                {{ paper.title }}
            </AppHeading>

            <!-- Authors -->

            <AppText
                v-if="paper.authors"
                tag="p"
                font="lora"
                size="lg"
                color="muted"
                class="mt-5 italic"
            >
                {{ paper.authors }}
            </AppText>

            <!-- Metadata -->

            <div class="mt-5">
                <MetaInfo 
                :items="[
                    {
                        label: 'By',
                        value: `${paper.institution}`,
                    },
                    {
                        value: String(year),
                    },
                    {
                        value: 'DOI',
                        icon: ExternalLink,
                        href: `https://doi.org/${paper.doi}`,
                    },
                ]" />
            </div>
        </div>
    </header>

    <!--
    |--------------------------------------------------------------------------
    | Main Content
    |--------------------------------------------------------------------------
    -->

    <main class="container mx-auto px-4 py-10 sm:py-14">
        <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_320px] lg:gap-16">
            <!-- Article -->

            <article class="min-w-0">
                <!-- Cover -->

                <div
                    v-if="paper.cover_image"
                    class="mb-10 overflow-hidden"
                >
                    <img
                        :src="cover"
                        :alt="paper.title"
                        class="h-auto w-full object-cover"
                    />
                </div>

                <!-- Abstract -->

                <section
                    v-if="paper.abstract"
                    class="border-b border-border-light pb-10 dark:border-border-dark"
                >
                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="2xl"
                        weight="normal"
                        class="mb-5"
                    >
                        Abstract
                    </AppHeading>

                    <AppText
                        tag="p"
                        font="lora"
                        size="lg"
                        color="muted"
                        leading="relaxed"
                        class="whitespace-pre-line"
                    >
                        {{ paper.abstract }}
                    </AppText>
                </section>

                <!-- Methodology -->

                <section
                    v-if="paper.methodology"
                    class="border-b border-border-light py-10 dark:border-border-dark"
                >
                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="2xl"
                        weight="normal"
                        class="mb-5"
                    >
                        Methodology
                    </AppHeading>

                    <AppText
                        tag="p"
                        font="lora"
                        size="lg"
                        color="muted"
                        leading="relaxed"
                        class="whitespace-pre-line"
                    >
                        {{ paper.methodology }}
                    </AppText>
                </section>

                <!-- Keywords -->

                <section
                    v-if="keywordList.length"
                    class="border-b border-border-light py-8 dark:border-border-dark"
                >
                    <AppText
                        tag="p"
                        size="xs"
                        weight="bold"
                        tracking="wide"
                        uppercase
                        color="muted"
                        class="mb-4"
                    >
                        Keywords
                    </AppText>

                    <div class="flex flex-wrap gap-2">
                        <Badge
                            v-for="(keyword, index) in keywordList"
                            :key="index"
                            variant="default"
                            size="sm"
                        >
                            {{ keyword }}
                        </Badge>
                    </div>
                </section>

                <!-- Citation -->
                <section v-if="paper.citation" class="pt-10">
                    <div class="mb-4 flex items-center justify-between gap-4">
                        <AppHeading tag="h2" font="prata" size="xl" weight="normal">
                            How to cite
                        </AppHeading>

                        <button type="button"
                            class="inline-flex items-center gap-2 text-xs font-semibold cursor-pointer transition-colors hover:opacity-70"
                            @click="copyCitation">
                            <Check v-if="copied" class="h-4 w-4" />

                            <Copy v-else class="h-4 w-4" />

                            {{ copied ? 'Copied' : 'Copy citation' }}
                        </button>
                    </div>

                    <div class="border-l-2 border-primary bg-surface-light px-5 py-4 dark:bg-surface-dark">
                        <AppText tag="p" size="sm" color="muted" leading="relaxed" class="break-words font-mono">
                            {{ paper.citation }}
                        </AppText>
                    </div>
                </section>
            </article>

            <!--
            |--------------------------------------------------------------------------
            | Sidebar
            |--------------------------------------------------------------------------
            -->

            <aside>
                <div
                    class="sticky top-24 border border-border-light bg-surface-light dark:border-border-dark dark:bg-surface-dark">
                    <!-- Cover -->
                    <div v-if="paper.cover_image" class="aspect-[4/3] overflow-hidden">
                        <img :src="paper.cover_image" :alt="paper.title" class="h-full w-full object-cover" />
                    </div>

                    <div class="p-6">
                        <!-- Label -->

                        <AppText tag="p" size="xs" weight="bold" tracking="wide" uppercase color="primary" class="mb-3">
                            Research Paper
                        </AppText>

                        <AppHeading tag="h2" font="prata" size="xl" weight="normal" leading="tight" class="mb-6">
                            Full paper
                        </AppHeading>

                        <!-- Details -->
                        <dl
                            class="divide-y divide-border-light border-y border-border-light dark:divide-border-dark dark:border-border-dark">
                            <div v-if="paper.file_type" class="flex items-center justify-between py-3">
                                <AppText tag="dt" size="xs" color="muted">
                                    Format
                                </AppText>

                                <AppText tag="dd" size="xs" weight="semibold" uppercase>
                                    {{ paper.file_type }}
                                </AppText>
                            </div>

                            <div v-if="paper.file_size_label" class="flex items-center justify-between py-3">
                                <AppText tag="dt" size="xs" color="muted">
                                    Size
                                </AppText>

                                <AppText tag="dd" size="xs" weight="semibold">
                                    {{ paper.file_size_label }}
                                </AppText>
                            </div>

                            <div class="flex items-center justify-between py-3">
                                <AppText tag="dt" size="xs" color="muted">
                                    Downloads
                                </AppText>

                                <AppText tag="dd" size="xs" weight="semibold">
                                    {{ paper.download_count ?? 0 }}
                                </AppText>
                            </div>
                        </dl>

                        <!-- Download -->

                        <div class="mt-6">
                            <Button variant="outline" v-if="paper.file_path" as-child size="lg" class="w-full">
                                <a :href="researchDownload(paper.slug).url" target="_blank" rel="noopener noreferrer">
                                    <Download class="h-4 w-4" />
                                    Download Paper
                                </a>
                            </Button>

                            <AppText v-else tag="p" size="sm" color="muted">
                                The full paper will be available soon.
                            </AppText>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <!--
    |--------------------------------------------------------------------------
    | Related Research
    |--------------------------------------------------------------------------
    -->

    <section
        v-if="related?.length"
        class="border-t border-border-light dark:border-border-dark"
    >
        <div class="container mx-auto px-4 py-12 sm:py-14">
            <div class="mb-8">
                <AppText
                    tag="p"
                    size="xs"
                    weight="bold"
                    tracking="wide"
                    uppercase
                    color="primary"
                    class="mb-3"
                >
                    Continue Reading
                </AppText>

                <AppHeading
                    tag="h2"
                    font="prata"
                    size="3xl"
                    weight="normal"
                >
                    Related Research
                </AppHeading>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <ResearchCard
                    v-for="relatedPaper in related"
                    :key="relatedPaper.id"
                    :paper="relatedPaper"
                    :href="researchShow(relatedPaper.slug).url"
                    compact
                />
            </div>
        </div>
    </section>
</template>