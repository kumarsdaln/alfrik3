<script setup lang="ts">
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { Eye, Pencil, Plus, Trash2 } from '@lucide/vue'

import Date from '@/components/datadisplay/Date.vue'
import AppHeading from '@/components/ui/AppHeading.vue'
import AppPagination from '@/components/ui/AppPagination.vue'
import AppStats from '@/components/ui/AppStats.vue'
import AppTable from '@/components/ui/AppTable.vue'
import AppTableActions from '@/components/ui/AppTableActions.vue'
import AppText from '@/components/ui/AppText.vue'
import Button from '@/components/ui/button/Button.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import FilterControl from '@/components/filters/FilterControl.vue'
import Layout from '@/layouts/table/Layout.vue'

import { useFilters } from '@/composables/useFilters'
import { create } from '@/routes/admin/reports'

import type { Option, Pagination, Report, ReportCategory } from '@/types'
import type { TableAction } from '@/components/ui/AppTableActions.vue'
import ProfileCell from '@/components/profile/ProfileCell.vue'
import Badge from '@/components/ui/badge/Badge.vue'

interface Props {
    reports: Pagination<Report>
    categories: ReportCategory[]
    statusOptions: Option[]
    filters: {
        search?: string
        category?: string
        status?: string
    }
    stats: {
        total: number
        published: number
        draft: number
        this_month: number
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
        category: props.filters.category ?? '',
        status: props.filters.status ?? '',
    },
    {
        url: window.location.pathname,
        searchKey: 'search',
        debounce: 500,
    },
)

const categoryOptions = computed(() =>
    props.categories.map(category => ({
        label: category.name,
        value: category.slug,
    })),
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
        label: 'This Month',
        value: props.stats.this_month,
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
        key: 'category',
        label: 'Category',
        width: '180px',
    },
    {
        key: 'author',
        label: 'Author',
        width: '200px',
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

function deleteReport(report: Report) {
    if (!confirm(`Are you sure you want to delete "${report.title}"?`)) {
        return
    }

    router.delete(`/reports/${report.id}`, {
        preserveScroll: true,
    })
}

function getReportActions(report: Report): TableAction[] {
    return [
        {
            label: 'View',
            icon: Eye,
            href: `/reports/${report.id}`,
        },
        {
            label: 'Edit',
            icon: Pencil,
            href: `/reports/${report.id}/edit`,
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
            <div class="
                    flex items-center justify-between
                    gap-4
                    px-6 py-5
                ">
                <div>
                    <AppHeading tag="h1">
                        Reports
                    </AppHeading>

                    <AppText>
                        Manage and organize your reports.
                    </AppText>
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
        <div class="px-6 py-6">
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
                            v-model="filters.category"
                            name="category"
                            label="Category"
                            placeholder="All categories"
                            :options="categoryOptions"
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
            <AppTable :columns="columns" :data="props.reports.data">
                <!-- Category -->
                <template #cell-category="{ value }">
                    <Badge>
                        {{ value.name}}
                    </Badge>
                </template>

                <!-- Author -->
                <template #cell-author="{ value }">
                    <ProfileCell :profile="value" />
                </template>

                <!-- Published At -->
                <template #cell-published_at="{ value }">
                    <Date :value="value"/>
                </template>

                <!-- Actions -->
                <template #cell-actions="{ row }">
                    <AppTableActions :actions="getReportActions(row)" />
                </template>
            </AppTable>
        </div>

        <!-- Footer -->
        <template #footer>
            <AppPagination :meta="props.reports.meta" />
        </template>
    </TableLayout>
</template>