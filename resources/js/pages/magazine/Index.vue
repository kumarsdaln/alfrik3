<script setup lang="ts">
    import { Head, Link, InfiniteScroll } from '@inertiajs/vue3'
    import { computed } from 'vue'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppBadge from '@/components/ui/AppBadge.vue'
    import ArticleCard from '@/components/magazine/ArticleCard.vue'
    import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'
    import AppFilterLayout from '@/Components/filters/layout/AppFilterLayout.vue'
    import FilterInput from '@/Components/filters/fields/FilterInput.vue'
    import FilterSelect from '@/Components/filters/fields/FilterSelect.vue'
    import { useInfiniteFilters } from '@/composables/useInfiniteFilters'
    import { index as magazineIndex, view as magazineView } from '@/routes/magazine'
    import type { Magazine, MagazineCategory } from '@/types'

    const props = defineProps<{
        magazines: { data: Magazine[] }
        featured: Magazine | null
        categories: MagazineCategory[]
        qfilters: { category?: string; search?: string }
    }>()

    const PAGE_URL = magazineIndex().url

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
        route: 'magazine.index',
        dataKey: 'magazines',
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

    const issueDate = (m: Magazine) => dateLabel(m.published_at || m.created_at)

    const coverUrl = (m: Magazine) => m.cover_image || '/frontend/images/placeholder.jpg'

    const jsonLdData = {
        '@context': 'https://schema.org',
        '@type': 'CollectionPage',
        name: 'Magazine - Alfrik',
        description:
            'Alfrik Magazine features stories, interviews, and digital covers spotlighting creativity, leadership and style.',
        url: PAGE_URL,
        publisher: {
            '@type': 'Organization',
            name: 'Alfrik',
            url: 'https://www.alfrik.com',
        },
    }
</script>

<template>

    <Head title="Magazine - Alfrik">
        <meta name="description"
            content="Alfrik Magazine features stories, interviews, and digital covers that spotlight creativity, leadership, and style across fashion, business, healthcare, lifestyle, and innovation." />
        <meta name="keywords"
            content="Alfrik Magazine, Fashion Magazine, Model Interviews, Digital Cover, Creative Stories, Fashion Industry" />
        <link rel="canonical" :href="PAGE_URL" />
        <component :is="'script'" type="application/ld+json">{{ JSON.stringify(jsonLdData) }}</component>
    </Head>
        <!-- Masthead -->
        <div class="relative isolate overflow-hidden">
            <div class="container mx-auto px-4 pt-12 pb-6 text-center">
                <AppText tag="p" font="redhat" size="xs" weight="bold" tracking="wide" uppercase color="brand"
                    align="center" class="mb-4">
                    The Digital Edition
                </AppText>
                <AppHeading tag="h1" font="prata" size="5xl" weight="normal" align="center" leading="tight" class="mb-5">
                    Alfrik Magazine
                </AppHeading>
                <AppText tag="p" font="lora" size="lg" color="muted" align="center" leading="relaxed"
                    class="max-w-2xl mx-auto">
                    Powerful stories, exclusive interviews, and artistic covers featuring the voices redefining
                    fashion, business, healthcare, lifestyle, and innovation.
                </AppText>
            </div>
        </div>

        <!-- Featured issue -->
        <section v-if="featured" class="container mx-auto px-4 mt-6">
            <Link :href="magazineView([featured.category?.slug ?? 'issue', featured.slug]).url"
                class="group grid md:grid-cols-2 gap-8 items-center rounded-3xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark overflow-hidden p-6 md:p-10 hover:shadow-2xl transition-shadow">
                <div class="relative overflow-hidden rounded-2xl bg-canvas-light dark:bg-white/5">
                    <!-- cover_image can be set but point at a file that no longer exists,
                         so fall back on error as well as on an empty value. -->
                    <img :src="coverUrl(featured)" :alt="featured.title"
                        @error="($event.target as HTMLImageElement).src = '/frontend/images/placeholder.jpg'"
                        class="w-full aspect-[5/6] object-cover transform group-hover:scale-105 transition-transform duration-700" />
                    <span class="absolute top-4 left-4">
                        <AppBadge variant="primary" size="sm">Latest Issue</AppBadge>
                    </span>
                </div>
                <div>
                    <AppText v-if="featured.category" tag="p" font="redhat" size="xs" weight="bold" tracking="wide"
                        uppercase color="brand" class="mb-4">
                        {{ featured.category.name }}
                    </AppText>
                    <AppHeading tag="h2" font="prata" size="2xl" weight="normal" hover-brand leading="tight"
                        class="mb-4">
                        {{ featured.title }}
                    </AppHeading>
                    <AppText v-if="featured.subtitle" tag="p" font="lora" color="muted" leading="relaxed" :clamp="4"
                        class="mb-6">
                        {{ featured.subtitle }}
                    </AppText>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1">
                        <AppText v-if="featured.author" tag="span" font="redhat" size="sm" weight="medium">
                            By {{ featured.author.name }}
                        </AppText>
                        <AppText tag="span" font="redhat" size="sm" color="muted">{{ issueDate(featured) }}</AppText>
                        <AppText v-if="featured.reading_minutes" tag="span" font="redhat" size="sm" color="muted">
                            · {{ featured.reading_minutes }} min read
                        </AppText>
                        <AppText tag="span" font="redhat" size="sm" weight="semibold" color="brand"
                            class="inline-flex items-center gap-1 group-hover:gap-2 transition-all">
                            Read issue →
                        </AppText>
                    </div>
                </div>
            </Link>
        </section>

        <!-- Filters (shared filter system) -->
        <section class="container mx-auto px-4 mt-12">
            <div class="sticky top-4 z-40 mb-8 sm:top-20">
                <AppFilterLayout :filters="filters" @apply="applyFilters" @reset="resetFilters">
                    <template #search>
                        <FilterInput v-model="filters.search" :field="{ placeholder: 'Search the magazine...' }" />
                    </template>

                    <template #inline-filters>
                        <FilterSelect v-model="filters.category" :field="{ placeholder: 'All Categories' }"
                            :options="categoryOptions" />
                    </template>
                </AppFilterLayout>
            </div>
        </section>

        <!-- Issue grid (infinite scroll) — magazine-style cover cards -->
        <section class="container mx-auto px-4 py-10 pb-20">
            <InfiniteScroll data="magazines" :key="[filters.search, filters.category].join('-')"
                class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <ArticleCard v-for="item in magazines.data" :key="item.id" :item="item"
                    :href="magazineView([item.category?.slug ?? 'issue', item.slug]).url" />
                <template #loading>
                    <div class="col-span-full flex justify-center py-20">
                        <LoadingSpinner :loading="true" />
                    </div>
                </template>
            </InfiniteScroll>

            <!-- Empty state -->
            <div v-if="!magazines.data.length"
                class="py-28 text-center border border-dashed border-border-light dark:border-border-dark rounded-3xl">
                <AppHeading tag="p" font="prata" size="2xl" weight="normal" color="muted" align="center"
                    class="italic mb-3">
                    No issues found.
                </AppHeading>
                <AppText tag="p" font="redhat" size="sm" color="muted" align="center">
                    Try a different category or search term.
                </AppText>
            </div>
        </section>
</template>
