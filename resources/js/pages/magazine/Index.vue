<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import AppBadge from '@/components/ui/AppBadge.vue'

import { index as magazineIndex, show as magazineShow } from '@/routes/magazine'

import type { Magazine, MagazineCategory } from '@/types'

interface MagazinePagination {
    data: Magazine[]
    current_page: number
    last_page: number
    total: number
}

const props = defineProps<{
    magazines: MagazinePagination
    featured: Magazine | null
    categories: MagazineCategory[]
    qfilters: {
        search?: string
        category?: string
    }
}>()

const formatDate = (date?: string | null) => {
    if (!date) {
        return ''
    }

    return new Date(date).toLocaleDateString('en-US', {
        month: 'long',
        year: 'numeric',
    })
}

const coverUrl = (magazine: Magazine) =>
    magazine.cover_image || '/frontend/images/placeholder.jpg'

const issueCount = (magazine: Magazine) =>
    magazine.published_issues_count ?? 0
</script>

<template>
    <Head title="Magazine — Alfrik">
        <meta
            name="description"
            content="Explore Alfrik Magazine — stories, interviews and perspectives covering business, culture, innovation and modern life."
        />
    </Head>

    <!-- ================================================================
         Masthead
    ================================================================= -->

    <header class="border-b border-border-light dark:border-border-dark">
        <div class="container mx-auto px-4 py-16 sm:py-20 lg:py-24">
            <div class="grid gap-8 lg:grid-cols-[1fr_auto] lg:items-end">
                <div>
                    <div class="mb-6 flex items-center gap-3">
                        <span
                            class="h-px w-10 bg-border-light dark:bg-border-dark"
                            aria-hidden="true"
                        />

                        <AppText
                            tag="span"
                            font="redhat"
                            size="xs"
                            weight="bold"
                            tracking="wide"
                            uppercase
                            color="primary"
                        >
                            Alfrik Publications
                        </AppText>
                    </div>

                    <AppHeading
                        tag="h1"
                        font="prata"
                        size="5xl"
                        weight="normal"
                        leading="tight"
                    >
                        Magazine
                    </AppHeading>

                    <AppText
                        tag="p"
                        font="lora"
                        size="lg"
                        color="muted"
                        leading="relaxed"
                        class="mt-6 max-w-2xl"
                    >
                        Stories, conversations and perspectives exploring the
                        people, ideas and movements shaping the world.
                    </AppText>
                </div>

                <AppText
                    tag="p"
                    font="redhat"
                    size="xs"
                    color="muted"
                    class="lg:pb-1"
                >
                    {{ magazines.total }}
                    {{ magazines.total === 1 ? 'publication' : 'publications' }}
                </AppText>
            </div>
        </div>
    </header>

    <!-- ================================================================
         Featured Publication
    ================================================================= -->

    <section
        v-if="featured"
        class="border-b border-border-light dark:border-border-dark"
    >
        <div class="container mx-auto px-4 py-12 sm:py-16 lg:py-20">
            <div class="grid gap-10 lg:grid-cols-[0.8fr_1.2fr] lg:gap-16">
                <!-- Cover -->

                <Link
                    :href="magazineShow(featured.slug).url"
                    class="group block"
                >
                    <div
                        class="relative mx-auto max-w-md overflow-hidden border border-border-light bg-muted dark:border-border-dark"
                    >
                        <div class="aspect-[4/5]">
                            <img
                                :src="coverUrl(featured)"
                                :alt="featured.title"
                                class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.02]"
                                @error="
                                    ($event.target as HTMLImageElement).src =
                                        '/frontend/images/placeholder.jpg'
                                "
                            />
                        </div>

                        <div
                            class="absolute left-0 top-0 border-b border-r border-border-light bg-surface-light px-4 py-2 dark:border-border-dark dark:bg-surface-dark"
                        >
                            <AppText
                                tag="span"
                                font="redhat"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="primary"
                            >
                                Featured
                            </AppText>
                        </div>
                    </div>
                </Link>

                <!-- Information -->

                <div class="flex flex-col justify-center">
                    <div class="mb-5">
                        <AppText
                            v-if="featured.category"
                            tag="p"
                            font="redhat"
                            size="xs"
                            weight="bold"
                            tracking="wide"
                            uppercase
                            color="primary"
                        >
                            {{ featured.category.name }}
                        </AppText>
                    </div>

                    <Link
                        :href="magazineShow(featured.slug).url"
                        class="group"
                    >
                        <AppHeading
                            tag="h2"
                            font="prata"
                            size="4xl"
                            weight="normal"
                            leading="tight"
                            hover-primary
                        >
                            {{ featured.title }}
                        </AppHeading>
                    </Link>

                    <AppText
                        v-if="featured.subtitle"
                        tag="p"
                        font="lora"
                        size="lg"
                        color="muted"
                        leading="relaxed"
                        class="mt-5 max-w-2xl"
                    >
                        {{ featured.subtitle }}
                    </AppText>

                    <div
                        class="mt-7 flex flex-wrap items-center gap-x-5 gap-y-2 border-y border-border-light py-4 dark:border-border-dark"
                    >
                        <AppText
                            tag="span"
                            font="redhat"
                            size="xs"
                            color="muted"
                        >
                            {{ issueCount(featured) }}
                            {{ issueCount(featured) === 1 ? 'Issue' : 'Issues' }}
                        </AppText>

                        <span
                            class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark"
                        />

                        <AppText
                            v-if="featured.published_at"
                            tag="span"
                            font="redhat"
                            size="xs"
                            color="muted"
                        >
                            {{ formatDate(featured.published_at) }}
                        </AppText>
                    </div>

                    <div class="mt-7">
                        <Link
                            :href="magazineShow(featured.slug).url"
                            class="inline-flex items-center gap-2 font-redhat text-sm font-semibold text-primary transition-all hover:gap-3"
                        >
                            Explore publication
                            <span aria-hidden="true">→</span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ================================================================
         Collection
    ================================================================= -->

    <section>
        <div class="container mx-auto px-4 py-12 sm:py-16 lg:py-20">
            <div
                class="mb-8 flex flex-col gap-5 border-b border-border-light pb-6 dark:border-border-dark sm:flex-row sm:items-end sm:justify-between"
            >
                <div>
                    <AppText
                        tag="p"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        tracking="wide"
                        uppercase
                        color="primary"
                    >
                        Our Publications
                    </AppText>

                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="3xl"
                        weight="normal"
                        leading="tight"
                        class="mt-2"
                    >
                        Browse Magazine
                    </AppHeading>
                </div>

                <AppText
                    tag="p"
                    font="lora"
                    size="sm"
                    color="muted"
                >
                    Discover every edition and publication.
                </AppText>
            </div>

            <!-- ========================================================
                 Filters
            ========================================================= -->

            <!--
                Filter UI should be added here using the NEW filter
                implementation used elsewhere in the application.

                We intentionally do NOT use:
                - FilterControl
                - FilterText
                - FilterSelect
                - route()
            -->

            <!-- ========================================================
                 Magazine Grid
            ========================================================= -->

            <div
                v-if="magazines.data.length"
                class="grid gap-x-8 gap-y-14 sm:grid-cols-2 lg:grid-cols-3"
            >
                <article
                    v-for="magazine in magazines.data"
                    :key="magazine.id"
                    class="group"
                >
                    <Link
                        :href="magazineShow(magazine.slug).url"
                        class="block"
                    >
                        <div
                            class="relative overflow-hidden border border-border-light bg-muted dark:border-border-dark"
                        >
                            <div class="aspect-[4/5] overflow-hidden">
                                <img
                                    :src="coverUrl(magazine)"
                                    :alt="magazine.title"
                                    class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.02]"
                                    @error="
                                        ($event.target as HTMLImageElement).src =
                                            '/frontend/images/placeholder.jpg'
                                    "
                                />
                            </div>

                            <div
                                v-if="magazine.featured"
                                class="absolute left-0 top-0"
                            >
                                <AppBadge
                                    variant="primary"
                                    size="sm"
                                >
                                    Featured
                                </AppBadge>
                            </div>
                        </div>
                    </Link>

                    <div class="pt-5">
                        <div class="flex items-center gap-3">
                            <AppText
                                v-if="magazine.category"
                                tag="span"
                                font="redhat"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="primary"
                            >
                                {{ magazine.category.name }}
                            </AppText>

                            <span
                                v-if="magazine.category"
                                class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark"
                            />

                            <AppText
                                tag="span"
                                font="redhat"
                                size="xs"
                                color="muted"
                            >
                                {{ issueCount(magazine) }}
                                {{ issueCount(magazine) === 1 ? 'Issue' : 'Issues' }}
                            </AppText>
                        </div>

                        <Link
                            :href="magazineShow(magazine.slug).url"
                            class="mt-3 block"
                        >
                            <AppHeading
                                tag="h3"
                                font="prata"
                                size="xl"
                                weight="normal"
                                leading="tight"
                                hover-primary
                            >
                                {{ magazine.title }}
                            </AppHeading>
                        </Link>

                        <AppText
                            v-if="magazine.subtitle"
                            tag="p"
                            font="lora"
                            size="sm"
                            color="muted"
                            leading="relaxed"
                            :clamp="2"
                            class="mt-3"
                        >
                            {{ magazine.subtitle }}
                        </AppText>

                        <AppText
                            v-if="magazine.published_at"
                            tag="p"
                            font="redhat"
                            size="xs"
                            color="muted"
                            class="mt-4"
                        >
                            {{ formatDate(magazine.published_at) }}
                        </AppText>
                    </div>
                </article>
            </div>

            <!-- Empty -->

            <div
                v-else
                class="border border-dashed border-border-light px-6 py-24 text-center dark:border-border-dark"
            >
                <AppHeading
                    tag="p"
                    font="prata"
                    size="2xl"
                    weight="normal"
                    color="muted"
                    align="center"
                    class="italic"
                >
                    No publications found.
                </AppHeading>

                <AppText
                    tag="p"
                    font="lora"
                    size="sm"
                    color="muted"
                    align="center"
                    class="mt-3"
                >
                    Try changing your search or category.
                </AppText>
            </div>
        </div>
    </section>
</template>