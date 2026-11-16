
<script setup lang="ts">
import { computed, ref } from 'vue'
import { Head } from '@inertiajs/vue3'
import {
    ArrowUpRight,
    Check,
    Copy,
    ExternalLink,
    Users,
} from '@lucide/vue'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import Section from '@/components/ui/Section.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import ResearchCard from '@/components/research/ResearchCard.vue'

import { show as researchShow } from '@/routes/research'

import type {
    Research,
    ResearchEvidence,
    ResearchFinding,
    ResearchMethodology,
    ResearchQuestion,
    ResearchSource,
    SeoMetadata,
} from '@/types'

interface Contributor {
    id: number
    position: number
    is_primary: boolean
    contribution: string | null
    user: {
        id: number
        name: string
        username: string
        avatar: string | null
    }
    role: {
        id: number
        name: string
        slug: string
    }
}

type ResearchShowData = Research & {
    methodology?: ResearchMethodology | null
    sources?: ResearchSource[]
    questions?: ResearchQuestion[]
    findings?: ResearchFinding[]
    evidence?: ResearchEvidence[]
    participants?: Contributor[]
}

interface Props {
    research: {
        data: ResearchShowData
    }
    related?: Research[]
    meta_data?: {
        data: SeoMetadata | null
    }
}

const props = defineProps<Props>()

const copied = ref(false)

const data = computed(() => props.research.data)

const year = computed(() => {
    if (!data.value.published_at) return ''

    return new Date(data.value.published_at).getFullYear().toString()
})

const typeLabel = computed(() =>
    data.value.type?.label ?? data.value.type?.value ?? 'Research',
)

const methodology = computed(() => data.value.methodology ?? null)

const questions = computed(() =>
    [...(data.value.questions ?? [])].sort(
        (a, b) => a.position - b.position,
    ),
)

const findings = computed(() =>
    [...(data.value.findings ?? [])].sort(
        (a, b) => a.position - b.position,
    ),
)

const sources = computed(() =>
    [...(data.value.sources ?? [])].sort(
        (a, b) => a.position - b.position,
    ),
)

const evidence = computed(() =>
    [...(data.value.evidence ?? [])].sort(
        (a, b) => a.position - b.position,
    ),
)

const contributors = computed(() =>
    [...(data.value.participants ?? [])].sort(
        (a, b) => a.position - b.position,
    ),
)

const evidenceForFinding = (findingId: number) =>
    evidence.value.filter(item => item.finding_id === findingId)

const methodologyDetails = computed(() => {
    if (!methodology.value) return []

    const method = methodology.value

    return [
        { label: 'Research design', value: method.research_design },
        { label: 'Data collection', value: method.data_collection_method },
        { label: 'Sample size', value: method.sample_size },
        { label: 'Population', value: method.population },
        { label: 'Geography', value: method.geography },
    ].filter(item =>
        item.value !== null &&
        item.value !== undefined &&
        item.value !== '',
    )
})

const formatDate = (date?: string | null) => {
    if (!date) return ''

    const parsed = new Date(date)

    if (Number.isNaN(parsed.getTime())) return ''

    return parsed.toLocaleDateString('en-IN', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    })
}

const citation = computed(() => {
    const publishedYear = year.value ? `(${year.value}). ` : ''

    return `${publishedYear}${data.value.title}. Alfrik Research.`
})

const copyCitation = async () => {
    try {
        await navigator.clipboard.writeText(citation.value)
        copied.value = true

        window.setTimeout(() => {
            copied.value = false
        }, 2000)
    } catch {
        copied.value = false
    }
}

const researchSections = computed(() => [
    {
        id: 'overview',
        label: 'Overview',
        visible: Boolean(data.value.summary || data.value.description),
    },
    {
        id: 'contributors',
        label: 'Contributors',
        visible: contributors.value.length > 0,
    },
    {
        id: 'questions',
        label: 'Research questions',
        visible: questions.value.length > 0,
    },
    {
        id: 'findings',
        label: 'Key findings',
        visible: findings.value.length > 0,
    },
    {
        id: 'methodology',
        label: 'Methodology',
        visible: Boolean(methodology.value),
    },
    {
        id: 'sources',
        label: 'Sources',
        visible: sources.value.length > 0,
    },
    {
        id: 'citation',
        label: 'Citation',
        visible: true,
    },
].filter(item => item.visible))

