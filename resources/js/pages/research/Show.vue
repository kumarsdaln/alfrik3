<script setup lang="ts">
    import { computed, ref } from 'vue'
    import { Head } from '@inertiajs/vue3'
    import {
        Check,
        Copy,
        ExternalLink,
    } from '@lucide/vue'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import ResearchCard from '@/components/research/ResearchCard.vue'
    import { MetaInfo } from '@/components/ui/metainfo'
    import Badge from '@/components/ui/badge/Badge.vue'

    import { show as researchShow } from '@/routes/research'

    import type {
        Research,
        ResearchEvidence,
        ResearchFinding,
        ResearchMember,
        ResearchMethodology,
        ResearchQuestion,
        ResearchSource,
        SeoMetadata,
    } from '@/types'

    type ResearchQuestionWithFindings = ResearchQuestion & {
        findings?: ResearchFinding[]
    }

    type ResearchShowData = Research & {
        methodology?: ResearchMethodology | null
        sources?: ResearchSource[]
        questions?: ResearchQuestionWithFindings[]
        findings?: ResearchFinding[]
        evidence?: ResearchEvidence[]
        members?: ResearchMember[]
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
    console.log('props', props.research)

    const copied = ref(false)

    const year = computed(() => {
        if (!props.research.data.published_at) {
            return ''
        }

        return String(
            new Date(props.research.data.published_at).getFullYear(),
        )
    })

    const typeLabel = computed(() => {
        return (
            props.research.data.type?.label ??
            props.research.data.type?.value ??
            ''
        )
    })

    const methodology = computed(() => {
        return props.research.data.methodology ?? null
    })

    const questions = computed(() => {
        return [...(props.research.data.questions ?? [])].sort(
            (a, b) => a.position - b.position,
        )
    })

    const findings = computed(() => {
        return [...(props.research.data.findings ?? [])].sort(
            (a, b) => a.position - b.position,
        )
    })

    const sources = computed(() => {
        return [...(props.research.data.sources ?? [])].sort(
            (a, b) => a.position - b.position,
        )
    })

    const evidence = computed(() => {
        return [...(props.research.data.evidence ?? [])].sort(
            (a, b) => a.position - b.position,
        )
    })

    const members = computed(() => {
        return props.research.data.members ?? []
    })

    const evidenceForFinding = (findingId: number) => {
        return evidence.value.filter(
            (item) => item.finding_id === findingId,
        )
    }

    const findingsForQuestion = (
        question: ResearchQuestionWithFindings,
    ) => {
        if (question.findings?.length) {
            return [...question.findings].sort(
                (a, b) => a.position - b.position,
            )
        }

        return findings.value.filter((finding) => {
            return question.findings?.some(
                (item) => item.id === finding.id,
            )
        })
    }

    const formatDate = (date?: string | null) => {
        if (!date) {
            return ''
        }

        return new Date(date).toLocaleDateString(undefined, {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
        })
    }

    const citation = computed(() => {
        const author = props.research.data.author?.name
            ? `${props.research.data.author.name}.`
            : ''

        const publishedYear = year.value
            ? `(${year.value}).`
            : ''

        return [
            author,
            publishedYear,
            `${props.research.data.title}.`,
            'Alfrik Research.',
        ]
            .filter(Boolean)
            .join(' ')
    })

    const copyCitation = async () => {
        try {
            await navigator.clipboard.writeText(
                citation.value,
            )

            copied.value = true

            setTimeout(() => {
                copied.value = false
            }, 2000)
        } catch {
            copied.value = false
        }
    }
</script>

<template>
    <Head :title="meta_data?.data?.title ?? research.data.title">
        <meta
            name="description"
            :content="
                meta_data?.data?.description ??
                research.data.summary ??
                research.data.description ??
                ''
            "
        />

        <link
            v-if="meta_data?.data?.canonical_url"
            rel="canonical"
            :href="meta_data?.data?.canonical_url"
        />

        <meta
            v-if="meta_data?.data?.og_title"
            property="og:title"
            :content="meta_data?.data?.og_title"
        />

        <meta
            v-if="meta_data?.data?.og_description"
            property="og:description"
            :content="meta_data?.data?.og_description"
        />

        <meta
            v-if="meta_data?.data?.og_type"
            property="og:type"
            :content="meta_data?.data?.og_type"
        />

        <meta
            v-if="meta_data?.data?.og_image_url"
            property="og:image"
            :content="meta_data?.data?.og_image_url"
        />

        <meta
            v-if="meta_data?.data?.twitter_card"
            name="twitter:card"
            :content="meta_data?.data?.twitter_card"
        />

        <meta
            v-if="meta_data?.data?.twitter_title"
            name="twitter:title"
            :content="meta_data?.data?.twitter_title"
        />

        <meta
            v-if="meta_data?.data?.twitter_description"
            name="twitter:description"
            :content="meta_data?.data?.twitter_description"
        />

        <meta
            v-if="meta_data?.data?.twitter_image_url"
            name="twitter:image"
            :content="meta_data?.data?.twitter_image_url"
        />
    </Head>

    <!-- Research Header -->
    <header
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
                py-10
                sm:py-14
            "
        >
            <AppText
                v-if="typeLabel"
                tag="p"
                size="xs"
                weight="bold"
                tracking="wide"
                color="brand"
                uppercase
                class="mb-4"
            >
                {{ typeLabel }}
            </AppText>

            <AppHeading
                tag="h1"
                font="prata"
                size="4xl"
                weight="normal"
                leading="tight"
                class="max-w-5xl"
            >
                {{ research.data.title }}
            </AppHeading>

            <AppText
                v-if="research.data.subtitle"
                tag="p"
                font="lora"
                size="lg"
                color="muted"
                leading="relaxed"
                class="mt-5 max-w-4xl italic"
            >
                {{ research.data.subtitle }}
            </AppText>

            <div class="mt-6">
                <MetaInfo
                    :items="[
                        ...(research.data.author?.name
                            ? [
                                  {
                                      label: 'Author',
                                      value: research.data.author.name,
                                  },
                              ]
                            : []),
                        ...(year
                            ? [
                                  {
                                      label: 'Published',
                                      value: year,
                                  },
                              ]
                            : []),
                        ...(typeLabel
                            ? [
                                  {
                                      label: 'Research Type',
                                      value: typeLabel,
                                  },
                              ]
                            : []),
                    ]"
                />
            </div>
        </div>
    </header>

    <!-- Main -->
    <main class="container mx-auto px-4 py-10 sm:py-14">
        <div
            class="
                grid
                gap-12
                lg:grid-cols-[minmax(0,1fr)_300px]
                lg:gap-16
            "
        >
            <!-- Content -->
            <article class="min-w-0">
                <!-- Summary -->
                <section
                    v-if="research.data.summary"
                    class="
                        border-b
                        border-border-light
                        pb-10
                        dark:border-border-dark
                    "
                >
                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="2xl"
                        weight="normal"
                        class="mb-5"
                    >
                        Summary
                    </AppHeading>

                    <AppText
                        tag="p"
                        font="lora"
                        size="lg"
                        color="muted"
                        leading="relaxed"
                        class="whitespace-pre-line"
                    >
                        {{ research.data.summary }}
                    </AppText>
                </section>

                <!-- Description -->
                <section
                    v-if="research.data.description"
                    class="
                        border-b
                        border-border-light
                        py-10
                        dark:border-border-dark
                    "
                >
                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="2xl"
                        weight="normal"
                        class="mb-5"
                    >
                        About this Research
                    </AppHeading>

                    <AppText
                        tag="p"
                        font="lora"
                        size="lg"
                        color="muted"
                        leading="relaxed"
                        class="whitespace-pre-line"
                    >
                        {{ research.data.description }}
                    </AppText>
                </section>

                <!-- Methodology -->
                <section
                    v-if="methodology"
                    class="
                        border-b
                        border-border-light
                        py-10
                        dark:border-border-dark
                    "
                >
                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="2xl"
                        weight="normal"
                        class="mb-6"
                    >
                        Methodology
                    </AppHeading>

                    <div class="space-y-6">
                        <div>
                            <AppText
                                tag="p"
                                size="xs"
                                weight="bold"
                                tracking="wide"
                                uppercase
                                color="muted"
                                class="mb-2"
                            >
                                Method
                            </AppText>

                            <AppText
                                tag="p"
                                font="lora"
                                size="lg"
                                leading="relaxed"
                            >
                                {{ methodology.method }}
                            </AppText>
                        </div>

                        <AppText
                            v-if="methodology.description"
                            tag="p"
                            font="lora"
                            size="xs"
                            color="muted"
                            leading="relaxed"
                            class="whitespace-pre-line"
                        >
                            {{ methodology.description }}
                        </AppText>

                        <dl
                            class="
                                divide-y
                                divide-border-light
                                border-y
                                border-border-light
                                dark:divide-border-dark
                                dark:border-border-dark
                            "
                        >
                            <div
                                v-if="methodology.research_design"
                                class="flex justify-between gap-6 py-3"
                            >
                                <AppText
                                    tag="dt"
                                    size="sm"
                                    color="muted"
                                >
                                    Research design
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="sm"
                                    weight="semibold"
                                    class="text-right"
                                >
                                    {{ methodology.research_design }}
                                </AppText>
                            </div>

                            <div
                                v-if="methodology.data_collection_method"
                                class="flex justify-between gap-6 py-3"
                            >
                                <AppText
                                    tag="dt"
                                    size="sm"
                                    color="muted"
                                >
                                    Data collection
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="sm"
                                    weight="semibold"
                                    class="text-right"
                                >
                                    {{ methodology.data_collection_method }}
                                </AppText>
                            </div>

                            <div
                                v-if="methodology.sample_size !== null"
                                class="flex justify-between gap-6 py-3"
                            >
                                <AppText
                                    tag="dt"
                                    size="sm"
                                    color="muted"
                                >
                                    Sample size
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="sm"
                                    weight="semibold"
                                >
                                    {{ methodology.sample_size }}
                                </AppText>
                            </div>

                            <div
                                v-if="methodology.population"
                                class="flex justify-between gap-6 py-3"
                            >
                                <AppText
                                    tag="dt"
                                    size="sm"
                                    color="muted"
                                >
                                    Population
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="sm"
                                    weight="semibold"
                                    class="max-w-md text-right"
                                >
                                    {{ methodology.population }}
                                </AppText>
                            </div>

                            <div
                                v-if="methodology.geography"
                                class="flex justify-between gap-6 py-3"
                            >
                                <AppText
                                    tag="dt"
                                    size="sm"
                                    color="muted"
                                >
                                    Geography
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="sm"
                                    weight="semibold"
                                    class="max-w-md text-right"
                                >
                                    {{ methodology.geography }}
                                </AppText>
                            </div>

                            <div
                                v-if="
                                    methodology.start_date ||
                                    methodology.end_date
                                "
                                class="flex justify-between gap-6 py-3"
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
                                    class="text-right"
                                >
                                    <span
                                        v-if="methodology.start_date"
                                    >
                                        {{ formatDate(methodology.start_date) }}
                                    </span>

                                    <span
                                        v-if="
                                            methodology.start_date &&
                                            methodology.end_date
                                        "
                                    >
                                        —
                                    </span>

                                    <span
                                        v-if="methodology.end_date"
                                    >
                                        {{ formatDate(methodology.end_date) }}
                                    </span>
                                </AppText>
                            </div>
                        </dl>

                        <div v-if="methodology.limitations">
                            <AppText
                                tag="p"
                                size="xs"
                                weight="bold"
                                tracking="wide"
                                uppercase
                                color="muted"
                                class="mb-2"
                            >
                                Limitations
                            </AppText>

                            <AppText
                                tag="p"
                                font="lora"
                                color="muted"
                                leading="relaxed"
                                class="whitespace-pre-line"
                            >
                                {{ methodology.limitations }}
                            </AppText>
                        </div>
                    </div>
                </section>

                <!-- Research Questions -->
                <section
                    v-if="questions.length"
                    class="
                        border-b
                        border-border-light
                        py-10
                        dark:border-border-dark
                    "
                >
                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="2xl"
                        weight="normal"
                        class="mb-7"
                    >
                        Research Questions
                    </AppHeading>

                    <div class="space-y-8">
                        <div
                            v-for="(question, index) in questions"
                            :key="question.id"
                            class="
                                border-l-2
                                border-primary
                                pl-5
                            "
                        >
                            <div
                                class="
                                    mb-3
                                    flex
                                    items-center
                                    gap-3
                                "
                            >
                                <AppText
                                    tag="span"
                                    size="xs"
                                    weight="bold"
                                    color="muted"
                                >
                                    {{ String(index + 1).padStart(2, '0') }}
                                </AppText>

                                <Badge>
                                    {{ question.type.label }}
                                </Badge>
                            </div>

                            <AppText
                                tag="p"
                                font="lora"
                                size="lg"
                                leading="relaxed"
                            >
                                {{ question.question }}
                            </AppText>

                            <AppText
                                v-if="question.description"
                                tag="p"
                                size="sm"
                                color="muted"
                                leading="relaxed"
                                class="mt-3"
                            >
                                {{ question.description }}
                            </AppText>

                            <!-- Findings connected to this question -->
                            <div
                                v-if="question.findings?.length"
                                class="mt-5"
                            >
                                <AppText
                                    tag="p"
                                    size="xs"
                                    weight="bold"
                                    tracking="wide"
                                    uppercase
                                    color="muted"
                                    class="mb-3"
                                >
                                    Related Findings
                                </AppText>

                                <div class="space-y-3">
                                    <div
                                        v-for="finding in question.findings"
                                        :key="finding.id"
                                        class="
                                            border
                                            border-border-light
                                            p-4
                                            dark:border-border-dark
                                        "
                                    >
                                        <div
                                            class="
                                                flex
                                                flex-wrap
                                                items-center
                                                gap-2
                                            "
                                        >
                                            <Badge>
                                                {{ finding.type.label }}
                                            </Badge>

                                            <AppText
                                                v-if="
                                                    finding.confidence !==
                                                    null
                                                "
                                                tag="span"
                                                size="xs"
                                                color="muted"
                                            >
                                                {{ finding.confidence }}%
                                                confidence
                                            </AppText>
                                        </div>

                                        <AppHeading
                                            tag="h3"
                                            font="prata"
                                            size="lg"
                                            weight="normal"
                                            class="mt-3"
                                        >
                                            {{ finding.title }}
                                        </AppHeading>

                                        <AppText
                                            v-if="finding.summary"
                                            tag="p"
                                            size="sm"
                                            color="muted"
                                            leading="relaxed"
                                            class="mt-2"
                                        >
                                            {{ finding.summary }}
                                        </AppText>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Findings -->
                <section
                    v-if="findings.length"
                    class="
                        border-b
                        border-border-light
                        py-10
                        dark:border-border-dark
                    "
                >
                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="2xl"
                        weight="normal"
                        class="mb-7"
                    >
                        Findings
                    </AppHeading>

                    <div class="space-y-10">
                        <article
                            v-for="finding in findings"
                            :key="finding.id"
                        >
                            <div
                                class="
                                    border-b
                                    border-border-light
                                    pb-8
                                    dark:border-border-dark
                                "
                            >
                                <div
                                    class="
                                        flex
                                        flex-wrap
                                        items-center
                                        gap-3
                                    "
                                >
                                    <Badge>
                                        {{ finding.type.label }}
                                    </Badge>

                                    <AppText
                                        v-if="
                                            finding.confidence !==
                                            null
                                        "
                                        tag="span"
                                        size="xs"
                                        color="muted"
                                    >
                                        Confidence:
                                        {{ finding.confidence }}%
                                    </AppText>
                                </div>

                                <AppHeading
                                    tag="h3"
                                    font="prata"
                                    size="xl"
                                    weight="normal"
                                    class="mt-3"
                                >
                                    {{ finding.title }}
                                </AppHeading>

                                <AppText
                                    v-if="finding.summary"
                                    tag="p"
                                    font="lora"
                                    size="lg"
                                    leading="relaxed"
                                    class="mt-3"
                                >
                                    {{ finding.summary }}
                                </AppText>

                                <AppText
                                    v-if="finding.description"
                                    tag="p"
                                    color="muted"
                                    leading="relaxed"
                                    class="
                                        mt-3
                                        whitespace-pre-line
                                    "
                                >
                                    {{ finding.description }}
                                </AppText>

                                <!-- Evidence -->
                                <div
                                    v-if="
                                        evidenceForFinding(finding.id)
                                            .length
                                    "
                                    class="mt-6"
                                >
                                    <AppText
                                        tag="p"
                                        size="xs"
                                        weight="bold"
                                        tracking="wide"
                                        uppercase
                                        color="muted"
                                        class="mb-3"
                                    >
                                        Evidence
                                    </AppText>

                                    <div class="space-y-4">
                                        <div
                                            v-for="item in evidenceForFinding(
                                                finding.id,
                                            )"
                                            :key="item.id"
                                            class="
                                                border-l-2
                                                border-border-light
                                                pl-4
                                                dark:border-border-dark
                                            "
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
                                                class="mt-1"
                                            >
                                                {{ item.description }}
                                            </AppText>

                                            <AppText
                                                v-if="item.citation"
                                                tag="p"
                                                size="xs"
                                                color="muted"
                                                class="mt-2"
                                            >
                                                {{ item.citation }}
                                            </AppText>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>

                <!-- Sources -->
                <section
                    v-if="sources.length"
                    class="
                        border-b
                        border-border-light
                        py-10
                        dark:border-border-dark
                    "
                >
                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="2xl"
                        weight="normal"
                        class="mb-7"
                    >
                        Sources
                    </AppHeading>

                    <div class="space-y-6">
                        <article
                            v-for="(source, index) in sources"
                            :key="source.id"
                            class="
                                flex
                                gap-4
                                border-b
                                border-border-light
                                pb-6
                                last:border-0
                                dark:border-border-dark
                            "
                        >
                            <AppText
                                tag="span"
                                size="xs"
                                weight="bold"
                                color="muted"
                            >
                                {{ String(index + 1).padStart(2, '0') }}
                            </AppText>

                            <div class="min-w-0 flex-1">
                                <div
                                    class="
                                        flex
                                        flex-wrap
                                        items-center
                                        gap-2
                                    "
                                >
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
                                    font="prata"
                                    size="lg"
                                    weight="normal"
                                    class="mt-2"
                                >
                                    {{ source.title }}
                                </AppHeading>

                                <AppText
                                    v-if="
                                        source.author ||
                                        source.publisher
                                    "
                                    tag="p"
                                    size="sm"
                                    color="muted"
                                    class="mt-2"
                                >
                                    <span v-if="source.author">
                                        {{ source.author }}
                                    </span>

                                    <span
                                        v-if="
                                            source.author &&
                                            source.publisher
                                        "
                                    >
                                        ·
                                    </span>

                                    <span v-if="source.publisher">
                                        {{ source.publisher }}
                                    </span>
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
                                    class="
                                        mt-3
                                        inline-flex
                                        items-center
                                        gap-1.5
                                        text-xs
                                        font-semibold
                                        hover:opacity-70
                                    "
                                >
                                    View source
                                    <ExternalLink class="h-3.5 w-3.5" />
                                </a>
                            </div>
                        </article>
                    </div>
                </section>

                <!-- Citation -->
                <section class="pt-10">
                    <div
                        class="
                            mb-4
                            flex
                            items-center
                            justify-between
                            gap-4
                        "
                    >
                        <AppHeading
                            tag="h2"
                            font="prata"
                            size="xl"
                            weight="normal"
                        >
                            How to cite
                        </AppHeading>

                        <button
                            type="button"
                            class="
                                inline-flex
                                items-center
                                gap-2
                                text-xs
                                font-semibold
                                hover:opacity-70
                            "
                            @click="copyCitation"
                        >
                            <Check
                                v-if="copied"
                                class="h-4 w-4"
                            />

                            <Copy
                                v-else
                                class="h-4 w-4"
                            />

                            {{ copied ? 'Copied' : 'Copy citation' }}
                        </button>
                    </div>

                    <div
                        class="
                            border-l-2
                            border-primary
                            bg-muted/20
                            px-5
                            py-4
                        "
                    >
                        <AppText
                            tag="p"
                            size="sm"
                            color="muted"
                            leading="relaxed"
                        >
                            {{ citation }}
                        </AppText>
                    </div>
                </section>
            </article>

            <!-- Sidebar -->
            <aside>
                <div
                    class="
                        sticky
                        top-24
                        border
                        border-border-light
                        dark:border-border-dark
                    "
                >
                    <div class="p-6">
                        <AppText
                            tag="p"
                            size="xs"
                            weight="bold"
                            tracking="wide"
                            uppercase
                            color="primary"
                            class="mb-3"
                        >
                            Research
                        </AppText>

                        <AppHeading
                            tag="h2"
                            font="prata"
                            size="xl"
                            weight="normal"
                            class="mb-6"
                        >
                            Research Details
                        </AppHeading>

                        <dl
                            class="
                                divide-y
                                divide-border-light
                                border-y
                                border-border-light
                                dark:divide-border-dark
                                dark:border-border-dark
                            "
                        >
                            <div
                                v-if="typeLabel"
                                class="
                                    flex
                                    justify-between
                                    gap-4
                                    py-3
                                "
                            >
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
                                v-if="research.data.author?.name"
                                class="
                                    flex
                                    justify-between
                                    gap-4
                                    py-3
                                "
                            >
                                <AppText
                                    tag="dt"
                                    size="xs"
                                    color="muted"
                                >
                                    Author
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="xs"
                                    weight="semibold"
                                    class="text-right"
                                >
                                    {{ research.data.author.name }}
                                </AppText>
                            </div>

                            <div
                                v-if="year"
                                class="
                                    flex
                                    justify-between
                                    gap-4
                                    py-3
                                "
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
                                v-if="questions.length"
                                class="
                                    flex
                                    justify-between
                                    gap-4
                                    py-3
                                "
                            >
                                <AppText
                                    tag="dt"
                                    size="xs"
                                    color="muted"
                                >
                                    Questions
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="xs"
                                    weight="semibold"
                                >
                                    {{ questions.length }}
                                </AppText>
                            </div>

                            <div
                                v-if="findings.length"
                                class="
                                    flex
                                    justify-between
                                    gap-4
                                    py-3
                                "
                            >
                                <AppText
                                    tag="dt"
                                    size="xs"
                                    color="muted"
                                >
                                    Findings
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="xs"
                                    weight="semibold"
                                >
                                    {{ findings.length }}
                                </AppText>
                            </div>

                            <div
                                v-if="sources.length"
                                class="
                                    flex
                                    justify-between
                                    gap-4
                                    py-3
                                "
                            >
                                <AppText
                                    tag="dt"
                                    size="xs"
                                    color="muted"
                                >
                                    Sources
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="xs"
                                    weight="semibold"
                                >
                                    {{ sources.length }}
                                </AppText>
                            </div>
                        </dl>

                        <!-- Team -->
                        <div
                            v-if="members.length"
                            class="mt-7"
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
                                Research Team
                            </AppText>

                            <div class="space-y-4">
                                <div
                                    v-for="member in members"
                                    :key="member.id"
                                >
                                    <AppText
                                        tag="p"
                                        size="sm"
                                        weight="semibold"
                                    >
                                        {{ member.user?.name }}
                                    </AppText>

                                    <AppText
                                        tag="p"
                                        size="xs"
                                        color="muted"
                                        class="mt-1"
                                    >
                                        {{ member.role.label }}

                                        <span
                                            v-if="member.joined_at"
                                        >
                                            ·
                                            {{
                                                formatDate(
                                                    member.joined_at,
                                                )
                                            }}
                                        </span>
                                    </AppText>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <!-- Related Research -->
    <section
        v-if="related?.length"
        class="
            border-t
            border-border-light
            dark:border-border-dark
        "
    >
        <div class="container mx-auto px-4 py-12">
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

            <div
                class="
                    grid
                    grid-cols-1
                    gap-6
                    sm:grid-cols-2
                    lg:grid-cols-3
                "
            >
                <ResearchCard
                    v-for="item in related"
                    :key="item.id"
                    :paper="item"
                    :href="researchShow(item.slug).url"
                    compact
                />
            </div>
        </div>
    </section>
</template>