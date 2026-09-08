```vue
<script setup lang="ts">
import AppPagination from '@/components/ui/AppPagination.vue'
import AppTable from '@/components/ui/AppTable.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import DateDisplay from '@/components/datadisplay/Date.vue'

import type {
    Interview,
    InterviewStatusOption,
    InterviewTypeOption,
} from '@/types/interviews'

import type { Pagination } from '@/types/pagination'
import type { TableColumn } from '@/types/table'

interface Props {
    interviews: Pagination<Interview>
    statusOptions: InterviewStatusOption[]
    typeOptions: InterviewTypeOption[]
}

const props = defineProps<Props>()

const columns: TableColumn[] = [
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

interface BadgeValue {
    value?: string
    label: string
    color?: string
}

const isBadgeValue = (value: unknown): value is BadgeValue => {
    return (
        typeof value === 'object' &&
        value !== null &&
        'label' in value &&
        typeof value.label === 'string'
    )
}

const getDateValue = (value: unknown): string | null => {
    return typeof value === 'string' ? value : null
}
</script>

<template>
    <div class="space-y-6">
        <div>
            <h1 class="font-lora text-2xl font-semibold">
                Interviews
            </h1>

            <p class="
                    mt-1
                    text-sm
                    text-content-light-muted
                    dark:text-content-dark-muted
                ">
                Manage your interviews.
            </p>
        </div>

        <AppTable :columns="columns" :data="props.interviews.data">
            <template #cell-title="{ row }">
                <span class="font-medium">
                    {{ row.title }}
                </span>
            </template>

            <template #cell-interview_type="{ value }">
                <Badge v-if="isBadgeValue(value)" :color="value.color">
                    {{ value.label }}
                </Badge>

                <span v-else>—</span>
            </template>

            <template #cell-createdBy.name="{ value }">
                <span>
                    {{ value ?? '—' }}
                </span>
            </template>

            <template #cell-status="{ value }">
                <Badge v-if="isBadgeValue(value)" :color="value.color">
                    {{ value.label }}
                </Badge>

                <span v-else>—</span>
            </template>

            <template #cell-created_at="{ value }">
                <DateDisplay v-if="getDateValue(value)" :value="getDateValue(value)" />

                <span v-else>—</span>
            </template>

            <template #empty>
                <div class="py-4">
                    <p class="font-lora text-sm italic">
                        No interviews found.
                    </p>
                </div>
            </template>
        </AppTable>

        <AppPagination :meta="props.interviews.meta" />
    </div>
</template>
```
