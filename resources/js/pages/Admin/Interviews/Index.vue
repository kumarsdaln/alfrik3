<script setup lang="ts">
    import { computed, ref } from 'vue'
    import { Link } from '@inertiajs/vue3'
    import { Edit, Trash } from '@lucide/vue'
    
    import AppTableLayout from '@/Layouts/AppTableLayout.vue'
    import DeleteConfirm from '@/components/ui/DeleteConfirm.vue'

    import AppFilterLayout from '@/components/filters/layout/AppFilterLayout.vue'
    import FilterInput from '@/components/filters/fields/FilterInput.vue'
    import filterselect from '@/components/filters/fields/filterselect.vue'

    import AppButton from '@/components/ui/AppButton.vue'
    import AppStatusDropdown from '@/components/ui/AppStatusDropdown.vue'
    import AppThreeDotOptions from '@/components/ui/AppThreeDotOptions.vue'

    import PlusIcon from '@/Icons/PlusIcon.vue'

    import { usefiltersync } from '@/composables/usefiltersync'
    import { usePatch } from '@/composables/usePatch'

    import { create as adminInterviewsCreate } from '@/routes/admin/interviews'
    import { show as adminInterviewsShow } from '@/routes/admin/interviews'
    import { edit as adminInterviewsEdit } from '@/routes/admin/interviews'
    import { destroy as adminInterviewsDestroy } from '@/routes/admin/interviews'
    import { participants as adminInterviewsParticipants } from '@/routes/admin/interviews'
    import { status as adminInterviewsUpdateStatus } from '@/routes/admin/interviews/update'
    import { type as adminInterviewsUpdateType } from '@/routes/admin/interviews/update'
    import { index as adminInterviewsQuestionsIndex } from '@/routes/admin/interviews/questions'

    import type {
        TableHeader,
        TableColumn,
        Tablefilters,
        TableRow,
        RowId,
    } from '@/types/table'

    // Types
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
        statuses: StatusOption[],
        types: StatusOption[]
    }

    const props = defineProps<Props>()

    const routes = {
        create: () => adminInterviewsCreate().url,
        show: (id: number) => adminInterviewsShow(id).url,
        edit: (id: number) => adminInterviewsEdit(id).url,
        updateStatus: (id: number) => adminInterviewsUpdateStatus(id).url,
        updateType: (id: number) => adminInterviewsUpdateType(id).url,
        destroy: (id: number) => adminInterviewsDestroy(id).url,
        participants: (id: number) => adminInterviewsParticipants(id).url,
        questions: (id: number) => adminInterviewsQuestionsIndex(id).url,
    }

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

    const searchField = {
        placeholder: 'Search Interview',
    }

    const statusField = {
        placeholder: 'All Status',
    }

    const {
        filters,
        applyfilters,
        resetfilters,
    } = usefiltersync({
        initialfilters: {
            search: '',
            status: '',
            type:'',
            sort: '',
            direction: '',
        },
    })

    const selectedInterview = ref<Interview | null>(null)
    const showDeleteConfirm = ref(false)

    const { patch } = usePatch()

    const visiblefilters = computed(() => ({
        search: filters.search,
        status: filters.status,
    }))

    const tablefilters = computed<Tablefilters>(
        () => ({ ...filters })
    )

    const serialNumber = (
        index: number
    ): number => {

        return (
            (props.interviews.current_page - 1) *
            props.interviews.per_page +
            index +
            1
        )

    }

    const updateStatus = (
        interview: Interview,
        option: StatusOption
    ): void => {
        patch(
            routes.updateStatus(
                interview.id
            ),
            {
                status: option.value,
            },
            'Status updated successfully.',
            () => {
                interview.status =
                    option.value
            }
        )
    }

    const handleStatusChange = (
        interview: Interview,
        option: StatusOption
    ): void => {
        updateStatus(
            interview,
            option
        )
    }

    const getActions = (
        interview: Interview
    ) => [
            {
                label: 'Edit',
                icon: Edit,
                href: routes.edit(
                    interview.id
                ),
            },

            {
                label: 'Delete',
                icon: Trash,
                danger: true,
                action: () =>
                    openDeleteConfirm(
                        interview
                    ),
            },

        ]

    const handleBulkExport = (
        ids: RowId[]
    ): void => {
        console.log(
            'Export:',
            ids
        )
    }

    const handleBulkDelete = (
        ids: RowId[]
    ): void => {
        console.log(
            'Delete:',
            ids
        )
    }

    const handleSort = (key: string): void => {
        if (filters.sort === key) {
            filters.direction =
                filters.direction === 'asc'
                    ? 'desc'
                    : 'asc'
        } else {
            filters.sort = key
            filters.direction = 'asc'
        }

        applyfilters()
    }

    const openDeleteConfirm = (
        interview: Interview
    ): void => {
        selectedInterview.value = interview
        showDeleteConfirm.value = true
    }

    const closeDeleteConfirm = (): void => {
        showDeleteConfirm.value = false
        selectedInterview.value = null
    }
