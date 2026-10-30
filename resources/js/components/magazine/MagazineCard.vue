<script setup lang="ts">
    import { Link } from '@inertiajs/vue3'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppBadge from '@/components/ui/AppBadge.vue'

    import { show as magazineShow } from '@/routes/magazine'
    import { Category, Magazine } from '@/types'

    const props = defineProps<{
        magazine: Magazine
    }>()

    const primaryCategory = (magazine: Magazine): Category | null =>
        magazine.categories?.[0] ?? null

    const coverUrl = (magazine: Magazine): string => {
        const cover =
            magazine.media?.find((media) => media.collection === 'cover') ??
            magazine.media?.[0]

        return cover?.url || '/frontend/images/placeholder.jpg'
    }

    const issueCount = (magazine: Magazine): number =>
        magazine.issues_count ?? magazine.issues?.length ?? 0

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
</script>

<template>
    <article :key="magazine.id" class="group">
        <Link :href="magazineShow(magazine.slug).url" class="block">
            <div class="relative overflow-hidden border border-border-light bg-muted dark:border-border-dark">
                <div class="aspect-[4/5] overflow-hidden">
                    <img :src="coverUrl(magazine)" :alt="magazine.media?.find(
                        (media) => media.collection === 'cover',
                    )?.alt || magazine.title
                        "
                        class="h-full w-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.02]"
                        loading="lazy"
                        @error="($event.target as HTMLImageElement).src = '/frontend/images/placeholder.jpg'" />
                </div>

                <div v-if="magazine.featured" class="absolute left-0 top-0">
                    <AppBadge variant="primary" size="sm">
                        Featured
                    </AppBadge>
                </div>
            </div>
        </Link>

        <div class="pt-5">
            <div class="flex flex-wrap items-center gap-3">
                <AppText v-if="primaryCategory(magazine)" tag="span" font="redhat" size="xs" weight="bold" uppercase
                    tracking="wide">
                    {{ primaryCategory(magazine)?.name }}
                </AppText>

                <span v-if="primaryCategory(magazine)"
                    class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark" />

                <AppText tag="span" font="redhat" size="xs" color="muted">
                    {{ issueCount(magazine) }}
                    {{ issueCount(magazine) === 1 ? 'Issue' : 'Issues' }}
                </AppText>
            </div>

            <Link :href="magazineShow(magazine.slug).url" class="mt-3 block">
                <AppHeading tag="h3" font="prata" size="xl" weight="normal" leading="tight" hover-primary>
                    {{ magazine.title }}
                </AppHeading>
            </Link>

            <AppText v-if="magazine.subtitle" tag="p" font="lora" size="sm" color="muted" leading="relaxed" :clamp="2"
                class="mt-3">
                {{ magazine.subtitle }}
            </AppText>

            <AppText v-if="magazine.published_at" tag="p" font="redhat" size="xs" color="muted" class="mt-4">
                {{ formatDate(magazine.published_at) }}
            </AppText>
        </div>
    </article>
</template>
