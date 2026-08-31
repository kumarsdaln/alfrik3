<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import AppBadge from '@/components/ui/AppBadge.vue'

import {
    index as magazineIndex,
} from '@/routes/magazine'

import {
    show as issueShow,
} from '@/routes/magazine/issues'

import {
    show as articleShow,
} from '@/routes/magazine/articles'

import type {
    MagazineIssue,
    MagazineArticle,
} from '@/types'

interface MagazineIssuePage extends MagazineIssue {
    articles?: MagazineArticle[]
}

const props = defineProps<{
    issue: MagazineIssuePage
}>()

const articles = props.issue.articles ?? []

const coverUrl = (item: MagazineIssuePage | MagazineArticle) =>
    item.cover_image || '/frontend/images/placeholder.jpg'

const formatDate = (date?: string | null) => {
    if (!date) {
        return ''
    }

    return new Date(date).toLocaleDateString('en-US', {
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    })
}

const editionLabel = (issue: MagazineIssuePage) => {
    if (issue.edition_label) {
        return issue.edition_label
    }

    const parts: string[] = []

    if (issue.volume) {
        parts.push(`Vol. ${issue.volume}`)
    }

    if (issue.issue_number) {
        parts.push(`Issue ${issue.issue_number}`)
    }

    return parts.join(' · ')
}
</script>

