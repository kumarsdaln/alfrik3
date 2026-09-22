<script setup lang="ts">
import { computed } from 'vue'
import { Head, InfiniteScroll } from '@inertiajs/vue3'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import ReportCard from '@/components/reports/ReportCard.vue'
import ReportHero from '@/components/reports/ReportHero.vue'
import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'

import FilterControl from '@/components/filters/FilterControl.vue'
import AppSelect from '@/components/form/AppSelect.vue'

import { useFilters } from '@/composables/useFilters'

import {
    show as reportShow,
} from '@/routes/reports'

import type {
    Report,
    ReportType,
    ReportTypeOption,
} from '@/types'


/*
|--------------------------------------------------------------------------
| Types
|--------------------------------------------------------------------------
*/

interface PaginatedReports {
    data: Report[]
    current_page?: number
    last_page?: number
    total?: number
}

interface Props {
    reports: PaginatedReports

    featured: {
        data: Report
    } | null

    types: ReportTypeOption[]

    qfilters?: {
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
    applyFilters,
    clearFilters,
} = useFilters({
    search: props.qfilters?.search ?? '',
    type: props.qfilters?.type ?? '',
})


/*
|--------------------------------------------------------------------------
| Type options
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
| Filter count
|--------------------------------------------------------------------------
*/

const filterCount = computed(() => {
    return filters.type ? 1 : 0
})


/*
|--------------------------------------------------------------------------
| Featured report
|--------------------------------------------------------------------------
*/

const featuredReport = computed(() =>
    props.featured?.data ?? null,
)


/*
|--------------------------------------------------------------------------
| Helpers
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


const typeLabel = (report: Report) => {
    return report.type?.label ?? ''
}


/*
|--------------------------------------------------------------------------
| Cover
|--------------------------------------------------------------------------
|
| The new Report model does not have cover_image.
| Keep a stable placeholder until report media is connected.
|
*/

const cover = () => {
    return '/frontend/images/placeholder.jpg'
}
</script>


<template>
    <Head title="Reports — Alfrik">
        <meta
            name="description"
            content="Explore research reports, industry insights, market analysis and data-driven perspectives from Alfrik."
        />
    </Head>


    <!-- ================================================================== -->
    <!-- Featured Report -->
    <!-- ================================================================== -->

    <ReportHero
        v-if="featuredReport"
        :report="featuredReport"
        :report-url="reportShow(featuredReport.slug).url"
        :cover-url="cover()"
        :date-label="
            dateLabel(
                featuredReport.published_at ||
                featuredReport.created_at
            )
        "
    />


    <!-- ================================================================== -->
    <!-- Filters -->
    <!-- ================================================================== -->

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


    <!-- ================================================================== -->
    <!-- Reports -->
    <!-- ================================================================== -->

    <section class="pb-20 pt-10">
        <InfiniteScroll
            data="reports"
            :key="[filters.search, filters.type].join('-')"
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


        <!-- ============================================================ -->
        <!-- Empty -->
        <!-- ============================================================ -->

        <div
            v-if="!reports.data.length"
            class="
                border
                border-dashed
                border-border-light
                px-6
                py-24
                text-center
                dark:border-border-dark
            "
        >
            <AppHeading
                tag="h2"
                font="prata"
                size="lg"
                weight="normal"
                align="center"
            >
                No reports found
            </AppHeading>

            <AppText
                tag="p"
                size="sm"
                color="muted"
                align="center"
                class="mx-auto mt-2 max-w-md"
            >
                Try adjusting your search or selecting a different report type.
            </AppText>
        </div>
    </section>
</template>