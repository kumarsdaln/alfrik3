<script setup lang="ts">
    import { Head, Link, InfiniteScroll } from '@inertiajs/vue3'
    import { computed } from 'vue'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppButton from '@/components/ui/AppButton.vue'
    import AppBadge from '@/components/ui/AppBadge.vue'
    import ReportCard from '@/components/reports/ReportCard.vue'
    import AppFilterLayout from '@/components/filters/layout/AppFilterLayout.vue'
    import FilterInput from '@/components/filters/fields/FilterInput.vue'
    import FilterSelect from '@/components/filters/fields/FilterSelect.vue'
    import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'
    import { useInfiniteFilters } from '@/composables/useInfiniteFilters'
    import { show as reportShow, download as reportDownload } from '@/routes/reports'
    import type { Report, ReportCategory } from '@/types'

    const props = defineProps<{
        reports: { data: Report[] }
        featured: Report | null
        categories: ReportCategory[]
        qfilters: Record<string, any>
    }>()

    const {
        filters,
        applyFilters,
        resetFilters,
    } = useInfiniteFilters({
        route: 'reports.index',
        dataKey: 'reports',
        initialFilters: {
            search: props.qfilters.search ?? '',
            category: props.qfilters.category ?? '',
        },
    })

    const categoryOptions = computed(() =>
        props.categories.map((c) => ({ value: c.slug, label: c.name }))
    )

    const dateLabel = (d?: string | null) =>
        d ? new Date(d).toLocaleDateString('en-US', { month: 'short', year: 'numeric' }) : ''
    const cover = (r: Report) => r.cover_image || '/frontend/images/placeholder.jpg'
</script>

<template>

    <Head title="Reports — Alfrik">
        <meta name="description"
            content="Download industry reports, market insights, and data-driven analysis from Alfrik." />
    </Head>

    <div class="container mx-auto px-4 pt-10 pb-4 text-center">
        <AppText tag="p" font="redhat" size="xs" weight="bold" tracking="wide" uppercase color="brand" align="center"
            class="mb-4">
            Insights &amp; Data
        </AppText>
        <AppHeading tag="h1" font="prata" size="5xl" weight="bold" align="center" class="mb-4">Reports</AppHeading>
        <AppText tag="p" font="lora" color="muted" align="center" class="max-w-2xl mx-auto">
            In-depth industry reports, market analyses, and data studies — free to download.
        </AppText>
    </div>

    <!-- Featured -->
    <section v-if="featured" class="container mx-auto px-4 mt-6">
        <div
            class="group grid md:grid-cols-2 gap-8 items-center rounded-3xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark overflow-hidden p-6 md:p-10">
            <Link :href="reportShow(featured.slug).url" class="relative overflow-hidden rounded-2xl block">
                <img :src="cover(featured)" :alt="featured.title"
                    class="w-full aspect-[4/3] object-cover group-hover:scale-105 transition-transform duration-700" />
                <span class="absolute top-4 left-4">
                    <AppBadge variant="primary" size="md" class="uppercase tracking-widest">Featured</AppBadge>
                </span>
            </Link>
            <div>
                <AppText v-if="featured.category" tag="p" font="redhat" size="xs" weight="bold" tracking="wide"
                    uppercase color="brand" class="mb-3">
                    {{ featured.category.name }}
                </AppText>
                <Link :href="reportShow(featured.slug).url">
                    <AppHeading tag="h2" font="prata" size="3xl" weight="semibold" hover-brand class="mb-3">
                        {{ featured.title }}
                    </AppHeading>
                </Link>
                <AppText v-if="featured.summary" font="lora" color="muted" :clamp="3" class="mb-5">
                    {{ featured.summary }}
                </AppText>
                <div class="flex flex-wrap items-center gap-3">
                    <AppButton :href="reportDownload(featured.slug).url" external variant="primary">
                        <template #icon-left>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                            </svg>
                        </template>
                        {{ featured.gated ? 'Get Report' : 'Download' }}
                    </AppButton>
                    <AppText tag="span" size="sm" color="muted">{{ dateLabel(featured.published_at ||
                        featured.created_at) }}</AppText>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters (shared filter system) -->
    <section class="container mx-auto px-4 mt-12">
        <div class="sticky top-4 z-40 sm:top-20">
            <AppFilterLayout :filters="filters" @apply="applyFilters" @reset="resetFilters">
                <template #search>
                    <FilterInput v-model="filters.search" :field="{ placeholder: 'Search reports...' }" />
                </template>
                <template #inline-filters>
                    <FilterSelect v-model="filters.category" :field="{ placeholder: 'All Categories' }"
                        :options="categoryOptions" />
                </template>
            </AppFilterLayout>
        </div>
    </section>

    <!-- Grid (infinite scroll) -->
    <section class="container mx-auto px-4 py-10 pb-20">
        <InfiniteScroll data="reports" :key="[filters.search, filters.category].join('-')"
            class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <ReportCard v-for="r in reports.data" :key="r.id" :report="r" :href="reportShow(r.slug).url"
                :download-href="reportDownload(r.slug).url" />
            <template #loading>
                <div class="col-span-full flex justify-center py-10">
                    <LoadingSpinner :loading="true" />
                </div>
            </template>
        </InfiniteScroll>

        <div v-if="!reports.data.length"
            class="py-28 text-center border border-dashed border-border-light dark:border-border-dark rounded-3xl">
            <AppText tag="p" font="lora" size="xl" color="muted" align="center" class="italic mb-3">No reports found.
            </AppText>
            <AppText tag="p" size="sm" color="muted" align="center">Try a different category or search term.</AppText>
        </div>
    </section>
</template>
