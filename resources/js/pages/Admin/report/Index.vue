<script setup lang="ts">
    import { computed } from 'vue'
    import { Link, router } from '@inertiajs/vue3'
    import { Eye, Pencil, Plus, Trash2 } from '@lucide/vue'

    import Date from '@/components/datadisplay/Date.vue'
    import AppPagination from '@/components/ui/AppPagination.vue'
    import AppStats from '@/components/ui/AppStats.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import FilterControl from '@/components/filters/FilterControl.vue'
    import Badge from '@/components/ui/badge/Badge.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import { useFilters } from '@/composables/useFilters'

    import {
        create,
        show,
        edit,
        destroy,
    } from '@/routes/admin/report'

    import { index as manageMedia } from '@/routes/admin/media'
    import { index as manageCategory } from '@/routes/admin/categories/assignment'
    import { index as manageTags } from '@/routes/admin/tags/assignment'
    import { index as manageSeo } from '@/routes/admin/seo'
    import { index as manageSection } from '@/routes/admin/report/sections'

    import type { Pagination, Report, FormOption } from '@/types'
    import type { TableAction } from '@/components/ui/AppTableActions.vue'

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

    interface Props {
        reports: Pagination<ReportItem>

        statusOptions: FormOption[]

        typeOptions: FormOption[]

        filters: {
            search?: string
            status?: string
            type?: string
        }

        stats: {
            total: number
            published: number
            draft: number
            featured: number
        }
    }

    const props = defineProps<Props>()

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    const {
        filters,
        filterCount,
        applyFilters,
        clearFilters,
    } = useFilters(
        {
            search: props.filters.search ?? '',
            status: props.filters.status ?? '',
            type: props.filters.type ?? '',
        },
        {
            url: window.location.pathname,
            searchKey: 'search',
            debounce: 500,
        },
    )

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    const statItems = computed(() => [
        {
            label: 'Total Reports',
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

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    const columns = [
        {
            key: 'id',
            label: '#',
            width: '80px',
        },
        {
            key: 'title',
            label: 'Title',
        },
        {
            key: 'type',
            label: 'Type',
            width: '160px',
        },
        {
            key: 'research',
            label: 'Research',
            width: '240px',
        },
        {
            key: 'status',
            label: 'Status',
            width: '140px',
        },
        {
            key: 'published_at',
            label: 'Published At',
            width: '180px',
        },
        {
            key: 'actions',
            label: '',
            width: '64px',
            align: 'right' as const,
        },
    ]

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    function deleteReport(report: ReportItem) {
        if (
            !confirm(
                `Are you sure you want to delete "${report.title}"?`,
            )
        ) {
            return
        }

        router.delete(
            destroy(report.id).url,
            {
                preserveScroll: true,
            },
        )
    }

    function getReportActions(
        report: ReportItem,
    ): TableAction[] {
        return [
            {
                label: 'View',
                icon: Eye,
                href: show(report.id).url,
            },
            {
                label: 'Edit',
                icon: Pencil,
                href: edit(report.id).url,
            },
            {
                label: 'Manage Category',
                icon: Pencil,
                href: manageCategory({
                    type: 'report',
                    id: report.id,
                }).url,
            },
            {
                label: 'Manage Tags',
                icon: Pencil,
                href: manageTags({
                    type: 'report',
                    id: report.id,
                }).url,
            },
            {
                label: 'Manage Media',
                icon: Pencil,
                href: manageMedia({
                    type: 'report',
                    id: report.id,
                }).url,
            },
            {
                label: 'Manage Sections',
                icon: Pencil,
                href: manageSection(report.id).url,
            },
            {
                label: 'Manage SEO',
                icon: Pencil,
                href: manageSeo({
                    type: 'report',
                    id: report.id,
                }).url,
            },
            {
                label: 'Delete',
                icon: Trash2,
                danger: true,
                onClick: () => deleteReport(report),
            },
        ]
    }
</script>

<template>
    <TableLayout>
        <!-- Header -->

        <template #header>
            <div class="flex items-center justify-between gap-4 py-5">
                <div class="flex gap-4">
                    <BackButton />

                    <Heading
                        title="Reports"
                        description="Manage and organize your reports."
                    />
                </div>

                <Button as-child class="gap-2">
                    <Link :href="create()">
                        <Plus class="size-4" />
                        Create Report
                    </Link>
                </Button>
            </div>
        </template>

        <!-- Content -->

        <div class="py-6">
            <!-- Stats -->

            <section class="mb-8">
                <AppStats :items="statItems" />
            </section>

            <!-- Filters -->

            <section class="mb-8">
                <FilterControl
                    v-model:search="filters.search"
                    search-placeholder="Search reports..."
                    :filter-count="filterCount"
                    @apply="applyFilters"
                    @clear="clearFilters"
                >
                    <div class="space-y-6">
                        <AppSelect
                            v-model="filters.type"
                            name="type"
                            label="Type"
                            placeholder="All types"
                            :options="typeOptions"
                        />

                        <AppSelect
                            v-model="filters.status"
                            name="status"
                            label="Status"
                            placeholder="All statuses"
                            :options="statusOptions"
                        />
                    </div>
                </FilterControl>
            </section>

            <!-- Table -->

            <AppTable
                :columns="columns"
                :data="props.reports.data"
            >
                <!-- Title -->

                <template #cell-title="{ row }">
                    <div class="min-w-0">
                        <Link
                            :href="show(row.id).url"
                            class="
                                block
                                truncate
                                font-medium
                                hover:text-primary
                            "
                        >
                            {{ row.title }}
                        </Link>

                        <div
                            v-if="row.subtitle"
                            class="
                                mt-1
                                truncate
                                text-sm
                                text-muted-foreground
                            "
                        >
                            {{ row.subtitle }}
                        </div>

                        <Badge
                            v-if="row.featured"
                            variant="secondary"
                            class="mt-2"
                        >
                            Featured
                        </Badge>
                    </div>
                </template>

                <!-- Type -->

                <template #cell-type="{ value }">
                    <Badge :color="value.color">
                        {{ value.label }}
                    </Badge>
                </template>

                <!-- Research -->

                <template #cell-research="{ row }">
                    <Link
                        v-if="row.research"
                        :href="`/admin/research/${row.research.id}`"
                        class="text-sm hover:text-primary"
                    >
                        {{ row.research.title }}
                    </Link>

                    <span
                        v-else
                        class="
                            text-sm
                            text-muted-foreground
                        "
                    >
                        —
                    </span>
                </template>

                <!-- Status -->

                <template #cell-status="{ value }">
                    <Badge :color="value.color">
                        {{ value.label }}
                    </Badge>
                </template>

                <!-- Published At -->

                <template #cell-published_at="{ value }">
                    <Date
                        v-if="value"
                        :value="value"
                    />

                    <span
                        v-else
                        class="
                            text-sm
                            text-muted-foreground
                        "
                    >
                        —
                    </span>
                </template>

                <!-- Actions -->

                <template #cell-actions="{ row }">
                    <AppTableActions
                        :actions="getReportActions(row)"
                    />
                </template>
            </AppTable>
        </div>

        <!-- Footer -->

        <template #footer>
            <AppPagination :meta="props.reports.meta" />
        </template>
    </TableLayout>
</template>