<script setup lang="ts">
    import { Head, Link, InfiniteScroll } from '@inertiajs/vue3'
    import { computed } from 'vue'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import ResearchCard from '@/Components/research/ResearchCard.vue'
    import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'
    import AppFilterLayout from '@/Components/filters/layout/AppFilterLayout.vue'
    import FilterInput from '@/components/filters/fields/FilterInput.vue'
    import FilterSelect from '@/components/filters/fields/FilterSelect.vue'
    import { useInfiniteFilters } from '@/composables/useInfiniteFilters'
    import { show as researchShow } from '@/routes/research'
    import type { ResearchPaper, ResearchArea } from '@/types'

    const props = defineProps<{
        papers: { data: ResearchPaper[] }
        featured: ResearchPaper | null
        areas: ResearchArea[]
        qfilters: Record<string, string | null>
    }>()

    /*
    |--------------------------------------------------------------------------
    | Catalog filtering — shared filter system (see Courses/Index)
    |--------------------------------------------------------------------------
    */

    const {
        filters,
        applyFilters,
        resetFilters,
    } = useInfiniteFilters({
        route: 'research.index',
        dataKey: 'papers',
        initialFilters: {
            search: props.qfilters.search ?? '',
            area: props.qfilters.area ?? '',
        },
    })

    const areaOptions = computed(() =>
        props.areas.map((a) => ({ value: a.slug, label: a.name }))
    )

    const yearOf = (d?: string | null) => (d ? new Date(d).getFullYear() : '')
</script>

<template>

    <Head title="Research — Alfrik">
        <meta name="description" content="Peer-style research papers, studies and working papers across disciplines." />
    </Head>

    <div class="container mx-auto px-4 pt-10 pb-4 text-center">
        <AppText tag="p" font="redhat" size="xs" weight="bold" tracking="wide" uppercase color="brand" align="center"
            class="mb-4">Knowledge & Studies</AppText>
        <AppHeading tag="h1" font="prata" size="5xl" weight="bold" align="center" class="mb-4">Research</AppHeading>
        <AppText tag="p" font="lora" color="muted" align="center" class="max-w-2xl mx-auto">
            Rigorous studies, working papers, and analyses — with abstracts, methodology, and full downloads.
        </AppText>
    </div>

    <!-- Featured -->
    <section v-if="featured" class="container mx-auto px-4 mt-6">
        <Link :href="researchShow(featured.slug).url"
            class="group block rounded-3xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-8 md:p-10 hover:shadow-2xl transition-shadow">
            <AppText v-if="featured.area" tag="p" font="redhat" size="xs" weight="bold" tracking="wide" uppercase
                color="brand" class="mb-3">{{ featured.area.name }}</AppText>
            <AppHeading tag="h2" font="prata" size="3xl" weight="bold" hover-brand class="mb-3">
                {{ featured.title }}
            </AppHeading>
            <AppText v-if="featured.authors" tag="p" font="lora" size="sm" color="muted" class="mb-4 italic">
                {{ featured.authors }}</AppText>
            <AppText v-if="featured.abstract" tag="p" font="lora" color="muted" :clamp="3" class="max-w-3xl">
                {{ featured.abstract }}</AppText>
            <div class="mt-5 flex items-center gap-3">
                <AppText v-if="featured.institution" tag="span" font="redhat" size="xs" color="muted">
                    {{ featured.institution }}</AppText>
                <AppText v-if="featured.institution && yearOf(featured.published_at)" tag="span" size="xs"
                    color="muted">·</AppText>
                <AppText tag="span" font="redhat" size="xs" color="muted">{{ yearOf(featured.published_at) }}
                </AppText>
                <AppText tag="span" font="redhat" size="xs" weight="semibold" color="brand">Read study →</AppText>
            </div>
        </Link>
    </section>

    <!-- Filters (shared filter system) -->
    <section class="container mx-auto px-4 mt-12">
        <div class="sticky top-4 z-40 border-b border-border-light dark:border-border-dark pb-4 sm:top-20">
            <AppFilterLayout :filters="filters" @apply="applyFilters" @reset="resetFilters">
                <template #search>
                    <FilterInput v-model="filters.search" :field="{ placeholder: 'Search research...' }" />
                </template>

                <template #inline-filters>
                    <FilterSelect v-model="filters.area" :field="{ placeholder: 'All Areas' }" :options="areaOptions" />
                </template>
            </AppFilterLayout>
        </div>
    </section>

    <!-- List (paper-style rows, infinite scroll) -->
    <section class="container mx-auto px-4 py-10 pb-20">
        <InfiniteScroll data="papers" :key="[filters.search, filters.area].join('-')" class="space-y-5">
            <ResearchCard v-for="p in papers.data" :key="p.id" :paper="p" :href="researchShow(p.slug).url" />
            <template #loading>
                <div class="flex justify-center py-10">
                    <LoadingSpinner :loading="true" />
                </div>
            </template>
        </InfiniteScroll>

        <!-- Empty state -->
        <div v-if="!papers.data.length"
            class="py-28 text-center border border-dashed border-border-light dark:border-border-dark rounded-3xl">
            <AppText tag="p" font="lora" size="xl" color="muted" align="center" class="mb-3 italic">No research
                found.</AppText>
            <AppText tag="p" font="redhat" size="sm" color="muted" align="center">Try a different area or search
                term.</AppText>
        </div>
    </section>
</template>
