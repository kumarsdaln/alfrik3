<script setup lang="ts">
    import { Head, Link, router } from '@inertiajs/vue3'
    import { ArrowLeft, Plus } from 'lucide-vue-next'

    import AppButton from '@/components/ui/AppButton.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import Heading from '@/components/Heading.vue'
    import TableLayout from '@/layouts/table/Layout.vue'

    import {
        create,
        edit,
        destroy,
    } from '@/routes/admin/report/sections'

    import { show as reportShow } from '@/routes/admin/report'

    import type { Report, ReportSection } from '@/types/report'

    interface Props {
        report: Report
        sections: {
            data: ReportSection[]
        }
    }

    const props = defineProps<Props>()

    const deleteSection = (section: ReportSection) => {
        if (
            !confirm(
                `Are you sure you want to delete "${section.title}"?`,
            )
        ) {
            return
        }

        router.delete(
            destroy.url({
                report: props.report.id,
                section: section.id,
            }),
        )
    }
</script>

<template>

    <Head :title="`${report.title} - Sections`" />

    <TableLayout>
        <div class="space-y-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-3">
                    <Link :href="reportShow.url({
                        report: report.id,
                    })
                        ">
                        <AppButton variant="ghost" size="icon" type="button">
                            <ArrowLeft class="size-4" />
                        </AppButton>
                    </Link>

                    <Heading title="Report Sections" :description="report.title" />
                </div>

                <Link :href="create.url({
                    report: report.id,
                })
                    ">
                    <AppButton>
                        <Plus class="mr-2 size-4" />
                        Add Section
                    </AppButton>
                </Link>
            </div>

            <div class="rounded-lg border bg-background">
                <AppTable :columns="[
                    {
                        key: 'position',
                        label: '#',
                        width: '80px',
                        align: 'center',
                    },
                    {
                        key: 'title',
                        label: 'Title',
                    },
                    {
                        key: 'subtitle',
                        label: 'Subtitle',
                    },
                    {
                        key: 'actions',
                        label: 'Actions',
                        align: 'right',
                        width: '120px',
                    },
                ]" :data="sections.data">
                    <template #cell-position="{ row }">
                        <span class="text-sm text-muted-foreground">
                            {{ row.position + 1 }}
                        </span>
                    </template>

                    <template #cell-title="{ row }">
                        <div class="min-w-0">
                            <div class="font-medium">
                                {{ row.title }}
                            </div>

                            <div v-if="row.content" class="mt-1 line-clamp-1 text-sm text-muted-foreground">
                                {{ row.content }}
                            </div>
                        </div>
                    </template>

                    <template #cell-subtitle="{ row }">
                        <span v-if="row.subtitle" class="text-sm text-muted-foreground">
                            {{ row.subtitle }}
                        </span>

                        <span v-else class="text-sm text-muted-foreground">
                            —
                        </span>
                    </template>

                    <template #cell-actions="{ row }">
                        <AppTableActions :edit-url="edit.url({
                            report: report.id,
                            section: row.id,
                        })
                            " @delete="deleteSection(row)" />
                    </template>
                </AppTable>
            </div>

            <div v-if="sections.data.length === 0" class="rounded-lg border bg-background px-6 py-12 text-center">
                <p class="font-medium">
                    No sections yet
                </p>

                <p class="mt-1 text-sm text-muted-foreground">
                    Add the first section to start building this report.
                </p>

                <Link class="mt-4 inline-flex" :href="create.url({
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
    </TableLayout>
</template>