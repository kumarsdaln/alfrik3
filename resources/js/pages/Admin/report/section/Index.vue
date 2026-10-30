<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import { Eye, Pencil, Plus, Trash2 } from '@lucide/vue'

import AppPagination from '@/components/ui/AppPagination.vue'
import AppTable from '@/components/ui/AppTable.vue'
import AppTableActions from '@/components/ui/AppTableActions.vue'
import Button from '@/components/ui/button/Button.vue'
import Heading from '@/components/Heading.vue'
import BackButton from '@/components/ui/BackButton.vue'
import TableLayout from '@/layouts/table/TableLayout.vue'

import {
    create,
    show,
    edit,
    destroy,
} from '@/routes/admin/report/sections'

import { index as manageBlock } from '@/routes/admin/report/sections/blocks'

import type { Pagination, Report, ReportSection } from '@/types'

interface Props {
    report: {
        data: Report
    }
    sections: Pagination<ReportSection>
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
        key: 'title',
        label: 'Title',
    },
    {
        key: 'subtitle',
        label: 'Subtitle',
    },
    {
        key: 'actions',
        label: '',
        width: '64px',
        align: 'right' as const,
    },
]

function deleteSection(section: ReportSection) {
    if (
        !confirm(
            `Are you sure you want to delete "${section.title}"?`,
        )
    ) {
        return
    }

    router.delete(
        destroy({
            report: props.report.data.id,
            section: section.id,
        }).url,
        {
            preserveScroll: true,
        },
    )
}

function getSectionActions(
    section: ReportSection,
) {
    return [
        {
            label: 'View',
            icon: Eye,
            href: show({
                report: props.report.data.id,
                section: section.id,
            }).url,
        },
        {
            label: 'Edit',
            icon: Pencil,
            href: edit({
                report: props.report.data.id,
                section: section.id,
            }).url,
        },
        {
            label: 'Manage Block',
            icon: Pencil,
            href: manageBlock({
                report: props.report.data.id,
                section: section.id,
            }).url,
        },
        {
            label: 'Delete',
            icon: Trash2,
            danger: true,
            onClick: () => deleteSection(section),
        },
    ]
}
</script>

<template>
    <TableLayout>
        <template #header>
            <div
                class="flex items-center justify-between gap-4 py-5"
            >
                <div class="flex gap-4">
                    <BackButton />

                    <Heading
                        title="Report Sections"
                        :description="report.data.title"
                    />
                </div>

                <Button as-child class="gap-2">
                    <Link :href="create(report.data.id).url">
                        <Plus class="size-4" />
                        Add Section
                    </Link>
                </Button>
            </div>
        </template>

        <div class="py-6">
            <AppTable
                :columns="columns"
                :data="sections.data"
            >
                <template #cell-position="{ row }">
                    <span class="text-sm text-muted-foreground">
                        {{ row.position + 1 }}
                    </span>
                </template>

                <template #cell-title="{ row }">
                    <div class="min-w-0">
                        <Link
                            :href="
                                show({
                                    report: props.report.data.id,
                                    section: row.id,
                                }).url
                            "
                            class="block truncate font-medium hover:text-primary"
                        >
                            {{ row.title }}
                        </Link>

                        <div
                            v-if="row.content"
                            class="mt-1 line-clamp-1 text-sm text-muted-foreground"
                        >
                            {{ row.content }}
                        </div>
                    </div>
                </template>

                <template #cell-subtitle="{ row }">
                    <span
                        v-if="row.subtitle"
                        class="text-sm text-muted-foreground"
                    >
                        {{ row.subtitle }}
                    </span>

                    <span
                        v-else
                        class="text-sm text-muted-foreground"
                    >
                        —
                    </span>
                </template>

                <template #cell-actions="{ row }">
                    <AppTableActions
                        :actions="getSectionActions(row)"
                    />
                </template>
            </AppTable>

            <div
                v-if="sections.data.length === 0"
                class="py-12 text-center"
            >
                <p class="font-medium">
                    No sections yet
                </p>

                <p class="mt-1 text-sm text-muted-foreground">
                    Add the first section to start building this report.data.
                </p>

                <Button
                    as-child
                    class="mt-4"
                >
                    <Link :href="create(report.data.id).url">
                        <Plus class="mr-2 size-4" />
                        Add Section
                    </Link>
                </Button>
            </div>
        </div>
    </TableLayout>
</template>