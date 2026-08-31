<script setup lang="ts">
    import { computed } from 'vue'
    import { Head, Link, InfiniteScroll } from '@inertiajs/vue3'

    import AppText from '@/components/ui/AppText.vue'
    import ResearchCard from '@/components/research/ResearchCard.vue'
    import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'

    import FilterControl from '@/components/filters/FilterControl.vue'
    import AppSelect from '@/components/form/AppSelect.vue'

    import { useFilters } from '@/composables/useFilters'
    import { show as researchShow } from '@/routes/research'

    import type { ResearchPaper, ResearchArea } from '@/types'
    import ResearchHero from '@/components/research/ResearchHero.vue'

    interface PaginatedPapers {
        data: ResearchPaper[]
        current_page?: number
        last_page?: number
        total?: number
    }

    interface Props {
        papers: PaginatedPapers
        featured: ResearchPaper | null
        areas: ResearchArea[]
        qfilters?: {
            search?: string
            area?: string
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
        area: props.qfilters?.area ?? '',
    })

    const areaOptions = computed(() =>
        props.areas.map((area) => ({
            value: area.slug,
            label: area.name,
        })),
    )

    const filterCount = computed(() => {
        let count = 0

        if (filters.area) {
            count++
        }

        return count
    })

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    const yearOf = (date?: string | null) => {
        if (!date) {
            return ''
        }

        return new Date(date).getFullYear()
    }
</script>

<template>

    <Head title="Research — Alfrik">
        <meta name="description"
            content="Explore research papers, studies, working papers and data-driven analysis from Alfrik." />
    </Head>

    <!--
    |--------------------------------------------------------------------------
    | Featured Research
    |--------------------------------------------------------------------------
    -->
    <ResearchHero
        v-if="featured"
        :research="featured"
        :research-url="researchShow(featured.slug).url"
        :year="yearOf(featured.published_at)"
    />

    <!--
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    -->

    <section>
        <FilterControl v-model:search="filters.search" search-placeholder="Search research..."
            :filter-count="filterCount" @clear="clearFilters" @apply="applyFilters">
            <div class="space-y-6">
                <AppSelect v-model="filters.area" name="area" label="Research area" placeholder="All research areas"
                    :options="areaOptions" />
            </div>
        </FilterControl>
    </section>

    <!--
    |--------------------------------------------------------------------------
    | Research List
    |--------------------------------------------------------------------------
    -->

    <section class="pb-20 pt-10">
        <InfiniteScroll data="papers" :key="[filters.search, filters.area].join('-')" class="
        divide-y
        divide-border-light
        dark:divide-border-dark
    ">
            <ResearchCard v-for="paper in papers.data" :key="paper.id" :paper="paper"
                :href="researchShow(paper.slug).url" />

            <template #loading>
                <div class="flex justify-center py-10">
                    <LoadingSpinner :loading="true" />
                </div>
            </template>
        </InfiniteScroll>

        <!-- Empty -->

        <div v-if="!papers.data.length"
            class="border border-dashed border-border-light py-28 text-center dark:border-border-dark">
            <AppText tag="p" font="lora" size="xl" color="muted" align="center" class="mb-3 italic">
                No research found.
            </AppText>

            <AppText tag="p" size="sm" color="muted" align="center">
                Try a different research area or search term.
            </AppText>
        </div>
    </section>
</template>