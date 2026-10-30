<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { CalendarDays, Eye, Ticket, UserRound } from '@lucide/vue'

import Date from '@/components/datadisplay/Date.vue'
import AppPagination from '@/components/ui/AppPagination.vue'
import AppStats from '@/components/ui/AppStats.vue'
import AppTable from '@/components/ui/AppTable.vue'
import AppTableActions from '@/components/ui/AppTableActions.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import FilterControl from '@/components/filters/FilterControl.vue'
import TableLayout from '@/layouts/table/TableLayout.vue'
import Heading from '@/components/Heading.vue'
import BackButton from '@/components/ui/BackButton.vue'
import Badge from '@/components/ui/badge/Badge.vue'

import { useFilters } from '@/composables/useFilters'
import type { FormOption, Pagination } from '@/types'
import type { TableAction } from '@/components/ui/AppTableActions.vue'

interface Registration {
    id: number
    registration_code: string
    status: FormOption
    created_at: string

    event: {
        id: number
        title: string
    } | null

    ticket: {
        id: number
        name: string
        price: number | string
        currency: string
    } | null

    user: {
        id: number
        name: string
        email: string
    } | null
}

interface Props {
    registrations: Pagination<Registration>
    statusOptions: FormOption[]
    filters: {
        search?: string
        status?: string
    }
    stats: {
        total: number
        confirmed: number
        pending: number
        cancelled: number
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
        label: 'Total Registrations',
        value: props.stats.total,
    },
    {
        label: 'Confirmed',
        value: props.stats.confirmed,
    },
    {
        label: 'Pending',
        value: props.stats.pending,
    },
    {
        label: 'Cancelled',
        value: props.stats.cancelled,
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
        key: 'registration_code',
        label: 'Registration',
    },
    {
        key: 'attendee',
        label: 'Attendee',
    },
    {
        key: 'event',
        label: 'Event',
    },
    {
        key: 'ticket',
        label: 'Ticket',
        width: '180px',
    },
    {
        key: 'status',
        label: 'Status',
        width: '140px',
    },
    {
        key: 'created_at',
        label: 'Registered At',
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

function getRegistrationActions(
    registration: Registration,
): TableAction[] {
    return [
        {
            label: 'View',
            icon: Eye,
            href: `/admin/event-registrations/${registration.id}`,
        },
    ]
}

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function formatPrice(
    price: number | string,
    currency: string,
): string {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency,
        maximumFractionDigits: 2,
    }).format(Number(price))
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
                        title="Event Registrations"
                        description="Manage attendee registrations for your events."
                    />
                </div>
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
                    search-placeholder="Search registrations..."
                    :filter-count="filterCount"
                    @apply="applyFilters"
                    @clear="clearFilters"
                >
                    <div class="space-y-6">
                        <AppSelect
                            v-model="filters.status"
                            name="status"
                            label="Registration Status"
                            placeholder="All statuses"
                            :options="statusOptions"
                        />
                    </div>
                </FilterControl>
            </section>

            <!-- Table -->
            <AppTable
                :columns="columns"
                :data="props.registrations.data"
            >
                <!-- Registration -->
                <template #cell-registration_code="{ row }">
                    <div class="flex items-start gap-3">
                        <div
                            class="flex size-10 shrink-0 items-center justify-center rounded-md border border-border"
                        >
                            <Ticket
                                class="size-5 text-muted-foreground"
                            />
                        </div>

                        <div class="min-w-0">
                            <div class="font-medium">
                                {{ row.registration_code }}
                            </div>

                            <div class="text-sm text-muted-foreground">
                                #{{ row.id }}
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Attendee -->
                <template #cell-attendee="{ row }">
                    <div class="flex items-start gap-3">
                        <div
                            class="flex size-10 shrink-0 items-center justify-center rounded-md border border-border"
                        >
                            <UserRound
                                class="size-5 text-muted-foreground"
                            />
                        </div>

                        <div class="min-w-0">
                            <div class="font-medium">
                                {{ row.user?.name ?? 'Guest attendee' }}
                            </div>

                            <div class="text-sm text-muted-foreground">
                                {{ row.user?.email ?? '—' }}
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Event -->
                <template #cell-event="{ row }">
                    <div class="flex items-start gap-2">
                        <CalendarDays
                            class="mt-0.5 size-4 shrink-0 text-muted-foreground"
                        />

                        <span class="font-medium">
                            {{ row.event?.title ?? 'Event unavailable' }}
                        </span>
                    </div>
                </template>

                <!-- Ticket -->
                <template #cell-ticket="{ row }">
                    <div v-if="row.ticket">
                        <div class="font-medium">
                            {{ row.ticket.name }}
                        </div>

                        <div class="text-sm text-muted-foreground">
                            {{ formatPrice(
                                row.ticket.price,
                                row.ticket.currency,
                            ) }}
                        </div>
                    </div>

                    <span v-else class="text-muted-foreground">
                        —
                    </span>
                </template>

                <!-- Status -->
                <template #cell-status="{ value }">
                    <Badge :color="value.color">
                        {{ value.label }}
                    </Badge>
                </template>

                <!-- Registration Date -->
                <template #cell-created_at="{ value }">
                    <Date :value="value" />
                </template>

                <!-- Actions -->
                <template #cell-actions="{ row }">
                    <AppTableActions
                        :actions="getRegistrationActions(row)"
                    />
                </template>
            </AppTable>
        </div>

        <!-- Footer -->
        <template #footer>
            <AppPagination :meta="registrations.meta" />
        </template>
    </TableLayout>
</template>