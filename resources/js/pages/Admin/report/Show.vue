<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3'
    import {
        ArrowLeft,
        Edit,
        FileText,
        Plus,
    } from '@lucide/vue'

    import AppButton from '@/components/ui/AppButton.vue'
    import Badge from '@/components/ui/Badge.vue'
    import Heading from '@/components/Heading.vue'
    import TableLayout from '@/layouts/table/Layout.vue'

    import { edit as reportEdit } from '@/routes/admin/report'

    import {
        create as createSection,
        index as sectionsIndex,
    } from '@/routes/admin/report/sections'

    import type {
        Report,
        ReportContentBlock,
    } from '@/types/report'

    interface Props {
        report: Report
    }

    const props = defineProps<Props>()

    const getBlockPreview = (
        block: ReportContentBlock,
    ): string => {
        if (!block.content) {
            return ''
        }

        switch (block.type.value) {
            case 'text':
                return String(
                    block.content.text ?? '',
                )

            case 'heading':
                return String(
                    block.content.text ?? '',
                )

            case 'quote':
                return String(
                    block.content.quote ?? '',
                )

            case 'statistic':
                return String(
                    block.content.value ?? '',
                )

            case 'finding':
                return String(
                    block.content.finding ?? '',
                )

            case 'recommendation':
                return String(
                    block.content.recommendation ?? '',
                )

            default:
                return ''
        }
    }
</script>

<template>

    <Head :title="report.title" />

    <TableLayout>
        <div class="space-y-8">
            <!-- HEADER -->

            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                <div class="flex items-start gap-3">
                    <Link href="/admin/report">
                        <AppButton variant="ghost" size="icon" type="button">
                            <ArrowLeft class="size-4" />
                        </AppButton>
                    </Link>

                    <Heading :title="report.title" :description="report.subtitle ??
                        'Report details'
                        " />
                </div>

                <div class="flex gap-2">
                    <Link :href="reportEdit.url({
                        report: report.id,
                    })
                        ">
                        <AppButton variant="outline">
                            <Edit class="mr-2 size-4" />
                            Edit
                        </AppButton>
                    </Link>
                </div>
            </div>

            <!-- META -->

            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="rounded-lg border p-4">
                    <p class="text-xs text-muted-foreground">
                        Type
                    </p>

                    <div class="mt-2">
                        <Badge :style="{
                            color: report.type.color,
                        }">
                            {{ report.type.label }}
                        </Badge>
                    </div>
                </div>

                <div class="rounded-lg border p-4">
                    <p class="text-xs text-muted-foreground">
                        Status
                    </p>

                    <div class="mt-2">
                        <Badge :variant="report.status.color
                            ">
                            {{ report.status.label }}
                        </Badge>
                    </div>
                </div>

                <div class="rounded-lg border p-4">
                    <p class="text-xs text-muted-foreground">
                        Author
                    </p>

                    <p class="mt-2 text-sm font-medium">
                        {{ report.author?.name ?? '—' }}
                    </p>
                </div>

                <div class="rounded-lg border p-4">
                    <p class="text-xs text-muted-foreground">
                        Sections
                    </p>

                    <p class="mt-2 text-sm font-medium">
                        {{ report.sections?.length ?? 0 }}
                    </p>
                </div>
            </div>

            <!-- SUMMARY -->

            <div v-if="report.summary" class="rounded-lg border bg-background p-6">
                <h2 class="text-base font-semibold">
                    Executive Summary
                </h2>

                <p class="mt-3 whitespace-pre-line text-sm leading-7 text-muted-foreground">
                    {{ report.summary }}
                </p>
            </div>

            <!-- SECTIONS -->

            <div class="space-y-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-lg font-semibold">
                            Report Structure
                        </h2>

                        <p class="text-sm text-muted-foreground">
                            Manage the sections and content of this report.
                        </p>
                    </div>

                    <div class="flex gap-2">
                        <Link :href="sectionsIndex.url({
                            report: report.id,
                        })
                            ">
                            <AppButton variant="outline">
                                Manage Sections
                            </AppButton>
                        </Link>

                        <Link :href="createSection.url({
                            report: report.id,
                        })
                            ">
                            <AppButton>
                                <Plus class="mr-2 size-4" />
                                Add Section
                            </AppButton>
                        </Link>
                    </div>
                </div>

                <div v-if="
                    report.sections?.length
                " class="space-y-4">
                    <div v-for="section in report.sections" :key="section.id" class="rounded-lg border bg-background">
                        <div class="flex items-start justify-between gap-4 border-b p-5">
                            <div class="flex gap-4">
                                <div
                                    class="flex size-8 shrink-0 items-center justify-center rounded-md border text-sm font-medium">
                                    {{ section.position + 1 }}
                                </div>

                                <div>
                                    <h3 class="font-semibold">
                                        {{ section.title }}
                                    </h3>

                                    <p v-if="section.subtitle" class="mt-1 text-sm text-muted-foreground">
                                        {{ section.subtitle }}
                                    </p>
                                </div>
                            </div>

                            <Link :href="sectionsIndex.url({
                                report: report.id,
                            })
                                ">
                                <AppButton variant="ghost" size="sm">
                                    Manage
                                </AppButton>
                            </Link>
                        </div>

                        <div class="p-5">
                            <p v-if="section.content"
                                class="whitespace-pre-line text-sm leading-7 text-muted-foreground">
                                {{ section.content }}
                            </p>

                            <div v-if="
                                section.content_blocks?.length
                            " class="mt-5 space-y-3">
                                <div v-for="block in section.content_blocks" :key="block.id"
                                    class="rounded-md border p-4">
                                    <div class="flex items-center gap-2">
                                        <Badge variant="secondary">
                                            {{ block.type.label }}
                                        </Badge>

                                        <span v-if="block.title" class="text-sm font-medium">
                                            {{ block.title }}
                                        </span>
                                    </div>

                                    <p v-if="
                                        getBlockPreview(block)
                                    " class="mt-2 line-clamp-2 text-sm text-muted-foreground">
                                        {{
                                            getBlockPreview(
                                                block,
                                            )
                                        }}
                                    </p>
                                </div>
                            </div>

                            <div v-else class="mt-4 rounded-md border border-dashed px-4 py-6 text-center">
                                <FileText class="mx-auto size-5 text-muted-foreground" />

                                <p class="mt-2 text-sm text-muted-foreground">
                                    No content blocks in this section.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div v-else class="rounded-lg border border-dashed px-6 py-12 text-center">
                    <FileText class="mx-auto size-6 text-muted-foreground" />

                    <p class="mt-3 font-medium">
                        No sections yet
                    </p>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Start building the structure of this report.
                    </p>

                    <Link class="mt-4 inline-flex" :href="createSection.url({
                        report: report.id,
                    })
                        ">
                        <AppButton>
                            <Plus class="mr-2 size-4" />
                            Add Section
                        </AppButton>
                    </Link>
                </div>
            </div>
        </div>
    </TableLayout>
</template>