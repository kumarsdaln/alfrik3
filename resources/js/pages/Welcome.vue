<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import {
    ArrowRight,
    CalendarDays,
    ChevronRight,
    MapPin,
} from '@lucide/vue'

import AppContainer from '@/components/ui/AppContainer.vue'
import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import AppBadge from '@/components/ui/AppBadge.vue'

import ResearchCard from '@/components/research/ResearchCard.vue'
import EventCard from '@/Components/Cards/EventCard.vue'

import { show as researchShow } from '@/routes/research'
import { show as eventShow } from '@/routes/events'

interface ResearchArea {
    id: number
    name: string
    slug: string
    description?: string | null
    papers_count?: number
}

interface ResearchPaper {
    id: number
    title: string
    slug: string
    description?: string | null
    cover_image?: string | null
    published_at?: string | null
    area?: ResearchArea | null
    areas?: ResearchArea[]
    author?: {
        id: number
        name: string
    } | null
}

interface Report {
    id: number
    title: string
    slug: string
    description?: string | null
    cover_image?: string | null
    published_at?: string | null
    category?: {
        id: number
        name: string
        slug: string
    } | null
}

interface Survey {
    id: number
    title: string
    slug: string
    description?: string | null
    cover_image?: string | null
    published_at?: string | null
}

interface Magazine {
    id: number
    title: string
    slug: string
    description?: string | null
    cover_image?: string | null
    published_at?: string | null
    category?: {
        id: number
        name: string
        slug: string
    } | null
}

interface Interview {
    id: number
    title: string
    slug: string
    description?: string | null
    cover_image?: string | null
    published_at?: string | null
    participants?: {
        id: number
        user?: {
            id: number
            name: string
            profile_image?: string | null
        } | null
    }[]
}

interface EventItem {
    id: number
    title: string
    slug: string
    description?: string | null
    cover_image?: string | null
    start_date: string
    end_date?: string | null
    city?: string | null
    venue?: string | null
    categories?: {
        id: number
        name: string
        slug: string
    }[]
}

interface Props {
    featured: ResearchPaper | Report | null
    research: ResearchPaper[]
    reports: Report[]
    surveys: Survey[]
    magazines: Magazine[]
    interviews: Interview[]
    events: EventItem[]
    areas: ResearchArea[]
}

const props = defineProps<Props>()

const formatDate = (date?: string | null) => {
    if (!date) {
        return ''
    }

    return new Intl.DateTimeFormat('en', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    }).format(new Date(date))
}

const eventDate = (event: EventItem) => {
    const start = formatDate(event.start_date)

    if (!event.end_date) {
        return start
    }

    const end = formatDate(event.end_date)

    return start === end ? start : `${start} — ${end}`
}

const featuredUrl = () => {
    if (!props.featured) {
        return '#'
    }

    return `/research/${props.featured.slug}`
}
</script>

