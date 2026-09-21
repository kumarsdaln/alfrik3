<script setup lang="ts">
    import { computed } from 'vue'
    import { Link, router } from '@inertiajs/vue3'
    import {
        Eye,
        Trash2,
    } from '@lucide/vue'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import AppStats from '@/components/ui/AppStats.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import AppPagination from '@/components/ui/AppPagination.vue'

    import FilterControl from '@/components/table/FilterControl.vue'
    import TableLayout from '@/layouts/table/Layout.vue'

    import Date from '@/components/datadisplay/Date.vue'

    import type {
        Pagination,
        SurveyResponse,
    } from '@/types'

    interface Props {
        survey: {
            id: number
            title: string
        }

        responses: Pagination<SurveyResponse>

        filters: {
            search?: string
            status?: string
        }

        stats: {
            total: number
            submitted: number
            in_progress: number
            abandoned: number
        }
    }

    const props = defineProps<Props>()

    const search = computed({
        get: () => props.filters.search ?? '',
        set: (value: string) => {
            router.get(
                window.location.pathname,
                {
                    ...props.filters,
                    search: value || undefined,
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    replace: true,
                }
            )
        },
    })

    const viewResponse = (response: SurveyResponse) => {
        router.get(
            `/admin/survey/${props.survey.id}/responses/${response.id}`
        )
    }

    const deleteResponse = (response: SurveyResponse) => {
        if (!confirm('Are you sure you want to delete this response?')) {
            return
        }

        router.delete(
            `/admin/survey/${props.survey.id}/responses/${response.id}`,
            {
                preserveScroll: true,
            }
        )
    }
</script>

<template>
    <TableLayout>
        <template #header>
            <div class="flex items-center gap-4 py-5">
                <BackButton :href="`/admin/survey/${survey.id}`" />

                <Heading title="Survey Responses" :description="survey.title" />
            </div>
        </template>

        <!-- Stats -->
        <div class="mb-6">
            <AppStats :items="[
                {
                    label: 'Total Responses',
                    value: stats.total,
                },
                {
                    label: 'Submitted',
                    value: stats.submitted,
                },
                {
                    label: 'In Progress',
                    value: stats.in_progress,
                },
                {
                    label: 'Abandoned',
                    value: stats.abandoned,
                },
            ]" />
        </div>

        <!-- Filters -->
        <div class="mb-4 flex items-center justify-between gap-4">
            <FilterControl v-model="search" placeholder="Search respondents..." />
        </div>

        <!-- Table -->
        <AppTable :columns="[
            {
                key: 'id',
                label: 'ID',
            },
            {
                key: 'respondent_name',
                label: 'Respondent',
            },
            {
                key: 'status',
                label: 'Status',
            },
            {
                key: 'started_at',
                label: 'Started',
            },
            {
                key: 'submitted_at',
                label: 'Submitted',
            },
            {
                key: 'actions',
                label: 'Actions',
                align: 'right',
            },
        ]" :data="responses.data">
            <template #cell-respondent_name="{ row }">
                <div>
                    <div class="font-medium">
                        {{ row.respondent_name || 'Anonymous' }}
                    </div>

                    <div v-if="row.respondent_email" class="text-sm text-muted-foreground">
                        {{ row.respondent_email }}
                    </div>
                </div>
            </template>

            <template #cell-status="{ row }">
                <Badge variant="outline" :style="{
                    borderColor: row.status.color,
                    color: row.status.color,
                }">
                    {{ row.status.label }}
                </Badge>
            </template>

            <template #cell-started_at="{ row }">
                <Date v-if="row.started_at" :date="row.started_at" />

                <span v-else>
                    —
                </span>
            </template>

            <template #cell-submitted_at="{ row }">
                <Date v-if="row.submitted_at" :date="row.submitted_at" />

                <span v-else>
                    —
                </span>
            </template>

            <template #cell-actions="{ row }">
                <AppTableActions :actions="[
                    {
                        label: 'View',
                        icon: Eye,
                        onClick: () => viewResponse(row),
                    },
                    {
                        label: 'Delete',
                        icon: Trash2,
                        onClick: () => deleteResponse(row),
                        destructive: true,
                    },
                ]" />
            </template>
        </AppTable>

        <div class="mt-4">
            <AppPagination :pagination="responses" />
        </div>
    </TableLayout>
</template>