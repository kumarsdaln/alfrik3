<script setup lang="ts">
import { Head, Link, router } from '@inertiajs/vue3'
import { FileText, Plus } from '@lucide/vue'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppStats from '@/components/ui/AppStats.vue'
import AppTable from '@/components/ui/AppTable.vue'
import AppTableActions from '@/components/ui/AppTableActions.vue'
import Badge from '@/components/ui/Badge.vue'
import Date from '@/components/datadisplay/Date.vue'
import FilterControl from '@/components/ui/FilterControl.vue'
import AppPagination from '@/components/ui/AppPagination.vue'
import TableLayout from '@/layouts/table/Layout.vue'

import {
    create as reportCreate,
    edit as reportEdit,
    show as reportShow,
    destroy as reportDestroy,
    index as reportIndex,
} from '@/routes/admin/report'

import type { Pagination, Report } from '@/types'

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
</script>

<template>
    <Head title="Reports" />

    <TableLayout>
        <template #header>
            <div
                class="
                    flex
                    flex-col
                    gap-4
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                "
            >
                <div>
                    <AppHeading
                        tag="h1"
                        size="2xl"
                        weight="semibold"
                    >
                        Reports
                    </AppHeading>

                    <p
                        class="
                            mt-1
                            text-sm
                            text-muted-foreground
                        "
                    >
                        Manage research reports, publications, and
                        analytical documents.
                    </p>
                </div>

                <Link
                    :href="reportCreate.url()"
                    class="
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
                    "
                >
                    <Plus :size="16" />
                    New Report
                </Link>
            </div>
        </template>

        <!-- ============================================================
             STATS
        ============================================================= -->

        <div
            class="
                grid
                gap-4
                sm:grid-cols-2
                xl:grid-cols-4
            "
        >
            <AppStats
                title="Total Reports"
                :value="stats.total"
                :icon="FileText"
            />

            <AppStats
                title="Published"
                :value="stats.published"
                :icon="FileText"
            />

            <AppStats
                title="Drafts"
                :value="stats.draft"
                :icon="FileText"
            />

            <AppStats
                title="Featured"
                :value="stats.featured"
                :icon="FileText"
            />
        </div>

        <!-- ============================================================
             FILTERS
        ============================================================= -->

        <div
            class="
                flex
                flex-col
                gap-3
                sm:flex-row
                sm:items-center
                sm:justify-between
            "
        >
            <FilterControl
                :model-value="props.filters.search ?? ''"
                placeholder="Search reports..."
                @update:model-value="
                    filter('search', $event)
                "
            />

            <div
                class="
                    flex
                    flex-col
                    gap-3
                    sm:flex-row
                "
            >
                <FilterControl
                    :model-value="props.filters.status ?? ''"
                    :options="statusOptions"
                    placeholder="All statuses"
                    @update:model-value="
                        filter('status', $event)
                    "
                />

                <FilterControl
                    :model-value="props.filters.type ?? ''"
                    :options="typeOptions"
                    placeholder="All types"
                    @update:model-value="
                        filter('type', $event)
                    "
                />
            </div>
        </div>

        <!-- ============================================================
             TABLE
        ============================================================= -->

        <AppTable
            :columns="[
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
            ]"
            :data="reports.data"
            empty-message="No reports found."
        >
            <template #title="{ row }">
                <div class="min-w-0 max-w-md">
                    <Link
                        :href="reportShow(row.id).url"
                        class="
                            block
                            truncate
                            text-sm
                            font-medium
                            hover:text-primary
                        "
                    >
                        {{ row.title }}
                    </Link>

                    <p
                        v-if="row.subtitle"
                        class="
                            mt-1
                            truncate
                            text-xs
                            text-muted-foreground
                        "
                    >
                        {{ row.subtitle }}
                    </p>

                    <Badge
                        v-if="row.featured"
                        variant="secondary"
                        class="mt-2"
                    >
                        Featured
                    </Badge>
                </div>
            </template>

            <template #type="{ row }">
                <Badge :variant="row.type.color">
                    {{ row.type.label }}
                </Badge>
            </template>

            <template #research="{ row }">
                <Link
                    v-if="row.research"
                    :href="`/admin/research/${row.research.id}`"
                    class="
                        text-sm
                        hover:text-primary
                    "
                >
                    {{ row.research.title }}
                </Link>

                <span
                    v-else
                    class="text-sm text-muted-foreground"
                >
                    —
                </span>
            </template>

            <template #status="{ row }">
                <Badge :variant="row.status.color">
                    {{ row.status.label }}
                </Badge>
            </template>

            <template #published_at="{ row }">
                <Date
                    v-if="row.published_at"
                    :date="row.published_at"
                />

                <span
                    v-else
                    class="text-sm text-muted-foreground"
                >
                    —
                </span>
            </template>

            <template #actions="{ row }">
                <AppTableActions>
                    <template #default>
                        <Link
                            :href="reportShow(row.id).url"
                            class="
                                block
                                px-3
                                py-2
                                text-sm
                                hover:bg-muted
                            "
                        >
                            View
                        </Link>

                        <Link
                            :href="reportEdit(row.id).url"
                            class="
                                block
                                px-3
                                py-2
                                text-sm
                                hover:bg-muted
                            "
                        >
                            Edit
                        </Link>

                        <button
                            type="button"
                            class="
                                block
                                w-full
                                px-3
                                py-2
                                text-left
                                text-sm
                                text-destructive
                                hover:bg-muted
                            "
                            @click="deleteReport(row)"
                        >
                            Delete
                        </button>
                    </template>
                </AppTableActions>
            </template>
        </AppTable>

        <!-- ============================================================
             PAGINATION
        ============================================================= -->

        <AppPagination
            :pagination="reports"
        />
    </TableLayout>
</template>