<script setup lang="ts">
    import { Link } from '@inertiajs/vue3'
    import AppHeading from '@/Components/Ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'
    import AppBadge from '@/Components/Ui/AppBadge.vue'
    import type { Magazine } from '@/types'

    const props = withDefaults(
        defineProps<{
            item: Magazine
            href: string
            compact?: boolean
        }>(),
        {
            compact: false,
        }
    )

    const dateLabel = (d?: string | null) =>
        d ? new Date(d).toLocaleDateString('en-US', { month: 'short', year: 'numeric' }) : ''

    const issueDate = (m: Magazine) => dateLabel(m.published_at || m.created_at)

    const coverUrl = (m: Magazine) => m.cover_image || '/frontend/images/placeholder.jpg'

    // Covers can be set but point at a missing file, so fall back on error too.
    const onImgError = (e: Event) => {
        (e.target as HTMLImageElement).src = '/frontend/images/placeholder.jpg'
    }
</script>

<template>
    <!-- Compact card: cover + title only (related / more articles) -->
    <Link v-if="compact" :href="href" class="group block">
        <div class="relative overflow-hidden rounded-xl bg-canvas-light dark:bg-white/5">
            <img :src="coverUrl(item)" :alt="item.title" @error="onImgError"
                class="w-full aspect-[5/7] object-cover group-hover:scale-105 transition-transform duration-500" />
        </div>
        <AppHeading tag="h3" font="prata" size="sm" weight="normal" hover-brand :clamp="2" class="mt-3">
            {{ item.title }}
        </AppHeading>
    </Link>

    <!-- Full card: cover + category + title + excerpt + meta (grid) -->
    <Link v-else :href="href"
        class="group bg-surface-light dark:bg-surface-dark rounded-2xl overflow-hidden border border-border-light dark:border-border-dark shadow-editorial dark:shadow-editorial-dark hover:shadow-2xl hover:-translate-y-1 transition-all duration-300 flex flex-col">
        <div class="relative overflow-hidden bg-canvas-light dark:bg-white/5">
            <img :src="coverUrl(item)" :alt="item.title" @error="onImgError"
                class="w-full aspect-[5/7] object-cover transform group-hover:scale-105 transition-transform duration-500" />
            <span v-if="item.category" class="absolute bottom-3 left-3">
                <AppBadge variant="primary" size="sm">{{ item.category.name }}</AppBadge>
            </span>
        </div>
        <div class="p-5 flex flex-col flex-1">
            <AppHeading tag="h3" font="prata" size="lg" weight="normal" hover-brand :clamp="2">
                {{ item.title }}
            </AppHeading>
            <AppText v-if="item.subtitle" tag="p" font="lora" size="sm" color="muted" :clamp="2" class="mt-2 flex-1">
                {{ item.subtitle }}
            </AppText>
            <div class="mt-4 flex flex-wrap items-center gap-x-2 gap-y-0.5">
                <AppText v-if="item.author" tag="span" font="redhat" size="xs" color="muted">
                    {{ item.author.name }}
                </AppText>
                <AppText v-if="item.author" tag="span" font="redhat" size="xs" color="muted">·</AppText>
                <AppText tag="span" font="redhat" size="xs" color="muted">{{ issueDate(item) }}</AppText>
                <AppText v-if="item.reading_minutes" tag="span" font="redhat" size="xs" color="muted">
                    · {{ item.reading_minutes }} min
                </AppText>
            </div>
        </div>
    </Link>
</template>
