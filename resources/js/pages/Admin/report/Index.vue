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

import type { Pagination, Report } from '@/types'
import type { TableAction } from '@/components/ui/AppTableActions.vue'

interface Props {
    reports: Pagination<Report>

    categories: {
        id: number
        name: string
        slug: string
    }[]

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

const statusOptions = [
    {
        label: 'Published',
        value: 'published',
    },
    {
        label: 'Draft',
        value: 'draft',
    },
]

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
    <Layout>
        <!-- Header -->
        <template #header>
            <div
                class="
                    flex items-center justify-between
                    gap-4
                    px-6 py-5
                "
            >
                <div>
                    <AppHeading tag="h1">
                        Reports
                    </AppHeading>

                    <AppText>
                        Manage and organize your reports.
                    </AppText>
                </div>

                <Button
                    as-child
                    class="gap-2"
                >
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
            <AppTable
                :columns="columns"
                :data="props.reports.data"
            >
                <!-- Category -->
                <template #cell-category="{ value }">
                    <span
                        v-if="value"
                        class="truncate"
                    >
                        {{ value.name ?? '—' }}
                    </span>

                    <span
                        v-else
                        class="
                            text-content-light-muted
                            dark:text-content-dark-muted
                        "
                    >
                        —
                    </span>
                </template>

                <!-- Author -->
                <template #cell-author="{ value }">
                    <div
                        v-if="value"
                        class="flex items-center gap-3"
                    >
                        <div
                            class="
                                flex size-8 shrink-0
                                items-center justify-center
                                rounded-full
                                bg-content-light-muted/10
                                text-xs font-medium
                                text-content-light-muted
                                dark:bg-content-dark-muted/10
                                dark:text-content-dark-muted
                            "
                        >
                            {{ value.name?.charAt(0)?.toUpperCase() }}
                        </div>

                        <span class="truncate">
                            {{ value.name ?? '—' }}
                        </span>
                    </div>

                    <span
                        v-else
                        class="
                            text-content-light-muted
                            dark:text-content-dark-muted
                        "
                    >
                        —
                    </span>
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
                            text-content-light-muted
                            dark:text-content-dark-muted
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

                <!-- Empty -->
                <template #empty>
                    <div
                        class="
                            flex flex-col
                            items-center justify-center
                            py-16
                        "
                    >
                        <div
                            class="
                                mb-4
                                flex size-12
                                items-center justify-center
                                rounded-full
                                bg-content-light-muted/10
                                dark:bg-content-dark-muted/10
                            "
                        >
                            <Eye
                                class="
                                    size-5
                                    text-content-light-muted
                                    dark:text-content-dark-muted
                                "
                            />
                        </div>

                        <p class="font-lora text-base font-medium">
                            No reports found
                        </p>

                        <p
                            class="
                                mt-1 max-w-sm
                                text-center text-sm
                                text-content-light-muted
                                dark:text-content-dark-muted
                            "
                        >
                            There are no reports matching your
                            current filters.
                        </p>

                        <Button
                            v-if="filterCount > 0"
                            variant="outline"
                            class="mt-5"
                            @click="clearFilters"
                        >
                            Clear filters
                        </Button>

                        <Button
                            v-else
                            as-child
                            class="mt-5 gap-2"
                        >
                            <Link :href="create()">
                                <Plus class="size-4" />
                                Create Report
                            </Link>
                        </Button>
                    </div>
                </template>
            </AppTable>
        </div>

        <!-- Footer -->
        <template #footer>
            <AppPagination :meta="props.reports.meta" />
        </template>
    </Layout>
</template>