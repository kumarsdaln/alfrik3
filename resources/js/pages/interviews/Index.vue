<script setup lang="ts">
import { computed } from 'vue'
import { InfiniteScroll } from '@inertiajs/vue3'

import InterviewCard from '@/components/interview/InterviewCard.vue'
import InterviewHero from '@/components/interview/InterviewHero.vue'
import FilterControl from '@/components/filters/FilterControl.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'

import {
    show as interviewShow,
} from '@/actions/App/Http/Controllers/Public/Interview/InterviewController'

import { useFilters } from '@/composables/useFilters'
import { Interview, Pagination } from '@/types'


/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/

interface Props {
    featured?: {
        data: Interview
    }

    interviews: Pagination<Interview>

    filters: {
        search?: string
        type?: string
    }

    types: Array<{
        label: string
        value: string
    }>
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
        type: props.filters.type ?? '',
    },
    {
        url: window.location.pathname,
        searchKey: 'search',
        debounce: 500,
    },
)
</script>


<template>
    <!-- ================================================================
         FEATURED INTERVIEW
    ================================================================= -->

    <InterviewHero
        v-if="featured"
        :interview="featured.data"
    />


    <!-- ================================================================
         FILTERS
    ================================================================= -->

    <div class="sticky top-4 z-40 mb-16 sm:top-20">
        <FilterControl
            v-model:search="filters.search"
            search-placeholder="Search interviews..."
            :filter-count="filterCount"
            @clear="clearFilters"
            @apply="applyFilters"
        >
            <AppSelect
                v-model="filters.type"
                name="type"
                label="Format"
                placeholder="All formats"
                :options="types"
            />
        </FilterControl>
    </div>


    <!-- ================================================================
         INTERVIEWS
    ================================================================= -->

    <div class="relative mb-6">
        <InfiniteScroll
            data="interviews"
            :key="`${filters.search}-${filters.type}`"
            class="
                relative
                z-10
                grid
                grid-cols-1
                gap-x-8
                gap-y-14
                sm:grid-cols-2
                lg:grid-cols-3
                lg:gap-x-10
                lg:gap-y-20
            "
        >
            <InterviewCard
                v-for="interview in interviews.data"
                :key="interview.id"
                :interview="interview"
            />

            <template #loading>
                <div class="col-span-full flex justify-center py-20">
                    <LoadingSpinner :loading="true" />
                </div>
            </template>
        </InfiniteScroll>
    </div>
</template>
