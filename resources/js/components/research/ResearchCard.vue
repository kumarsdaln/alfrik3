<script setup lang="ts">
    import { Link } from '@inertiajs/vue3'
    import AppHeading from '@/Components/Ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'
    import AppBadge from '@/Components/Ui/AppBadge.vue'
    import type { ResearchPaper } from '@/types'

    const props = withDefaults(
        defineProps<{
            paper: ResearchPaper
            href: string
            compact?: boolean
        }>(),
        { compact: false }
    )

    const yearOf = (d?: string | null) => (d ? new Date(d).getFullYear() : '')
</script>

<template>
    <!-- Compact card (related research) -->
    <Link v-if="compact" :href="href"
        class="group block rounded-xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-5 hover:shadow-lg transition-all">
        <AppHeading tag="h3" font="prata" size="lg" weight="semibold" hover-brand :clamp="2">
            {{ paper.title }}
        </AppHeading>
        <AppText v-if="paper.authors" tag="p" font="lora" size="xs" color="muted" :clamp="1" class="mt-2 italic">
            {{ paper.authors }}
        </AppText>
    </Link>

    <!-- Full paper row (library list) -->
    <Link v-else :href="href"
        class="group flex gap-5 rounded-2xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-5 hover:shadow-xl transition-all">
        <div v-if="paper.cover_image" class="hidden sm:block w-28 shrink-0">
            <img :src="paper.cover_image" :alt="paper.title" class="w-full aspect-[3/4] object-cover rounded-lg" />
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 mb-1">
                <AppText v-if="paper.area" tag="p" font="redhat" size="xs" weight="bold" tracking="wide" uppercase
                    color="brand">
                    {{ paper.area.name }}
                </AppText>
                <AppBadge v-if="paper.featured" variant="primary" size="sm">Featured</AppBadge>
            </div>
            <AppHeading tag="h3" font="prata" size="lg" weight="semibold" hover-brand :clamp="2">
                {{ paper.title }}
            </AppHeading>
            <AppText v-if="paper.authors" tag="p" font="lora" size="xs" color="muted" class="mt-1 italic">
                {{ paper.authors }}
            </AppText>
            <AppText v-if="paper.abstract" tag="p" font="lora" size="xs" color="muted" :clamp="2" class="mt-2">
                {{ paper.abstract }}
            </AppText>
            <div class="mt-3 flex items-center gap-3">
                <AppText v-if="paper.institution" tag="span" font="redhat" size="xs" color="muted">
                    {{ paper.institution }}
                </AppText>
                <AppText v-if="paper.institution && yearOf(paper.published_at)" tag="span" size="xs" color="muted">
                    ·
                </AppText>
                <AppText tag="span" font="redhat" size="xs" color="muted">{{ yearOf(paper.published_at) }}</AppText>
            </div>
        </div>
    </Link>
</template>
