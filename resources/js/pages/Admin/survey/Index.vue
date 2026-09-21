<script setup lang="ts">
    import { computed } from 'vue'
    import { Link, router } from '@inertiajs/vue3'
    import { Eye, Pencil, Plus, Trash2 } from '@lucide/vue'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import AppStats from '@/components/ui/AppStats.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import FilterControl from '@/components/ui/FilterControl.vue'

    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import AppPagination from '@/components/ui/AppPagination.vue'

    import TableLayout from '@/layouts/table/Layout.vue'

    import { useFilters } from '@/composables/useFilters'

    import { create, show, edit } from '@/routes/admin/survey'

    import type {
        FormOption,
        Pagination,
        Survey,
    } from '@/types'

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

    const {
        filters,
        search,
        filter,
    } = useFilters({
        initialFilters: props.filters,
        url: window.location.pathname,
        searchKey: 'search',
        debounce: 500,
    })

    const columns = computed(() => [
        {
            key: 'id',
            label: 'ID',
            width: '80px',
        },
        {
            key: 'title',
            label: 'Survey',
        },
        {
            key: 'status',
            label: 'Status',
        },
        {
            key: 'responses',
            label: 'Responses',
        },
        {
            key: 'starts_at',
            label: 'Starts',
        },
        {
            key: 'ends_at',
            label: 'Ends',
        },
        {
            key: 'actions',
            label: 'Actions',
            align: 'right',
        },
    ])

    const deleteSurvey = (id: number) => {
        if (!confirm('Are you sure you want to delete this survey?')) {
            return
        }

        router.delete(`/admin/survey/${id}`)
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

                <Button as-child>
                    <Link :href="create.url()">
                        <Plus class="mr-2 h-4 w-4" />
                        Create Survey
                    </Link>
                </Button>
            </div>
        </template>

        <!-- Stats -->

        <AppStats :items="[
            {
                label: 'Total Surveys',
                value: stats.total,
            },
            {
                label: 'Published',
                value: stats.published,
            },
            {
                label: 'Drafts',
                value: stats.draft,
            },
            {
                label: 'Closed',
                value: stats.closed,
            },
        ]" class="mb-6" />

        <!-- Filters -->

        <div class="mb-4 flex flex-wrap items-center gap-3">
            <FilterControl v-model="search" placeholder="Search surveys..." />

            <AppSelect :model-value="filters.status" :options="statusOptions" placeholder="All statuses"
                @update:model-value="filter('status', $event)" />
        </div>

        <!-- Table -->

        <AppTable :data="surveys.data" :columns="columns">
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

            <template #cell-status="{ row }">
                <Badge :variant="row.status.value === 'published'
                        ? 'default'
                        : row.status.value === 'closed'
                            ? 'destructive'
                            : 'secondary'
                    ">
                    {{ row.status.label }}
                </Badge>
            </template>

            <template #cell-responses="{ row }">
                {{ row.response_count }}
            </template>

            <template #cell-starts_at="{ row }">
                {{
                    row.starts_at
                        ? new Date(row.starts_at).toLocaleString()
                        : '—'
                }}
            </template>

            <template #cell-ends_at="{ row }">
                {{
                    row.ends_at
                        ? new Date(row.ends_at).toLocaleString()
                        : '—'
                }}
            </template>

            <template #cell-actions="{ row }">
                <AppTableActions :actions="[
                    {
                        label: 'View',
                        icon: Eye,
                        href: show.url(row.id),
                    },
                    {
                        label: 'Edit',
                        icon: Pencil,
                        href: edit.url(row.id),
                    },
                    {
                        label: 'Delete',
                        icon: Trash2,
                        onClick: () => deleteSurvey(row.id),
                    },
                ]" />
            </template>
        </AppTable>

        <!-- Pagination -->

        <AppPagination v-if="surveys.meta" :links="surveys.meta.links" />

    </TableLayout>
</template>