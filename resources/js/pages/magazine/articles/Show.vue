<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'

    import { show as issueShow } from '@/routes/magazine/issues'
    import { show as articleShow } from '@/routes/magazine/articles'

    import type { MagazineArticle, SeoMetadata, Category } from '@/types'

    const props = defineProps<{
        article: MagazineArticle
        related: MagazineArticle[]
        meta_data: SeoMetadata
    }>()

    const formatDate = (date?: string | null): string => {
        if (!date) return ''

        const parsedDate = new Date(date)

        if (Number.isNaN(parsedDate.getTime())) return ''

        return parsedDate.toLocaleDateString('en-US', {
            month: 'long',
            day: 'numeric',
            year: 'numeric',
        })
    }

    const primaryCategory = (article: MagazineArticle): Category | null =>
        article.categories?.[0] ?? null

    const articleType = (article: MagazineArticle): string | null => {
        if (!article.type) return null

        if (typeof article.type === 'string') {
            return article.type
        }

        return article.type.label ?? article.type.value ?? null
    }

    const coverUrl = (article: MagazineArticle): string => {
        const cover = article.media?.find(
            (media) => media.collection === 'cover',
        )

        return cover?.url || '/frontend/images/placeholder.jpg'
    }

    const articleReadingTime = (article: MagazineArticle): string | null => {
        if (article.reading_time_label) {
            return article.reading_time_label
        }

        return article.reading_time
            ? `${article.reading_time} min read`
            : null
    }

    const issueUrl = (article: MagazineArticle): string | null => {
        if (!article.issue?.slug || !article.issue.magazine?.slug) {
            return null
        }

        return issueShow({
            magazine: article.issue.magazine.slug,
            issue: article.issue.slug,
        }).url
    }
</script>

