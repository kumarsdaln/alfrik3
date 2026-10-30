<script setup lang="ts">
    import { computed } from 'vue'
    import { Link, router } from '@inertiajs/vue3'
    import { Eye, Pencil, Plus, Trash2 } from '@lucide/vue'

    import Date from '@/components/datadisplay/Date.vue'
    import AppPagination from '@/components/ui/AppPagination.vue'
    import AppStats from '@/components/ui/AppStats.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import Button from '@/components/ui/button/Button.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import FilterControl from '@/components/filters/FilterControl.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import TableLayout from '@/layouts/table/TableLayout.vue'

    import { useFilters } from '@/composables/useFilters'

    import {
        create,
        show,
        edit,
        destroy,
    } from '@/routes/admin/survey'

    import type { FormOption, Pagination, Survey } from '@/types'
    import type { TableAction } from '@/components/ui/AppTableActions.vue'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import { index as manageMedia } from '@/routes/admin/media'
    import { index as manageCategory } from '@/routes/admin/categories/assignment'
    import { index as manageTags } from '@/routes/admin/tags/assignment'
    import { index as manageSections } from '@/routes/admin/survey/sections'

    interface Props {
        surveys: Pagination<Survey>

        statusOptions: FormOption[]

        filters: {
            search?: string
            status?: string
            research_id?: string
        }

        stats: {
            total: number
            published: number
            draft: number
            closed: number
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
            label: 'Total Surveys',
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
            label: 'Closed',
            value: props.stats.closed,
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
            key: 'status',
            label: 'Status',
            width: '140px',
        },
        {
            key: 'response_count',
            label: 'Responses',
            width: '120px',
        },
        {
            key: 'starts_at',
            label: 'Starts At',
            width: '180px',
        },
        {
            key: 'ends_at',
            label: 'Ends At',
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

    function deleteSurvey(survey: Survey) {
        if (
            !confirm(
                `Are you sure you want to delete "${survey.title}"?`,
            )
        ) {
            return
        }

        router.delete(
            destroy(survey.id).url,
            {
                preserveScroll: true,
            },
        )
    }

    function getSurveyActions(survey: Survey): TableAction[] {
        return [
            {
                label: 'View',
                icon: Eye,
                href: show(survey.id).url,
            },
            {
                label: 'Edit',
                icon: Pencil,
                href: edit(survey.id).url,
            },
            {
                label: 'Manage Category',
                icon: Pencil,
                href: manageCategory({
                    type: 'survey',
                    id: survey.id,
                }).url,
            },
            {
                label: 'Manage Tags',
                icon: Pencil,
                href: manageTags({
                    type: 'survey',
                    id: survey.id,
                }).url,
            },
            {
                label: 'Manage Media',
                icon: Pencil,
                href: manageMedia({
                   type: 'survey',
                    id: survey.id,
                }).url,
            },
            {
                label: 'Manage Sections',
                icon: Pencil,
                href: manageSections(survey.id).url,
            },
            {
                label: 'Delete',
                icon: Trash2,
                danger: true,
                onClick: () => deleteSurvey(survey),
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

                    <Heading title="Surveys"
                        description="Create and manage surveys and collect structured responses." />
                </div>

                <Button as-child class="gap-2">
                    <Link :href="create()">
                        <Plus class="size-4" />
                        Create Survey
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
                <FilterControl v-model:search="filters.search" search-placeholder="Search surveys..."
                    :filter-count="filterCount" @apply="applyFilters" @clear="clearFilters">
                    <div class="space-y-6">
                        <AppSelect v-model="filters.status" name="status" label="Status" placeholder="All statuses"
                            :options="statusOptions" />
                    </div>
                </FilterControl>
            </section>

            <!-- Table -->

            <AppTable :columns="columns" :data="props.surveys.data">
                <!-- Title -->

                <template #cell-title="{ row }">
                    <div class="min-w-0">
                        <div class="font-medium">
                            {{ row.title }}
                        </div>

                        <div class="mt-1 text-sm text-muted-foreground">
                            {{ row.slug }}
                        </div>
                    </div>
                </template>

                <!-- Status -->

                <template #cell-status="{ value }">
                    <Badge :color="value.color">
                        {{ value.label }}
                    </Badge>
                </template>

                <!-- Responses -->

                <template #cell-response_count="{ value }">
                    {{ value }}
                </template>

                <!-- Starts At -->

                <template #cell-starts_at="{ value }">
                    <Date :value="value" />
                </template>

                <!-- Ends At -->

                <template #cell-ends_at="{ value }">
                    <Date :value="value" />
                </template>

                <!-- Actions -->

                <template #cell-actions="{ row }">
                    <AppTableActions :actions="getSurveyActions(row)" />
                </template>
            </AppTable>
        </div>

        <!-- Footer -->

        <template #footer>
            <AppPagination :meta="props.surveys.meta" />
        </template>
    </TableLayout>
</template>