const pageDescription = computed(() =>
    props.meta_data?.data?.description ??
    data.value.summary ??
    data.value.description ??
    '',
)
</script>

<template>
    <Head :title="meta_data?.data?.title ?? data.title">
        <meta
            name="description"
            :content="pageDescription"
        />

        <link
            v-if="meta_data?.data?.canonical_url"
            rel="canonical"
            :href="meta_data.data.canonical_url"
        />

        <meta
            v-if="meta_data?.data?.og_title"
            property="og:title"
            :content="meta_data.data.og_title"
        />

        <meta
            v-if="meta_data?.data?.og_description"
            property="og:description"
            :content="meta_data.data.og_description"
        />

        <meta
            v-if="meta_data?.data?.og_type"
            property="og:type"
            :content="meta_data.data.og_type"
        />

        <meta
            v-if="meta_data?.data?.og_image_url"
            property="og:image"
            :content="meta_data.data.og_image_url"
        />

        <meta
            v-if="meta_data?.data?.twitter_card"
            name="twitter:card"
            :content="meta_data.data.twitter_card"
        />

        <meta
            v-if="meta_data?.data?.twitter_title"
            name="twitter:title"
            :content="meta_data.data.twitter_title"
        />

        <meta
            v-if="meta_data?.data?.twitter_description"
            name="twitter:description"
            :content="meta_data.data.twitter_description"
        />

        <meta
            v-if="meta_data?.data?.twitter_image_url"
            name="twitter:image"
            :content="meta_data.data.twitter_image_url"
        />
    </Head>

    <!-- Publication masthead -->
    <header class="border-b border-border bg-surface">
        <div class="container mx-auto px-4 py-14 sm:py-20 lg:py-24">
            <div class="max-w-5xl">
                <div class="mb-8 flex flex-wrap items-center gap-3">
                    <Badge variant="secondary">
                        {{ typeLabel }}
                    </Badge>

                    <AppText
                        v-if="year"
                        tag="span"
                        size="xs"
                        color="muted"
                        weight="medium"
                    >
                        Published {{ year }}
                    </AppText>

                    <Badge
                        v-if="data.featured"
                        variant="secondary"
                        class="border-gold/30 bg-gold-soft text-gold"
                    >
                        Featured research
                    </Badge>
                </div>

                <AppText
                    tag="p"
                    size="xs"
                    weight="bold"
                    tracking="wide"
                    uppercase
                    color="brand"
                    class="mb-5"
                >
                    Alfrik Research / Research publication
                </AppText>

                <AppHeading
                    tag="h1"
                    size="4xl"
                    weight="normal"
                    leading="tight"
                    class="max-w-5xl font-heading sm:text-5xl lg:text-6xl"
                >
                    {{ data.title }}
                </AppHeading>

                <AppText
                    v-if="data.subtitle"
                    tag="p"
                    size="xl"
                    color="muted"
                    leading="relaxed"
                    class="mt-7 max-w-4xl font-editorial sm:text-2xl"
                >
                    {{ data.subtitle }}
                </AppText>

                <div class="mt-9 flex flex-wrap items-center gap-x-8 gap-y-4 border-t border-border pt-6">
                    <div
                        v-if="year"
                        class="flex flex-col gap-1"
                    >
                        <AppText
                            tag="span"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="muted"
                        >
                            Publication year
                        </AppText>

                        <AppText
                            tag="span"
                            size="sm"
                            weight="semibold"
                        >
                            {{ year }}
                        </AppText>
                    </div>

                    <div class="flex flex-col gap-1">
                        <AppText
                            tag="span"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="muted"
                        >
                            Research type
                        </AppText>

                        <AppText
                            tag="span"
                            size="sm"
                            weight="semibold"
                        >
                            {{ typeLabel }}
                        </AppText>
                    </div>

                    <div
                        v-if="contributors.length"
                        class="flex flex-col gap-1"
                    >
                        <AppText
                            tag="span"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="muted"
                        >
                            Contributors
                        </AppText>

                        <AppText
                            tag="span"
                            size="sm"
                            weight="semibold"
                        >
                            {{ contributors.length }}
                        </AppText>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main publication content -->
    <main class="container mx-auto px-4 py-12 sm:py-16 lg:py-20">
        <div class="grid items-start gap-12 lg:grid-cols-[minmax(0,1fr)_280px] lg:gap-16">

            <!-- Main article -->
            <article class="min-w-0 space-y-12">

                <!-- Overview -->
                <Section
                    v-if="data.summary || data.description"
                    id="overview"
                    padding="none"
                    spacing="lg"
                >
                    <div class="flex items-center gap-3">
                        <AppText
                            tag="span"
                            size="lg"
                            font="lora"
                            color="brand"
                        >
                            01
                        </AppText>

                        <AppText
                            tag="span"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="muted"
                        >
                            Research overview
                        </AppText>
                    </div>

                    <div
                        v-if="data.summary"
                        class="border-l-[3px] border-brand bg-brand-soft px-6 py-7 sm:px-8"
                    >
                        <AppText
                            tag="p"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="brand"
                            class="mb-3"
                        >
                            Executive summary
                        </AppText>

                        <AppText
                            tag="p"
                            size="xl"
                            leading="relaxed"
                            class="font-editorial text-foreground sm:text-2xl"
                        >
                            {{ data.summary }}
                        </AppText>
                    </div>

                    <div
                        v-if="data.description"
                        class="max-w-3xl"
                    >
                        <AppHeading
                            tag="h2"
                            size="2xl"
                            weight="normal"
                            class="mb-4 font-heading sm:text-3xl"
                        >
                            About this research
                        </AppHeading>

                        <AppText
                            tag="p"
                            size="md"
                            color="muted"
                            leading="relaxed"
                            class="whitespace-pre-line reading-text"
                        >
                            {{ data.description }}
                        </AppText>
                    </div>
                </Section>

                <!-- Contributors -->
                <Section
                    v-if="contributors.length"
                    id="contributors"
                    padding="lg"
                    spacing="lg"
                    background="paper"
                    radius="md"
                >
                    <div class="flex flex-wrap items-end justify-between gap-4">
                        <div>
                            <AppText
                                tag="p"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="brand"
                                class="mb-3"
                            >
                                Research team
                            </AppText>

                            <AppHeading
                                tag="h2"
                                size="2xl"
                                weight="normal"
                                class="font-heading sm:text-3xl"
                            >
                                Contributors
                            </AppHeading>

                            <AppText
                                tag="p"
                                size="sm"
                                color="muted"
                                class="mt-2 max-w-2xl"
                            >
                                The people who contributed to the development of this publication.
                            </AppText>
                        </div>

                        <div class="flex items-center gap-2">
                            <Users class="size-4 text-muted-foreground" />

                            <AppText
                                tag="span"
                                size="sm"
                                color="muted"
                            >
                                {{ contributors.length }} contributors
                            </AppText>
                        </div>
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <article
                            v-for="contributor in contributors"
                            :key="contributor.id"
                            class="flex gap-4 border border-border bg-surface p-4"
                        >
                            <div class="size-14 shrink-0 overflow-hidden rounded-full bg-muted">
                                <img
                                    v-if="contributor.user.avatar"
                                    :src="contributor.user.avatar"
                                    :alt="contributor.user.name"
                                    class="size-full object-cover"
                                    loading="lazy"
                                />

                                <div
                                    v-else
                                    class="flex size-full items-center justify-center"
                                >
                                    <AppText
                                        tag="span"
                                        size="xl"
                                        weight="semibold"
                                        color="brand"
                                    >
                                        {{ contributor.user.name?.charAt(0)?.toUpperCase() }}
                                    </AppText>
                                </div>
                            </div>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-2">
                                    <AppText
                                        tag="p"
                                        size="sm"
                                        weight="semibold"
                                    >
                                        {{ contributor.user.name }}
                                    </AppText>

                                    <Badge
                                        v-if="contributor.is_primary"
                                        variant="secondary"
                                    >
                                        Lead
                                    </Badge>
                                </div>

                                <AppText
                                    tag="p"
                                    size="xs"
                                    weight="semibold"
                                    uppercase
                                    color="brand"
                                    class="mt-1"
                                >
                                    {{ contributor.role.name }}
                                </AppText>

                                <AppText
                                    v-if="contributor.contribution"
                                    tag="p"
                                    size="sm"
                                    color="muted"
                                    leading="relaxed"
                                    class="mt-3"
                                >
                                    {{ contributor.contribution }}
                                </AppText>
                            </div>
                        </article>
                    </div>
                </Section>

                <!-- Research questions -->
                <Section
                    v-if="questions.length"
                    id="questions"
                    padding="lg"
                    spacing="lg"
                    border="default"
                >
                    <div>
                        <AppText
                            tag="p"
                            size="xs"
                            weight="bold"
                            color="brand"
                            tracking="wide"
                            uppercase
                            class="mb-3"
                        >
                            02 / Research framework
                        </AppText>

                        <AppHeading
                            tag="h2"
                            size="2xl"
                            weight="normal"
                            class="font-heading sm:text-3xl"
                        >
                            Research questions
                        </AppHeading>

                        <AppText
                            tag="p"
                            size="sm"
                            color="muted"
                            class="mt-2"
                        >
                            The questions that define the scope and direction of this research.
                        </AppText>
                    </div>

                    <div class="divide-y divide-border">
                        <article
                            v-for="(question, index) in questions"
                            :key="question.id"
                            class="flex gap-5 py-6 first:pt-0 last:pb-0"
                        >
                            <AppText
                                tag="span"
                                size="2xl"
                                color="brand"
                                font="lora"
                                class="shrink-0 pt-1"
                            >
                                {{ String(index + 1).padStart(2, '0') }}
                            </AppText>

                            <div class="min-w-0 flex-1">
                                <div class="mb-3">
                                    <Badge>
                                        {{ question.type.label }}
                                    </Badge>
                                </div>

                                <AppHeading
                                    tag="h3"
                                    size="xl"
                                    weight="normal"
                                    class="font-heading"
                                >
                                    {{ question.question }}
                                </AppHeading>

                                <AppText
                                    v-if="question.description"
                                    tag="p"
                                    size="sm"
                                    color="muted"
                                    leading="relaxed"
                                    class="mt-3 whitespace-pre-line"
                                >
                                    {{ question.description }}
                                </AppText>
                            </div>
                        </article>
                    </div>
                </Section>

                <!-- Findings -->
                <Section
                    v-if="findings.length"
                    id="findings"
                    padding="lg"
                    spacing="lg"
                    border="default"
                >
                    <div>
                        <AppText
                            tag="p"
                            size="xs"
                            weight="bold"
                            color="brand"
                            tracking="wide"
                            uppercase
                            class="mb-3"
                        >
                            03 / Research outcomes
                        </AppText>

                        <AppHeading
                            tag="h2"
                            size="2xl"
                            weight="normal"
                            class="font-heading sm:text-3xl"
                        >
                            Key findings
                        </AppHeading>

                        <AppText
                            tag="p"
                            size="sm"
                            color="muted"
                            class="mt-2"
                        >
                            The principal insights identified through the research.
                        </AppText>
                    </div>

                    <div class="space-y-9">
                        <article
                            v-for="(finding, index) in findings"
                            :key="finding.id"
                            class="border-b border-border pb-9 last:border-0 last:pb-0"
                        >
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <AppText
                                    tag="span"
                                    size="2xl"
                                    color="brand"
                                    font="lora"
                                >
                                    {{ String(index + 1).padStart(2, '0') }}
                                </AppText>

                                <div class="flex flex-wrap items-center gap-2">
                                    <Badge>
                                        {{ finding.type.label }}
                                    </Badge>

                                    <AppText
                                        v-if="finding.confidence !== null && finding.confidence !== undefined"
                                        tag="span"
                                        size="xs"
                                        color="muted"
                                    >
                                        {{ finding.confidence }}% confidence
                                    </AppText>
                                </div>
                            </div>

                            <AppHeading
                                tag="h3"
                                size="xl"
                                weight="normal"
                                class="mt-4 font-heading sm:text-2xl"
                            >
                                {{ finding.title }}
                            </AppHeading>

                            <AppText
                                v-if="finding.summary"
                                tag="p"
                                size="lg"
                                leading="relaxed"
                                class="mt-3 font-editorial"
                            >
                                {{ finding.summary }}
                            </AppText>

                            <AppText
                                v-if="finding.description"
                                tag="p"
                                size="md"
                                color="muted"
                                leading="relaxed"
                                class="mt-3 whitespace-pre-line"
                            >
                                {{ finding.description }}
                            </AppText>

                            <!-- Evidence attached to this finding -->
                            <div
                                v-if="evidenceForFinding(finding.id).length"
                                class="mt-7"
                            >
                                <AppText
                                    tag="p"
                                    size="xs"
                                    weight="bold"
                                    uppercase
                                    tracking="wide"
                                    color="muted"
                                    class="mb-4"
                                >
                                    Supporting evidence
                                </AppText>

                                <div class="space-y-4">
                                    <div
                                        v-for="item in evidenceForFinding(finding.id)"
                                        :key="item.id"
                                        class="border-l-2 border-brand/30 pl-5"
                                    >
                                        <Badge>
                                            {{ item.type.label }}
                                        </Badge>

                                        <AppText
                                            v-if="item.title"
                                            tag="p"
                                            size="sm"
                                            weight="semibold"
                                            class="mt-2"
                                        >
                                            {{ item.title }}
                                        </AppText>

                                        <AppText
                                            v-if="item.description"
                                            tag="p"
                                            size="sm"
                                            color="muted"
                                            leading="relaxed"
                                            class="mt-2"
                                        >
                                            {{ item.description }}
                                        </AppText>

                                        <AppText
                                            v-if="item.citation"
                                            tag="p"
                                            size="xs"
                                            color="muted"
                                            leading="relaxed"
                                            class="mt-3"
                                        >
                                            {{ item.citation }}
                                        </AppText>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </Section>

                <!-- Methodology -->
                <Section
                    v-if="methodology"
                    id="methodology"
                    padding="lg"
                    spacing="lg"
                    border="default"
                >
                    <div>
                        <AppText
                            tag="p"
                            size="xs"
                            weight="bold"
                            color="brand"
                            tracking="wide"
                            uppercase
                            class="mb-3"
                        >
                            04 / Research approach
                        </AppText>

                        <AppHeading
                            tag="h2"
                            size="2xl"
                            weight="normal"
                            class="font-heading sm:text-3xl"
                        >
                            Methodology
                        </AppHeading>
                    </div>

                    <div class="border-l-[3px] border-brand bg-surface-sunken px-6 py-6">
                        <AppText
                            tag="p"
                            size="xs"
                            weight="bold"
                            uppercase
                            color="muted"
                            class="mb-2"
                        >
                            Research method
                        </AppText>

                        <AppHeading
                            tag="h3"
                            size="xl"
                            weight="normal"
                            class="font-heading"
                        >
                            {{ methodology.method }}
                        </AppHeading>

                        <AppText
                            v-if="methodology.description"
                            tag="p"
                            size="sm"
                            color="muted"
                            leading="relaxed"
                            class="mt-3 whitespace-pre-line"
                        >
                            {{ methodology.description }}
                        </AppText>
                    </div>

                    <dl
                        v-if="methodologyDetails.length || methodology.start_date || methodology.end_date"
                        class="divide-y divide-border border-y border-border"
                    >
                        <div
                            v-for="item in methodologyDetails"
                            :key="item.label"
                            class="flex flex-col gap-2 py-4 sm:flex-row sm:justify-between sm:gap-8"
                        >
                            <AppText
                                tag="dt"
                                size="sm"
                                color="muted"
                            >
                                {{ item.label }}
                            </AppText>

                            <AppText
                                tag="dd"
                                size="sm"
                                weight="semibold"
                                class="sm:max-w-[65%] sm:text-right"
                            >
                                {{ item.value }}
                            </AppText>
                        </div>

                        <div
                            v-if="methodology.start_date || methodology.end_date"
                            class="flex flex-col gap-2 py-4 sm:flex-row sm:justify-between sm:gap-8"
                        >
                            <AppText
                                tag="dt"
                                size="sm"
                                color="muted"
                            >
                                Study period
                            </AppText>

                            <AppText
                                tag="dd"
                                size="sm"
                                weight="semibold"
                                class="sm:text-right"
                            >
                                {{ formatDate(methodology.start_date) || '—' }}

                                <template v-if="methodology.start_date && methodology.end_date">
                                    —
                                </template>

                                {{ formatDate(methodology.end_date) || '—' }}
                            </AppText>
                        </div>
                    </dl>

                    <div
                        v-if="methodology.limitations"
                        class="bg-warning/10 px-5 py-5"
                    >
                        <AppText
                            tag="p"
                            size="xs"
                            weight="bold"
                            uppercase
                            color="warning"
                            class="mb-2"
                        >
                            Research limitations
                        </AppText>

                        <AppText
                            tag="p"
                            size="sm"
                            color="muted"
                            leading="relaxed"
                            class="whitespace-pre-line"
                        >
                            {{ methodology.limitations }}
                        </AppText>
                    </div>
                </Section>

                <!-- Sources -->
                <Section
                    v-if="sources.length"
                    id="sources"
                    padding="lg"
                    spacing="lg"
                    border="default"
                >
                    <div>
                        <AppText
                            tag="p"
                            size="xs"
                            weight="bold"
                            color="brand"
                            tracking="wide"
                            uppercase
                            class="mb-3"
                        >
                            05 / References
                        </AppText>

                        <AppHeading
                            tag="h2"
                            size="2xl"
                            weight="normal"
                            class="font-heading sm:text-3xl"
                        >
                            Sources & references
                        </AppHeading>

                        <AppText
                            tag="p"
                            size="sm"
                            color="muted"
                            class="mt-2"
                        >
                            Publications and materials referenced in this research.
                        </AppText>
                    </div>

                    <div class="divide-y divide-border">
                        <article
                            v-for="(source, index) in sources"
                            :key="source.id"
                            class="flex gap-5 py-6 first:pt-0 last:pb-0"
                        >
                            <AppText
                                tag="span"
                                size="xl"
                                color="brand"
                                font="lora"
                                class="shrink-0 pt-1"
                            >
                                {{ String(index + 1).padStart(2, '0') }}
                            </AppText>

                            <div class="min-w-0 flex-1">
                                <div class="flex flex-wrap items-center gap-3">
                                    <Badge>
                                        {{ source.source_type.label }}
                                    </Badge>

                                    <AppText
                                        v-if="source.published_at"
                                        tag="span"
                                        size="xs"
                                        color="muted"
                                    >
                                        {{ formatDate(source.published_at) }}
                                    </AppText>
                                </div>

                                <AppHeading
                                    tag="h3"
                                    size="lg"
                                    weight="normal"
                                    class="mt-3 font-heading"
                                >
                                    {{ source.title }}
                                </AppHeading>

                                <AppText
                                    v-if="source.publisher"
                                    tag="p"
                                    size="sm"
                                    weight="medium"
                                    color="muted"
                                    class="mt-2"
                                >
                                    {{ source.publisher }}
                                </AppText>

                                <AppText
                                    v-if="source.description"
                                    tag="p"
                                    size="sm"
                                    color="muted"
                                    leading="relaxed"
                                    class="mt-3"
                                >
                                    {{ source.description }}
                                </AppText>

                                <AppText
                                    v-if="source.citation"
                                    tag="p"
                                    size="xs"
                                    color="muted"
                                    leading="relaxed"
                                    class="mt-3"
                                >
                                    {{ source.citation }}
                                </AppText>

                                <a
                                    v-if="source.url"
                                    :href="source.url"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    class="mt-4 inline-flex items-center gap-2 text-brand transition hover:opacity-70"
                                >
                                    <AppText
                                        tag="span"
                                        size="sm"
                                        weight="semibold"
                                        color="brand"
                                    >
                                        View original source
                                    </AppText>

                                    <ExternalLink class="size-4" />
                                </a>
                            </div>
                        </article>
                    </div>
                </Section>

                <!-- Citation -->
                <Section
                    id="citation"
                    padding="lg"
                    spacing="md"
                    background="soft"
                    radius="md"
                >
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <AppText
                                tag="p"
                                size="xs"
                                weight="bold"
                                tracking="wide"
                                uppercase
                                color="brand"
                                class="mb-2"
                            >
                                Cite this publication
                            </AppText>

                            <AppHeading
                                tag="h2"
                                size="xl"
                                weight="normal"
                                class="font-heading sm:text-2xl"
                            >
                                How to cite
                            </AppHeading>
                        </div>

                        <button
                            type="button"
                            class="inline-flex items-center gap-2 border border-border bg-surface px-4 py-2.5 transition hover:border-brand hover:text-brand"
                            @click="copyCitation"
                        >
                            <Check
                                v-if="copied"
                                class="size-4"
                            />

                            <Copy
                                v-else
                                class="size-4"
                            />

                            <AppText
                                tag="span"
                                size="sm"
                                weight="semibold"
                            >
                                {{ copied ? 'Copied' : 'Copy citation' }}
                            </AppText>
                        </button>
                    </div>

                    <AppText
                        tag="p"
                        size="lg"
                        color="muted"
                        leading="relaxed"
                        class="font-editorial"
                    >
                        {{ citation }}
                    </AppText>
                </Section>
            </article>

            <!-- Sidebar -->
            <aside class="min-w-0">
                <div class="space-y-6 lg:sticky lg:top-24">

                    <!-- Table of contents -->
                    <div class="border border-border bg-surface p-5">
                        <AppText
                            tag="p"
                            size="xs"
                            weight="bold"
                            tracking="wide"
                            uppercase
                            color="brand"
                            class="mb-4"
                        >
                            In this report
                        </AppText>

                        <nav class="space-y-1">
                            <a
                                v-for="(item, index) in researchSections"
                                :key="item.id"
                                :href="`#${item.id}`"
                                class="group flex items-center gap-3 px-3 py-2.5 transition hover:bg-brand-soft"
                            >
                                <AppText
                                    tag="span"
                                    size="sm"
                                    color="brand"
                                    font="lora"
                                >
                                    {{ String(index + 1).padStart(2, '0') }}
                                </AppText>

                                <AppText
                                    tag="span"
                                    size="sm"
                                    color="muted"
                                    class="flex-1 group-hover:text-brand"
                                >
                                    {{ item.label }}
                                </AppText>

                                <ArrowUpRight class="size-3.5 text-brand opacity-0 transition group-hover:opacity-100" />
                            </a>
                        </nav>
                    </div>

                    <!-- Publication details -->
                    <div class="border border-border bg-surface p-5">
                        <AppText
                            tag="p"
                            size="xs"
                            weight="bold"
                            tracking="wide"
                            uppercase
                            color="brand"
                            class="mb-4"
                        >
                            Publication details
                        </AppText>

                        <dl class="divide-y divide-border">
                            <div class="flex justify-between gap-4 py-3">
                                <AppText
                                    tag="dt"
                                    size="xs"
                                    color="muted"
                                >
                                    Type
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="xs"
                                    weight="semibold"
                                    class="text-right"
                                >
                                    {{ typeLabel }}
                                </AppText>
                            </div>

                            <div
                                v-if="year"
                                class="flex justify-between gap-4 py-3"
                            >
                                <AppText
                                    tag="dt"
                                    size="xs"
                                    color="muted"
                                >
                                    Published
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="xs"
                                    weight="semibold"
                                >
                                    {{ year }}
                                </AppText>
                            </div>

                            <div
                                v-for="item in [
                                    { label: 'Questions', value: questions.length },
                                    { label: 'Findings', value: findings.length },
                                    { label: 'Sources', value: sources.length },
                                    { label: 'Evidence', value: evidence.length },
                                    { label: 'Contributors', value: contributors.length },
                                ]"
                                :key="item.label"
                                class="flex justify-between gap-4 py-3"
                            >
                                <AppText
                                    tag="dt"
                                    size="xs"
                                    color="muted"
                                >
                                    {{ item.label }}
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="xs"
                                    weight="semibold"
                                    class="tabular-nums"
                                >
                                    {{ item.value }}
                                </AppText>
                            </div>
                        </dl>
                    </div>

                    <!-- Contributor summary -->
                    <div
                        v-if="contributors.length"
                        class="border border-border bg-surface p-5"
                    >
                        <div class="flex items-center justify-between gap-3">
                            <AppText
                                tag="p"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="brand"
                            >
                                Research contributors
                            </AppText>

                            <Users class="size-4 text-muted-foreground" />
                        </div>

                        <div class="mt-5 space-y-4">
                            <div
                                v-for="contributor in contributors"
                                :key="contributor.id"
                                class="flex items-center gap-3"
                            >
                                <div class="size-9 shrink-0 overflow-hidden rounded-full bg-muted">
                                    <img
                                        v-if="contributor.user.avatar"
                                        :src="contributor.user.avatar"
                                        :alt="contributor.user.name"
                                        class="size-full object-cover"
                                        loading="lazy"
                                    />

                                    <div
                                        v-else
                                        class="flex size-full items-center justify-center"
                                    >
                                        <AppText
                                            tag="span"
                                            size="sm"
                                            weight="semibold"
                                            color="brand"
                                        >
                                            {{ contributor.user.name?.charAt(0)?.toUpperCase() }}
                                        </AppText>
                                    </div>
                                </div>

                                <div class="min-w-0">
                                    <AppText
                                        tag="p"
                                        size="sm"
                                        weight="semibold"
                                        class="truncate"
                                    >
                                        {{ contributor.user.name }}
                                    </AppText>

                                    <AppText
                                        tag="p"
                                        size="xs"
                                        color="muted"
                                        class="mt-0.5 truncate"
                                    >
                                        {{ contributor.role.name }}
                                    </AppText>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <!-- Related research -->
    <Section
        v-if="related?.length"
        padding="xl"
        spacing="lg"
        background="paper"
        border="default"
    >
        <div class="container mx-auto px-4">
            <div class="mb-9 flex flex-wrap items-end justify-between gap-5">
                <div class="max-w-2xl">
                    <AppText
                        tag="p"
                        size="xs"
                        weight="bold"
                        tracking="wide"
                        uppercase
                        color="brand"
                        class="mb-3"
                    >
                        Continue exploring
                    </AppText>

                    <AppHeading
                        tag="h2"
                        size="3xl"
                        weight="normal"
                        class="font-heading"
                    >
                        Related research
                    </AppHeading>

                    <AppText
                        tag="p"
                        size="sm"
                        color="muted"
                        class="mt-3"
                    >
                        Explore more research and insights from Alfrik.
                    </AppText>
                </div>

                <a
                    href="/research"
                    class="inline-flex items-center gap-2"
                >
                    <AppText
                        tag="span"
                        size="sm"
                        weight="semibold"
                        color="brand"
                    >
                        Explore research
                    </AppText>

                    <ArrowUpRight class="size-4 text-brand" />
                </a>
            </div>

            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <ResearchCard
                    v-for="item in related"
                    :key="item.id"
                    :paper="item"
                    :href="researchShow(item.slug).url"
                    compact
                />
            </div>
        </div>
    </Section>
</template>
