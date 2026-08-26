<script setup lang="ts">
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import {
    Plus,
    Pencil,
    Eye,
    Trash2,
} from '@lucide/vue'

import AppTableLayout from '@/layouts/dashboard/AppTableLayout.vue'
import AppButton from '@/components/ui/AppButton.vue'
import AppToggle from '@/components/ui/AppToggle.vue'
import AppThreeDotOptions from '@/components/ui/AppThreeDotOptions.vue'
import AppConfirmDialog from '@/components/ui/AppConfirmDialog.vue'

import AppFilterLayout from '@/components/filters/layout/AppFilterLayout.vue'
import FilterInput from '@/components/filters/fields/FilterInput.vue'

import useFilterSync from '@/composables/useFilterSync'

import {
    index as surveyIndex,
    create as surveyCreate,
    edit as surveyEdit,
    results as surveyResults,
    destroy as surveyDestroy,
} from '@/routes/admin/surveys'

import {
    status as surveyStatus,
} from '@/routes/admin/surveys/update'

import type { Survey } from '@/types'


/*
|--------------------------------------------------------------------------
| Types
|--------------------------------------------------------------------------
*/

interface SurveyRow extends Survey {
    questions_count?: number
    responses_count?: number
}

interface PaginatedSurveys {
    data: SurveyRow[]
    total: number
    per_page: number
    current_page: number
    last_page: number
    prev_page_url: string | null
    next_page_url: string | null
}


/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/

const props = defineProps<{
    surveys: PaginatedSurveys

    filters?: {
        search?: string | null
    }
}>()


/*
|--------------------------------------------------------------------------
| Table
|--------------------------------------------------------------------------
*/

const headers = [
    {
        key: 'sno',
        label: '#',
    },
    {
        key: 'title',
        label: 'Title',
    },
    {
        key: 'questions',
        label: 'Questions',
    },
    {
        key: 'responses',
        label: 'Responses',
    },
    {
        key: 'status',
        label: 'Status',
    },
]

const columns = [
    {
        key: 'sno',
        slot: 'sno',
    },
    {
        key: 'title',
        slot: 'title',
    },
    {
        key: 'questions',
        slot: 'questions',
    },
    {
        key: 'responses',
        slot: 'responses',
    },
    {
        key: 'status',
        slot: 'status',
    },
]


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
    url: surveyIndex().url,
    initialFilters: {
        search: props.filters?.search ?? '',
    },
    debounce: 500,
    autoApply: true,
    preserveState: true,
    preserveScroll: true,
})


/*
|--------------------------------------------------------------------------
| Status
|--------------------------------------------------------------------------
*/

function toggleStatus(
    survey: SurveyRow,
): void {
    const newStatus = !survey.status
    router.patch(
        surveyStatus(survey.id).url,
        {
            status: newStatus,
        },
        {
            preserveScroll: true,
            onSuccess: () => {
                survey.status = newStatus
            },
        },
    )
}


/*
|--------------------------------------------------------------------------
| Delete
|--------------------------------------------------------------------------
*/

const toDelete = ref<SurveyRow | null>(null)
const deleting = ref(false)

function openDeleteDialog(
    survey: SurveyRow,
): void {
    toDelete.value = survey
}

function closeDeleteDialog(): void {
    if (deleting.value) {
        return
    }

    toDelete.value = null
}

function confirmDelete(): void {
    if (!toDelete.value) {
        return
    }
    deleting.value = true
    router.delete(
        surveyDestroy(toDelete.value.id).url,
        {
            preserveScroll: true,
            onFinish: () => {
                deleting.value = false
                toDelete.value = null
            },
        },
    )
}
</script>


<template>
        <AppTableLayout
            title="Surveys"
            :headers="headers"
            :columns="columns"
            :data="surveys.data"
            :total="surveys.total"
            :per-page="surveys.per_page"
            :current-page="surveys.current_page"
            :last-page="surveys.last_page"
            :prev-page-url="surveys.prev_page_url"
            :next-page-url="surveys.next_page_url"
            :filters="filters"
            primary-key="id"
        >

            <!-- Header -->

            <template #header>
                <AppButton
                    variant="add"
                    size="sm"
                    :href="surveyCreate().url"
                >
                    <template #icon-left>
                        <Plus class="h-4 w-4" />
                    </template>

                    New Survey
                </AppButton>
            </template>


            <!-- Filters -->

            <template #filter>
                <AppFilterLayout
                    :filters="filters"
                    @apply="applyFilters"
                    @reset="resetFilters"
                >
                    <template #search>
                        <FilterInput
                            v-model="filters.search"
                            :field="{
                                placeholder: 'Search surveys…',
                            }"
                        />
                    </template>
                </AppFilterLayout>
            </template>


            <!-- Serial Number -->

            <template #sno="{ index }">
                {{
                    (surveys.current_page - 1) *
                        surveys.per_page +
                    index +
                    1
                }}
            </template>


            <!-- Title -->

            <template #title="{ data }">
                <Link
                    :href="surveyEdit(data.id).url"
                    class="font-medium text-[#e0006c] hover:underline"
                >
                    {{ data.title }}
                </Link>
            </template>


            <!-- Questions -->

            <template #questions="{ data }">
                {{ data.questions_count ?? 0 }}
            </template>


            <!-- Responses -->

            <template #responses="{ data }">
                <Link
                    :href="surveyResults(data.id).url"
                    class="hover:underline"
                >
                    {{ data.responses_count ?? 0 }}
                </Link>
            </template>


            <!-- Status -->

            <template #status="{ data }">
                <AppToggle
                    :model-value="!!data.status"
                    true-label="Live"
                    false-label="Draft"
                    @update:modelValue="toggleStatus(data)"
                />
            </template>


            <!-- Actions -->

            <template #threedot="{ data }">
                <AppThreeDotOptions
                    :actions="[
                        {
                            label: 'Edit',
                            icon: Pencil,
                            href: surveyEdit(data.id).url,
                        },
                        {
                            label: 'Results',
                            icon: Eye,
                            href: surveyResults(data.id).url,
                        },
                        {
                            label: 'Delete',
                            icon: Trash2,
                            danger: true,
                            action: () =>
                                openDeleteDialog(data),
                        },
                    ]"
                />
            </template>
        </AppTableLayout>


        <!-- Delete Confirmation -->

        <AppConfirmDialog
            :open="!!toDelete"
            title="Delete survey?"
            :description="
                `This removes “${toDelete?.title}”, its questions and all responses.`
            "
            danger
            confirm-text="Delete"
            :loading="deleting"
            @confirm="confirmDelete"
            @cancel="closeDeleteDialog"
        />
</template>