<template>
    <Head title="Alfrik">
        <meta
            name="description"
            content="Research, ideas, conversations, publications and events from Alfrik."
        />
    </Head>

    <!--
    |--------------------------------------------------------------------------
    | Hero
    |--------------------------------------------------------------------------
    -->

    <section
        class="
            border-b
            border-border-light
            dark:border-border-dark
        "
    >
        <AppContainer>
            <div
                class="
                    grid
                    min-h-[560px]
                    items-end
                    gap-12
                    py-20
                    lg:grid-cols-[1.2fr_0.8fr]
                    lg:py-28
                "
            >
                <div>
                    <AppText
                        tag="p"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        tracking="wide"
                        uppercase
                        color="brand"
                        class="mb-6"
                    >
                        Research · Ideas · Conversations
                    </AppText>

                    <AppHeading
                        tag="h1"
                        font="prata"
                        size="6xl"
                        weight="normal"
                        leading="tight"
                        class="max-w-4xl"
                    >
                        Understanding the world through
                        <span class="text-brand"> research and ideas.</span>
                    </AppHeading>

                    <AppText
                        tag="p"
                        font="lora"
                        size="lg"
                        color="muted"
                        leading="relaxed"
                        class="mt-8 max-w-2xl"
                    >
                        Alfrik brings together research, evidence,
                        conversations and public knowledge to make complex
                        questions easier to understand.
                    </AppText>

                    <div class="mt-9 flex flex-wrap items-center gap-6">
                        <Link
                            href="/research"
                            class="
                                inline-flex
                                items-center
                                gap-2
                                bg-content-light
                                px-6
                                py-3
                                font-redhat
                                text-sm
                                font-semibold
                                text-white
                                transition-colors
                                hover:bg-brand
                                dark:bg-content-dark
                                dark:text-content-light
                                dark:hover:bg-brand
                                dark:hover:text-white
                            "
                        >
                            Explore research
                            <ArrowRight class="h-4 w-4" />
                        </Link>

                        <Link
                            href="/events"
                            class="
                                inline-flex
                                items-center
                                gap-2
                                font-redhat
                                text-sm
                                font-semibold
                                text-content-light
                                transition-colors
                                hover:text-brand
                                dark:text-content-dark
                            "
                        >
                            Upcoming events
                            <ArrowRight class="h-4 w-4" />
                        </Link>
                    </div>
                </div>

                <div
                    class="
                        hidden
                        border-l
                        border-border-light
                        pl-10
                        lg:block
                        dark:border-border-dark
                    "
                >
                    <AppText
                        tag="p"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        tracking="wide"
                        uppercase
                        color="muted"
                    >
                        What we publish
                    </AppText>

                    <div class="mt-6 divide-y divide-border-light dark:divide-border-dark">
                        <Link
                            v-for="item in [
                                ['Research', '/research'],
                                ['Reports', '/reports'],
                                ['Surveys', '/surveys'],
                                ['Interviews', '/interviews'],
                                ['Magazines', '/magazines'],
                                ['Events', '/events'],
                            ]"
                            :key="item[0]"
                            :href="item[1]"
                            class="
                                group
                                flex
                                items-center
                                justify-between
                                py-4
                                font-prata
                                text-xl
                                transition-colors
                                hover:text-brand
                            "
                        >
                            {{ item[0] }}

                            <ChevronRight
                                class="
                                    h-4
                                    w-4
                                    transition-transform
                                    group-hover:translate-x-1
                                "
                            />
                        </Link>
                    </div>
                </div>
            </div>
        </AppContainer>
    </section>

    <!--
    |--------------------------------------------------------------------------
    | Editor's Choice
    |--------------------------------------------------------------------------
    -->

    <section v-if="featured" class="py-20 lg:py-28">
        <AppContainer>
            <div class="mb-10 flex items-end justify-between gap-6">
                <div>
                    <AppText
                        tag="p"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        tracking="wide"
                        uppercase
                        color="brand"
                    >
                        Editor's choice
                    </AppText>

                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="3xl"
                        weight="normal"
                        leading="tight"
                        class="mt-2"
                    >
                        Worth your attention
                    </AppHeading>
                </div>
            </div>

            <Link
                :href="featuredUrl()"
                class="
                    group
                    grid
                    overflow-hidden
                    border
                    border-border-light
                    dark:border-border-dark
                    lg:grid-cols-[1.15fr_0.85fr]
                "
            >
                <div
                    class="
                        relative
                        min-h-[320px]
                        overflow-hidden
                        bg-surface-light
                        dark:bg-surface-dark
                    "
                >
                    <img
                        v-if="featured.cover_image"
                        :src="featured.cover_image"
                        :alt="featured.title"
                        class="
                            h-full
                            w-full
                            object-cover
                            transition-transform
                            duration-700
                            group-hover:scale-[1.02]
                        "
                    />

                    <div
                        v-else
                        class="
                            flex
                            h-full
                            min-h-[320px]
                            items-center
                            justify-center
                            bg-muted
                        "
                    >
                        <AppText
                            size="xs"
                            weight="bold"
                            tracking="wide"
                            uppercase
                            color="muted"
                        >
                            Alfrik
                        </AppText>
                    </div>
                </div>

                <div class="flex flex-col justify-center p-8 sm:p-10 lg:p-14">
                    <AppBadge variant="primary" size="sm">
                        Featured
                    </AppBadge>

                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="3xl"
                        weight="normal"
                        leading="tight"
                        hover-brand
                        class="mt-5"
                    >
                        {{ featured.title }}
                    </AppHeading>

                    <AppText
                        v-if="featured.description"
                        tag="p"
                        font="lora"
                        size="sm"
                        color="muted"
                        leading="relaxed"
                        :clamp="4"
                        class="mt-5"
                    >
                        {{ featured.description }}
                    </AppText>

                    <div
                        class="
                            mt-8
                            flex
                            items-center
                            gap-2
                            font-redhat
                            text-sm
                            font-semibold
                            text-brand
                        "
                    >
                        Read more
                        <ArrowRight class="h-4 w-4 transition-transform group-hover:translate-x-1" />
                    </div>
                </div>
            </Link>
        </AppContainer>
    </section>

    <!--
    |--------------------------------------------------------------------------
    | Latest from Alfrik
    |--------------------------------------------------------------------------
    -->

    <section
        v-if="research.length || reports.length || interviews.length"
        class="
            border-y
            border-border-light
            py-20
            dark:border-border-dark
            lg:py-24
        "
    >
        <AppContainer>
            <div class="mb-10 flex items-end justify-between">
                <div>
                    <AppText
                        tag="p"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        tracking="wide"
                        uppercase
                        color="brand"
                    >
                        Latest from Alfrik
                    </AppText>

                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="3xl"
                        weight="normal"
                        class="mt-2"
                    >
                        New work and ideas
                    </AppHeading>
                </div>

                <Link
                    href="/research"
                    class="
                        hidden
                        items-center
                        gap-1
                        font-redhat
                        text-sm
                        font-semibold
                        hover:text-brand
                        sm:inline-flex
                    "
                >
                    View all
                    <ArrowRight class="h-4 w-4" />
                </Link>
            </div>

            <div
                class="
                    divide-y
                    divide-border-light
                    border-y
                    border-border-light
                    dark:divide-border-dark
                    dark:border-border-dark
                "
            >
                <Link
                    v-for="paper in research.slice(0, 4)"
                    :key="paper.id"
                    :href="researchShow(paper.slug).url"
                    class="
                        group
                        grid
                        gap-5
                        py-7
                        transition-colors
                        hover:bg-surface-light
                        sm:grid-cols-[140px_1fr_auto]
                        sm:items-center
                        dark:hover:bg-surface-dark
                    "
                >
                    <AppText
                        tag="span"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        uppercase
                        tracking="wide"
                        color="brand"
                    >
                        Research
                    </AppText>

                    <div>
                        <AppHeading
                            tag="h3"
                            font="prata"
                            size="xl"
                            weight="normal"
                            leading="tight"
                            hover-brand
                        >
                            {{ paper.title }}
                        </AppHeading>

                        <AppText
                            v-if="paper.description"
                            tag="p"
                            font="lora"
                            size="sm"
                            color="muted"
                            :clamp="2"
                            class="mt-2"
                        >
                            {{ paper.description }}
                        </AppText>
                    </div>

                    <ArrowRight
                        class="
                            hidden
                            h-5
                            w-5
                            transition-transform
                            group-hover:translate-x-1
                            sm:block
                        "
                    />
                </Link>

                <Link
                    v-for="report in reports.slice(0, 2)"
                    :key="`report-${report.id}`"
                    :href="`/reports/${report.slug}`"
                    class="
                        group
                        grid
                        gap-5
                        py-7
                        transition-colors
                        hover:bg-surface-light
                        sm:grid-cols-[140px_1fr_auto]
                        sm:items-center
                        dark:hover:bg-surface-dark
                    "
                >
                    <AppText
                        tag="span"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        uppercase
                        tracking="wide"
                        color="brand"
                    >
                        Report
                    </AppText>

                    <div>
                        <AppHeading
                            tag="h3"
                            font="prata"
                            size="xl"
                            weight="normal"
                            leading="tight"
                            hover-brand
                        >
                            {{ report.title }}
                        </AppHeading>

                        <AppText
                            v-if="report.description"
                            tag="p"
                            font="lora"
                            size="sm"
                            color="muted"
                            :clamp="2"
                            class="mt-2"
                        >
                            {{ report.description }}
                        </AppText>
                    </div>

                    <ArrowRight
                        class="
                            hidden
                            h-5
                            w-5
                            transition-transform
                            group-hover:translate-x-1
                            sm:block
                        "
                    />
                </Link>

                <Link
                    v-for="interview in interviews.slice(0, 2)"
                    :key="`interview-${interview.id}`"
                    :href="`/interviews/${interview.slug}`"
                    class="
                        group
                        grid
                        gap-5
                        py-7
                        transition-colors
                        hover:bg-surface-light
                        sm:grid-cols-[140px_1fr_auto]
                        sm:items-center
                        dark:hover:bg-surface-dark
                    "
                >
                    <AppText
                        tag="span"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        uppercase
                        tracking="wide"
                        color="brand"
                    >
                        Interview
                    </AppText>

                    <div>
                        <AppHeading
                            tag="h3"
                            font="prata"
                            size="xl"
                            weight="normal"
                            leading="tight"
                            hover-brand
                        >
                            {{ interview.title }}
                        </AppHeading>

                        <AppText
                            v-if="interview.description"
                            tag="p"
                            font="lora"
                            size="sm"
                            color="muted"
                            :clamp="2"
                            class="mt-2"
                        >
                            {{ interview.description }}
                        </AppText>
                    </div>

                    <ArrowRight
                        class="
                            hidden
                            h-5
                            w-5
                            transition-transform
                            group-hover:translate-x-1
                            sm:block
                        "
                    />
                </Link>
            </div>
        </AppContainer>
    </section>

    <!--
    |--------------------------------------------------------------------------
    | Research & Evidence
    |--------------------------------------------------------------------------
    -->

    <section v-if="research.length" class="py-20 lg:py-24">
        <AppContainer>
            <div class="mb-10 max-w-2xl">
                <AppText
                    tag="p"
                    font="redhat"
                    size="xs"
                    weight="bold"
                    tracking="wide"
                    uppercase
                    color="brand"
                >
                    Research & evidence
                </AppText>

                <AppHeading
                    tag="h2"
                    font="prata"
                    size="3xl"
                    weight="normal"
                    class="mt-2"
                >
                    Evidence for better understanding
                </AppHeading>

                <AppText
                    tag="p"
                    font="lora"
                    size="sm"
                    color="muted"
                    leading="relaxed"
                    class="mt-4"
                >
                    Explore original research and evidence-driven work
                    across Alfrik's areas of study.
                </AppText>
            </div>

            <div
                class="
                    grid
                    gap-6
                    md:grid-cols-2
                "
            >
                <ResearchCard
                    v-for="paper in research.slice(0, 4)"
                    :key="paper.id"
                    :paper="paper"
                    :href="researchShow(paper.slug).url"
                />
            </div>

            <div class="mt-8">
                <Link
                    href="/research"
                    class="
                        inline-flex
                        items-center
                        gap-2
                        font-redhat
                        text-sm
                        font-semibold
                        text-brand
                        hover:gap-3
                    "
                >
                    Explore all research
                    <ArrowRight class="h-4 w-4" />
                </Link>
            </div>
        </AppContainer>
    </section>

    <!--
    |--------------------------------------------------------------------------
    | Reports + Surveys
    |--------------------------------------------------------------------------
    -->

    <section
        v-if="reports.length || surveys.length"
        class="
            border-y
            border-border-light
            bg-surface-light
            py-20
            dark:border-border-dark
            dark:bg-surface-dark
            lg:py-24
        "
    >
        <AppContainer>
            <div class="grid gap-14 lg:grid-cols-2 lg:gap-20">
                <!-- Reports -->

                <div v-if="reports.length">
                    <div class="mb-8 flex items-end justify-between">
                        <div>
                            <AppText
                                tag="p"
                                font="redhat"
                                size="xs"
                                weight="bold"
                                tracking="wide"
                                uppercase
                                color="brand"
                            >
                                Reports
                            </AppText>

                            <AppHeading
                                tag="h2"
                                font="prata"
                                size="2xl"
                                weight="normal"
                                class="mt-2"
                            >
                                Detailed findings
                            </AppHeading>
                        </div>

                        <Link
                            href="/reports"
                            class="font-redhat text-sm font-semibold hover:text-brand"
                        >
                            All reports →
                        </Link>
                    </div>

                    <div
                        class="
                            divide-y
                            divide-border-light
                            border-y
                            border-border-light
                            dark:divide-border-dark
                            dark:border-border-dark
                        "
                    >
                        <Link
                            v-for="report in reports.slice(0, 3)"
                            :key="report.id"
                            :href="`/reports/${report.slug}`"
                            class="group block py-6"
                        >
                            <AppText
                                tag="span"
                                font="redhat"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="brand"
                            >
                                Report
                            </AppText>

                            <AppHeading
                                tag="h3"
                                font="prata"
                                size="xl"
                                weight="normal"
                                leading="tight"
                                hover-brand
                                class="mt-2"
                            >
                                {{ report.title }}
                            </AppHeading>

                            <AppText
                                v-if="report.description"
                                tag="p"
                                font="lora"
                                size="sm"
                                color="muted"
                                :clamp="2"
                                class="mt-2"
                            >
                                {{ report.description }}
                            </AppText>
                        </Link>
                    </div>
                </div>

                <!-- Surveys -->

                <div v-if="surveys.length">
                    <div class="mb-8 flex items-end justify-between">
                        <div>
                            <AppText
                                tag="p"
                                font="redhat"
                                size="xs"
                                weight="bold"
                                tracking="wide"
                                uppercase
                                color="brand"
                            >
                                Data & surveys
                            </AppText>

                            <AppHeading
                                tag="h2"
                                font="prata"
                                size="2xl"
                                weight="normal"
                                class="mt-2"
                            >
                                What the evidence tells us
                            </AppHeading>
                        </div>

                        <Link
                            href="/surveys"
                            class="font-redhat text-sm font-semibold hover:text-brand"
                        >
                            All surveys →
                        </Link>
                    </div>

                    <div
                        class="
                            divide-y
                            divide-border-light
                            border-y
                            border-border-light
                            dark:divide-border-dark
                            dark:border-border-dark
                        "
                    >
                        <Link
                            v-for="survey in surveys.slice(0, 3)"
                            :key="survey.id"
                            :href="`/surveys/${survey.slug}`"
                            class="group block py-6"
                        >
                            <AppText
                                tag="span"
                                font="redhat"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="brand"
                            >
                                Survey
                            </AppText>

                            <AppHeading
                                tag="h3"
                                font="prata"
                                size="xl"
                                weight="normal"
                                leading="tight"
                                hover-brand
                                class="mt-2"
                            >
                                {{ survey.title }}
                            </AppHeading>

                            <AppText
                                v-if="survey.description"
                                tag="p"
                                font="lora"
                                size="sm"
                                color="muted"
                                :clamp="2"
                                class="mt-2"
                            >
                                {{ survey.description }}
                            </AppText>
                        </Link>
                    </div>
                </div>
            </div>
        </AppContainer>
    </section>

    <!--
    |--------------------------------------------------------------------------
    | Magazine
    |--------------------------------------------------------------------------
    -->

    <section v-if="magazines.length" class="py-20 lg:py-28">
        <AppContainer>
            <div class="grid items-center gap-12 lg:grid-cols-[0.7fr_1.3fr]">
                <div class="flex justify-center lg:justify-start">
                    <Link
                        :href="`/magazines/${magazines[0].slug}`"
                        class="
                            group
                            block
                            w-full
                            max-w-sm
                            overflow-hidden
                            border
                            border-border-light
                            dark:border-border-dark
                        "
                    >
                        <div class="aspect-[3/4] overflow-hidden bg-muted">
                            <img
                                v-if="magazines[0].cover_image"
                                :src="magazines[0].cover_image"
                                :alt="magazines[0].title"
                                class="
                                    h-full
                                    w-full
                                    object-cover
                                    transition-transform
                                    duration-700
                                    group-hover:scale-[1.02]
                                "
                            />

                            <div
                                v-else
                                class="flex h-full items-center justify-center"
                            >
                                <AppText
                                    size="xs"
                                    weight="bold"
                                    uppercase
                                    tracking="wide"
                                    color="muted"
                                >
                                    Alfrik Magazine
                                </AppText>
                            </div>
                        </div>
                    </Link>
                </div>

                <div>
                    <AppText
                        tag="p"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        tracking="wide"
                        uppercase
                        color="brand"
                    >
                        The Alfrik Magazine
                    </AppText>

                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="4xl"
                        weight="normal"
                        leading="tight"
                        class="mt-3 max-w-2xl"
                    >
                        Ideas, conversations and stories beyond the report.
                    </AppHeading>

                    <AppText
                        tag="p"
                        font="lora"
                        size="md"
                        color="muted"
                        leading="relaxed"
                        class="mt-6 max-w-xl"
                    >
                        A space for longer-form conversations, perspectives,
                        cultural observations and ideas.
                    </AppText>

                    <Link
                        :href="`/magazines/${magazines[0].slug}`"
                        class="
                            mt-8
                            inline-flex
                            items-center
                            gap-2
                            font-redhat
                            text-sm
                            font-semibold
                            text-brand
                            hover:gap-3
                        "
                    >
                        Read the magazine
                        <ArrowRight class="h-4 w-4" />
                    </Link>
                </div>
            </div>
        </AppContainer>
    </section>

    <!--
    |--------------------------------------------------------------------------
    | Conversations
    |--------------------------------------------------------------------------
    -->

    <section
        v-if="interviews.length"
        class="
            border-y
            border-border-light
            py-20
            dark:border-border-dark
            lg:py-24
        "
    >
        <AppContainer>
            <div class="mb-10 flex items-end justify-between">
                <div>
                    <AppText
                        tag="p"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        tracking="wide"
                        uppercase
                        color="brand"
                    >
                        Conversations
                    </AppText>

                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="3xl"
                        weight="normal"
                        class="mt-2"
                    >
                        Ideas become clearer when people talk.
                    </AppHeading>
                </div>

                <Link
                    href="/interviews"
                    class="hidden font-redhat text-sm font-semibold hover:text-brand sm:block"
                >
                    All conversations →
                </Link>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <Link
                    v-for="interview in interviews"
                    :key="interview.id"
                    :href="`/interviews/${interview.slug}`"
                    class="
                        group
                        border
                        border-border-light
                        p-6
                        transition-colors
                        hover:border-brand
                        dark:border-border-dark
                    "
                >
                    <div
                        class="
                            mb-6
                            aspect-[4/3]
                            overflow-hidden
                            bg-muted
                        "
                    >
                        <img
                            v-if="interview.cover_image"
                            :src="interview.cover_image"
                            :alt="interview.title"
                            class="
                                h-full
                                w-full
                                object-cover
                                transition-transform
                                duration-700
                                group-hover:scale-[1.02]
                            "
                        />

                        <div
                            v-else
                            class="flex h-full items-center justify-center"
                        >
                            <AppText
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="muted"
                            >
                                Conversation
                            </AppText>
                        </div>
                    </div>

                    <AppText
                        tag="span"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        uppercase
                        tracking="wide"
                        color="brand"
                    >
                        Interview
                    </AppText>

                    <AppHeading
                        tag="h3"
                        font="prata"
                        size="xl"
                        weight="normal"
                        leading="tight"
                        hover-brand
                        class="mt-2"
                    >
                        {{ interview.title }}
                    </AppHeading>

                    <AppText
                        v-if="interview.description"
                        tag="p"
                        font="lora"
                        size="sm"
                        color="muted"
                        :clamp="3"
                        class="mt-3"
                    >
                        {{ interview.description }}
                    </AppText>

                    <div
                        class="
                            mt-6
                            flex
                            items-center
                            gap-2
                            font-redhat
                            text-xs
                            font-semibold
                            text-brand
                        "
                    >
                        Read conversation
                        <ArrowRight class="h-3.5 w-3.5 transition-transform group-hover:translate-x-1" />
                    </div>
                </Link>
            </div>
        </AppContainer>
    </section>

    <!--
    |--------------------------------------------------------------------------
    | Upcoming Events
    |--------------------------------------------------------------------------
    -->

    <section v-if="events.length" class="py-20 lg:py-24">
        <AppContainer>
            <div class="mb-10 flex items-end justify-between">
                <div>
                    <AppText
                        tag="p"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        tracking="wide"
                        uppercase
                        color="brand"
                    >
                        What's happening
                    </AppText>

                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="3xl"
                        weight="normal"
                        class="mt-2"
                    >
                        Upcoming at Alfrik
                    </AppHeading>
                </div>

                <Link
                    href="/events"
                    class="
                        hidden
                        items-center
                        gap-1
                        font-redhat
                        text-sm
                        font-semibold
                        hover:text-brand
                        sm:inline-flex
                    "
                >
                    All events
                    <ArrowRight class="h-4 w-4" />
                </Link>
            </div>

            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                <EventCard
                    v-for="event in events"
                    :key="event.id"
                    :event="event"
                    :href="eventShow(event.slug).url"
                />
            </div>
        </AppContainer>
    </section>

    <!--
    |--------------------------------------------------------------------------
    | Areas of Work
    |--------------------------------------------------------------------------
    -->

    <section
        v-if="areas.length"
        class="
            border-y
            border-border-light
            bg-content-light
            py-20
            text-white
            dark:border-border-dark
            dark:bg-content-dark
            lg:py-24
        "
    >
        <AppContainer>
            <div class="grid gap-12 lg:grid-cols-[0.75fr_1.25fr]">
                <div>
                    <AppText
                        tag="p"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        tracking="wide"
                        uppercase
                        class="text-brand"
                    >
                        Explore our areas
                    </AppText>

                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="3xl"
                        weight="normal"
                        leading="tight"
                        class="mt-3 !text-white"
                    >
                        Questions without borders.
                    </AppHeading>

                    <AppText
                        tag="p"
                        font="lora"
                        size="sm"
                        leading="relaxed"
                        class="mt-5 max-w-md !text-white/65"
                    >
                        Explore the subjects and questions that shape
                        Alfrik's research.
                    </AppText>
                </div>

                <div class="divide-y divide-white/15 border-y border-white/15">
                    <Link
                        v-for="area in areas"
                        :key="area.id"
                        :href="`/research?area=${area.slug}`"
                        class="
                            group
                            flex
                            items-center
                            justify-between
                            gap-6
                            py-5
                        "
                    >
                        <div class="min-w-0">
                            <AppHeading
                                tag="h3"
                                font="prata"
                                size="xl"
                                weight="normal"
                                leading="tight"
                                class="!text-white transition-colors group-hover:!text-brand"
                            >
                                {{ area.name }}
                            </AppHeading>

                            <AppText
                                v-if="area.description"
                                tag="p"
                                font="lora"
                                size="xs"
                                leading="relaxed"
                                class="mt-1 !text-white/55"
                                :clamp="1"
                            >
                                {{ area.description }}
                            </AppText>
                        </div>

                        <ChevronRight
                            class="
                                h-5
                                w-5
                                shrink-0
                                transition-transform
                                group-hover:translate-x-1
                                group-hover:text-brand
                            "
                        />
                    </Link>
                </div>
            </div>
        </AppContainer>
    </section>

    <!--
    |--------------------------------------------------------------------------
    | Newsletter
    |--------------------------------------------------------------------------
    -->

    <section class="py-20 lg:py-24">
        <AppContainer>
            <div
                class="
                    border
                    border-border-light
                    p-8
                    sm:p-12
                    lg:flex
                    lg:items-center
                    lg:justify-between
                    lg:gap-16
                    lg:p-14
                    dark:border-border-dark
                "
            >
                <div class="max-w-xl">
                    <AppText
                        tag="p"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        tracking="wide"
                        uppercase
                        color="brand"
                    >
                        Stay informed
                    </AppText>

                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="3xl"
                        weight="normal"
                        class="mt-2"
                    >
                        Research, ideas and conversations.
                    </AppHeading>

                    <AppText
                        tag="p"
                        font="lora"
                        size="sm"
                        color="muted"
                        leading="relaxed"
                        class="mt-4"
                    >
                        Receive selected work and important updates from
                        Alfrik.
                    </AppText>
                </div>

                <form
                    class="
                        mt-8
                        flex
                        max-w-xl
                        flex-col
                        gap-3
                        sm:flex-row
                        lg:mt-0
                        lg:min-w-[440px]
                    "
                    @submit.prevent
                >
                    <input
                        type="email"
                        placeholder="Your email address"
                        class="
                            h-12
                            min-w-0
                            flex-1
                            border
                            border-border-light
                            bg-transparent
                            px-4
                            font-redhat
                            text-sm
                            outline-none
                            transition-colors
                            placeholder:text-content-light/40
                            focus:border-brand
                            dark:border-border-dark
                            dark:placeholder:text-content-dark/40
                        "
                    />

                    <button
                        type="submit"
                        class="
                            h-12
                            shrink-0
                            bg-content-light
                            px-6
                            font-redhat
                            text-sm
                            font-semibold
                            text-white
                            transition-colors
                            hover:bg-brand
                            dark:bg-content-dark
                            dark:hover:bg-brand
                        "
                    >
                        Subscribe
                    </button>
                </form>
            </div>
        </AppContainer>
    </section>
</template>