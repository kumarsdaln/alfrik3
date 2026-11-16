<script setup lang="ts">
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import {
    Eye,
    Pencil,
    Plus,
    Trash2,
} from '@lucide/vue'

import Date from '@/components/datadisplay/Date.vue'
import ParticipantCell from '@/components/datadisplay/ParticipantCell.vue'
import CategoryCell from '@/components/datadisplay/CategoryCell.vue'
import TagCell from '@/components/datadisplay/TagCell.vue'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppPagination from '@/components/ui/AppPagination.vue'
import AppStats from '@/components/ui/AppStats.vue'
import AppTable from '@/components/ui/AppTable.vue'
import AppTableActions from '@/components/ui/AppTableActions.vue'
import AppText from '@/components/ui/AppText.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import Button from '@/components/ui/button/Button.vue'

import AppSelect from '@/components/form/AppSelect.vue'
import FilterControl from '@/components/filters/FilterControl.vue'
import TableLayout from '@/layouts/table/TableLayout.vue'

import { useFilters } from '@/composables/useFilters'

import {
    create,
    edit as editMagazine,
    destroy as destroyMagazine,
} from '@/routes/admin/magazines'

import { index as manageIssues } from '@/routes/admin/magazines/issues'
import { index as manageCategories } from '@/routes/admin/categories/assignment'
import { index as manageTags } from '@/routes/admin/tags/assignment'
import { index as manageMedia } from '@/routes/admin/media'
import { index as manageSeo } from '@/routes/admin/seo'
import { index as manageParticipants } from '@/actions/App/Http/Controllers/Admin/ContentParticipant/ContentParticipantController'

import type {
    Magazine,
    FormOption,
    Pagination,
} from '@/types'

import type { TableAction } from '@/components/ui/AppTableActions.vue'

interface Props {
    magazines: Pagination<Magazine>
    statusOptions: FormOption[]

    filters: {
        search?: string
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
        status: props.filters.status ?? '',
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
        label: 'Total Magazines',
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
| Table Columns
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
        key: 'participants',
        label: 'Participants',
        width: '220px',
    },
    {
        key: 'categories',
        label: 'Categories',
        width: '240px',
    },
    {
        key: 'tags',
        label: 'Tags',
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
| Delete
|--------------------------------------------------------------------------
*/

function deleteMagazine(magazine: Magazine): void {
    if (
        !confirm(
            `Are you sure you want to delete "${magazine.title}"?`,
        )
    ) {
        return
    }

    router.delete(
        destroyMagazine(magazine.id).url,
        {
            preserveScroll: true,
        },
    )
}

/*
|--------------------------------------------------------------------------
| Table Actions
|--------------------------------------------------------------------------
*/

function getMagazineActions(
    magazine: Magazine,
): TableAction[] {
    return [
        {
            label: 'View',
            icon: Eye,
            href: `/admin/magazines/${magazine.slug}`,
        },
        {
            label: 'Edit',
            icon: Pencil,
            href: editMagazine(magazine.id).url,
        },
        {
            label: 'Manage Issues',
            icon: Pencil,
            href: manageIssues(magazine.id).url,
        },
        {
            label: 'Manage Participants',
            icon: Pencil,
            href: manageParticipants({
                type: 'magazine',
                id: magazine.id,
            }).url,
        },
        {
            label: 'Manage Categories',
            icon: Pencil,
            href: manageCategories({
                type: 'magazine',
                id: magazine.id,
            }).url,
        },
        {
            label: 'Manage Tags',
            icon: Pencil,
            href: manageTags({
                type: 'magazine',
                id: magazine.id,
            }).url,
        },
        {
            label: 'Manage Media',
            icon: Pencil,
            href: manageMedia({
                type: 'magazine',
                id: magazine.id,
            }).url,
        },
        {
            label: 'Manage SEO',
            icon: Pencil,
            href: manageSeo({
                type: 'magazine',
                id: magazine.id,
            }).url,
        },
        {
            label: 'Delete',
            icon: Trash2,
            danger: true,
            onClick: () => deleteMagazine(magazine),
        },
    ]
}
</script>

<template>
    <TableLayout>
        <!-- Header -->
        <template #header>
            <div class="flex items-center justify-between gap-4 px-6 py-5">
                <div>
                    <AppHeading tag="h1">
                        Magazines
                    </AppHeading>

                    <AppText>
                        Manage and organize your magazine publications.
                    </AppText>
                </div>

                <Button as-child class="gap-2">
                    <Link :href="create().url">
                        <Plus class="size-4" />
                        Create Magazine
                    </Link>
                </Button>
            </div>
        </template>

        <!-- Content -->
        <div class="px-6 py-6">
            <!-- Statistics -->
            <section class="mb-8">
                <AppStats :items="statItems" />
            </section>

            <!-- Filters -->
            <section class="mb-8">
                <FilterControl
                    v-model:search="filters.search"
                    search-placeholder="Search magazines..."
                    :filter-count="filterCount"
                    @apply="applyFilters"
                    @clear="clearFilters"
                >
                    <div class="space-y-6">
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

            <!-- Magazine Table -->
            <AppTable
                :columns="columns"
                :data="props.magazines.data"
            >
                <!-- Title -->
                <template #cell-title="{ row }">
                    <div class="min-w-0">
                        <Link
                            :href="editMagazine(row.id).url"
                            class="font-medium hover:underline"
                        >
                            {{ row.title }}
                        </Link>

                        <p
                            v-if="row.subtitle"
                            class="mt-1 max-w-md truncate text-sm text-muted-foreground"
                        >
                            {{ row.subtitle }}
                        </p>
                    </div>
                </template>

                <!-- Participants -->
                <template #cell-participants="{ value }">
                    <ParticipantCell
                        :participants="value ?? []"
                        :max-visible="3"
                    />
                </template>

                <!-- Categories -->
                <template #cell-categories="{ value }">
                    <CategoryCell
                        :categories="value ?? []"
                        :max-visible="3"
                    />
                </template>

                <!-- Tags -->
                <template #cell-tags="{ value }">
                    <TagCell
                        :tags="value ?? []"
                        :max-visible="3"
                    />
                </template>

                <!-- Status -->
                <template #cell-status="{ value }">
                    <Badge :color="value.color">
                        {{ value.label }}
                    </Badge>
                </template>

                <!-- Published At -->
                <template #cell-published_at="{ value }">
                    <Date :value="value" />
                </template>

                <!-- Actions -->
                <template #cell-actions="{ row }">
                    <AppTableActions
                        :actions="getMagazineActions(row)"
                    />
                </template>
            </AppTable>
        </div>

        <!-- Pagination -->
        <template #footer>
            <AppPagination :meta="props.magazines.meta" />
        </template>
    </TableLayout>
</template>
