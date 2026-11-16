<script setup lang="ts">
    import { computed } from 'vue'
    import { Link } from '@inertiajs/vue3'
    import {
        ArrowLeft,
        Edit,
        Plus,
        Eye,
        Trash2,
        Edit2,
    } from '@lucide/vue'

    import AppButton from '@/components/ui/AppButton.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import Heading from '@/components/Heading.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import {
        edit as editSection,
        index as sectionIndex,
    } from '@/routes/admin/report/sections'

    import {
        create as createBlock,
        show as showBlock,
        edit as editBlock,
        destroy as deleteBlock,
    } from '@/routes/admin/report/sections/blocks'

    import type {
        Report,
        ReportSection,
        ReportContentBlock,
    } from '@/types/report'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'

    interface Props {
        report: Report

        section: {
            data: ReportSection & {
                blocks?: ReportContentBlock[]
            }
        }
    }

    const props = defineProps<Props>()

    const blocks = computed(() => props.section.data.blocks ?? [])

    const columns = [
        {
            key: 'position',
            label: '#',
            width: '80px',
            align: 'center' as const,
        },
        {
            key: 'type',
            label: 'Type',
            width: '180px',
        },
        {
            key: 'title',
            label: 'Title',
        },
        {
            key: 'description',
            label: 'Description',
        },
        {
            key: 'actions',
            label: '',
            width: '64px',
            align: 'right' as const,
        },
    ]

    const createBlockUrl = computed(() =>
        createBlock({
            report: props.report.id,
            section: props.section.data.id,
        }).url
    )

    const sectionIndexUrl = computed(() =>
        sectionIndex({
            report: props.report.id,
        }).url
    )

    const sectionEditUrl = computed(() =>
        editSection({
            report: props.report.id,
            section: props.section.data.id,
        }).url
    )

    const blockActions = (block: ReportContentBlock) => [
        {
            label: 'View',
            icon: Eye,
            href: showBlock({
                report: props.report.id,
                section: props.section.data.id,
                block: block.id,
            }).url,
        },
        {
            label: 'Edit',
            icon: Edit,
            href: editBlock({
                report: props.report.id,
                section: props.section.data.id,
                block: block.id,
            }).url,
        },
        {
            label: 'Delete',
            icon: Trash2,
            href: deleteBlock({
                report: props.report.id,
                section: props.section.data.id,
                block: block.id,
            }).url,
            method: 'delete',
            confirm: true,
        },
    ]
</script>

<template>
    <TableLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4 border-b py-5">
                <div class="flex items-center gap-4">
                    <BackButton />

                    <Heading :title="section.data.title" :description="section.data.subtitle" />
                </div>
                <Button variant="outline" as-child class="gap-2">
                    <Link :href="editSection({
                        report: props.report.id,
                        section: props.section.data.id,
                    }).url">
                        <Edit2 class="size-4" />
                        Edit Section
                    </Link>
                </Button>
            </div>
        </template>

        <div class="space-y-8 py-6">
            <!-- Section Information -->
            <div>
                <div v-if="section.data.content" class="prose max-w-none dark:prose-invert">
                    {{ section.data.content }}
                </div>

                <div v-else class="py-8 text-center text-sm text-muted-foreground">
                    No section content has been added.
                </div>
            </div>

            <!-- Content Blocks -->
            <section>
                <div class="mb-4 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold">
                            Content Blocks
                        </h2>

                        <p class="mt-1 text-sm text-muted-foreground">
                            Manage the content blocks that make up this section.data.
                        </p>
                    </div>
                    <Button as-child class="gap-2">
                        <Link :href="createBlock({
                            report: props.report.id,
                            section: props.section.data.id,
                        }).url">
                            <Plus class="size-4" />
                            Add Block
                        </Link>
                    </Button>
                </div>

                <div v-if="blocks.length" class="border bg-background">
                    <AppTable :columns="columns" :data="blocks">
                        <template #cell-position="{ row }">
                            <span class="text-sm text-muted-foreground">
                                {{ row.position }}
                            </span>
                        </template>

                        <template #cell-type="{ row }">
                            <span class="text-sm font-medium">
                                {{ typeof row.type === 'object' ? row.type.label : row.type }}
                            </span>
                        </template>

                        <template #cell-title="{ row }">
                            <span v-if="row.title" class="font-medium">
                                {{ row.title }}
                            </span>

                            <span v-else class="text-sm text-muted-foreground">
                                —
                            </span>
                        </template>

                        <template #cell-description="{ row }">
                            <span v-if="row.description" class="line-clamp-2 text-sm text-muted-foreground">
                                {{ row.description }}
                            </span>

                            <span v-else class="text-sm text-muted-foreground">
                                —
                            </span>
                        </template>

                        <template #cell-actions="{ row }">
                            <AppTableActions :actions="blockActions(row)" />
                        </template>
                    </AppTable>
                </div>

                <div v-else class="border bg-background px-6 py-12 text-center">
                    <h3 class="font-medium">
                        No content blocks yet
                    </h3>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Add content blocks to build this section.data.
                    </p>

                    <Link :href="createBlockUrl" class="mt-4 inline-flex">
                        <AppButton>
                            <Plus class="mr-2 size-4" />
                            Add Block
                        </AppButton>
                    </Link>
                </div>
            </section>
        </div>
    </TableLayout>
</template>