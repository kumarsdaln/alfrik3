<script setup lang="ts">
    import { computed } from 'vue'
    import { Link } from '@inertiajs/vue3'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import { edit } from '@/routes/admin/research'
    import type { Research } from '@/types'
    import { Edit, MoveUpRight } from '@lucide/vue'
    import Button from '@/components/ui/button/Button.vue'

    interface Props {
        research: {
            data: Research
        }
    }

    const props = defineProps<Props>()

    const research = computed(() => props.research.data)

    /**
     * Media module
     *
     * Prefer the cover collection.
     * Fall back to default, then the first available media item.
     */
    const coverMedia = computed(() =>
        props.research.data.media?.find(media => media.collection === 'cover')
        ?? props.research.data.media?.find(media => media.collection === 'default')
        ?? props.research.data.media?.[0]
        ?? null,
    )

    const galleryMedia = computed(() =>
        props.research.data.media?.filter(
            media => media.id !== coverMedia.value?.id,
        ) ?? [],
    )

    const workspaceItems = computed(() => [
        {
            title: 'Methodology',
            description: 'Research design, methods and data collection.',
            href: `/admin/research/${props.research.data.id}/methodology`,
            icon: 'M12 6v6l4 2',
            count: props.research.data.methodology ? 1 : 0,
        },
        {
            title: 'Sources',
            description: 'References and source materials.',
            href: `/admin/research/${props.research.data.id}/sources`,
            icon: 'M4 5h16M4 12h16M4 19h16',
            count: props.research.data.sources?.length ?? 0,
        },
        {
            title: 'Questions',
            description: 'Research questions and hypotheses.',
            href: `/admin/research/${props.research.data.id}/questions`,
            icon: 'M9.5 9a2.5 2.5 0 1 1 4.5 1.5c-1 1-2 1.5-2 3',
            count: props.research.data.questions?.length ?? 0,
        },
        {
            title: 'Findings',
            description: 'Research results, insights and evidence.',
            href: `/admin/research/${props.research.data.id}/findings`,
            icon: 'M3 3v18h18M7 14l4-4 4 4 6-7',
            count: props.research.data.findings?.length ?? 0,
        },
        {
            title: 'Surveys',
            description: 'Questionnaires and collected responses.',
            href: `/admin/research/${props.research.data.id}/surveys`,
            icon: 'M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01',
            count: null,
        },
        {
            title: 'Team',
            description: 'Research members and responsibilities.',
            href: `/admin/research/${props.research.data.id}/team`,
            icon: 'M16 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2M10 11a4 4 0 1 0 0-8 4 4 0 0 0 0 8',
            count: props.research.data.members?.length ?? 0,
        },
        {
            title: 'Activity',
            description: 'Changes, updates and research history.',
            href: `/admin/research/${props.research.data.id}/activity`,
            icon: 'M3 12a9 9 0 1 0 3-6.7M3 4v5h5M12 7v5l3 2',
            count: null,
        },
    ])

    const totalModules = computed(() => workspaceItems.value.length)

    const formatDate = (date?: string | null) => {
        if (!date) return '—'

        const parsedDate = new Date(date)

        if (Number.isNaN(parsedDate.getTime())) return date

        return parsedDate.toLocaleDateString('en-IN', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        })
    }
</script>

