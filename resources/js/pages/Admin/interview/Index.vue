<script setup lang="ts">
    import AppTable, {
        type AppTableColumn,
    } from '@/components/ui/AppTable.vue'
    import AppPagination from '@/components/ui/AppPagination.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import {
        Interview,
        InterviewStatusOption,
        InterviewTypeOption,
    } from '@/types/interviews'
    import { Pagination } from '@/types/pagination'
    import Date from '@/components/datadisplay/Date.vue'


    interface Props {
        interviews: Pagination<Interview>
        statusOptions: InterviewStatusOption[]
        typeOptions: InterviewTypeOption[]
    }

    const props = defineProps<Props>()

    const columns: AppTableColumn[] = [
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
            width: '140px',
        },
        {
            key: 'createdBy.name',
            label: 'Created By',
            width: '180px',
        },
        {
            key: 'status',
            label: 'Status',
            width: '140px',
        },
        {
            key: 'created_at',
            label: 'Created',
            width: '180px',
        },
    ]
</script>

<template>
    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="font-lora text-2xl font-semibold">
                Interviews
            </h1>

            <p class="mt-1 text-sm text-content-light-muted dark:text-content-dark-muted">
                Manage your interviews.
            </p>
        </div>

        <!-- Table -->
        <AppTable :columns="columns" :data="props.interviews.data">
            <!-- Title -->
            <template #cell-title="{ row }">
                <span class="font-medium">
                    {{ row.title }}
                </span>
            </template>

            <!-- Type -->
            <template #cell-interview_type="{ value }">
                <Badge :color="value.color">
                    {{ value.label }}
                </Badge>
            </template>

            <!-- Created By -->
            <template #cell-createdBy.name="{ value }">
                <span>
                    {{ value ?? '—' }}
                </span>
            </template>

            <!-- Status -->
            <template #cell-status="{ value }">
                <Badge :color="value.color">
                    {{ value.label }}
                </Badge>
            </template>

            <!-- Created -->
            <template #cell-created_at="{ value }">
                <Date :value="value" />
            </template>

            <!-- Empty -->
            <template #empty>
                <div class="py-4">
                    <p class="font-lora text-sm italic">
                        No interviews found.
                    </p>
                </div>
            </template>
        </AppTable>
         <AppPagination :meta="interviews.meta" />
    </div>
</template>

