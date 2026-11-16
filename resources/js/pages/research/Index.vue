
<script setup lang="ts">
import { computed } from 'vue'
import { Head, InfiniteScroll } from '@inertiajs/vue3'

import ResearchCard from '@/components/research/ResearchCard.vue'
import ResearchHero from '@/components/research/ResearchHero.vue'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'
import Section from '@/components/ui/Section.vue'

import FilterControl from '@/components/filters/FilterControl.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import Empty from '@/components/datadisplay/empty/Empty.vue'

import { useFilters } from '@/composables/useFilters'
import { show as researchShow } from '@/routes/research'

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
| Filter Options
|--------------------------------------------------------------------------
*/

const researchTypeOptions = computed(() => [
    {
        value: '',
        label: 'All research types',
    },
    ...props.typeOptions.map(option => ({
        value: option.value,
        label: option.label,
    })),
])

/*
|--------------------------------------------------------------------------
| Page State
|--------------------------------------------------------------------------
*/

const hasResearches = computed(() =>
    props.researches.data.length > 0
)

const hasFeaturedResearch = computed(() =>
    Boolean(props.featured.data)
)

const resultCount = computed(() =>
    props.researches.data.length
)
</script>


<template>
    <Head title="Research — Alfrik">
        <meta
            head-key="description"
            name="description"
            content="Explore research papers, industry studies, market insights and data-driven analysis from Alfrik."
        />
    </Head>

    <!-- Featured Research -->
    <ResearchHero
        v-if="featured.data"
        :research="featured.data"
        :research-url="researchShow(featured.data.slug).url"
        class="mt-4"
    />

    <!-- Page Introduction -->
    <Section
        background="transparent"
        spacing="sm"
    >
        <AppText
            tag="p"
            size="xs"
            color="brand"
            weight="semibold"
            uppercase
            tracking="wide"
        >
            Alfrik Research
        </AppText>

        <AppHeading
            tag="h1"
            font="prata"
            size="3xl"
            weight="normal"
            leading="tight"
        >
            Research & Insights
        </AppHeading>

        <AppText
            tag="p"
            font="lora"
            size="sm"
            color="muted"
            leading="relaxed"
            class="max-w-2xl"
        >
            Explore industry research, market studies, consumer insights
            and data-driven perspectives across the fashion industry.
        </AppText>
    </Section>

    <!-- Filters -->
    <Section
        background="transparent"
        border="none"
        padding="none"
        spacing="none"
    >
        <FilterControl
            v-model:search="filters.search"
            search-placeholder="Search research, studies and insights..."
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
    </Section>

    <!-- Research Results -->
    <Section
        background="transparent"
        spacing="lg"
    >
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="space-y-2">
                <AppHeading
                    tag="h2"
                    font="prata"
                    size="2xl"
                    weight="normal"
                >
                    Explore research
                </AppHeading>

                <AppText tag="p" size="sm" color="muted">
                    Discover reports, studies and research publications.
                </AppText>
            </div>

            <AppText
                v-if="hasResearches"
                tag="span"
                size="xs"
                color="muted"
            >
                {{ researches.data.length }} results loaded
            </AppText>
        </div>

        <InfiniteScroll
            v-if="hasResearches"
            data="researches"
            :key="`${filters.search}-${filters.type}`"
            class="space-y-8"
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

        <Empty
            v-else
            title="No research found"
            description="We couldn't find research matching your search. Try changing your filters or search keywords."
        />
    </Section>
</template>

