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


/*
|--------------------------------------------------------------------------
| Types
|--------------------------------------------------------------------------
*/

interface User {
    id: number
    username: string
    name: string
    avatar?: string | null
}

interface Participant {
    id: number
    role: string
    user?: User | null
}

interface Interview {
    id: number
    slug: string
    title: string
    description?: string | null
    thumbnail?: string | null
    interview_type?: string | null
    published_at?: string | null
    duration?: string | number | null
    action_label?: string | null
    participants?: Participant[]
}

interface PaginatedInterviews {
    data: Interview[]
    current_page: number
    last_page: number
    total: number
}

interface FilterOption {
    label: string
    value: string
}


/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/

interface Props {
    featured?: Interview | null
    interviews: PaginatedInterviews

    qfilters?: {
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
    applyFilters,
    clearFilters,
    filterCount,
} = useFilters({
    search: props.qfilters?.search ?? '',
    type: props.qfilters?.type ?? '',
})


/*
|--------------------------------------------------------------------------
| Interview Cards
|--------------------------------------------------------------------------
*/

const interviewCards = computed(() =>
    props.interviews.data.map(interview => ({
        id: interview.id,

        href: interviewShow(interview.slug).url,

        title: interview.title,

        format: normalizeFormat(interview.interview_type),

        image: interview.thumbnail ?? '',

        participants: interview.participants ?? [],

        created_at: interview.published_at ?? undefined,
    })),
)


/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function normalizeFormat(
    type?: string | null,
): 'written' | 'video' | 'audio' {
    if (!type) {
        return 'written'
    }

    const value = type.toLowerCase().trim()

    if (value.includes('video')) {
        return 'video'
    }

    if (value.includes('audio')) {
        return 'audio'
    }

    return 'written'
}
</script>


<template>
    <InterviewHero :interview="featured.data" />


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

    <div class="relative">
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
                v-for="interview in interviewCards"
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