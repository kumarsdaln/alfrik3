<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import AppBadge from '@/components/ui/AppBadge.vue'

import { index as magazineIndex } from '@/routes/magazine'
import { show as issueShow } from '@/routes/magazine/issues'
import { show as articleShow } from '@/routes/magazine/articles'

import type {
    MagazineArticle,
    MagazineIssue,
} from '@/types'

interface RelatedArticle extends MagazineArticle {
    issue?: MagazineIssue | null
}

const props = defineProps<{
    article: MagazineArticle
    related: RelatedArticle[]
    meta_data: {
        meta_title?: string | null
        meta_description?: string | null
        meta_keywords?: string | null
    }
}>()

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

const coverUrl = (article: MagazineArticle) =>
    article.cover_image || '/frontend/images/placeholder.jpg'

const articleReadingTime = (article: MagazineArticle) => {
    if (article.reading_time_label) {
        return article.reading_time_label
    }

    return article.reading_time
        ? `${article.reading_time} min read`
        : null
}
</script>

<template>
    <Head
        :title="
            meta_data.meta_title ||
            `${article.title} — Alfrik Magazine`
        "
    >
        <meta
            name="description"
            :content="
                meta_data.meta_description ||
                article.excerpt ||
                article.subtitle ||
                article.title
            "
        />

        <meta
            v-if="meta_data.meta_keywords"
            name="keywords"
            :content="meta_data.meta_keywords"
        />

        <link
            rel="canonical"
            :href="articleShow(article.slug).url"
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

            <Link
                v-if="article.issue"
                :href="issueShow(article.issue.slug).url"
                class="font-redhat text-xs text-muted transition-colors hover:text-primary"
            >
                {{ article.issue.title }}
            </Link>

            <span
                v-if="article.issue"
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
                class="max-w-xs truncate"
            >
                {{ article.title }}
            </AppText>
        </nav>
    </div>

    <!-- ================================================================
         Article Header
    ================================================================= -->

    <header>
        <div class="container mx-auto px-4 pb-12 pt-10 sm:pb-16 sm:pt-14 lg:pb-20 lg:pt-16">
            <div class="mx-auto max-w-5xl text-center">
                <!-- Category -->

                <div
                    class="flex flex-wrap items-center justify-center gap-3"
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
                        uppercase
                        tracking="wide"
                        color="muted"
                    >
                        {{ article.type }}
                    </AppText>
                </div>

                <!-- Title -->

                <AppHeading
                    tag="h1"
                    font="prata"
                    size="5xl"
                    weight="normal"
                    leading="tight"
                    class="mt-5 sm:text-6xl lg:text-7xl"
                >
                    {{ article.title }}
                </AppHeading>

                <!-- Subtitle -->

                <AppText
                    v-if="article.subtitle"
                    tag="p"
                    font="lora"
                    size="lg"
                    color="muted"
                    leading="relaxed"
                    class="mx-auto mt-7 max-w-3xl sm:text-xl"
                >
                    {{ article.subtitle }}
                </AppText>

                <!-- Metadata -->

                <div
                    class="mt-8 flex flex-wrap items-center justify-center gap-x-5 gap-y-3 border-y border-border-light py-4 dark:border-border-dark"
                >
                    <AppText
                        v-if="article.byline"
                        tag="span"
                        font="redhat"
                        size="xs"
                        weight="semibold"
                    >
                        {{ article.byline }}
                    </AppText>

                    <span
                        v-if="article.byline && article.author"
                        class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark"
                    />

                    <AppText
                        v-if="article.author"
                        tag="span"
                        font="redhat"
                        size="xs"
                        color="muted"
                    >
                        By {{ article.author.name }}
                    </AppText>

                    <span
                        v-if="article.published_at"
                        class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark"
                    />

                    <AppText
                        v-if="article.published_at"
                        tag="span"
                        font="redhat"
                        size="xs"
                        color="muted"
                    >
                        {{ formatDate(article.published_at) }}
                    </AppText>

                    <span
                        v-if="articleReadingTime(article)"
                        class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark"
                    />

                    <AppText
                        v-if="articleReadingTime(article)"
                        tag="span"
                        font="redhat"
                        size="xs"
                        color="muted"
                    >
                        {{ articleReadingTime(article) }}
                    </AppText>
                </div>
            </div>
        </div>
    </header>

    <!-- ================================================================
         Hero Image
    ================================================================= -->

    <section
        v-if="article.cover_image"
        class="container mx-auto px-4"
    >
        <figure
            class="mx-auto max-w-6xl overflow-hidden border border-border-light dark:border-border-dark"
        >
            <div class="aspect-[16/8] overflow-hidden">
                <img
                    :src="coverUrl(article)"
                    :alt="article.title"
                    class="h-full w-full object-cover"
                    @error="($event.target as HTMLImageElement).src = '/frontend/images/placeholder.jpg'"
                />
            </div>
        </figure>
    </section>

    <!-- ================================================================
         Article Content
    ================================================================= -->

    <main>
        <div class="container mx-auto px-4 py-14 sm:py-18 lg:py-20">
            <div
                class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_220px] lg:gap-20"
            >
                <!-- Content -->

                <article class="min-w-0">
                    <AppText
                        v-if="article.excerpt"
                        tag="p"
                        font="lora"
                        size="lg"
                        weight="medium"
                        leading="relaxed"
                        class="mb-10 max-w-3xl border-l-2 border-primary pl-6"
                    >
                        {{ article.excerpt }}
                    </AppText>

                    <div
                        class="magazine-article-content max-w-3xl"
                        v-html="article.content"
                    />
                </article>

                <!-- Article aside -->

                <aside class="lg:pt-2">
                    <div
                        class="border-y border-border-light py-5 dark:border-border-dark"
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
                            This Story
                        </AppText>

                        <div class="mt-4 space-y-3">
                            <div v-if="article.category">
                                <AppText
                                    tag="p"
                                    font="redhat"
                                    size="xs"
                                    color="muted"
                                >
                                    Category
                                </AppText>

                                <AppText
                                    tag="p"
                                    font="redhat"
                                    size="sm"
                                    weight="medium"
                                    class="mt-1"
                                >
                                    {{ article.category.name }}
                                </AppText>
                            </div>

                            <div v-if="article.author">
                                <AppText
                                    tag="p"
                                    font="redhat"
                                    size="xs"
                                    color="muted"
                                >
                                    Author
                                </AppText>

                                <AppText
                                    tag="p"
                                    font="redhat"
                                    size="sm"
                                    weight="medium"
                                    class="mt-1"
                                >
                                    {{ article.author.name }}
                                </AppText>
                            </div>

                            <div v-if="article.issue">
                                <AppText
                                    tag="p"
                                    font="redhat"
                                    size="xs"
                                    color="muted"
                                >
                                    Issue
                                </AppText>

                                <Link
                                    :href="issueShow(article.issue.slug).url"
                                    class="mt-1 block font-redhat text-sm font-medium transition-colors hover:text-primary"
                                >
                                    {{ article.issue.title }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <!-- ================================================================
         Related Stories
    ================================================================= -->

    <section
        v-if="related.length"
        class="border-y border-border-light dark:border-border-dark"
    >
        <div class="container mx-auto px-4 py-14 sm:py-16 lg:py-20">
            <div
                class="mb-10 flex flex-col gap-3 border-b border-border-light pb-6 dark:border-border-dark"
            >
                <AppText
                    tag="p"
                    font="redhat"
                    size="xs"
                    weight="bold"
                    uppercase
                    tracking="wide"
                    color="primary"
                >
                    Continue Reading
                </AppText>

                <AppHeading
                    tag="h2"
                    font="prata"
                    size="3xl"
                    weight="normal"
                    leading="tight"
                >
                    More Stories
                </AppHeading>
            </div>

            <div
                class="grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3"
            >
                <article
                    v-for="item in related"
                    :key="item.id"
                    class="group"
                >
                    <Link
                        :href="articleShow(item.slug).url"
                        class="block"
                    >
                        <div
                            class="overflow-hidden border border-border-light dark:border-border-dark"
                        >
                            <div class="aspect-[4/3] overflow-hidden">
                                <img
                                    :src="coverUrl(item)"
                                    :alt="item.title"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
                                    @error="($event.target as HTMLImageElement).src = '/frontend/images/placeholder.jpg'"
                                />
                            </div>
                        </div>
                    </Link>

                    <div class="pt-5">
                        <div
                            class="flex flex-wrap items-center gap-3"
                        >
                            <AppText
                                v-if="item.category"
                                tag="span"
                                font="redhat"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="primary"
                            >
                                {{ item.category.name }}
                            </AppText>

                            <span
                                v-if="item.category && item.type"
                                class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark"
                            />

                            <AppText
                                v-if="item.type"
                                tag="span"
                                font="redhat"
                                size="xs"
                                color="muted"
                            >
                                {{ item.type }}
                            </AppText>
                        </div>

                        <Link
                            :href="articleShow(item.slug).url"
                            class="mt-2 block"
                        >
                            <AppHeading
                                tag="h3"
                                font="prata"
                                size="xl"
                                weight="normal"
                                leading="tight"
                                hover-primary
                            >
                                {{ item.title }}
                            </AppHeading>
                        </Link>

                        <AppText
                            v-if="item.subtitle"
                            tag="p"
                            font="lora"
                            size="sm"
                            color="muted"
                            leading="relaxed"
                            :clamp="2"
                            class="mt-3"
                        >
                            {{ item.subtitle }}
                        </AppText>

                        <AppText
                            v-if="itemReadingTime(item)"
                            tag="p"
                            font="redhat"
                            size="xs"
                            color="muted"
                            class="mt-3"
                        >
                            {{ itemReadingTime(item) }}
                        </AppText>
                    </div>
                </article>
            </div>
        </div>
    </section>

    <!-- ================================================================
         Back Navigation
    ================================================================= -->

    <section>
        <div
            class="container mx-auto flex flex-col items-center gap-5 px-4 py-14 text-center sm:py-16"
        >
            <AppBadge
                v-if="article.issue"
                variant="secondary"
                size="sm"
            >
                {{ article.issue.title }}
            </AppBadge>

            <Link
                v-if="article.issue"
                :href="issueShow(article.issue.slug).url"
                class="font-redhat text-sm font-semibold text-primary transition-colors hover:underline"
            >
                ← Back to this issue
            </Link>

            <Link
                :href="magazineIndex().url"
                class="font-redhat text-xs text-muted transition-colors hover:text-primary"
            >
                Browse Magazine
            </Link>
        </div>
    </section>
</template>

<style scoped>
.magazine-article-content {
    font-family: 'Lora', serif;
    color: var(--foreground);
    font-size: 1.125rem;
    line-height: 1.9;
}

.magazine-article-content :deep(p) {
    margin-bottom: 1.5rem;
}

.magazine-article-content :deep(h2) {
    font-family: 'Prata', serif;
    font-size: 1.875rem;
    line-height: 1.3;
    font-weight: 400;
    margin-top: 3rem;
    margin-bottom: 1rem;
}

.magazine-article-content :deep(h3) {
    font-family: 'Prata', serif;
    font-size: 1.5rem;
    line-height: 1.35;
    font-weight: 400;
    margin-top: 2.5rem;
    margin-bottom: 1rem;
}

.magazine-article-content :deep(a) {
    text-decoration: underline;
    text-underline-offset: 3px;
}

.magazine-article-content :deep(a:hover) {
    color: var(--primary);
}

.magazine-article-content :deep(blockquote) {
    border-left: 2px solid var(--primary);
    margin: 2.5rem 0;
    padding-left: 1.5rem;
    font-family: 'Prata', serif;
    font-size: 1.5rem;
    line-height: 1.5;
}

.magazine-article-content :deep(img) {
    width: 100%;
    height: auto;
    margin: 2.5rem 0;
}

.magazine-article-content :deep(ul),
.magazine-article-content :deep(ol) {
    margin: 1.5rem 0;
    padding-left: 1.5rem;
}

.magazine-article-content :deep(li) {
    margin-bottom: 0.5rem;
}
</style>