<template>
    <div class="space-y-10 py-5">

        <!-- Navigation and actions -->
        <div class="flex items-center justify-between gap-4 py-5">
            <div class="flex min-w-0 gap-4">
                <BackButton />
                <Heading :title="research.title" :description="research.subtitle" />
            </div>

            <Button variant="outline" as-child class="shrink-0 gap-2">
                <Link :href="edit(research.id).url">
                    <Edit class="size-4" />
                    <span class="hidden sm:inline">
                        Edit Research
                    </span>

                    <span class="sm:hidden">
                        Edit
                    </span>
                </Link>
            </Button>
        </div>

        <!-- Research identity / Hero -->
        <section class="border-b border-border pb-8">
            <div class="grid gap-7 lg:grid-cols-[minmax(0,1fr)_360px]">

                <!-- Research title and information -->
                <div class="flex min-w-0 flex-col justify-center">

                    <div class="mb-4 flex flex-wrap items-center gap-2">
                        <Badge v-if="research.type" :color="research.type.color">
                            {{ research.type.label }}
                        </Badge>

                        <Badge v-if="research.status" :color="research.status.color">
                            {{ research.status.label }}
                        </Badge>

                        <Badge v-if="research.featured" variant="outline">
                            Featured
                        </Badge>
                    </div>

                    <!-- Categories and tags: inline taxonomy -->
                    <div v-if="research.categories?.length || research.tags?.length"
                        class="mt-6 flex flex-wrap items-center gap-x-5 gap-y-3">
                        <div v-if="research.categories?.length" class="flex flex-wrap items-center gap-2">
                            <span class="text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                                Filed under
                            </span>

                            <template v-for="(category, index) in research.categories" :key="category.id">
                                <span class="text-sm font-medium">
                                    {{ category.name }}
                                </span>

                                <span v-if="index !== research.categories.length - 1" class="text-muted-foreground">
                                    /
                                </span>
                            </template>
                        </div>

                        <div v-if="research.tags?.length" class="flex flex-wrap items-center gap-2">
                            <span class="text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                                Topics
                            </span>

                            <span v-for="tag in research.tags" :key="tag.id"
                                class="border border-border px-2 py-1 text-[11px] text-muted-foreground">
                                {{ tag.name }}
                            </span>
                        </div>
                    </div>

                    <!-- Publication metadata -->
                    <div class="mt-7 flex flex-wrap items-center gap-x-6 gap-y-3 border-t border-border pt-5">
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                                Published
                            </p>
                            <p class="mt-1 text-sm font-medium">
                                {{ formatDate(research.published_at) }}
                            </p>
                        </div>

                        <div v-if="research.created_at" class="border-l border-border pl-6">
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                                Created
                            </p>
                            <p class="mt-1 text-sm font-medium">
                                {{ formatDate(research.created_at) }}
                            </p>
                        </div>

                        <div v-if="research.updated_at" class="border-l border-border pl-6">
                            <p class="text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                                Last updated
                            </p>
                            <p class="mt-1 text-sm font-medium">
                                {{ formatDate(research.updated_at) }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Cover media -->
                <div v-if="coverMedia"
                    class="relative min-h-52 overflow-hidden border border-border bg-muted lg:min-h-64">
                    <img v-if="coverMedia.mime_type?.startsWith('image/')" :src="coverMedia.url"
                        :alt="coverMedia.alt || research.title" class="absolute inset-0 size-full object-cover">

                    <video v-else-if="coverMedia.mime_type?.startsWith('video/')" :src="coverMedia.url" controls
                        class="absolute inset-0 size-full object-cover" />

                    <a v-else :href="coverMedia.url" target="_blank" rel="noopener noreferrer"
                        class="flex size-full min-h-52 flex-col items-center justify-center gap-3 p-6 text-center">
                        <span class="flex size-12 items-center justify-center border border-border bg-background">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                class="size-6">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <path d="M14 2v6h6M8 13h8M8 17h8" />
                            </svg>
                        </span>

                        <span class="text-sm font-medium">
                            {{ coverMedia.name || coverMedia.file_name }}
                        </span>
                        <span class="text-xs text-muted-foreground">
                            Open media
                        </span>
                    </a>

                    <span
                        class="absolute bottom-3 left-3 border border-border bg-background/95 px-2 py-1 text-[10px] font-semibold uppercase tracking-widest">
                        Research cover
                    </span>
                </div>

            </div>
        </section>

        <!-- Research overview -->
        <section class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_280px]">

            <div class="min-w-0 space-y-6">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-muted-foreground">
                        About this research
                    </p>

                    <h2 class="mt-2 text-xl font-semibold tracking-tight">
                        Research overview
                    </h2>
                </div>

                <div v-if="research.summary" class="border-l-2 border-primary pl-5">
                    <p class="text-base font-medium leading-8">
                        {{ research.summary }}
                    </p>
                </div>

                <div v-if="research.description" class="max-w-4xl text-sm leading-8 text-muted-foreground">
                    {{ research.description }}
                </div>

                <div v-if="!research.summary && !research.description" class="text-sm text-muted-foreground">
                    No overview information is available.
                </div>
            </div>

            <!-- Research facts -->
            <aside class="border border-border bg-card">
                <div class="border-b border-border px-4 py-3">
                    <h3 class="text-xs font-semibold uppercase tracking-widest">
                        Research at a glance
                    </h3>
                </div>

                <div class="divide-y divide-border">
                    <div class="flex items-center justify-between gap-3 px-4 py-3">
                        <span class="text-xs text-muted-foreground">Participants</span>
                        <span class="text-sm font-semibold tabular-nums">
                            {{ research.participants?.length ?? 0 }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-3 px-4 py-3">
                        <span class="text-xs text-muted-foreground">Research questions</span>
                        <span class="text-sm font-semibold tabular-nums">
                            {{ research.questions?.length ?? 0 }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-3 px-4 py-3">
                        <span class="text-xs text-muted-foreground">Sources</span>
                        <span class="text-sm font-semibold tabular-nums">
                            {{ research.sources?.length ?? 0 }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-3 px-4 py-3">
                        <span class="text-xs text-muted-foreground">Findings</span>
                        <span class="text-sm font-semibold tabular-nums">
                            {{ research.findings?.length ?? 0 }}
                        </span>
                    </div>

                    <div class="flex items-center justify-between gap-3 px-4 py-3">
                        <span class="text-xs text-muted-foreground">Reports</span>
                        <span class="text-sm font-semibold tabular-nums">
                            {{ research.reports?.length ?? 0 }}
                        </span>
                    </div>
                </div>
            </aside>
        </section>

        <!-- Participants -->
        <section class="space-y-5 border-t border-border pt-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-muted-foreground">
                        Contributors
                    </p>
                    <h2 class="mt-2 text-xl font-semibold tracking-tight">
                        Research participants
                    </h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        People who contributed to this research.
                    </p>
                </div>

                <span class="text-xs tabular-nums text-muted-foreground">
                    {{ research.participants?.length ?? 0 }} participants
                </span>
            </div>

            <div v-if="research.participants?.length"
                class="grid gap-px border border-border bg-border sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="participant in research.participants" :key="participant.id"
                    class="flex items-start gap-3 bg-card p-4">
                    <img v-if="participant.user?.avatar" :src="participant.user.avatar" :alt="participant.user.name"
                        class="size-12 shrink-0 border border-border object-cover">

                    <div v-else
                        class="flex size-12 shrink-0 items-center justify-center border border-border bg-muted text-sm font-semibold">
                        {{ participant.user?.name?.charAt(0)?.toUpperCase() ?? '?' }}
                    </div>

                    <div class="min-w-0">
                        <h3 class="truncate text-sm font-semibold">
                            {{ participant.user?.name ?? 'Unnamed participant' }}
                        </h3>

                        <p class="mt-1 text-xs text-muted-foreground">
                            {{ participant.role?.name ?? 'Participant' }}
                        </p>

                        <p v-if="participant.user?.email" class="mt-1 break-all text-xs text-muted-foreground">
                            {{ participant.user.email }}
                        </p>
                    </div>
                </div>
            </div>

            <p v-else class="text-sm text-muted-foreground">
                No participants have been added.
            </p>
        </section>

        <!-- Methodology -->
        <section class="space-y-5 border-t border-border pt-8">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-muted-foreground">
                    Research framework
                </p>
                <h2 class="mt-2 text-xl font-semibold tracking-tight">
                    Methodology
                </h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    How the research was designed and conducted.
                </p>
            </div>

            <div v-if="research.methodology" class="border border-border">
                <div class="grid gap-px bg-border sm:grid-cols-2 lg:grid-cols-4">
                    <div class="bg-card p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                            Method
                        </p>
                        <p class="mt-2 text-sm font-medium">
                            {{ research.methodology.method || '—' }}
                        </p>
                    </div>

                    <div class="bg-card p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                            Research design
                        </p>
                        <p class="mt-2 text-sm font-medium">
                            {{ research.methodology.research_design || '—' }}
                        </p>
                    </div>

                    <div class="bg-card p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                            Data collection
                        </p>
                        <p class="mt-2 text-sm font-medium">
                            {{ research.methodology.data_collection_method || '—' }}
                        </p>
                    </div>

                    <div class="bg-card p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                            Sample size
                        </p>
                        <p class="mt-2 text-sm font-medium tabular-nums">
                            {{ research.methodology.sample_size ?? '—' }}
                        </p>
                    </div>

                    <div class="bg-card p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                            Population
                        </p>
                        <p class="mt-2 text-sm font-medium">
                            {{ research.methodology.population || '—' }}
                        </p>
                    </div>

                    <div class="bg-card p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                            Geography
                        </p>
                        <p class="mt-2 text-sm font-medium">
                            {{ research.methodology.geography || '—' }}
                        </p>
                    </div>

                    <div class="bg-card p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                            Start date
                        </p>
                        <p class="mt-2 text-sm font-medium">
                            {{ formatDate(research.methodology.start_date) }}
                        </p>
                    </div>

                    <div class="bg-card p-4">
                        <p class="text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                            End date
                        </p>
                        <p class="mt-2 text-sm font-medium">
                            {{ formatDate(research.methodology.end_date) }}
                        </p>
                    </div>
                </div>

                <div v-if="research.methodology.description" class="border-t border-border p-5">
                    <h3 class="text-xs font-semibold uppercase tracking-widest text-muted-foreground">
                        Methodology description
                    </h3>
                    <p class="mt-3 whitespace-pre-line text-sm leading-7">
                        {{ research.methodology.description }}
                    </p>
                </div>

                <div v-if="research.methodology.limitations" class="border-t border-border p-5">
                    <h3 class="text-xs font-semibold uppercase tracking-widest text-muted-foreground">
                        Research limitations
                    </h3>
                    <p class="mt-3 whitespace-pre-line text-sm leading-7 text-muted-foreground">
                        {{ research.methodology.limitations }}
                    </p>
                </div>
            </div>

            <p v-else class="text-sm text-muted-foreground">
                No methodology has been documented.
            </p>
        </section>

        <!-- Research Questions -->
        <section class="space-y-5 border-t border-border pt-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-muted-foreground">
                        Research framework
                    </p>
                    <h2 class="mt-2 text-xl font-semibold tracking-tight">
                        Research questions
                    </h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        The questions and hypotheses guiding this investigation.
                    </p>
                </div>

                <span class="text-xs tabular-nums text-muted-foreground">
                    {{ research.questions?.length ?? 0 }} questions
                </span>
            </div>

            <div v-if="research.questions?.length" class="divide-y divide-border border-y border-border">
                <article v-for="(question, index) in research.questions" :key="question.id"
                    class="grid gap-3 py-5 sm:grid-cols-[48px_minmax(0,1fr)]">
                    <span class="text-sm font-semibold tabular-nums text-muted-foreground">
                        {{ String(index + 1).padStart(2, '0') }}
                    </span>

                    <div>
                        <Badge variant="outline">
                            {{ question.type?.label ?? question.type }}
                        </Badge>

                        <h3 class="mt-3 text-base font-medium leading-7">
                            {{ question.question }}
                        </h3>

                        <p v-if="question.description"
                            class="mt-2 max-w-4xl whitespace-pre-line text-sm leading-7 text-muted-foreground">
                            {{ question.description }}
                        </p>
                    </div>
                </article>
            </div>

            <p v-else class="text-sm text-muted-foreground">
                No research questions have been added.
            </p>
        </section>

        <!-- Sources -->
        <section class="space-y-5 border-t border-border pt-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-muted-foreground">
                        References
                    </p>
                    <h2 class="mt-2 text-xl font-semibold tracking-tight">
                        Research sources
                    </h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Publications, datasets and other materials used in this research.
                    </p>
                </div>

                <span class="text-xs tabular-nums text-muted-foreground">
                    {{ research.sources?.length ?? 0 }} sources
                </span>
            </div>

            <div v-if="research.sources?.length" class="divide-y divide-border border-y border-border">
                <article v-for="(source, index) in research.sources" :key="source.id" class="py-5">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div class="flex min-w-0 gap-4">
                            <span class="text-xs font-semibold tabular-nums text-muted-foreground">
                                {{ String(index + 1).padStart(2, '0') }}
                            </span>

                            <div class="min-w-0">
                                <h3 class="text-sm font-semibold">
                                    {{ source.title }}
                                </h3>

                                <p class="mt-1 text-xs text-muted-foreground">
                                    {{ source.publisher || 'Publisher not specified' }}
                                    <span v-if="source.published_at">
                                        · {{ formatDate(source.published_at) }}
                                    </span>
                                </p>
                            </div>
                        </div>

                        <Badge variant="secondary">
                            {{ source.source_type?.label ?? source.source_type }}
                        </Badge>
                    </div>

                    <p v-if="source.description" class="mt-3 pl-8 text-sm leading-7 text-muted-foreground">
                        {{ source.description }}
                    </p>

                    <p v-if="source.citation"
                        class="mt-3 border-l-2 border-border pl-3 text-xs leading-6 text-muted-foreground sm:ml-8">
                        {{ source.citation }}
                    </p>

                    <a
    v-if="source.url"
    :href="source.url"
    target="_blank"
    rel="noopener noreferrer"
    class="group mt-3 inline-flex items-center gap-1.5 text-xs font-medium text-primary transition-colors hover:text-primary/70 sm:ml-8"
>
    <span class="underline underline-offset-4 decoration-primary/30 group-hover:decoration-primary">
        View original source
    </span>

    <MoveUpRight class="size-3.5 transition-transform duration-200 group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
</a>
                </article>
            </div>

            <p v-else class="text-sm text-muted-foreground">
                No sources have been added.
            </p>
        </section>

        <!-- Findings -->
        <section class="space-y-5 border-t border-border pt-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-muted-foreground">
                        Research outcomes
                    </p>
                    <h2 class="mt-2 text-xl font-semibold tracking-tight">
                        Findings and insights
                    </h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Key findings, observations and supporting evidence.
                    </p>
                </div>

                <span class="text-xs tabular-nums text-muted-foreground">
                    {{ research.findings?.length ?? 0 }} findings
                </span>
            </div>

            <div v-if="research.findings?.length" class="space-y-5">
                <article v-for="(finding, index) in research.findings" :key="finding.id" class="border border-border">
                    <div class="flex flex-wrap items-start justify-between gap-4 p-5">
                        <div class="flex min-w-0 gap-4">
                            <span class="text-sm font-semibold tabular-nums text-muted-foreground">
                                {{ String(index + 1).padStart(2, '0') }}
                            </span>

                            <div class="min-w-0">
                                <h3 class="text-base font-semibold">
                                    {{ finding.title }}
                                </h3>

                                <p v-if="finding.summary"
                                    class="mt-2 max-w-4xl text-sm leading-7 text-muted-foreground">
                                    {{ finding.summary }}
                                </p>
                            </div>
                        </div>

                        <div class="flex flex-wrap items-center gap-2">
                            <Badge variant="secondary">
                                {{ finding.type?.label ?? finding.type }}
                            </Badge>

                            <Badge v-if="finding.confidence !== null && finding.confidence !== undefined"
                                variant="outline">
                                {{ finding.confidence }}% confidence
                            </Badge>
                        </div>
                    </div>

                    <div v-if="finding.description"
                        class="border-t border-border px-5 py-4 text-sm leading-7 text-muted-foreground">
                        {{ finding.description }}
                    </div>

                    <div class="border-t border-border bg-muted/20 p-5">
                        <div class="mb-4 flex items-center justify-between gap-3">
                            <h4 class="text-xs font-semibold uppercase tracking-widest">
                                Supporting evidence
                            </h4>

                            <span class="text-xs text-muted-foreground">
                                {{ finding.evidence?.length ?? 0 }} items
                            </span>
                        </div>

                        <div v-if="finding.evidence?.length" class="space-y-4">
                            <div v-for="evidence in finding.evidence" :key="evidence.id"
                                class="border-l-2 border-border pl-4">
                                <div class="flex flex-wrap items-center gap-2">
                                    <h5 class="text-sm font-medium">
                                        {{ evidence.title }}
                                    </h5>

                                    <Badge variant="outline">
                                        {{ evidence.type?.label ?? evidence.type }}
                                    </Badge>
                                </div>

                                <p v-if="evidence.description" class="mt-2 text-sm leading-6 text-muted-foreground">
                                    {{ evidence.description }}
                                </p>

                                <p v-if="evidence.citation" class="mt-2 text-xs leading-5 text-muted-foreground">
                                    {{ evidence.citation }}
                                </p>
                            </div>
                        </div>

                        <p v-else class="text-xs text-muted-foreground">
                            No evidence attached to this finding.
                        </p>
                    </div>
                </article>
            </div>

            <p v-else class="text-sm text-muted-foreground">
                No findings have been documented.
            </p>
        </section>

        <!-- Reports -->
        <!-- <section class="space-y-5 border-t border-border pt-8">
            <div class="flex items-end justify-between gap-4">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-muted-foreground">
                        Publications
                    </p>
                    <h2 class="mt-2 text-xl font-semibold tracking-tight">
                        Reports
                    </h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Published reports and documents associated with this research.
                    </p>
                </div>

                <span class="text-xs tabular-nums text-muted-foreground">
                    {{ research.reports?.length ?? 0 }} reports
                </span>
            </div>

            <div v-if="research.reports?.length" class="divide-y divide-border border-y border-border">
                <article v-for="report in research.reports" :key="report.id"
                    class="flex flex-wrap items-start justify-between gap-4 py-5">
                    <div class="min-w-0">
                        <h3 class="text-sm font-semibold">
                            {{ report.title }}
                        </h3>

                        <p v-if="report.description" class="mt-2 max-w-3xl text-sm leading-6 text-muted-foreground">
                            {{ report.description }}
                        </p>

                        <p v-if="report.published_at" class="mt-2 text-xs text-muted-foreground">
                            Published {{ formatDate(report.published_at) }}
                        </p>
                    </div>

                    <Badge v-if="report.status" variant="secondary">
                        {{ report.status.label ?? report.status }}
                    </Badge>
                </article>
            </div>

            <p v-else class="text-sm text-muted-foreground">
                No reports are associated with this research.
            </p>
        </section> -->

        <!-- Additional media -->
        <section v-if="galleryMedia.length" class="space-y-5 border-t border-border pt-8">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-muted-foreground">
                    Research assets
                </p>
                <h2 class="mt-2 text-xl font-semibold tracking-tight">
                    Media gallery
                </h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    Images, videos and other media attached to this research.
                </p>
            </div>

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <a v-for="media in galleryMedia" :key="media.id" :href="media.url" target="_blank"
                    rel="noopener noreferrer" class="group border border-border bg-card">
                    <div class="relative aspect-video overflow-hidden bg-muted">
                        <img v-if="media.mime_type?.startsWith('image/')" :src="media.url"
                            :alt="media.alt || media.name"
                            class="size-full object-cover transition-transform duration-300 group-hover:scale-[1.02]">

                        <video v-else-if="media.mime_type?.startsWith('video/')" :src="media.url"
                            class="size-full object-cover" muted preload="metadata" />

                        <div v-else
                            class="flex size-full flex-col items-center justify-center gap-2 text-muted-foreground">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"
                                class="size-8">
                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                <path d="M14 2v6h6M8 13h8M8 17h8" />
                            </svg>
                            <span class="text-xs">Open media</span>
                        </div>
                    </div>

                    <div class="flex items-center justify-between gap-3 p-3">
                        <div class="min-w-0">
                            <p class="truncate text-sm font-medium">
                                {{ media.name || media.file_name }}
                            </p>
                            <p class="mt-1 text-xs text-muted-foreground">
                                {{ media.extension?.toUpperCase() || media.mime_type }}
                            </p>
                        </div>

                        <span class="shrink-0 text-xs text-muted-foreground">
                            ↗
                        </span>
                    </div>
                </a>
            </div>
        </section>

        <!-- Research team -->
        <section class="space-y-5 border-t border-border pt-8">
            <div>
                <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-muted-foreground">
                    Project contributors
                </p>
                <h2 class="mt-2 text-xl font-semibold tracking-tight">
                    Research team
                </h2>
                <p class="mt-1 text-sm text-muted-foreground">
                    Internal members assigned to this research.
                </p>
            </div>

            <div v-if="research.members?.length"
                class="grid gap-px border border-border bg-border sm:grid-cols-2 lg:grid-cols-3">
                <div v-for="member in research.members" :key="member.id" class="flex items-center gap-3 bg-card p-4">
                    <div
                        class="flex size-11 shrink-0 items-center justify-center border border-border bg-muted text-sm font-semibold">
                        {{ member.user?.name?.charAt(0)?.toUpperCase() ?? '?' }}
                    </div>

                    <div class="min-w-0">
                        <p class="truncate text-sm font-semibold">
                            {{ member.user?.name ?? 'Team member' }}
                        </p>

                        <p class="mt-1 text-xs text-muted-foreground">
                            {{ member.role?.name ?? 'Research member' }}
                        </p>
                    </div>
                </div>
            </div>

            <p v-else class="text-sm text-muted-foreground">
                No team members have been assigned.
            </p>
        </section>

        <!-- Workspace -->
        <section class="space-y-5 border-t border-border pt-8">
            <div class="flex flex-wrap items-end justify-between gap-3">
                <div>
                    <p class="text-[10px] font-semibold uppercase tracking-[0.18em] text-muted-foreground">
                        Administration
                    </p>
                    <h2 class="mt-2 text-xl font-semibold tracking-tight">
                        Research workspace
                    </h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Manage the different parts of this research project.
                    </p>
                </div>

                <span class="text-xs text-muted-foreground">
                    {{ totalModules }} modules
                </span>
            </div>

            <div class="grid gap-px border border-border bg-border sm:grid-cols-2 lg:grid-cols-4">
                <Link v-for="(item, index) in workspaceItems" :key="item.title" :href="item.href"
                    class="group flex min-h-36 flex-col justify-between bg-card p-4 transition-colors hover:bg-muted/50">
                    <div class="flex items-start justify-between gap-3">
                        <div
                            class="flex size-9 items-center justify-center border border-border bg-background text-muted-foreground group-hover:text-foreground">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"
                                stroke-linecap="round" stroke-linejoin="round" class="size-4">
                                <path :d="item.icon" />
                            </svg>
                        </div>

                        <span class="text-xs tabular-nums text-muted-foreground">
                            {{ item.count ?? String(index + 1).padStart(2, '0') }}
                        </span>
                    </div>

                    <div class="mt-5">
                        <div class="flex items-center justify-between gap-2">
                            <h3 class="text-sm font-semibold">
                                {{ item.title }}
                            </h3>

                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7"
                                class="size-4 shrink-0 text-muted-foreground transition-transform group-hover:translate-x-1">
                                <path d="M5 12h14m-6-6 6 6-6 6" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>

                        <p class="mt-1 text-xs leading-5 text-muted-foreground">
                            {{ item.description }}
                        </p>
                    </div>
                </Link>
            </div>
        </section>

    </div>
</template>
