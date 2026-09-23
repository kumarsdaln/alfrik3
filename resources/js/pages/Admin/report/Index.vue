<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { Eye, FileText, Pencil, Plus, Trash2 } from '@lucide/vue'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppStats from '@/components/ui/AppStats.vue'
import AppTable from '@/components/ui/AppTable.vue'
import AppTableActions, { TableAction } from '@/components/ui/AppTableActions.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import Date from '@/components/datadisplay/Date.vue'
import FilterControl from '@/components/filters/FilterControl.vue'
import AppPagination from '@/components/ui/AppPagination.vue'
import TableLayout from '@/layouts/table/TableLayout.vue'

import {
    create as reportCreate,
    edit as reportEdit,
    show as reportShow,
    destroy as reportDestroy,
    index as reportIndex,
} from '@/routes/admin/report'

import { index as manageMedia } from '@/routes/admin/media'
import { index as manageCategory } from '@/routes/admin/categories/assignment'
import { index as manageTags } from '@/routes/admin/tags/assignment'

import type { Pagination, Report } from '@/types'
import { computed } from 'vue'

interface ReportItem extends Report {
    research?: {
        id: number
        title: string
    } | null

    author?: {
        id: number
        name: string
    } | null
}

interface ReportStats {
    total: number
    draft: number
    published: number
    featured: number
}

interface FilterOption {
    value: string
    label: string
}

interface Props {
    reports: Pagination<ReportItem>
    stats: ReportStats
    filters: {
        search?: string
        status?: string
        type?: string
    }
    statusOptions: FilterOption[]
    typeOptions: FilterOption[]
}

const props = defineProps<Props>()

const statItems = computed(() => [
    {
        label: 'Total Interviews',
        value: props.stats.total,
    },
    {
        label: 'Published',
        value: props.stats.published,
    },
    {
        label: 'Drafts',
        value: props.stats.draft,
    },
    {
        label: 'Featured',
        value: props.stats.featured,
    },
])

function filter(
    key: 'search' | 'status' | 'type',
    value: string | null,
) {
    const params = {
        ...props.filters,
        [key]: value || undefined,
    }

    router.get(
        reportIndex.url(),
        params,
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    )
}

function deleteReport(report: ReportItem) {
    if (
        !confirm(
            `Are you sure you want to delete "${report.title}"?`,
        )
    ) {
        return
    }

    router.delete(
        reportDestroy(report.id).url,
        {
            preserveScroll: true,
        },
    )
}

function getInterviewActions(interview: Report): TableAction[] {
    return [
        {
            label: 'View',
            icon: Eye,
            href: reportShow(interview.id).url,
        },
        {
            label: 'Edit',
            icon: Pencil,
            href: reportEdit(interview.id).url,
        },
        {
            label: 'Manage Category',
            icon: Pencil,
            href: manageCategory({ type: 'interview', id: interview.id }).url,
        },
        {
            label: 'Manage Tags',
            icon: Pencil,
            href: manageTags({ type: 'interview', id: interview.id }).url,
        },
        {
            label: 'Manage Media',
            icon: Pencil,
            href: manageMedia({ type: 'interview', id: interview.id }).url,
        },
        {
            label: 'Delete',
            icon: Trash2,
            danger: true,
            onClick: () => deleteReport(interview),
        },
    ]
}
</script>

<template>

    <Head title="Reports" />

    <TableLayout>
        <template #header>
            <div class="
                    flex
                    flex-col
                    gap-4
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                ">
                <div>
                    <AppHeading tag="h1" size="2xl" weight="semibold">
                        Reports
                    </AppHeading>

                    <p class="
                            mt-1
                            text-sm
                            text-muted-foreground
                        ">
                        Manage research reports, publications, and
                        analytical documents.
                    </p>
                </div>

                <Link :href="reportCreate.url()" class="
                        inline-flex
                        w-full
                        items-center
                        justify-center
                        gap-2
                        border
                        border-primary
                        bg-primary
                        px-4
                        py-2.5
                        text-sm
                        font-medium
                        text-primary-foreground
                        transition-colors
                        hover:bg-primary/90
                        sm:w-auto
                    ">
                    <Plus :size="16" />
                    New Report
                </Link>
            </div>
        </template>

        <!-- ============================================================
             STATS
        ============================================================= -->

        <div class="
                grid
                gap-4
                sm:grid-cols-2
                xl:grid-cols-4
            ">
            <AppStats :items="statItems" />
        </div>

        <!-- ============================================================
             FILTERS
        ============================================================= -->

        <div class="
                flex
                flex-col
                gap-3
                sm:flex-row
                sm:items-center
                sm:justify-between
            ">
            <FilterControl :model-value="props.filters.search ?? ''" placeholder="Search reports..."
                @update:model-value="
                    filter('search', $event)
                    " />

            <div class="
                    flex
                    flex-col
                    gap-3
                    sm:flex-row
                ">
                <FilterControl :model-value="props.filters.status ?? ''" :options="statusOptions"
                    placeholder="All statuses" @update:model-value="
                        filter('status', $event)
                        " />

                <FilterControl :model-value="props.filters.type ?? ''" :options="typeOptions" placeholder="All types"
                    @update:model-value="
                        filter('type', $event)
                        " />
            </div>
        </div>

        <!-- ============================================================
             TABLE
        ============================================================= -->

        <AppTable :columns="[
            {
                key: 'title',
                label: 'Report',
            },
            {
                key: 'type',
                label: 'Type',
            },
            {
                key: 'research',
                label: 'Research',
            },
            {
                key: 'status',
                label: 'Status',
            },
            {
                key: 'published_at',
                label: 'Published',
            },
            {
                key: 'actions',
                label: '',
                align: 'right',
            },
        ]" :data="reports.data" empty-message="No reports found.">
            <template #cell-title="{ row }">
                <div class="min-w-0 max-w-md">
                    <Link :href="reportShow(row.id).url" class="
                            block
                            truncate
                            text-sm
                            font-medium
                            hover:text-primary
                        ">
                        {{ row.title }}
                    </Link>

                    <p v-if="row.subtitle" class="
                            mt-1
                            truncate
                            text-xs
                            text-muted-foreground
                        ">
                        {{ row.subtitle }}
                    </p>

                    <Badge v-if="row.featured" variant="secondary" class="mt-2">
                        Featured
                    </Badge>
                </div>
            </template>

            <template #cell-type="{ value }">
                <Badge :color="value.color">
                    {{ value.label }}
                </Badge>
            </template>

            <template #cell-research="{ row }">
                <Link v-if="row.research" :href="`/admin/research/${row.research.id}`" class="
                        text-sm
                        hover:text-primary
                    ">
                    {{ row.research.title }}
                </Link>

                <span v-else class="text-sm text-muted-foreground">
                    —
                </span>
            </template>

            <template #cell-status="{ value }">
                <Badge :color="value.color">
                    {{ value.label }}
                </Badge>
            </template>

            <template #cell-published_at="{ value }">
                <Date v-if="value" :value="value" />

                <span v-else class="text-sm text-muted-foreground">
                    —
                </span>
            </template>

            <template #cell-actions="{ row }">
                <!-- Actions -->
                <template #cell-actions="{ row }">
                    <AppTableActions :actions="getInterviewActions(row)" />
                </template>
            </template>
        </AppTable>

        <!-- ============================================================
             PAGINATION
        ============================================================= -->

        <AppPagination :meta="reports.meta" />
    </TableLayout>
</template>