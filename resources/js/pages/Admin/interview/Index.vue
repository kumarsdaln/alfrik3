
<script setup lang="ts">
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { Eye, Pencil, Plus, Trash2 } from '@lucide/vue'

import Date from '@/components/datadisplay/Date.vue'
import ProfileCell from '@/components/profile/ProfileCell.vue'
import AppPagination from '@/components/ui/AppPagination.vue'
import AppStats from '@/components/ui/AppStats.vue'
import AppTable from '@/components/ui/AppTable.vue'
import AppTableActions from '@/components/ui/AppTableActions.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import Button from '@/components/ui/button/Button.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import FilterControl from '@/components/filters/FilterControl.vue'
import Heading from '@/components/Heading.vue'
import BackButton from '@/components/ui/BackButton.vue'
import TableLayout from '@/layouts/table/TableLayout.vue'

import { useFilters } from '@/composables/useFilters'

import { create, show, edit, destroy } from '@/actions/App/Http/Controllers/Admin/Interview/InterviewController'
import { index as manageParticipants } from '@/actions/App/Http/Controllers/Admin/ContentParticipant/ContentParticipantController'
import { index as manageQuestions } from '@/routes/admin/interviews/questions'
import { index as manageMedia } from '@/routes/admin/media'
import { index as manageCategory } from '@/routes/admin/categories/assignment'
import { index as manageTags } from '@/routes/admin/tags/assignment'

import type { Interview, FormOption, Pagination } from '@/types'
import type { TableAction } from '@/components/ui/AppTableActions.vue'
import ParticipantCell from '@/components/datadisplay/ParticipantCell.vue'
import CategoryCell from '@/components/datadisplay/CategoryCell.vue'
import TagCell from '@/components/datadisplay/TagCell.vue'

interface Props {
    interviews: Pagination<Interview>
    typeOptions: FormOption[]
    statusOptions: FormOption[]
    filters: {
        search?: string
        type?: string
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
        type: props.filters.type ?? '',
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
        key: 'interview_type',
        label: 'Type',
        width: '160px',
    },
    {
        key: 'participants',
        label: 'Participants',
        width: '240px',
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
| Actions
|--------------------------------------------------------------------------
*/

function deleteInterview(interview: Interview): void {
    if (!confirm(`Are you sure you want to delete "${interview.title}"?`)) {
        return
    }

    router.delete(destroy(interview.id).url, {
        preserveScroll: true,
    })
}

function getInterviewActions(interview: Interview): TableAction[] {
    return [
        {
            label: 'View',
            icon: Eye,
            href: show(interview.id).url,
        },
        {
            label: 'Edit',
            icon: Pencil,
            href: edit(interview.id).url,
        },
        {
            label: 'Manage Participants',
            icon: Pencil,
            href: manageParticipants({
                type: 'interview',
                id: interview.id,
            }).url,
        },
        {
            label: 'Manage Categories',
            icon: Pencil,
            href: manageCategory({
                type: 'interview',
                id: interview.id,
            }).url,
        },
        {
            label: 'Manage Tags',
            icon: Pencil,
            href: manageTags({
                type: 'interview',
                id: interview.id,
            }).url,
        },
        {
            label: 'Manage Media',
            icon: Pencil,
            href: manageMedia({
                type: 'interview',
                id: interview.id,
            }).url,
        },
        {
            label: 'Manage Questions',
            icon: Pencil,
            href: manageQuestions(interview.id).url,
        },
        {
            label: 'Delete',
            icon: Trash2,
            danger: true,
            onClick: () => deleteInterview(interview),
        },
    ]
}
</script>

<template>
    <TableLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4 py-5">
                <div class="flex items-center gap-4">
                    <BackButton />

                    <Heading
                        title="Interviews"
                        description="Manage and organize your interviews."
                    />
                </div>

                <Button as-child class="gap-2">
                    <Link :href="create()">
                        <Plus class="size-4" />
                        Create Interview
                    </Link>
                </Button>
            </div>
        </template>

        <div class="py-6">
            <!-- Statistics -->
            <section class="mb-8">
                <AppStats :items="statItems" />
            </section>

            <!-- Filters -->
            <section class="mb-8">
                <FilterControl
                    v-model:search="filters.search"
                    search-placeholder="Search interviews..."
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

            <!-- Interviews Table -->
            <AppTable
                :columns="columns"
                :data="props.interviews.data"
            >
                <template #cell-interview_type="{ value }">
                    <Badge :color="value.color">
                        {{ value.label }}
                    </Badge>
                </template>

                <!-- Participants: ContentParticipant[] -->
                <template #cell-participants="{ value }">
                    <ParticipantCell :participants="value" :max-visible="3" />
                </template>

                <template #cell-categories="{ value }">
                    <CategoryCell :categories="value" :max-visible="3" />
                </template>

                <template #cell-tags="{ value }">
                    <TagCell :tags="value" :max-visible="3" />
                </template>

                <template #cell-status="{ value }">
                    <Badge :color="value.color">
                        {{ value.label }}
                    </Badge>
                </template>

                <template #cell-published_at="{ value }">
                    <Date :value="value" />
                </template>

                <template #cell-actions="{ row }">
                    <AppTableActions
                        :actions="getInterviewActions(row)"
                    />
                </template>
            </AppTable>
        </div>

        <template #footer>
            <AppPagination :meta="props.interviews.meta" />
        </template>
    </TableLayout>
</template>