<template>

    <Head :title="meta_data.title || `${article.title} — Alfrik Magazine`">
        <meta head-key="description" name="description" :content="meta_data.description ||
            article.excerpt ||
            article.subtitle ||
            article.title
            " />

        <meta v-if="meta_data.indexable === false" head-key="robots" name="robots" content="noindex" />

        <meta v-else-if="meta_data.followable === false" head-key="robots" name="robots" content="nofollow" />

        <meta v-if="meta_data.og_title" head-key="og:title" property="og:title" :content="meta_data.og_title" />

        <meta v-if="meta_data.og_description || meta_data.description" head-key="og:description"
            property="og:description" :content="meta_data.og_description || meta_data.description || ''" />

        <meta v-if="meta_data.og_type" head-key="og:type" property="og:type" :content="meta_data.og_type" />

        <meta v-if="meta_data.og_image_url" head-key="og:image" property="og:image" :content="meta_data.og_image_url" />

        <meta v-if="meta_data.twitter_card" head-key="twitter:card" name="twitter:card"
            :content="meta_data.twitter_card" />

        <meta v-if="meta_data.twitter_title" head-key="twitter:title" name="twitter:title"
            :content="meta_data.twitter_title" />

        <meta v-if="meta_data.twitter_description" head-key="twitter:description" name="twitter:description"
            :content="meta_data.twitter_description" />

        <meta v-if="meta_data.twitter_image_url" head-key="twitter:image" name="twitter:image"
            :content="meta_data.twitter_image_url" />

        <link head-key="canonical" rel="canonical" :href="meta_data.canonical_url" />
    </Head>

    <!-- Article Header -->
    <header>
        <div class="container mx-auto px-4 pb-12 pt-10 sm:pb-16 sm:pt-14 lg:pb-20 lg:pt-16">
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <AppText v-if="primaryCategory(article)" tag="span" font="redhat" size="xs" weight="bold" uppercase
                        tracking="wide">
                        {{ primaryCategory(article)?.name }}
                    </AppText>

                    <span v-if="primaryCategory(article) && articleType(article)"
                        class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark" />

                    <AppText v-if="articleType(article)" tag="span" font="redhat" size="xs" uppercase tracking="wide"
                        color="muted">
                        {{ articleType(article) }}
                    </AppText>
                </div>

                <AppHeading tag="h1" font="prata" size="5xl" weight="normal" leading="tight"
                    class="mt-5 sm:text-6xl lg:text-7xl">
                    {{ article.title }}
                </AppHeading>

                <AppText v-if="article.subtitle" tag="p" font="lora" size="lg" color="muted" leading="relaxed">
                    {{ article.subtitle }}
                </AppText>

                <div
                    class="mt-8 flex flex-wrap items-center gap-x-5 gap-y-3 border-y border-border-light py-4 dark:border-border-dark">
                    <AppText v-if="article.byline" tag="span" font="redhat" size="xs" weight="semibold">
                        {{ article.byline }}
                    </AppText>

                    <span v-if="article.byline && article.author"
                        class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark" />

                    <AppText v-if="article.author" tag="span" font="redhat" size="xs" color="muted">
                        By {{ article.author.name }}
                    </AppText>

                    <span v-if="article.published_at"
                        class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark" />

                    <AppText v-if="article.published_at" tag="span" font="redhat" size="xs" color="muted">
                        {{ formatDate(article.published_at) }}
                    </AppText>

                    <span v-if="articleReadingTime(article)"
                        class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark" />

                    <AppText v-if="articleReadingTime(article)" tag="span" font="redhat" size="xs" color="muted">
                        {{ articleReadingTime(article) }}
                    </AppText>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero Image -->
    <section v-if="article.media?.some((media) => media.collection === 'cover')" class="container mx-auto px-4">
        <figure class="mx-auto max-w-6xl overflow-hidden border border-border-light dark:border-border-dark">
            <div class="aspect-[16/8] overflow-hidden">
                <img :src="coverUrl(article)" :alt="article.media?.find((media) => media.collection === 'cover')?.alt ||
                    article.title
                    " class="h-full w-full object-cover"
                    @error="($event.target as HTMLImageElement).src = '/frontend/images/placeholder.jpg'" />
            </div>
        </figure>
    </section>

    <!-- Article Content -->
    <main>
        <div class="container mx-auto px-4 py-14 sm:py-18 lg:py-20">
            <div class="grid gap-12 lg:grid-cols-[minmax(0,1fr)_220px] lg:gap-20">
                <article class="min-w-0">
                    <AppText v-if="article.excerpt" tag="p" font="lora" size="lg" weight="medium" leading="relaxed"
                        class="mb-10 max-w-3xl border-l-2 border-primary pl-6">
                        {{ article.excerpt }}
                    </AppText>

                    <div class="magazine-article-content max-w-3xl" v-html="article.content || ''" />
                </article>

                <!-- Article Aside -->
                <aside class="lg:pt-2">
                    <div class="border-y border-border-light py-5 dark:border-border-dark">
                        <AppText tag="p" font="redhat" size="xs" weight="bold" uppercase tracking="wide" color="muted">
                            This Story
                        </AppText>

                        <div class="mt-4 space-y-3">
                            <div v-if="primaryCategory(article)">
                                <AppText tag="p" font="redhat" size="xs" color="muted">
                                    Category
                                </AppText>

                                <AppText tag="p" font="redhat" size="sm" weight="medium" class="mt-1">
                                    {{ primaryCategory(article)?.name }}
                                </AppText>
                            </div>

                            <div v-if="article.author">
                                <AppText tag="p" font="redhat" size="xs" color="muted">
                                    Author
                                </AppText>

                                <AppText tag="p" font="redhat" size="sm" weight="medium" class="mt-1">
                                    {{ article.author.name }}
                                </AppText>
                            </div>

                            <div v-if="article.issue && issueUrl(article)">
                                <AppText tag="p" font="redhat" size="xs" color="muted">
                                    Issue
                                </AppText>

                                <Link :href="issueUrl(article)!"
                                    class="mt-1 block font-redhat text-sm font-medium transition-colors hover:text-primary">
                                    {{ article.issue.title }}
                                </Link>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </main>

    <!-- Related Stories -->
    <section v-if="related.length" class="border-y border-border-light dark:border-border-dark">
        <div class="container mx-auto px-4 py-14 sm:py-16 lg:py-20">
            <div class="mb-10 flex flex-col gap-3 border-b border-border-light pb-6 dark:border-border-dark">
                <AppText tag="p" font="redhat" size="xs" weight="bold" uppercase tracking="wide">
                    Continue Reading
                </AppText>

                <AppHeading tag="h2" font="prata" size="3xl" weight="normal" leading="tight">
                    More Stories
                </AppHeading>
            </div>

            <div class="grid gap-x-8 gap-y-12 sm:grid-cols-2 lg:grid-cols-3">
                <article v-for="item in related" :key="item.id" class="group">
                    <Link :href="articleShow(item.slug).url" class="block">
                        <div class="overflow-hidden border border-border-light dark:border-border-dark">
                            <div class="aspect-[4/3] overflow-hidden">
                                <img :src="coverUrl(item)" :alt="item.title"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
                                    @error="($event.target as HTMLImageElement).src = '/frontend/images/placeholder.jpg'" />
                            </div>
                        </div>
                    </Link>

                    <div class="pt-5">
                        <div class="flex flex-wrap items-center gap-3">
                            <AppText v-if="primaryCategory(item)" tag="span" font="redhat" size="xs" weight="bold"
                                uppercase tracking="wide">
                                {{ primaryCategory(item)?.name }}
                            </AppText>

                            <span v-if="primaryCategory(item) && articleType(item)"
                                class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark" />

                            <AppText v-if="articleType(item)" tag="span" font="redhat" size="xs" color="muted">
                                {{ articleType(item) }}
                            </AppText>
                        </div>

                        <Link :href="articleShow(item.slug).url" class="mt-2 block">
                            <AppHeading tag="h3" font="prata" size="xl" weight="normal" leading="tight" hover-primary>
                                {{ item.title }}
                            </AppHeading>
                        </Link>

                        <AppText v-if="item.subtitle || item.excerpt" tag="p" font="lora" size="sm" color="muted"
                            leading="relaxed" :clamp="2" class="mt-3">
                            {{ item.subtitle || item.excerpt }}
                        </AppText>

                        <AppText v-if="articleReadingTime(item)" tag="p" font="redhat" size="xs" color="muted"
                            class="mt-3">
                            {{ articleReadingTime(item) }}
                        </AppText>
                    </div>
                </article>
            </div>
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
