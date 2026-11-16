<script setup lang="ts">
    import { computed } from 'vue'
    import { Head, Link } from '@inertiajs/vue3'
    import {
        ArrowUpRight,
        BookOpen,
        CalendarDays,
        FileText,
        Layers3,
        ListTree,
        Pencil,
        Plus,
    } from '@lucide/vue'

    import BackButton from '@/components/ui/BackButton.vue'
    import Badge from '@/components/ui/badge/Badge.vue'
    import Button from '@/components/ui/button/Button.vue'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppQuote from '@/components/ui/AppQuote.vue'
    import AppImage from '@/components/ui/AppImage.vue'
    import AppStatistic from '@/components/ui/AppStatistic.vue'
    import AppChart from '@/components/ui/AppChart.vue'

    import {
        edit as reportEdit,
    } from '@/routes/admin/report'

    import {
        create as createSection,
        index as sectionsIndex,
    } from '@/routes/admin/report/sections'

    import type {
        Report,
        ReportContentBlock,
    } from '@/types/report'
    import Heading from '@/components/Heading.vue'

    interface ReportSectionWithBlocks {
        id: number
        title: string
        subtitle?: string | null
        content?: string | null
        position: number
        blocks?: ReportContentBlock[]
    }

    interface Props {
        report: {
            data: Report & {
                sections?: ReportSectionWithBlocks[]
                author?: {
                    name: string
                } | null
            }
        }
    }

    const props = defineProps<Props>()

    const report = computed(() => props.report.data)

    const sections = computed(() =>
        [...(report.value.sections ?? [])].sort(
            (a, b) => a.position - b.position,
        ),
    )

    const totalBlocks = computed(() =>
        sections.value.reduce(
            (total, section) => total + (section.blocks?.length ?? 0),
            0,
        ),
    )

    const reportDate = computed(() => {
        if (!report.value.report_date) return null

        return new Intl.DateTimeFormat('en', {
            day: '2-digit',
            month: 'short',
            year: 'numeric',
        }).format(new Date(report.value.report_date))
    })

    const editUrl = computed(() =>
        reportEdit.url({ report: report.value.id }),
    )

    const sectionsUrl = computed(() =>
        sectionsIndex.url({ report: report.value.id }),
    )

    const createSectionUrl = computed(() =>
        createSection.url({ report: report.value.id }),
    )

    const getBlockContent = (block: ReportContentBlock) =>
        block.content && typeof block.content === 'object'
            ? block.content
            : {}

    const getChartData = (block: ReportContentBlock) => {
        const content = getBlockContent(block)

        return {
            labels: Array.isArray(content.labels) ? content.labels : [],
            datasets: Array.isArray(content.datasets) ? content.datasets : [],
        }
    }
</script>

