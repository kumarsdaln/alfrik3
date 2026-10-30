<script setup lang="ts">
    import { computed } from 'vue'
    import { Head, InfiniteScroll } from '@inertiajs/vue3'

    import AppText from '@/components/ui/AppText.vue'
    import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'

    import FilterControl from '@/components/filters/FilterControl.vue'
    import AppSelect from '@/components/form/AppSelect.vue'

    import { useFilters } from '@/composables/useFilters'
    import { index as eventIndex, show as eventShow } from '@/routes/events'

    import EventCard from '@/components/events/EventCard.vue'
    import EventHero from '@/components/events/EventHero.vue'

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
        city?: string | null
        venue?: string | null
        categories?: EventCategory[]
    }

    interface PaginatedEvents {
        data: EventItem[]
        current_page?: number
        last_page?: number
        total?: number
    }

    interface Props {
        events: PaginatedEvents
        featured?: EventItem | null
        categories: EventCategory[]
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
    } = useFilters(
        {
            search: props.qfilters?.search ?? '',
            category: props.qfilters?.category ?? '',
            status: props.qfilters?.status ?? 'upcoming',
        },
        {
            url: eventIndex().url,
            searchKey: 'search',
            debounce: 500,
        },
    )

    /*
    |--------------------------------------------------------------------------
    | Filter Options
    |--------------------------------------------------------------------------
    */

    const categoryOptions = computed(() =>
        props.categories.map((category) => ({
            value: category.slug,
            label: category.name,
        })),
    )

    const statusOptions = [
        {
            value: 'upcoming',
            label: 'Upcoming',
        },
        {
            value: 'past',
            label: 'Past',
        },
        {
            value: 'all',
            label: 'All events',
        },
    ]

    /*
    |--------------------------------------------------------------------------
    | Active Filter Count
    |--------------------------------------------------------------------------
    */

    const filterCount = computed(() => {
        let count = 0

        if (filters.category) count++
        if (filters.status && filters.status !== 'upcoming') count++

        return count
    })
</script>

<template>

    <Head title="Events — Alfrik">
        <meta name="description" content="Explore conferences, discussions, workshops and other events from Alfrik." />
    </Head>

    <!-- Featured Event -->

    <EventHero v-if="featured" :event="featured" :event-url="eventShow(featured.slug).url" />

    <!-- Filters -->
    <FilterControl v-model:search="filters.search" search-placeholder="Search events..." :filter-count="filterCount"
        @clear="clearFilters" @apply="applyFilters">
        <div class="space-y-6">
            <AppSelect v-model="filters.category" name="category" label="Event category"
                placeholder="All event categories" :options="categoryOptions" />

            <AppSelect v-model="filters.status" name="status" label="Event status" placeholder="All events"
                :options="statusOptions" />
        </div>
    </FilterControl>

    <!-- Event List -->
    <section class="pb-20 pt-10">
        <div class="mb-6 flex items-center justify-between gap-4">
            <div>
                <AppText tag="h2" font="lora" size="xl" class="font-semibold">
                    Discover events
                </AppText>

                <AppText tag="p" size="sm" color="muted" class="mt-1">
                    Find conferences, workshops and upcoming experiences.
                </AppText>
            </div>

            <AppText tag="p" size="sm" color="muted" class="shrink-0">
                {{ events.total ?? events.data.length }} events
            </AppText>
        </div>

        <InfiniteScroll data="events" class="divide-y divide-border-light dark:divide-border-dark">
            <EventCard v-for="event in events.data" :key="event.id" :event="event" :href="eventShow(event.slug).url" />

            <template #loading>
                <div class="flex justify-center py-10">
                    <LoadingSpinner :loading="true" />
                </div>
            </template>
        </InfiniteScroll>

        <!-- Empty State -->

        <div v-if="!events.data.length"
            class="border border-dashed border-border-light py-28 text-center dark:border-border-dark">
            <AppText tag="p" font="lora" size="xl" color="muted" align="center" class="mb-3 italic">
                No events found.
            </AppText>

            <AppText tag="p" size="sm" color="muted" align="center">
                Try a different event category or search term.
            </AppText>
        </div>
    </section>
</template>
