<script setup lang="ts">
    import { Link } from '@inertiajs/vue3'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import { show as articleShow } from '@/routes/magazine/articles'
    import { Category, MagazineArticle } from '@/types';

    defineProps<{
        article: MagazineArticle
        index: number
    }>()

    const primaryCategory = (
        article: MagazineArticle,
    ): Category | null => {
        return article.categories?.[0] ?? null
    }

    const coverUrl = (article: MagazineArticle): string => {
        const cover =
            article.media?.find((media) => media.collection === 'cover') ??
            article.media?.[0]

        return cover?.url || '/frontend/images/placeholder.jpg'
    }
</script>

<template>
    <article class="group py-8">
        <Link :href="articleShow(article.slug).url"
            class="grid gap-6 md:grid-cols-[70px_220px_minmax(0,1fr)] md:items-center lg:grid-cols-[90px_280px_minmax(0,1fr)]">
            <!-- Number -->
            <div class="hidden md:block">
                <AppText tag="span" font="prata" size="2xl" color="muted">
                    {{ String(index + 1).padStart(2, '0') }}
                </AppText>
            </div>

            <!-- Image -->
            <div class="overflow-hidden border border-border-light dark:border-border-dark">
                <div class="aspect-[4/3] overflow-hidden">
                    <img :src="coverUrl(article)" :alt="article.media?.find(
                        (media) => media.collection === 'cover',
                    )?.alt || article.title
                        " class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.03]"
                        loading="lazy" @error="
                            ($event.target as HTMLImageElement).src =
                            '/frontend/images/placeholder.jpg'
                            " />
                </div>
            </div>

            <!-- Article -->
            <div>
                <div class="flex flex-wrap items-center gap-3">
                    <AppText v-if="primaryCategory(article)" tag="span" font="redhat" size="xs" weight="bold" uppercase
                        tracking="wide">
                        {{ primaryCategory(article)?.name }}
                    </AppText>

                    <span v-if="primaryCategory(article) && article.type"
                        class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark" />

                    <AppText v-if="article.type" tag="span" font="redhat" size="xs" color="muted">
                        {{ article.type }}
                    </AppText>
                </div>

                <AppHeading tag="h3" font="prata" size="2xl" weight="normal" leading="tight" hover-primary class="mt-3">
                    {{ article.title }}
                </AppHeading>

                <AppText v-if="article.subtitle" tag="p" font="lora" size="sm" color="muted" leading="relaxed"
                    :clamp="2" class="mt-3 max-w-2xl">
                    {{ article.subtitle }}
                </AppText>

                <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2">
                    <AppText v-if="article.byline" tag="span" font="redhat" size="xs" weight="medium">
                        {{ article.byline }}
                    </AppText>

                    <AppText v-if="
                        article.reading_time != null &&
                        article.reading_time > 0
                    " tag="span" font="redhat" size="xs" color="muted">
                        {{ article.reading_time }} min read
                    </AppText>
                </div>
            </div>
        </Link>
    </article>
</template>
