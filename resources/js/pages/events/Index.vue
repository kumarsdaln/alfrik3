<script setup lang="ts">
    import { Head, Link, InfiniteScroll } from '@inertiajs/vue3'
    import { computed } from 'vue'
    import { MapPin, Video, Globe, Users, CalendarDays, ArrowRight } from '@lucide/vue'
    import AppContainer from '@/components/ui/AppContainer.vue'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppBadge from '@/components/ui/AppBadge.vue'
    import EventCard from '@/Components/Cards/EventCard.vue'
    import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'
    import AppFilterLayout from '@/Components/filters/layout/AppFilterLayout.vue'
    import FilterInput from '@/components/filters/fields/FilterInput.vue'
    import FilterSelect from '@/components/filters/fields/FilterSelect.vue'
    import { useInfiniteFilters } from '@/composables/useInfiniteFilters'
    import { index as eventsIndex, show as eventShow } from '@/routes/events'
    import type { BreadcrumbItem } from '@/types'

    const props = defineProps<{
        events: { data: any[] }
        featured: any | null
        categories: { id: number; name: string; slug: string }[]
        counts: { upcoming: number; past: number }
        qfilters: { search?: string; category?: string; when?: string }
        breadcrumbs?: BreadcrumbItem[]
    }>()

    const {
        filters,
        applyFilters,
        resetFilters,
    } = useInfiniteFilters({
        route: 'events.index',
        dataKey: 'events',
        initialFilters: {
            search: props.qfilters.search ?? '',
            category: props.qfilters.category ?? '',
            when: props.qfilters.when ?? 'upcoming',
        },
    })

    const categoryOptions = computed(() =>
        props.categories.map((c) => ({ value: c.slug, label: c.name })),
    )

    const setWhen = (when: string) => {
        filters.when = when
        applyFilters()
    }

    const isFiltering = computed(() => Boolean(filters.search) || Boolean(filters.category))

    // Featured hero helpers
    const featuredType = computed(() => {
        switch (props.featured?.event_type) {
            case 'online': return { label: 'Online', icon: Video }
            case 'hybrid': return { label: 'Hybrid', icon: Globe }
            default: return { label: 'In-person', icon: MapPin }
        }
    })
    const featuredDate = computed(() =>
        props.featured?.start_date
            ? new Date(props.featured.start_date).toLocaleDateString('en-US', {
                weekday: 'long', month: 'long', day: 'numeric', year: 'numeric',
            })
            : '',
    )
    const featuredPlace = computed(() => {
        const f = props.featured
        if (!f) return ''
        if (f.event_type === 'online') return 'Virtual event'
        return [f.city, f.country].filter(Boolean).join(', ') || f.location_name || ''
    })
    const bannerUrl = (b?: string | null) => {
        const v = (b || '').trim()
        if (!v) return '/frontend/images/placeholder.jpg'
        return /^(https?:)?\/\//.test(v) || v.startsWith('/') ? v : `/${v}`
    }
</script>