<template>
    <Head :title="`${issue.title} — Alfrik Magazine`">
        <meta
            name="description"
            :content="
                issue.meta_description ||
                issue.description ||
                issue.subtitle ||
                issue.title
            "
        />

        <meta
            v-if="issue.meta_keywords"
            name="keywords"
            :content="issue.meta_keywords"
        />

        <link
            rel="canonical"
            :href="issueShow(issue.slug).url"
        />
    </Head>

    <!-- ================================================================
         Breadcrumb
    ================================================================= -->

    <div class="container mx-auto px-4 pt-8">
        <nav
            aria-label="Breadcrumb"
            class="flex flex-wrap items-center gap-2"
        >
            <Link
                :href="magazineIndex().url"
                class="font-redhat text-xs text-muted transition-colors hover:text-primary"
            >
                Magazine
            </Link>

            <span
                class="text-xs text-muted"
                aria-hidden="true"
            >
                /
            </span>

            <AppText
                tag="span"
                font="redhat"
                size="xs"
                color="muted"
            >
                {{ issue.title }}
            </AppText>
        </nav>
    </div>

    <!-- ================================================================
         Issue Hero
    ================================================================= -->

    <header>
        <div class="container mx-auto px-4 py-12 sm:py-16 lg:py-20">
            <div
                class="grid items-center gap-10 lg:grid-cols-[420px_minmax(0,1fr)] lg:gap-16 xl:grid-cols-[460px_minmax(0,1fr)]"
            >
                <!-- Cover -->

                <div
                    class="relative overflow-hidden border border-border-light bg-surface-light dark:border-border-dark dark:bg-surface-dark"
                >
                    <div class="aspect-[4/5] overflow-hidden">
                        <img
                            :src="coverUrl(issue)"
                            :alt="issue.title"
                            class="h-full w-full object-cover"
                            @error="($event.target as HTMLImageElement).src = '/frontend/images/placeholder.jpg'"
                        />
                    </div>

                    <div
                        v-if="issue.featured"
                        class="absolute left-5 top-5"
                    >
                        <AppBadge
                            variant="primary"
                            size="sm"
                        >
                            Featured Issue
                        </AppBadge>
                    </div>
                </div>

                <!-- Issue information -->

                <div class="max-w-3xl">
                    <div class="mb-6 flex items-center gap-3">
                        <span
                            class="h-px w-12 bg-primary"
                            aria-hidden="true"
                        />

                        <AppText
                            tag="span"
                            font="redhat"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="primary"
                        >
                            Alfrik Magazine
                        </AppText>
                    </div>

                    <AppText
                        v-if="editionLabel(issue)"
                        tag="p"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        uppercase
                        tracking="wide"
                        color="primary"
                    >
                        {{ editionLabel(issue) }}
                    </AppText>

                    <AppHeading
                        tag="h1"
                        font="prata"
                        size="5xl"
                        weight="normal"
                        leading="tight"
                        class="mt-3 sm:text-6xl"
                    >
                        {{ issue.title }}
                    </AppHeading>

                    <AppText
                        v-if="issue.subtitle"
                        tag="p"
                        font="lora"
                        size="lg"
                        color="muted"
                        leading="relaxed"
                        class="mt-6 max-w-2xl"
                    >
                        {{ issue.subtitle }}
                    </AppText>

                    <!-- Metadata -->

                    <div
                        class="mt-8 flex flex-wrap items-center gap-x-5 gap-y-3 border-y border-border-light py-4 dark:border-border-dark"
                    >
                        <AppText
                            v-if="issue.cover_date"
                            tag="span"
                            font="redhat"
                            size="xs"
                            color="muted"
                        >
                            {{ formatDate(issue.cover_date) }}
                        </AppText>

                        <span
                            v-if="issue.cover_date && issue.editor"
                            class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark"
                        />

                        <AppText
                            v-if="issue.editor"
                            tag="span"
                            font="redhat"
                            size="xs"
                            color="muted"
                        >
                            Edited by {{ issue.editor }}
                        </AppText>

                        <span
                            v-if="articles.length"
                            class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark"
                        />

                        <AppText
                            tag="span"
                            font="redhat"
                            size="xs"
                            color="muted"
                        >
                            {{ articles.length }}
                            {{ articles.length === 1 ? 'Story' : 'Stories' }}
                        </AppText>
                    </div>

                    <AppText
                        v-if="issue.description"
                        tag="div"
                        font="lora"
                        color="muted"
                        leading="relaxed"
                        class="mt-7 max-w-2xl"
                    >
                        {{ issue.description }}
                    </AppText>

                    <!-- Digital edition -->

                    <div
                        v-if="issue.file_path"
                        class="mt-8"
                    >
                        <a
                            :href="issue.file_path"
                            target="_blank"
                            rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 border border-foreground px-5 py-3 font-redhat text-sm font-semibold transition-colors hover:border-primary hover:text-primary"
                        >
                            Read digital edition

                            <span aria-hidden="true">
                                ↗
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- ================================================================
         Contents Header
    ================================================================= -->

    <section
        class="border-y border-border-light dark:border-border-dark"
    >
        <div class="container mx-auto px-4 py-14 sm:py-16 lg:py-20">
            <div
                class="flex flex-col gap-5 border-b border-border-light pb-7 sm:flex-row sm:items-end sm:justify-between dark:border-border-dark"
            >
                <div>
                    <AppText
                        tag="p"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        uppercase
                        tracking="wide"
                        color="primary"
                    >
                        In This Issue
                    </AppText>

                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="3xl"
                        weight="normal"
                        leading="tight"
                        class="mt-2"
                    >
                        Contents
                    </AppHeading>
                </div>

                <AppText
                    v-if="articles.length"
                    tag="span"
                    font="redhat"
                    size="xs"
                    color="muted"
                >
                    {{ articles.length }}
                    {{ articles.length === 1 ? 'story' : 'stories' }}
                </AppText>
            </div>

            <!-- ============================================================
                 Articles
            ============================================================= -->

            <div
                v-if="articles.length"
                class="divide-y divide-border-light dark:divide-border-dark"
            >
                <article
                    v-for="(article, index) in articles"
                    :key="article.id"
                    class="group py-8 first:pt-10 last:pb-0"
                >
                    <Link
                        :href="articleShow(article.slug).url"
                        class="grid gap-6 md:grid-cols-[70px_220px_minmax(0,1fr)] md:items-center lg:grid-cols-[90px_280px_minmax(0,1fr)]"
                    >
                        <!-- Number -->

                        <div class="hidden md:block">
                            <AppText
                                tag="span"
                                font="prata"
                                size="2xl"
                                color="muted"
                            >
                                {{ String(index + 1).padStart(2, '0') }}
                            </AppText>
                        </div>

                        <!-- Image -->

                        <div
                            class="overflow-hidden border border-border-light dark:border-border-dark"
                        >
                            <div class="aspect-[4/3] overflow-hidden">
                                <img
                                    :src="coverUrl(article)"
                                    :alt="article.title"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
                                    @error="($event.target as HTMLImageElement).src = '/frontend/images/placeholder.jpg'"
                                />
                            </div>
                        </div>

                        <!-- Article -->

                        <div>
                            <div
                                class="flex flex-wrap items-center gap-3"
                            >
                                <AppText
                                    v-if="article.category"
                                    tag="span"
                                    font="redhat"
                                    size="xs"
                                    weight="bold"
                                    uppercase
                                    tracking="wide"
                                    color="primary"
                                >
                                    {{ article.category.name }}
                                </AppText>

                                <span
                                    v-if="article.category && article.type"
                                    class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark"
                                />

                                <AppText
                                    v-if="article.type"
                                    tag="span"
                                    font="redhat"
                                    size="xs"
                                    color="muted"
                                >
                                    {{ article.type }}
                                </AppText>
                            </div>

                            <AppHeading
                                tag="h3"
                                font="prata"
                                size="2xl"
                                weight="normal"
                                leading="tight"
                                hover-primary
                                class="mt-3"
                            >
                                {{ article.title }}
                            </AppHeading>

                            <AppText
                                v-if="article.subtitle"
                                tag="p"
                                font="lora"
                                size="sm"
                                color="muted"
                                leading="relaxed"
                                :clamp="2"
                                class="mt-3 max-w-2xl"
                            >
                                {{ article.subtitle }}
                            </AppText>

                            <div
                                class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2"
                            >
                                <AppText
                                    v-if="article.byline"
                                    tag="span"
                                    font="redhat"
                                    size="xs"
                                    weight="medium"
                                >
                                    {{ article.byline }}
                                </AppText>

                                <AppText
                                    v-if="article.reading_time"
                                    tag="span"
                                    font="redhat"
                                    size="xs"
                                    color="muted"
                                >
                                    {{ article.reading_time }} min read
                                </AppText>
                            </div>
                        </div>
                    </Link>
                </article>
            </div>

            <!-- ============================================================
                 Empty
            ============================================================= -->

            <div
                v-else
                class="border border-dashed border-border-light py-24 text-center dark:border-border-dark"
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
                    Stories coming soon.
                </AppHeading>

                <AppText
                    tag="p"
                    font="lora"
                    size="sm"
                    color="muted"
                    align="center"
                    class="mt-3"
                >
                    Articles for this issue will appear here.
                </AppText>
            </div>
        </div>
    </section>

    <!-- ================================================================
         Footer navigation
    ================================================================= -->

    <section>
        <div
            class="container mx-auto flex flex-col items-center gap-5 px-4 py-14 text-center sm:py-16"
        >
            <AppText
                tag="p"
                font="redhat"
                size="xs"
                weight="bold"
                uppercase
                tracking="wide"
                color="muted"
            >
                Explore more
            </AppText>

            <Link
                :href="magazineIndex().url"
                class="font-redhat text-sm font-semibold text-primary transition-colors hover:underline"
            >
                ← Back to Magazine
            </Link>
        </div>
    </section>
</template>