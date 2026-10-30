<script setup lang="ts">
    import { computed } from 'vue'
    import { Head, InfiniteScroll } from '@inertiajs/vue3'

    import ResearchCard from '@/components/research/ResearchCard.vue'
    import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'

    import FilterControl from '@/components/filters/FilterControl.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import Empty from '@/components/datadisplay/empty/Empty.vue'

    import { useFilters } from '@/composables/useFilters'
    import { show as researchShow } from '@/routes/research'

    import ResearchHero from '@/components/research/ResearchHero.vue'

    import type { FormOption, Pagination, Research } from '@/types'

    interface Props {
        researches: Pagination<Research>
        featured: {
            data: Research | null
        }
        typeOptions: FormOption[]
        filters?: {
            search?: string
            type?: string
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
            search: props.filters?.search ?? '',
            type: props.filters?.type ?? '',
        },
        {
            url: window.location.pathname,
            searchKey: 'search',
            debounce: 500,
        },
    )

    /*
    |--------------------------------------------------------------------------
    | Type Options
    |--------------------------------------------------------------------------
    */

    const researchTypeOptions = computed(() =>
        props.typeOptions.map((option) => ({
            value: option.value,
            label: option.label,
        })),
    )

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
        <meta
            name="description"
            content="Explore research papers, studies, working papers and data-driven analysis from Alfrik."
        />
    </Head>

    <!--
    |--------------------------------------------------------------------------
    | Featured Research
    |--------------------------------------------------------------------------
    -->

    <ResearchHero
        v-if="featured.data"
        :research="featured.data"
        :research-url="researchShow(featured.data.slug).url"
        :year="yearOf(featured.data.published_at)"
    />

    <!--
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    -->

    <section>
        <FilterControl
            v-model:search="filters.search"
            search-placeholder="Search research..."
            :filter-count="filterCount"
            @clear="clearFilters"
            @apply="applyFilters"
        >
            <div class="space-y-6">
                <AppSelect
                    v-model="filters.type"
                    name="type"
                    label="Research type"
                    placeholder="All research types"
                    :options="researchTypeOptions"
                />
            </div>
        </FilterControl>
    </section>

    <!--
    |--------------------------------------------------------------------------
    | Research List
    |--------------------------------------------------------------------------
    -->

    <section class="pb-20 pt-10">
        <InfiniteScroll
            data="researches"
            :key="`${filters.search}-${filters.type}`"
            class="divide-y divide-border-light dark:divide-border-dark"
        >
            <ResearchCard
                v-for="research in researches.data"
                :key="research.id"
                :research="research"
                :href="researchShow(research.slug).url"
            />

            <template #loading>
                <div class="flex justify-center py-10">
                    <LoadingSpinner :loading="true" />
                </div>
            </template>
        </InfiniteScroll>

        <!-- Empty -->
        <Empty v-if="!researches.data.length" title="No reports found."
            description="Try changing your filters or search keywords." />
    </section>
</template>