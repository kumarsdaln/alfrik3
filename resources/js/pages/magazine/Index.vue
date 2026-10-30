<script setup lang="ts">
    import { computed } from 'vue'
    import { Head, Link, InfiniteScroll } from '@inertiajs/vue3'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import LoadingSpinner from '@/components/ui/LoadingSpinner.vue'

    import FilterControl from '@/components/filters/FilterControl.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import MagazineCard from '@/components/magazine/MagazineCard.vue'
    import Empty from '@/components/datadisplay/empty/Empty.vue'

    import { useFilters } from '@/composables/useFilters'

    import {
        index as magazineIndex,
        show as magazineShow,
    } from '@/routes/magazine'

    import type { Category, Magazine, Pagination } from '@/types'

    interface Props {
        magazines: Pagination<Magazine>
        featured: Magazine | null
        categories: Category[]
        filters?: {
            search?: string
            category?: string
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
            category: props.filters?.category ?? '',
        },
        {
            url: magazineIndex().url,
        },
    )

    /*
    |--------------------------------------------------------------------------
    | Category Options
    |--------------------------------------------------------------------------
    */

    const categoryOptions = computed(() =>
        props.categories.map(category => ({
            value: category.slug,
            label: category.name,
        })),
    )

    /*
    |--------------------------------------------------------------------------
    | Date
    |--------------------------------------------------------------------------
    */

    const formatDate = (date?: string | null) => {
        if (!date) {
            return ''
        }

        const value = new Date(date)

        if (Number.isNaN(value.getTime())) {
            return ''
        }

        return value.toLocaleDateString('en-US', {
            month: 'long',
            year: 'numeric',
        })
    }

    /*
    |--------------------------------------------------------------------------
    | Media
    |--------------------------------------------------------------------------
    |
    | Magazine covers are stored through the polymorphic media relationship.
    |
    */

    const coverUrl = (magazine: Magazine) => {
        const cover = magazine.media?.find(
            media => media.collection === 'cover',
        )

        return cover?.url ?? '/frontend/images/placeholder.jpg'
    }

    /*
    |--------------------------------------------------------------------------
    | Issues
    |--------------------------------------------------------------------------
    */

    const issueCount = (magazine: Magazine) =>
        magazine.published_issues_count ?? 0

    /*
    |--------------------------------------------------------------------------
    | Category
    |--------------------------------------------------------------------------
    */

    const primaryCategory = (magazine: Magazine) =>
        magazine.categories?.[0] ?? null
</script>

