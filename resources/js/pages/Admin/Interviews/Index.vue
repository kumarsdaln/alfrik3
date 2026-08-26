<script setup lang="ts">
import { computed, ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { Edit, Trash } from '@lucide/vue'

import AppTableLayout from '@/layouts/dashboard/AppTableLayout.vue'
import DeleteConfirm from '@/components/ui/DeleteConfirm.vue'

import AppFilterLayout from '@/components/filters/layout/AppFilterLayout.vue'
import FilterInput from '@/components/filters/fields/FilterInput.vue'
import FilterSelect from '@/components/filters/fields/FilterSelect.vue'

import AppButton from '@/components/ui/AppButton.vue'
import AppStatusDropdown from '@/components/ui/AppStatusDropdown.vue'
import AppThreeDotOptions from '@/components/ui/AppThreeDotOptions.vue'

import { useFilterSync } from '@/composables/useFilterSync'

import {
    index as adminInterviewsIndex,
    create as adminInterviewsCreate,
    show as adminInterviewsShow,
    edit as adminInterviewsEdit,
    destroy as adminInterviewsDestroy,
    participants as adminInterviewsParticipants,
} from '@/routes/admin/interviews'

import {
    status as adminInterviewsUpdateStatus,
    type as adminInterviewsUpdateType,
} from '@/routes/admin/interviews/update'

import {
    index as adminInterviewsQuestionsIndex,
} from '@/routes/admin/interviews/questions'

import type {
    TableHeader,
    TableColumn,
    TableFilters,
    TableRow,
    RowId,
} from '@/types/table'


/*
|--------------------------------------------------------------------------
| Types
|--------------------------------------------------------------------------
*/

interface StatusOption {
    value: string
    label: string
}

interface User {
    id: number
    name: string
}

interface Interview extends TableRow {
    id: number
    title: string
    interview_type: string
    status: string
    created_by?: User
    created_at: string
    updated_at: string
}

interface PaginatedInterviews {
    data: Interview[]
    total: number
    current_page: number
    last_page: number
    per_page: number
    prev_page_url: string | null
    next_page_url: string | null
}

interface Props {
    interviews: PaginatedInterviews
    statuses: StatusOption[]
    types: StatusOption[]
}

const props = defineProps<Props>()


/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

const routes = {
    index: () =>
        adminInterviewsIndex().url,

    create: () =>
        adminInterviewsCreate().url,

    show: (id: number) =>
        adminInterviewsShow(id).url,

    edit: (id: number) =>
        adminInterviewsEdit(id).url,

    updateStatus: (id: number) =>
        adminInterviewsUpdateStatus(id).url,

    updateType: (id: number) =>
        adminInterviewsUpdateType(id).url,

    destroy: (id: number) =>
        adminInterviewsDestroy(id).url,

    participants: (id: number) =>
        adminInterviewsParticipants(id).url,

    questions: (id: number) =>
        adminInterviewsQuestionsIndex(id).url,
}


/*
|--------------------------------------------------------------------------
| Table Headers
|--------------------------------------------------------------------------
*/

const headers: TableHeader[] = [
    {
        key: 'sno',
        label: '#',
    },
    {
        key: 'title',
        label: 'Title',
        sortable: true,
    },
    {
        key: 'interview_type',
        label: 'Type',
    },
    {
        key: 'created_by',
        label: 'Created By',
    },
    {
        key: 'status',
        label: 'Status',
    },
    {
        key: 'participants',
        label: 'Participants',
    },
    {
        key: 'questions',
        label: 'Q&A',
    },
    {
        key: 'created_at',
        label: 'Created At',
    },
    {
        key: 'updated_at',
        label: 'Updated At',
    },
]


/*
|--------------------------------------------------------------------------
| Table Columns
|--------------------------------------------------------------------------
*/

const columns: TableColumn[] = [
    {
        key: 'sno',
        slot: 'sno',
    },
    {
        key: 'title',
        slot: 'title',
    },
    {
        key: 'interview_type',
        slot: 'interview_type',
    },
    {
        key: 'created_by',
        slot: 'created_by',
    },
    {
        key: 'status',
        slot: 'status',
    },
    {
        key: 'participants',
        slot: 'participants',
    },
    {
        key: 'questions',
        slot: 'questions',
    },
    {
        key: 'created_at',
        type: 'datetime',
    },
    {
        key: 'updated_at',
        type: 'datetime',
    },
]


/*
|--------------------------------------------------------------------------
| Filter Configuration
|--------------------------------------------------------------------------
*/

const searchField = {
    placeholder: 'Search Interview',
}

const statusField = {
    placeholder: 'All Status',
}

const typeField = {
    placeholder: 'All Types',
}


/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

const {
    filters,
    applyFilters,
    resetFilters,
} = useFilterSync({
    url: routes.index(),

    initialFilters: {
        search: '',
        status: '',
        type: '',
        sort: '',
        direction: '',
    },

    debounce: 500,
    autoApply: true,
    preserveState: true,
    preserveScroll: true,
})


/*
|--------------------------------------------------------------------------
| Visible Filters
|--------------------------------------------------------------------------
*/

const visibleFilters = computed(() => ({
    search: filters.search,
    status: filters.status,
    type: filters.type,
}))


/*
|--------------------------------------------------------------------------
| Table Filters
|--------------------------------------------------------------------------
*/

const tableFilters = computed<TableFilters>(() => ({
    ...filters,
}))


/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
*/

const serialNumber = (
    index: number,
): number => {
    return (
        (props.interviews.current_page - 1) *
        props.interviews.per_page +
        index +
        1
    )
}


/*
|--------------------------------------------------------------------------
| Status / Type Update
|--------------------------------------------------------------------------
*/

const updateStatus = (
    interview: Interview,
    option: StatusOption,
): void => {
    if (interview.status === option.value) {
        return
    }

    router.patch(
        routes.updateStatus(interview.id),
        {
            status: option.value,
        },
    )
}


/*
|--------------------------------------------------------------------------
| Sorting
|--------------------------------------------------------------------------
*/

const handleSort = (
    key: string,
): void => {
    if (filters.sort === key) {
        filters.direction =
            filters.direction === 'asc'
                ? 'desc'
                : 'asc'
    } else {
        filters.sort = key
        filters.direction = 'asc'
    }

    applyFilters()
}


/*
|--------------------------------------------------------------------------
| Delete Confirmation
|--------------------------------------------------------------------------
*/

const selectedInterview = ref<Interview | null>(null)

const showDeleteConfirm = ref(false)

const openDeleteConfirm = (
    interview: Interview,
): void => {
    selectedInterview.value = interview
    showDeleteConfirm.value = true
}

const closeDeleteConfirm = (): void => {
    showDeleteConfirm.value = false
    selectedInterview.value = null
}


/*
|--------------------------------------------------------------------------
| Row Actions
|--------------------------------------------------------------------------
*/

const getActions = (
    interview: Interview,
) => [
    {
        label: 'Edit',
        icon: Edit,
        href: routes.edit(interview.id),
    },
    {
        label: 'Delete',
        icon: Trash,
        danger: true,
        action: () => openDeleteConfirm(interview),
    },
]


/*
|--------------------------------------------------------------------------
| Bulk Actions
|--------------------------------------------------------------------------
*/

const handleBulkExport = (
    ids: RowId[],
): void => {
    console.log('Export:', ids)
}

const handleBulkDelete = (
    ids: RowId[],
): void => {
    console.log('Delete:', ids)
}
</script>


<template>
    <AppTableLayout
        title="Interviews"
        :headers="headers"
        :columns="columns"
        :data="props.interviews.data"
        :total="props.interviews.total"
        :per-page="props.interviews.per_page"
        :current-page="props.interviews.current_page"
        :last-page="props.interviews.last_page"
        :prev-page-url="props.interviews.prev_page_url"
        :next-page-url="props.interviews.next_page_url"
        :filters="tableFilters"
        selectable
        primary-key="id"
        @sort="handleSort"
        @bulk-export="handleBulkExport"
        @bulk-delete="handleBulkDelete"
    >

        <!-- Header -->

        <template #header>
            <AppButton
                variant="add"
                :href="routes.create()"
                size="sm"
                autoIcon
            >
                Create
            </AppButton>
        </template>


        <!-- Filters -->

        <template #filter>
            <AppFilterLayout
                :filters="visibleFilters"
                @apply="applyFilters"
                @reset="resetFilters"
            >
                <template #search>
                    <FilterInput
                        v-model="filters.search"
                        :field="searchField"
                    />
                </template>

                <template #inline-filters>
                    <FilterSelect
                        v-model="filters.status"
                        :field="statusField"
                        :options="props.statuses"
                    />

                    <FilterSelect
                        v-model="filters.type"
                        :field="typeField"
                        :options="props.types"
                    />
                </template>
            </AppFilterLayout>
        </template>


        <!-- Serial Number -->

        <template #sno="{ index }">
            {{ serialNumber(index) }}
        </template>


        <!-- Title -->

        <template #title="{ data }">
            <Link
                :href="routes.show(data.id)"
                class="font-medium text-brand hover:underline"
            >
                {{ data.title }}
            </Link>
        </template>


        <!-- Interview Type -->

        <template #interview_type="{ data }">
            <AppStatusDropdown
                v-model="data.interview_type"
                :options="props.types"
                @change="option => router.patch(
                    routes.updateType(data.id),
                    {
                        interview_type: option.value,
                    }
                )"
            />
        </template>


        <!-- Created By -->

        <template #created_by="{ data }">
            {{ data.created_by?.name ?? '-' }}
        </template>


        <!-- Status -->

        <template #status="{ data }">
            <AppStatusDropdown
                v-model="data.status"
                :options="props.statuses"
                @change="updateStatus(data, $event)"
            />
        </template>


        <!-- Participants -->

        <template #participants="{ data }">
            <Link
                :href="routes.participants(data.id)"
                class="text-brand hover:underline"
            >
                Manage Participants
            </Link>
        </template>


        <!-- Questions -->

        <template #questions="{ data }">
            <Link
                :href="routes.questions(data.id)"
                class="text-brand hover:underline"
            >
                Manage Q&amp;A
            </Link>
        </template>


        <!-- Actions -->

        <template #threedot="{ data }">
            <AppThreeDotOptions
                :actions="getActions(data)"
            />
        </template>

    </AppTableLayout>


    <!-- Delete Confirmation -->

    <DeleteConfirm
        :show="showDeleteConfirm"
        :delete-url="
            selectedInterview
                ? routes.destroy(selectedInterview.id)
                : ''
        "
        title="Delete Interview"
        message="Are you sure you want to delete this interview?"
        @close="closeDeleteConfirm"
    />
</template>