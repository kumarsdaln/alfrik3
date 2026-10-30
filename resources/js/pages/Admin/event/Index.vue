
<script setup lang="ts">
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { CalendarDays, Eye, Pencil, Plus, Trash2 } from '@lucide/vue'

import Date from '@/components/datadisplay/Date.vue'
import AppPagination from '@/components/ui/AppPagination.vue'
import AppStats from '@/components/ui/AppStats.vue'
import AppTable from '@/components/ui/AppTable.vue'
import AppTableActions from '@/components/ui/AppTableActions.vue'
import Button from '@/components/ui/button/Button.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import FilterControl from '@/components/filters/FilterControl.vue'
import TableLayout from '@/layouts/table/TableLayout.vue'
import Heading from '@/components/Heading.vue'
import BackButton from '@/components/ui/BackButton.vue'
import Badge from '@/components/ui/badge/Badge.vue'

import { useFilters } from '@/composables/useFilters'
import { create, show, edit, destroy } from '@/routes/admin/events'
import { index as manageMedia } from '@/routes/admin/media'
import { index as manageCategory } from '@/routes/admin/categories/assignment'
import { index as manageTags } from '@/routes/admin/tags/assignment'

import type { Event, FormOption, Pagination } from '@/types'
import type { TableAction } from '@/components/ui/AppTableActions.vue'

interface Props {
    events: Pagination<Event>
    typeOptions: FormOption[]
    statusOptions: FormOption[]
    visibilityOptions: FormOption[]
    filters: {
        search?: string
        event_type?: string
        status?: string
        visibility?: string
    }
    stats: {
        total: number
        published: number
        draft: number
        upcoming: number
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
        event_type: props.filters.event_type ?? '',
        status: props.filters.status ?? '',
        visibility: props.filters.visibility ?? '',
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
        label: 'Total Events',
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
        label: 'Upcoming',
        value: props.stats.upcoming,
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
        label: 'Event',
    },
    {
        key: 'event_type',
        label: 'Type',
        width: '140px',
    },
    {
        key: 'start_date',
        label: 'Start Date',
        width: '180px',
    },
    {
        key: 'location',
        label: 'Location',
        width: '200px',
    },
    {
        key: 'visibility',
        label: 'Visibility',
        width: '130px',
    },
    {
        key: 'status',
        label: 'Status',
        width: '140px',
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

function deleteEvent(event: Event): void {
    if (!confirm(`Are you sure you want to delete "${event.title}"?`)) {
        return
    }

    router.delete(destroy({ id: event.id }).url, {
        preserveScroll: true,
    })
}

function getEventActions(event: Event): TableAction[] {
    return [
        {
            label: 'View',
            icon: Eye,
            href: show({ id: event.id }).url,
        },
        {
            label: 'Edit',
            icon: Pencil,
            href: edit({ id: event.id }).url,
        },
        {
            label: 'Manage Categories',
            icon: Pencil,
            href: manageCategory({type:'event', id:event.id}).url,
        },
        {
            label: 'Manage Tags',
            icon: Pencil,
            href: manageTags({type:'event', id:event.id}).url,
        },
        {
            label: 'Manage Media',
            icon: Pencil,
            href: manageMedia({type:'event', id:event.id}).url,
        },
        {
            label: 'Delete',
            icon: Trash2,
            danger: true,
            onClick: () => deleteEvent(event),
        },
    ]
}

function getLocation(event: Event): string {
    if (event.location_name) {
        return event.location_name
    }

    if (event.event_type.value === 'online') {
        return 'Online'
    }

    return [event.city, event.state, event.country]
        .filter(Boolean)
        .join(', ') || '—'
}
</script>

<template>
    <TableLayout>
        <!-- Header -->
        <template #header>
            <div class="flex items-center justify-between gap-4 py-5">
                <div class="flex gap-4">
                    <BackButton />

                    <Heading
                        title="Events"
                        description="Manage and organize your events."
                    />
                </div>

                <Button as-child class="gap-2">
                    <Link :href="create()">
                        <Plus class="size-4" />
                        Create Event
                    </Link>
                </Button>
            </div>
        </template>

        <!-- Content -->
        <div class="py-6">
            <!-- Statistics -->
            <section class="mb-8">
                <AppStats :items="statItems" />
            </section>

            <!-- Filters -->
            <section class="mb-8">
                <FilterControl
                    v-model:search="filters.search"
                    search-placeholder="Search events..."
                    :filter-count="filterCount"
                    @apply="applyFilters"
                    @clear="clearFilters"
                >
                    <div class="space-y-6">
                        <AppSelect
                            v-model="filters.event_type"
                            name="event_type"
                            label="Event Type"
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

                        <AppSelect
                            v-model="filters.visibility"
                            name="visibility"
                            label="Visibility"
                            placeholder="All visibility"
                            :options="visibilityOptions"
                        />
                    </div>
                </FilterControl>
            </section>

            <!-- Table -->
            <AppTable
                :columns="columns"
                :data="props.events.data"
            >
                <!-- Event -->
                <template #cell-title="{ row }">
                    <div class="flex items-start gap-3">
                        <div
                            class="flex size-10 shrink-0 items-center justify-center rounded-md border border-border"
                        >
                            <CalendarDays class="size-5 text-muted-foreground" />
                        </div>

                        <div class="min-w-0">
                            <div class="font-medium">
                                {{ row.title }}
                            </div>

                            <div class="text-sm text-muted-foreground">
                                {{ row.slug }}
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Event Type -->
                <template #cell-event_type="{ value }">
                    <Badge :color="value.color">
                        {{ value.label }}
                    </Badge>
                </template>

                <!-- Start Date -->
                <template #cell-start_date="{ value }">
                    <Date :value="value" />
                </template>

                <!-- Location -->
                <template #cell-location="{ row }">
                    <span class="text-sm">
                        {{ getLocation(row) }}
                    </span>
                </template>

                <!-- Visibility -->
                <template #cell-visibility="{ value }">
                    <Badge :color="value.color">
                        {{ value.label }}
                    </Badge>
                </template>

                <!-- Status -->
                <template #cell-status="{ value }">
                    <Badge :color="value.color">
                        {{ value.label }}
                    </Badge>
                </template>

                <!-- Actions -->
                <template #cell-actions="{ row }">
                    <AppTableActions
                        :actions="getEventActions(row)"
                    />
                </template>
            </AppTable>
        </div>

        <!-- Footer -->
        <template #footer>
            <AppPagination :meta="props.events.meta" />
        </template>
    </TableLayout>
</template>
