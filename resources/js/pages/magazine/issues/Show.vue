<script setup lang="ts">
    import { computed } from 'vue'
    import { Head, Link } from '@inertiajs/vue3'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppBadge from '@/components/ui/AppBadge.vue'

    import { index as magazineIndex } from '@/routes/magazine'
    import { show as issueShow } from '@/routes/magazine/issues'
    import MagazineArticleCard from '@/components/magazine/MagazineArticleCard.vue'

    import type { MagazineIssue, MagazineArticle, SeoMetadata, Media, Category } from '@/types'

    const props = defineProps<{
        issue: MagazineIssue
        meta_data?: SeoMetadata
    }>()

    const articles = computed(() => props.issue.articles ?? [])

    const mediaUrl = (
        media?: Media[] | null,
        collection = 'cover',
    ): string | null => {
        const selected =
            media?.find((item) => item.collection === collection) ?? media?.[0]

        return selected?.url ?? null
    }

    const coverUrl = (item: MagazineIssue | MagazineArticle): string =>
        mediaUrl(item.media) || '/frontend/images/placeholder.jpg'

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

    const editionLabel = (issue: MagazineIssue): string => {
        const parts: string[] = []

        if (issue.volume) parts.push(`Vol. ${issue.volume}`)
        if (issue.issue_number) parts.push(`Issue ${issue.issue_number}`)

        return parts.join(' · ')
    }

    const issueUrl = computed(() => {
        const magazineSlug = props.issue.magazine?.slug

        if (!magazineSlug) return magazineIndex().url

        return issueShow({
            magazine: magazineSlug,
            issue: props.issue.slug,
        }).url
    })

    const canonicalUrl = computed(
        () => props.meta_data?.canonical_url || issueUrl.value,
    )
</script>