<template>

    <Head title="Events - Alfrik">
        <meta name="description"
            content="Summits, sessions and gatherings from the Alfrik community — browse upcoming and past events, view agendas and speakers, and register." />
    </Head>


    <AppContainer class="pb-24 pt-6 lg:pt-10">
        <!-- Masthead -->
        <header class="max-w-2xl">
            <AppText tag="p" font="redhat" size="xs" weight="black" uppercase color="brand"
                class="mb-3 !tracking-[0.32em]">
                Gatherings
            </AppText>
            <AppHeading tag="h1" font="prata" weight="normal" size="5xl" leading="tight">
                Events
            </AppHeading>
            <AppText tag="p" font="lora" size="lg" color="muted" leading="relaxed" class="mt-5">
                Summits, sessions and salons where the Alfrik community gathers — view agendas,
                meet the speakers, and save your seat.
            </AppText>
        </header>

        <!-- Featured (next upcoming) -->
        <section v-if="featured && !isFiltering && filters.when === 'upcoming'" class="mt-12">
            <Link :href="eventShow(featured.slug).url"
                class="group grid overflow-hidden rounded-3xl border border-border-light bg-surface-light shadow-editorial transition-shadow hover:shadow-2xl dark:border-border-dark dark:bg-surface-dark dark:shadow-editorial-dark md:grid-cols-2">
                <div class="relative min-h-[18rem] overflow-hidden bg-canvas-light dark:bg-white/5">
                    <img :src="bannerUrl(featured.banner)" :alt="featured.title"
                        class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105" />
                    <span class="absolute left-5 top-5">
                        <AppBadge variant="primary" size="sm">Next up</AppBadge>
                    </span>
                </div>
                <div class="flex flex-col justify-center p-8 md:p-12">
                    <div class="flex flex-wrap items-center gap-2">
                        <span
                            class="inline-flex items-center gap-1.5 font-redhat text-xs font-bold uppercase tracking-wide text-brand">
                            <component :is="featuredType.icon" class="h-3.5 w-3.5" />
                            {{ featuredType.label }}
                        </span>
                        <AppBadge v-for="c in (featured.categories || [])" :key="c.id" variant="default" size="sm">
                            {{ c.name }}
                        </AppBadge>
                    </div>

                    <AppHeading tag="h2" font="prata" weight="normal" size="3xl" leading="tight" hover-brand
                        class="mt-4">
                        {{ featured.title }}
                    </AppHeading>

                    <AppText v-if="featured.description" tag="p" font="lora" size="lg" color="muted" leading="relaxed"
                        :clamp="2" class="mt-4">
                        {{ featured.description }}
                    </AppText>

                    <div class="mt-6 space-y-2">
                        <AppText tag="p" font="redhat" size="sm" color="muted" class="inline-flex items-center gap-2">
                            <CalendarDays class="h-4 w-4 text-brand" /> {{ featuredDate }}
                        </AppText>
                        <AppText v-if="featuredPlace" tag="p" font="redhat" size="sm" color="muted"
                            class="flex items-center gap-2">
                            <MapPin class="h-4 w-4 text-brand" /> {{ featuredPlace }}
                        </AppText>
                    </div>

                    <div
                        class="mt-7 flex items-center justify-between gap-3 border-t border-border-light pt-5 dark:border-border-dark">
                        <AppText v-if="featured.registrations_count" tag="span" font="redhat" size="sm" color="muted"
                            class="inline-flex items-center gap-1.5">
                            <Users class="h-4 w-4" /> {{ featured.registrations_count }} registered
                        </AppText>
                        <span
                            class="ml-auto inline-flex items-center gap-1 font-redhat text-sm font-semibold text-brand transition-all group-hover:gap-2">
                            View event
                            <ArrowRight class="h-4 w-4" />
                        </span>
                    </div>
                </div>
            </Link>
        </section>

        <!-- Upcoming / Past toggle + filters -->
        <div
            class="sticky top-0 z-40 -mx-4 mt-12 bg-canvas-light/90 px-4 py-5 backdrop-blur dark:bg-canvas-dark/90 sm:top-16 sm:-mx-12 sm:px-12 lg:-mx-16 lg:px-16">
            <div
                class="mb-4 inline-flex rounded-full border border-border-light bg-surface-light p-1 dark:border-border-dark dark:bg-surface-dark">
                <button type="button" @click="setWhen('upcoming')" :class="[
                    'rounded-full px-5 py-1.5 font-redhat text-xs font-bold uppercase tracking-wide transition-colors',
                    filters.when === 'upcoming' ? 'bg-brand text-white' : 'text-content-lightMuted hover:text-brand dark:text-content-darkMuted',
                ]">
                    Upcoming <span class="opacity-70">· {{ counts.upcoming }}</span>
                </button>
                <button type="button" @click="setWhen('past')" :class="[
                    'rounded-full px-5 py-1.5 font-redhat text-xs font-bold uppercase tracking-wide transition-colors',
                    filters.when === 'past' ? 'bg-brand text-white' : 'text-content-lightMuted hover:text-brand dark:text-content-darkMuted',
                ]">
                    Past <span class="opacity-70">· {{ counts.past }}</span>
                </button>
            </div>

            <AppFilterLayout :filters="filters" @apply="applyFilters" @reset="resetFilters">
                <template #search>
                    <FilterInput v-model="filters.search" :field="{ placeholder: 'Search events, cities…' }" />
                </template>
                <template #inline-filters>
                    <FilterSelect v-model="filters.category" :field="{ placeholder: 'All Categories' }"
                        :options="categoryOptions" />
                </template>
            </AppFilterLayout>
        </div>

        <!-- Grid -->
        <section class="mt-8">
            <InfiniteScroll data="events" :key="[filters.search, filters.category, filters.when].join('-')"
                class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <EventCard v-for="event in events.data" :key="event.id" :event="event" />
                <template #loading>
                    <div class="col-span-full flex justify-center py-20">
                        <LoadingSpinner :loading="true" />
                    </div>
                </template>
            </InfiniteScroll>

            <div v-if="!events.data.length"
                class="rounded-3xl border border-dashed border-border-light py-28 text-center dark:border-border-dark">
                <AppHeading tag="p" font="prata" weight="normal" size="2xl" color="muted" align="center" class="mb-3">
                    No {{ filters.when }} events found
                </AppHeading>
                <AppText tag="p" font="lora" size="sm" color="muted" align="center">
                    Try a different category or search term.
                </AppText>
            </div>
        </section>
    </AppContainer>
</template>
