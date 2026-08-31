<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import {
    CalendarCheck,
    CalendarDays,
    Plus,
    Users,
} from '@lucide/vue'

import AppContainer from '@/components/ui/AppContainer.vue'
import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import AppBadge from '@/components/ui/AppBadge.vue'
import AppStats from '@/components/ui/AppStats.vue'
import AppPagination from '@/components/ui/AppPagination.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import FilterControl from '@/components/filters/FilterControl.vue'
import AppTable from '@/components/ui/AppTable.vue'

import { useFilters } from '@/composables/useFilters'

import {
    create,
    edit,
    index,
    show,
} from '@/routes/admin/events'

/*
|--------------------------------------------------------------------------
| Types
|--------------------------------------------------------------------------
*/

interface EventCategory {
    id: number
    name: string
    slug: string
}

interface EventItem {
    id: number
    title: string
    slug: string
    description?: string | null
    cover_image?: string | null
    start_date: string
    end_date?: string | null
    event_type?: string | null
    visibility?: string | null
    status?: string | null
    city?: string | null
    venue?: string | null
    registrations_count?: number
    categories?: EventCategory[]
}

interface PaginatedEvents {
    data: EventItem[]
    current_page: number
    last_page: number
    from: number | null
    to: number | null
    total: number
    per_page: number
}

interface EventStats {
    total: number
    published: number
    upcoming: number
    registrations: number
}

interface Props {
    events: PaginatedEvents
    categories: EventCategory[]
    stats: EventStats

