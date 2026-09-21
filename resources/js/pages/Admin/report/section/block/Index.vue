<script setup lang="ts">
    import { Head, Link, router } from '@inertiajs/vue3'
    import { ArrowLeft, Plus } from '@lucide/vue'

    import AppButton from '@/components/ui/AppButton.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import Badge from '@/components/ui/Badge.vue'
    import Heading from '@/components/Heading.vue'
    import TableLayout from '@/layouts/table/Layout.vue'

    import {
        create,
        edit,
        destroy,
    } from '@/routes/admin/report/sections/blocks'

    import { index as sectionIndex } from '@/routes/admin/report/sections'

    import type {
        Report,
        ReportContentBlock,
        ReportSection,
    } from '@/types/report'

    interface Props {
        report: {
            id: number
            title: string
        }

        section: {
            id: number
            title: string
        }

        blocks: {
            data: ReportContentBlock[]
        }
    }

    const props = defineProps<Props>()

    const deleteBlock = (block: ReportContentBlock) => {
        if (
            !confirm(
                `Are you sure you want to delete "${block.title || block.type.label}"?`,
            )
        ) {
            return
        }

        router.delete(
            destroy.url({
                report: props.report.id,
                section: props.section.id,
                block: block.id,
            }),
        )
    }

    const typeBadgeVariant = (
        type: ReportContentBlock['type']['value'],
    ) => {
        switch (type) {
            case 'finding':
            case 'recommendation':
                return 'success'

            case 'statistic':
            case 'chart':
                return 'secondary'

            case 'quote':
                return 'outline'

            default:
                return 'secondary'
        }
    }
</script>

<template>

    <Head :title="`${section.title} - Content Blocks`" />

    <TableLayout>
        <div class="space-y-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <Link :href="sectionIndex.url({
                        report: report.id,
                        section: section.id,
                    })
                        ">
                        <AppButton variant="ghost" size="icon" type="button">
                            <ArrowLeft class="size-4" />
                        </AppButton>
                    </Link>

                    <Heading title="Content Blocks" :description="`${report.title} / ${section.title}`" />
                </div>

                <Link :href="create.url({
                    report: report.id,
                    section: section.id,
                })
                    ">
                    <AppButton>
                        <Plus class="mr-2 size-4" />
                        Add Block
                    </AppButton>
                </Link>
            </div>

            <div v-if="blocks.data.length" class="rounded-lg border bg-background">
                <AppTable :columns="[
                    {
                        key: 'position',
                        label: '#',
                        width: '80px',
                        align: 'center',
                    },
                    {
                        key: 'type',
                        label: 'Type',
                        width: '150px',
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
                        label: 'Actions',
                        align: 'right',
                        width: '120px',
                    },
                ]" :data="blocks.data">
                    <template #cell-position="{ row }">
                        <span class="text-sm text-muted-foreground">
                            {{ row.position + 1 }}
                        </span>
                    </template>

                    <template #cell-type="{ row }">
                        <Badge :variant="typeBadgeVariant(
                            row.type.value,
                        )
                            ">
                            {{ row.type.label }}
                        </Badge>
                    </template>

                    <template #cell-title="{ row }">
                        <div class="min-w-0">
                            <span v-if="row.title" class="font-medium">
                                {{ row.title }}
                            </span>

                            <span v-else class="text-muted-foreground">
                                Untitled block
                            </span>
                        </div>
                    </template>

                    <template #cell-description="{ row }">
                        <span v-if="row.description" class="line-clamp-1 text-sm text-muted-foreground">
                            {{ row.description }}
                        </span>

                        <span v-else class="text-sm text-muted-foreground">
                            —
                        </span>
                    </template>

                    <template #cell-actions="{ row }">
                        <AppTableActions :edit-url="edit.url({
                            report: report.id,
                            section: section.id,
                            block: row.id,
                        })
                            " @delete="deleteBlock(row)" />
                    </template>
                </AppTable>
            </div>

            <div v-else class="rounded-lg border bg-background px-6 py-12 text-center">
                <p class="font-medium">
                    No content blocks yet
                </p>

                <p class="mt-1 text-sm text-muted-foreground">
                    Add content blocks to build the section.
                </p>

                <Link class="mt-4 inline-flex" :href="create.url({
                    report: report.id,
                    section: section.id,
                })
                    ">
                    <AppButton>
                        <Plus class="mr-2 size-4" />
                        Add Block
                    </AppButton>
                </Link>
            </div>
        </div>
    </TableLayout>
</template>