<template>

    <Head title="Magazine — Alfrik">
        <meta name="description"
            content="Explore Alfrik Magazine — stories, interviews and perspectives covering business, culture, innovation and modern life." />
    </Head>

    <!--
    |--------------------------------------------------------------------------
    | Masthead
    |--------------------------------------------------------------------------
    -->

    <header class="
            border-b
            border-border-light
            dark:border-border-dark
        ">
        <div class="container mx-auto px-4 py-16 sm:py-20 lg:py-24">
            <div class="
                    grid
                    gap-8
                    lg:grid-cols-[1fr_auto]
                    lg:items-end
                ">
                <div>
                    <div class="mb-6">
                        <AppText tag="span" font="redhat" size="xs" weight="bold" tracking="wide" uppercase>
                            Alfrik Publications
                        </AppText>
                    </div>

                    <AppHeading tag="h1" font="prata" size="5xl" weight="normal" leading="tight">
                        Magazine
                    </AppHeading>

                    <AppText tag="p" font="lora" size="lg" color="muted" leading="relaxed" class="mt-6 max-w-2xl">
                        Stories, conversations and perspectives exploring the
                        people, ideas and movements shaping the world.
                    </AppText>
                </div>

                <AppText tag="p" font="redhat" size="xs" color="muted" class="lg:pb-1">
                    {{ magazines.total ?? magazines.data.length }}
                    {{
                        (magazines.total ?? magazines.data.length) === 1
                            ? 'publication'
                            : 'publications'
                    }}
                </AppText>
            </div>
        </div>
    </header>

    <!--
    |--------------------------------------------------------------------------
    | Featured Publication
    |--------------------------------------------------------------------------
    -->

    <section v-if="featured" class="
            border-b
            border-border-light
            dark:border-border-dark
        ">
        <div class="container mx-auto px-4 py-12 sm:py-16 lg:py-20">
            <div class="
                    grid
                    gap-10
                    lg:grid-cols-[0.8fr_1.2fr]
                    lg:gap-16
                ">
                <!-- Cover -->

                <Link :href="magazineShow(featured.slug).url" class="group block">
                    <div class="
                            relative
                            mx-auto
                            max-w-md
                            overflow-hidden
                            border
                            border-border-light
                            bg-muted
                            dark:border-border-dark
                        ">
                        <div class="aspect-[4/5]">
                            <img :src="coverUrl(featured)" :alt="featured.title" class="
                                    h-full
                                    w-full
                                    object-cover
                                    transition-transform
                                    duration-700
                                    ease-out
                                    group-hover:scale-[1.02]
                                " @error="
                                    ($event.target as HTMLImageElement).src =
                                    '/frontend/images/placeholder.jpg'
                                    " />
                        </div>

                        <div class="
                                absolute
                                left-0
                                top-0
                                border-b
                                border-r
                                border-border-light
                                bg-surface-light
                                px-4
                                py-2
                                dark:border-border-dark
                                dark:bg-surface-dark
                            ">
                            <AppText tag="span" font="redhat" size="xs" weight="bold" uppercase tracking="wide">
                                Featured
                            </AppText>
                        </div>
                    </div>
                </Link>

                <!-- Information -->

                <div class="flex flex-col justify-center">
                    <div v-if="primaryCategory(featured)" class="mb-5">
                        <AppText tag="p" font="redhat" size="xs" weight="bold" tracking="wide" uppercase>
                            {{ primaryCategory(featured)?.name }}
                        </AppText>
                    </div>

                    <Link :href="magazineShow(featured.slug).url" class="group">
                        <AppHeading tag="h2" font="prata" size="4xl" weight="normal" leading="tight" hover-primary>
                            {{ featured.title }}
                        </AppHeading>
                    </Link>

                    <AppText v-if="featured.subtitle" tag="p" font="lora" size="lg" color="muted" leading="relaxed"
                        class="mt-5 max-w-2xl">
                        {{ featured.subtitle }}
                    </AppText>

                    <div class="
                            mt-7
                            flex
                            flex-wrap
                            items-center
                            gap-x-5
                            gap-y-2
                            border-y
                            border-border-light
                            py-4
                            dark:border-border-dark
                        ">
                        <AppText tag="span" font="redhat" size="xs" color="muted">
                            {{ issueCount(featured) }}
                            {{
                                issueCount(featured) === 1
                                    ? 'Issue'
                                    : 'Issues'
                            }}
                        </AppText>

                        <span class="
                                h-1
                                w-1
                                rounded-full
                                bg-border-light
                                dark:bg-border-dark
                            " />

                        <AppText v-if="featured.published_at" tag="span" font="redhat" size="xs" color="muted">
                            {{ formatDate(featured.published_at) }}
                        </AppText>
                    </div>

                    <div class="mt-7">
                        <Link :href="magazineShow(featured.slug).url" class="
                                inline-flex
                                items-center
                                gap-2
                                font-redhat
                                text-sm
                                font-semibold
                                text-primary
                                transition-all
                                hover:gap-3
                            ">
                            Explore publication
                            <span aria-hidden="true">→</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--
    |--------------------------------------------------------------------------
    | Collection
    |--------------------------------------------------------------------------
    -->

    <section>
        <div class="container mx-auto px-4 py-12 sm:py-16 lg:py-20">
            <div class="
                    mb-8
                    flex
                    flex-col
                    gap-5
                    border-b
                    border-border-light
                    pb-6
                    dark:border-border-dark
                    sm:flex-row
                    sm:items-end
                    sm:justify-between
                ">
                <div>
                    <AppText tag="p" font="redhat" size="xs" weight="bold" tracking="wide" uppercase>
                        Our Publications
                    </AppText>

                    <AppHeading tag="h2" font="prata" size="3xl" weight="normal" leading="tight" class="mt-2">
                        Browse Magazine
                    </AppHeading>
                </div>

                <AppText tag="p" font="lora" size="sm" color="muted">
                    Discover every edition and publication.
                </AppText>
            </div>

            <!--
            |--------------------------------------------------------------------------
            | Filters
            |--------------------------------------------------------------------------
            -->

            <div class="mb-10">
                <FilterControl v-model:search="filters.search" search-placeholder="Search magazines..."
                    :filter-count="filterCount" @apply="applyFilters" @clear="clearFilters">
                    <div class="space-y-6">
                        <AppSelect v-model="filters.category" name="category" label="Category"
                            placeholder="All categories" :options="categoryOptions" />
                    </div>
                </FilterControl>
            </div>

            <!--
            |--------------------------------------------------------------------------
            | Magazine Grid
            |--------------------------------------------------------------------------
            -->

            <InfiniteScroll data="magazines" :key="[filters.search, filters.category].join('-')">
                <div v-if="magazines.data.length" class="
                        grid
                        gap-x-8
                        gap-y-14
                        sm:grid-cols-2
                        lg:grid-cols-3
                    ">
                    <MagazineCard
                        v-for="magazine in magazines.data"
                        :key="magazine.id"
                        :magazine="magazine"
                    />
                </div>

                <template #loading>
                    <div class="flex justify-center py-16">
                        <LoadingSpinner :loading="true" />
                    </div>
                </template>
            </InfiniteScroll>

            <!-- Empty -->
             <Empty v-if="!magazines.data.length" title="No magazines found."
            description="Try changing your filters or search keywords." />
        </div>
    </section>
</template>