    qfilters?: {
        search?: string
        category?: string
        status?: string
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
    applyFilters,
    clearFilters,
} = useFilters({
    search: props.qfilters?.search ?? '',
    category: props.qfilters?.category ?? '',
    status: props.qfilters?.status ?? '',
})

const filterCount = computed(() => {
    return [
        filters.category,
        filters.status,
    ].filter(Boolean).length
})

/*
|--------------------------------------------------------------------------
| Filter Options
|--------------------------------------------------------------------------
*/

const categoryOptions = computed(() => [
    {
        value: '',
        label: 'All categories',
    },

    ...props.categories.map((category) => ({
        value: category.slug,
        label: category.name,
    })),
])

const statusOptions = [
    {
        value: '',
        label: 'All statuses',
    },
    {
        value: 'draft',
        label: 'Draft',
    },
    {
        value: 'published',
        label: 'Published',
    },
    {
        value: 'cancelled',
        label: 'Cancelled',
    },
]

/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

const statItems = computed(() => [
    {
        label: 'Total events',
        value: props.stats.total,
        description: 'All events',
        icon: CalendarDays,
    },
    {
        label: 'Published',
        value: props.stats.published,
        description: 'Currently live',
        icon: CalendarCheck,
    },
    {
        label: 'Upcoming',
        value: props.stats.upcoming,
        description: 'Scheduled events',
        icon: CalendarDays,
    },
    {
        label: 'Registrations',
        value: props.stats.registrations,
        description: 'Across all events',
        icon: Users,
    },
])

/*
|--------------------------------------------------------------------------
| Table
|--------------------------------------------------------------------------
*/

const tableColumns = [
    {
        key: 'title',
        label: 'Event',
        width: '34%',
    },
    {
        key: 'start_date',
        label: 'Date',
    },
    {
        key: 'event_type',
        label: 'Type',
    },
    {
        key: 'registrations_count',
        label: 'Registrations',
        align: 'right' as const,
    },
    {
        key: 'visibility',
        label: 'Visibility',
    },
    {
        key: 'status',
        label: 'Status',
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
| Helpers
|--------------------------------------------------------------------------
*/

const formatDate = (date?: string | null) => {
    if (!date) {
        return '—'
    }

    return new Intl.DateTimeFormat('en', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    }).format(new Date(date))
}

const eventTypeLabel = (type?: string | null) => {
    switch (type) {
        case 'online':
            return 'Online'

        case 'hybrid':
            return 'Hybrid'

        case 'in-person':
        case 'in_person':
            return 'In-person'

        default:
            return type || '—'
    }
}

const statusVariant = (status?: string | null) => {
    switch (status) {
        case 'published':
            return 'primary'

        case 'cancelled':
            return 'destructive'

        default:
            return 'secondary'
    }
}

/*
|--------------------------------------------------------------------------
| Pagination
|--------------------------------------------------------------------------
*/

const changePage = (page: number) => {
    router.get(
        index().url,
        {
            ...filters,
            page,
        },
        {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        },
    )
}
const paginationMeta = computed(() => ({
    current_page: props.events.current_page,
    last_page: props.events.last_page,
    from: props.events.from,
    to: props.events.to,
    total: props.events.total,
    per_page: props.events.per_page,
}))
</script>

<template>
    <Head title="Events — Admin" />

    <AppContainer class="py-10 lg:py-14">

        <!--
        |--------------------------------------------------------------------------
        | Header
        |--------------------------------------------------------------------------
        -->

        <header
            class="
                mb-10
                flex
                flex-col
                gap-6
                border-b
                border-border-light
                pb-8
                dark:border-border-dark
                sm:flex-row
                sm:items-end
                sm:justify-between
            "
        >
            <div class="min-w-0">
                <AppText
                    tag="p"
                    font="redhat"
                    size="xs"
                    weight="bold"
                    tracking="wide"
                    uppercase
                    color="brand"
                >
                    Administration
                </AppText>

                <AppHeading
                    tag="h1"
                    font="prata"
                    size="4xl"
                    weight="normal"
                    leading="tight"
                    class="mt-2"
                >
                    Events
                </AppHeading>

                <AppText
                    tag="p"
                    size="sm"
                    color="muted"
                    class="mt-3 max-w-2xl"
                >
                    Manage events, schedules, registrations and
                    publication status.
                </AppText>
            </div>

            <Link
                :href="create().url"
                class="
                    inline-flex
                    h-10
                    shrink-0
                    items-center
                    justify-center
                    gap-2
                    bg-content-light
                    px-5
                    font-redhat
                    text-sm
                    font-semibold
                    text-white
                    transition-colors
                    hover:bg-brand
                    dark:bg-content-dark
                    dark:text-black
                    dark:hover:bg-brand
                    dark:hover:text-white
                "
            >
                <Plus class="h-4 w-4" />

                Create event
            </Link>
        </header>

        <!--
        |--------------------------------------------------------------------------
        | Stats
        |--------------------------------------------------------------------------
        -->

        <section class="mb-10">
            <AppStats :items="statItems" />
        </section>

        <!--
        |--------------------------------------------------------------------------
        | Filters
        |--------------------------------------------------------------------------
        -->

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
                        v-model="filters.category"
                        name="category"
                        label="Category"
                        placeholder="All categories"
                        :options="categoryOptions"
                    />

                    <AppSelect
                        v-model="filters.status"
                        name="status"
                        label="Status"
                        placeholder="All statuses"
                        :options="statusOptions"
                    />

                </div>
            </FilterControl>
        </section>

        <!--
        |--------------------------------------------------------------------------
        | Table
        |--------------------------------------------------------------------------
        -->

        <section>
            <AppTable
                :columns="tableColumns"
                :data="events.data"
                empty-text="No events found."
            >

                <!-- Event -->

                <template #cell-title="{ row }">
                    <div class="flex min-w-0 items-center gap-4">

                        <div
                            class="
                                h-12
                                w-16
                                shrink-0
                                overflow-hidden
                                bg-muted
                            "
                        >
                            <img
                                v-if="row.cover_image"
                                :src="row.cover_image"
                                :alt="row.title"
                                class="h-full w-full object-cover"
                            />

                            <div
                                v-else
                                class="
                                    flex
                                    h-full
                                    items-center
                                    justify-center
                                "
                            >
                                <AppText
                                    size="xs"
                                    weight="bold"
                                    tracking="wide"
                                    uppercase
                                    color="muted"
                                >
                                    Event
                                </AppText>
                            </div>
                        </div>

                        <div class="min-w-0">
                            <Link
                                :href="show(row.slug).url"
                                class="
                                    block
                                    truncate
                                    font-redhat
                                    text-sm
                                    font-semibold
                                    text-content-light
                                    transition-colors
                                    hover:text-brand
                                    dark:text-content-dark
                                "
                            >
                                {{ row.title }}
                            </Link>

                            <AppText
                                v-if="row.categories?.length"
                                tag="p"
                                size="xs"
                                color="muted"
                                class="mt-1 truncate"
                            >
                                {{ row.categories[0].name }}
                            </AppText>
                        </div>

                    </div>
                </template>

                <!-- Date -->

                <template #cell-start_date="{ value }">
                    <AppText
                        size="sm"
                        class="whitespace-nowrap"
                    >
                        {{ formatDate(value) }}
                    </AppText>
                </template>

                <!-- Type -->

                <template #cell-event_type="{ value }">
                    <AppText
                        size="sm"
                        color="muted"
                    >
                        {{ eventTypeLabel(value) }}
                    </AppText>
                </template>

                <!-- Registrations -->

                <template #cell-registrations_count="{ value }">
                    <AppText
                        size="sm"
                        weight="medium"
                    >
                        {{ value ?? 0 }}
                    </AppText>
                </template>

                <!-- Visibility -->

                <template #cell-visibility="{ value }">
                    <AppBadge
                        :variant="
                            value === 'public'
                                ? 'primary'
                                : 'secondary'
                        "
                        size="sm"
                    >
                        {{ value || '—' }}
                    </AppBadge>
                </template>

                <!-- Status -->

                <template #cell-status="{ value }">
                    <AppBadge
                        :variant="statusVariant(value)"
                        size="sm"
                    >
                        {{ value || '—' }}
                    </AppBadge>
                </template>

                <!-- Actions -->

                <template #cell-actions="{ row }">
                    <div class="flex justify-end">
                        <Link
                            :href="edit(row.id).url"
                            :aria-label="`Edit ${row.title}`"
                            class="
                                inline-flex
                                h-8
                                w-8
                                items-center
                                justify-center
                                text-content-light/50
                                transition-colors
                                hover:bg-muted
                                hover:text-content-light
                                dark:text-content-dark/50
                                dark:hover:text-content-dark
                            "
                        >
                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                                class="h-4 w-4"
                                aria-hidden="true"
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 20h9"
                                />

                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5Z"
                                />
                            </svg>
                        </Link>
                    </div>
                </template>

            </AppTable>
        </section>

        <!--
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        -->
        <section class="mt-6">
            <AppPagination :meta="paginationMeta" @page-change="changePage" />
        </section>

    </AppContainer>
</template>