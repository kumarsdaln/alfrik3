<script setup lang="ts">
    import { Link } from '@inertiajs/vue3'
    import {
        ArrowLeft,
        Edit,
        Plus,
    } from '@lucide/vue'

    import AppButton from '@/components/ui/AppButton.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import Heading from '@/components/Heading.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import {
        edit,
    } from '@/routes/admin/report/sections'

    import { index as sectionIndex } from '@/routes/admin/report/sections'

    import type {
        Report,
        ReportSection,
        ReportContentBlock,
    } from '@/types/report'

    interface Props {
        report: Report
        section: ReportSection & {
            content_blocks?: ReportContentBlock[]
        }
    }

    const props = defineProps<Props>()

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
</script>

<template>
    <TableLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4 py-5">
                <div class="flex items-center gap-4">
                    <Link :href="sectionIndex({
                        report: props.report.id,
                    }).url
                        ">
                        <AppButton variant="ghost" size="icon" type="button">
                            <ArrowLeft class="size-4" />
                        </AppButton>
                    </Link>

                    <Heading title="Report Section" :description="props.report.title" />
                </div>

                <Link :href="edit({
                    report: props.report.id,
                    section: props.section.id,
                }).url
                    ">
                    <AppButton>
                        <Edit class="mr-2 size-4" />
                        Edit Section
                    </AppButton>
                </Link>
            </div>
        </template>

        <div class="space-y-8 py-6">
            <!-- Section Information -->

            <section class="rounded-lg border bg-background">
                <div class="border-b px-6 py-5">
                    <div class="flex items-start justify-between gap-4">
                        <div class="min-w-0">
                            <p class="mb-2 text-sm font-medium text-muted-foreground">
                                Section {{ props.section.position + 1 }}
                            </p>

                            <h2 class="text-xl font-semibold">
                                {{ props.section.title }}
                            </h2>

                            <p v-if="props.section.subtitle" class="mt-2 text-sm text-muted-foreground">
                                {{ props.section.subtitle }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-6">
                    <div v-if="props.section.content" class="prose max-w-none dark:prose-invert">
                        {{ props.section.content }}
                    </div>

                    <div v-else class="py-8 text-center text-sm text-muted-foreground">
                        No section content has been added.
                    </div>
                </div>
            </section>

            <!-- Content Blocks -->

            <section>
                <div class="mb-4 flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-semibold">
                            Content Blocks
                        </h2>

                        <p class="mt-1 text-sm text-muted-foreground">
                            Manage the content blocks that make up this
                            section.
                        </p>
                    </div>

                    <AppButton>
                        <Plus class="mr-2 size-4" />
                        Add Block
                    </AppButton>
                </div>

                <div v-if="props.section.content_blocks?.length" class="rounded-lg border bg-background">
                    <AppTable :columns="columns" :data="props.section.content_blocks">
                        <template #cell-position="{ row }">
                            <span class="text-sm text-muted-foreground">
                                {{ row.position + 1 }}
                            </span>
                        </template>

                        <template #cell-type="{ row }">
                            <span class="text-sm font-medium">
                                {{ row.type }}
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
                            <AppTableActions :actions="[]" />
                        </template>
                    </AppTable>
                </div>

                <div v-else class="rounded-lg border bg-background px-6 py-12 text-center">
                    <p class="font-medium">
                        No content blocks yet
                    </p>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Add content blocks to build this section.
                    </p>

                    <AppButton class="mt-4">
                        <Plus class="mr-2 size-4" />
                        Add Block
                    </AppButton>
                </div>
            </section>
        </div>
    </TableLayout>
</template>