</script>

<template>
        <AppTableLayout title="Interviews" 
            :headers="headers" 
            :columns="columns" 
            :data="interviews.data"
            :total="interviews.total" 
            :per-page="interviews.per_page"
            :current-page="interviews.current_page"
            :last-page="interviews.last_page" 
            :prev-page-url="interviews.prev_page_url"
            :next-page-url="interviews.next_page_url" 
            :filters="tablefilters" 
            selectable 
            primary-key="id"
            @sort="handleSort"
            @bulk-export="handleBulkExport" 
            @bulk-delete="handleBulkDelete">

            <!-- Header -->
            <template #header>
                <AppButton variant="add" :href="routes.create()" size="sm" autoIcon>
                    Create
                </AppButton>
            </template>

            <!-- filters -->
            <template #filter>
                <AppFilterLayout :filters="visiblefilters" @apply="applyfilters" @reset="resetfilters">
                    <template #search>
                        <FilterInput v-model="filters.search" :field="searchField" />
                    </template>
                    <template #inline-filters>
                        <filterselect v-model="filters.status" :field="statusField" :options="props.statuses" />
                        <filterselect v-model="filters.type" :field="{placeholder:'All Types'}" :options="props.types" />
                    </template>
                </AppFilterLayout>
            </template>
            
            <!-- S.No -->
            <template #sno="{ index }">
                {{ serialNumber(index) }}
            </template>

            <!-- Title -->
            <template #title="{ data }">
                <Link :href="routes.show(data.id)" class="font-medium text-brand hover:underline">
                    {{ data.title }}
                </Link>
            </template>

            
            <!-- Interview Type -->
            <template #interview_type="{ data }">
                <AppStatusDropdown v-model="data.interview_type" :options="props.types"
                    @change="option => patch(
                    routes.updateType(data.id), 
                    { 
                        interview_type: option.value,
                    }, 
                    'Status updated successfully.',
                    () => {
                        data.interview_type = option.value
                    }
                )" />
            </template>

            
            <!-- Created By -->
            <template #created_by="{ data }">
                {{ data.created_by?.name ?? '-' }}
            </template>

            <!-- Status -->
            <template #status="{ data }">
                <AppStatusDropdown v-model="data.status" :options="props.statuses"
                    @change="handleStatusChange(data, $event)" />
            </template>
            
            <!-- Participants -->
            <template #participants="{ data }">
                <Link :href="routes.participants(data.id)" class="text-brand hover:underline">
                    Manage Participants
                </Link>
            </template>

            <!-- Questions -->
            <template #questions="{ data }">
                <Link :href="routes.questions(data.id)" class="text-brand hover:underline">
                    Manage Q&amp;A
                </Link>
            </template>

            <!-- Actions -->
            <template #threedot="{ data }">
                <AppThreeDotOptions :actions="getActions(data)" />
            </template>
        </AppTableLayout>

        <DeleteConfirm
            :show="showDeleteConfirm"
            :delete-url="selectedInterview ? routes.destroy(selectedInterview.id) : ''"
            title="Delete Interview"
            message="Are you sure you want to delete this interview?"
            @close="closeDeleteConfirm" />
</template>