<template>

    <Head :title="report.title" />

    <div class="w-full space-y-10 pb-12 pt-4">

        <!-- Header -->
        <header class="space-y-7">
            <div class="flex flex-wrap items-center justify-between gap-4">
                <div class="flex gap-4">
                    <BackButton />
                    <div class="max-w-5xl space-y-5">
                        <div class="flex flex-wrap items-center gap-2">
                            <Badge variant="secondary">
                                {{ report.type.label }}
                            </Badge>

                            <Badge :color="report.status.color">
                                {{ report.status.label }}
                            </Badge>

                            <Badge v-if="report.featured" variant="secondary"
                                class="border-brand bg-brand/10 text-brand">
                                Featured
                            </Badge>
                        </div>

                        <Heading :title="report.title" :description="report.subtitle ?? report.description" />
                    </div>
                </div>

                <Button as-child variant="outline" class="gap-2">
                    <Link :href="editUrl">
                        <Pencil class="size-4" />
                        Edit Report
                    </Link>
                </Button>
            </div>



            <!-- Metadata -->
            <div class="flex flex-wrap items-center gap-x-7 gap-y-3 border-y py-4 text-sm">
                <div class="flex items-center gap-2">
                    <span class="text-muted-foreground">Author</span>
                    <span class="font-medium">
                        {{ report.author?.name ?? '—' }}
                    </span>
                </div>

                <div v-if="reportDate" class="flex items-center gap-2">
                    <CalendarDays class="size-4 text-muted-foreground" />
                    <span>{{ reportDate }}</span>
                </div>

                <div class="flex items-center gap-2">
                    <Layers3 class="size-4 text-muted-foreground" />
                    <span>{{ sections.length }} sections</span>
                </div>

                <div class="flex items-center gap-2">
                    <FileText class="size-4 text-muted-foreground" />
                    <span>{{ totalBlocks }} content blocks</span>
                </div>
            </div>
        </header>

        <!-- Executive summary -->
        <section v-if="report.summary"
            class="grid gap-5 border-l-2 border-foreground/70 bg-muted/30 p-5 sm:grid-cols-[180px_minmax(0,1fr)] sm:gap-8 sm:p-7">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-muted-foreground">
                    Overview
                </p>

                <h2 class="mt-2 text-lg font-semibold tracking-tight">
                    Executive Summary
                </h2>
            </div>

            <div class="max-w-4xl">
                <AppText>{{ report.summary }}</AppText>    
            </div>
        </section>

        <!-- Report contents -->
        <section class="space-y-7">

            <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                <div class="space-y-2">
                    <p class="text-xs font-semibold uppercase tracking-widest text-muted-foreground">
                        Contents
                    </p>

                    <h2 class="text-2xl font-semibold tracking-tight">
                        Report Structure
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Explore the sections and findings of this report.
                    </p>
                </div>

                <div class="flex flex-wrap gap-2">
                    <Link :href="sectionsUrl">
                        <Button variant="outline" class="gap-2">
                            <ListTree class="size-4" />
                            Manage Sections
                        </Button>
                    </Link>

                    <Link :href="createSectionUrl">
                        <Button class="gap-2">
                            <Plus class="size-4" />
                            Add Section
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Empty state -->
            <div v-if="!sections.length" class="border border-dashed px-6 py-16 text-center">
                <div class="mx-auto flex size-12 items-center justify-center bg-muted">
                    <BookOpen class="size-5 text-muted-foreground" />
                </div>

                <h3 class="mt-4 text-base font-semibold">
                    Your report is ready to be structured
                </h3>

                <p class="mx-auto mt-2 max-w-sm text-sm leading-6 text-muted-foreground">
                    Create your first section to start adding research content,
                    statistics, charts, images, and findings.
                </p>

                <Link :href="createSectionUrl" class="mt-5 inline-flex">
                    <Button class="gap-2">
                        <Plus class="size-4" />
                        Create First Section
                    </Button>
                </Link>
            </div>

            <!-- Section list -->
            <div v-else class="divide-y border-y">
                <article v-for="(section, index) in sections" :key="section.id"
                    class="grid gap-5 py-9 sm:grid-cols-[56px_minmax(0,1fr)] sm:gap-7">
                    <!-- Number -->
                    <div class="pt-1">
                        <span class="font-mono text-2xl font-light tabular-nums text-muted-foreground">
                            {{ String(index + 1).padStart(2, '0') }}
                        </span>
                    </div>

                    <!-- Section -->
                    <div class="min-w-0 space-y-7">

                        <header class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div class="space-y-2">
                                <h3 class="text-xl font-semibold tracking-tight">
                                    {{ section.title }}
                                </h3>

                                <p v-if="section.subtitle" class="text-sm text-muted-foreground">
                                    {{ section.subtitle }}
                                </p>
                            </div>

                            <Link :href="sectionsUrl"
                                class="inline-flex shrink-0 items-center gap-1 text-sm font-medium text-muted-foreground transition-colors hover:text-foreground">
                                Manage
                                <ArrowUpRight class="size-4" />
                            </Link>
                        </header>

                        <!-- Section introduction -->
                        <div v-if="section.content" class="max-w-4xl">
                            <AppText>{{ section.content }}</AppText>
                        </div>

                        <!-- Actual content blocks -->
                        <div v-if="section.blocks?.length" class="space-y-10">
                            <template v-for="block in section.blocks" :key="block.id">
                                <!-- Heading -->
                                <AppHeading v-if="block.type.value === 'heading'" >
                                    {{ getBlockContent(block).text }}
                                </AppHeading>

                                <!-- Text -->
                                <div v-else-if="block.type.value === 'text'" class="max-w-4xl">
                                    <AppText>
                                        {{ getBlockContent(block).text }}
                                    </AppText>  
                                </div>

                                <!-- Quote -->
                                <AppQuote v-else-if="block.type.value === 'quote'"
                                    :text="getBlockContent(block).quote ?? getBlockContent(block).text ?? ''"
                                    :author="getBlockContent(block).author" :role="getBlockContent(block).role"
                                    :source="getBlockContent(block).source" />

                                <!-- Image -->
                                <AppImage v-else-if="block.type.value === 'image'"
                                    :src="getBlockContent(block).url ?? ''" :alt="getBlockContent(block).alt ?? ''"
                                    :caption="getBlockContent(block).caption" />

                                <!-- Statistic -->
                                <AppStatistic v-else-if="block.type.value === 'statistic'"
                                    :value="getBlockContent(block).value ?? ''"
                                    :label="getBlockContent(block).label ?? block.title ?? ''"
                                    :description="getBlockContent(block).description"
                                    :source="getBlockContent(block).source"
                                    :variant="getBlockContent(block).variant ?? 'default'" />

                                <!-- Chart -->
                                <AppChart v-else-if="block.type.value === 'chart'"
                                    :type="getBlockContent(block).chart_type ?? 'bar'" :data="getChartData(block)"
                                    :aria-label="block.title ?? 'Report chart'" />

                                <!-- Finding -->
                                <div v-else-if="block.type.value === 'finding'"
                                    class="max-w-4xl border-l-2 border-foreground/70 pl-5">
                                    <p
                                        class="mb-2 text-xs font-semibold uppercase tracking-widest text-muted-foreground">
                                        Key Finding
                                    </p>

                                    <AppText>
                                        {{ getBlockContent(block).finding ?? '' }}
                                    </AppText>  

                                    <p v-if="getBlockContent(block).evidence"
                                        class="mt-3 text-sm leading-6 text-muted-foreground">
                                        {{ getBlockContent(block).evidence }}
                                    </p>
                                </div>

                                <!-- Recommendation -->
                                <div v-else-if="block.type.value === 'recommendation'"
                                    class="max-w-4xl border-l-2 border-brand pl-5">
                                    <p
                                        class="mb-2 text-xs font-semibold uppercase tracking-widest text-muted-foreground">
                                        Recommendation
                                    </p>

                                    <AppText>
                                        {{ getBlockContent(block).recommendation ?? '' }}
                                    </AppText>

                                    <p v-if="getBlockContent(block).rationale"
                                        class="mt-3 text-sm leading-6 text-muted-foreground">
                                        {{ getBlockContent(block).rationale }}
                                    </p>
                                </div>

                                <!-- Table -->
                                <div v-else-if="block.type.value === 'table'" class="max-w-full overflow-x-auto">
                                    <table class="w-full border-collapse text-left text-sm">
                                        <thead>
                                            <tr class="border-y bg-muted/30">
                                                <th v-for="(column, columnIndex) in (getBlockContent(block).columns ?? getBlockContent(block).headers ?? [])"
                                                    :key="columnIndex" class="px-4 py-3 font-semibold">
                                                    {{ column }}
                                                </th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr v-for="(row, rowIndex) in (getBlockContent(block).rows ?? [])"
                                                :key="rowIndex" class="border-b">
                                                <td v-for="(cell, cellIndex) in row" :key="cellIndex"
                                                    class="px-4 py-3 text-muted-foreground">
                                                    {{ cell }}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </template>
                        </div>

                        <!-- No content -->
                        <div v-else class="flex items-center gap-3 border-t py-5">
                            <FileText class="size-4 text-muted-foreground" />

                            <p class="text-sm text-muted-foreground">
                                No content blocks have been added to this section.
                            </p>
                        </div>

                        <!-- Section footer -->
                        <div class="border-t pt-4 text-xs text-muted-foreground">
                            {{ section.blocks?.length ?? 0 }}
                            {{ section.blocks?.length === 1 ? 'content block' : 'content blocks' }}
                        </div>
                    </div>
                </article>
            </div>
        </section>
    </div>
</template>