<template>

    <Head :title="meta_data?.title || `${issue.title} — Alfrik Magazine`">
        <meta head-key="description" name="description" :content="meta_data?.description ||
            issue.description ||
            issue.subtitle ||
            issue.title
            " />

        <meta v-if="meta_data?.indexable === false || meta_data?.followable === false" head-key="robots" name="robots"
            :content="`${meta_data?.indexable === false ? 'noindex' : 'index'},${meta_data?.followable === false ? 'nofollow' : 'follow'}`" />

        <meta v-if="meta_data?.og_title" head-key="og:title" property="og:title" :content="meta_data.og_title" />

        <meta v-if="meta_data?.og_description || meta_data?.description" head-key="og:description"
            property="og:description" :content="meta_data?.og_description || meta_data?.description || ''" />

        <meta v-if="meta_data?.og_type" head-key="og:type" property="og:type" :content="meta_data.og_type" />

        <meta v-if="meta_data?.og_image_url" head-key="og:image" property="og:image"
            :content="meta_data.og_image_url" />

        <meta v-if="meta_data?.twitter_card" head-key="twitter:card" name="twitter:card"
            :content="meta_data.twitter_card" />

        <meta v-if="meta_data?.twitter_title" head-key="twitter:title" name="twitter:title"
            :content="meta_data.twitter_title" />

        <meta v-if="meta_data?.twitter_description" head-key="twitter:description" name="twitter:description"
            :content="meta_data.twitter_description" />

        <meta v-if="meta_data?.twitter_image_url" head-key="twitter:image" name="twitter:image"
            :content="meta_data.twitter_image_url" />

        <link head-key="canonical" rel="canonical" :href="canonicalUrl" />
    </Head>

    <!-- Issue Hero -->
    <header>
        <div class="container mx-auto px-4 py-12 sm:py-16 lg:py-20">
            <div
                class="grid items-center gap-10 lg:grid-cols-[420px_minmax(0,1fr)] lg:gap-16 xl:grid-cols-[460px_minmax(0,1fr)]">
                <!-- Cover -->
                <div
                    class="relative overflow-hidden border border-border-light bg-surface-light dark:border-border-dark dark:bg-surface-dark">
                    <div class="aspect-[4/5] overflow-hidden">
                        <img :src="coverUrl(issue)"
                            :alt="issue.media?.find((media) => media.collection === 'cover')?.alt || issue.title"
                            class="h-full w-full object-cover"
                            @error="($event.target as HTMLImageElement).src = '/frontend/images/placeholder.jpg'" />
                    </div>

                    <div v-if="issue.featured" class="absolute left-5 top-5">
                        <AppBadge variant="primary" size="sm">
                            Featured Issue
                        </AppBadge>
                    </div>
                </div>

                <!-- Issue information -->
                <div class="max-w-3xl">
                    <div class="mb-6 flex items-center gap-3">
                        <span class="h-px w-12 bg-primary" aria-hidden="true" />

                        <AppText tag="span" font="redhat" size="xs" weight="bold" uppercase tracking="wide">
                            Alfrik Magazine
                        </AppText>
                    </div>

                    <AppText v-if="editionLabel(issue)" tag="p" font="redhat" size="xs" weight="bold" uppercase
                        tracking="wide">
                        {{ editionLabel(issue) }}
                    </AppText>

                    <AppHeading tag="h1" font="prata" size="5xl" weight="normal" leading="tight"
                        class="mt-3 sm:text-6xl">
                        {{ issue.title }}
                    </AppHeading>

                    <AppText v-if="issue.subtitle" tag="p" font="lora" size="lg" color="muted" leading="relaxed"
                        class="mt-6 max-w-2xl">
                        {{ issue.subtitle }}
                    </AppText>

                    <!-- Metadata -->
                    <div
                        class="mt-8 flex flex-wrap items-center gap-x-5 gap-y-3 border-y border-border-light py-4 dark:border-border-dark">
                        <AppText v-if="issue.cover_date" tag="span" font="redhat" size="xs" color="muted">
                            {{ formatDate(issue.cover_date) }}
                        </AppText>

                        <span v-if="issue.cover_date && issue.editor"
                            class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark" />

                        <AppText v-if="issue.editor" tag="span" font="redhat" size="xs" color="muted">
                            Edited by {{ issue.editor }}
                        </AppText>

                        <span v-if="articles.length" class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark" />

                        <AppText tag="span" font="redhat" size="xs" color="muted">
                            {{ articles.length }}
                            {{ articles.length === 1 ? 'Story' : 'Stories' }}
                        </AppText>
                    </div>

                    <AppText v-if="issue.description" tag="div" font="lora" color="muted" leading="relaxed"
                        class="mt-7 max-w-2xl">
                        {{ issue.description }}
                    </AppText>

                    <!-- Digital edition -->
                    <div v-if="issue.file_path" class="mt-8">
                        <a :href="issue.file_path" target="_blank" rel="noopener noreferrer"
                            class="inline-flex items-center gap-2 border border-foreground px-5 py-3 font-redhat text-sm font-semibold transition-colors hover:border-primary hover:text-primary">
                            Read digital edition

                            <span aria-hidden="true">↗</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Contents -->
    <section class="border-y border-border-light dark:border-border-dark">
        <div class="container mx-auto px-4 py-14 sm:py-16 lg:py-20">
            <div
                class="flex flex-col gap-5 border-b border-border-light pb-7 sm:flex-row sm:items-end sm:justify-between dark:border-border-dark">
                <div>
                    <AppText tag="p" font="redhat" size="xs" weight="bold" uppercase tracking="wide">
                        In This Issue
                    </AppText>

                    <AppHeading tag="h2" font="prata" size="3xl" weight="normal" leading="tight" class="mt-2">
                        Contents
                    </AppHeading>
                </div>

                <AppText v-if="articles.length" tag="span" font="redhat" size="xs" color="muted">
                    {{ articles.length }}
                    {{ articles.length === 1 ? 'story' : 'stories' }}
                </AppText>
            </div>

            <!-- Articles -->
            <div v-if="articles.length" class="divide-y divide-border-light dark:divide-border-dark">
                <MagazineArticleCard
                    v-for="(article, index) in articles"
                    :key="article.id"
                    :article="article"
                    :index="index"
                />
            </div>

            <!-- Empty state -->
            <div v-else class="border border-dashed border-border-light py-24 text-center dark:border-border-dark">
                <AppHeading tag="h6" font="prata" size="2xl" weight="normal" color="muted" align="center" class="italic">
                    Stories coming soon.
                </AppHeading>

                <AppText tag="p" font="lora" size="sm" color="muted" align="center" class="mt-3">
                    Articles for this issue will appear here.
                </AppText>
            </div>
        </div>
    </section>
</template>
