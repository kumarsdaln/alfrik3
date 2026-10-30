<script setup lang="ts">
import { computed } from 'vue'
import { Head, InfiniteScroll } from '@inertiajs/vue3'

import ReportCard from '@/components/reports/ReportCard.vue'
import ReportHero from '@/components/reports/ReportHero.vue'
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'

import FilterControl from '@/components/filters/FilterControl.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import Empty from '@/components/datadisplay/empty/Empty.vue'

import { useFilters } from '@/composables/useFilters'

import {
    index as reportsIndex,
    show as reportShow,
} from '@/routes/reports'

import type {
    Pagination,
    Report,
    ReportTypeOption,
} from '@/types'

interface Props {
    reports: Pagination<Report>

    featured: {
        data: Report
    } | null

    types: ReportTypeOption[]

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
        url: reportsIndex().url,
    },
)


/*
|--------------------------------------------------------------------------
| Type Options
|--------------------------------------------------------------------------
*/

const typeOptions = computed(() =>
    props.types.map(type => ({
        value: type.value,
        label: type.label,
    })),
)


/*
|--------------------------------------------------------------------------
| Featured Report
|--------------------------------------------------------------------------
*/

const featuredReport = computed(() =>
    props.featured?.data ?? null,
)


/*
|--------------------------------------------------------------------------
| Date
|--------------------------------------------------------------------------
*/

const dateLabel = (date?: string | null) => {
    if (!date) {
        return ''
    }

    return new Date(date).toLocaleDateString('en-US', {
        day: 'numeric',
        month: 'short',
        year: 'numeric',
    })
}


/*
|--------------------------------------------------------------------------
| Cover
|--------------------------------------------------------------------------
*/

const cover = '/frontend/images/placeholder.jpg'
</script>


<template>
    <Head title="Reports — Alfrik">
        <meta
            name="description"
            content="Explore research reports, industry insights, market analysis and data-driven perspectives from Alfrik."
        />
    </Head>


    <!-- ================================================================ -->
    <!-- Featured Report -->
    <!-- ================================================================ -->

    <ReportHero
        v-if="featuredReport"
        :report="featuredReport"
        :report-url="reportShow(featuredReport.slug).url"
        :cover-url="cover"
        :date-label="
            dateLabel(
                featuredReport.published_at ??
                featuredReport.report_date ??
                featuredReport.created_at
            )
        "
    />


    <!-- ================================================================ -->
    <!-- Filters -->
    <!-- ================================================================ -->

    <section>
        <FilterControl
            v-model:search="filters.search"
            search-placeholder="Search reports..."
            :filter-count="filterCount"
            @clear="clearFilters"
            @apply="applyFilters"
        >
            <div class="space-y-6">
                <AppSelect
                    v-model="filters.type"
                    name="type"
                    label="Report Type"
                    placeholder="All report types"
                    :options="typeOptions"
                />
            </div>
        </FilterControl>
    </section>


    <!-- ================================================================ -->
    <!-- Reports -->
    <!-- ================================================================ -->

    <section class="pb-20 pt-10">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <InfiniteScroll
                data="reports"
                class="
                    grid
                    grid-cols-1
                    gap-x-8
                    gap-y-14
                    sm:grid-cols-2
                    lg:grid-cols-3
                    lg:gap-x-10
                    lg:gap-y-16
                "
            >
                <ReportCard
                    v-for="report in reports.data"
                    :key="report.id"
                    :report="report"
                    :href="reportShow(report.slug).url"
                />

                <template #loading>
                    <div class="col-span-full flex justify-center py-16">
                        <LoadingSpinner :loading="true" />
                    </div>
                </template>
            </InfiniteScroll>


            <!-- ======================================================== -->
            <!-- Empty -->
            <!-- ======================================================== -->
            <Empty v-if="!reports.data.length" title="No reports found."
            description="Try changing your filters or search keywords." />
        </div>
    </section>
</template>