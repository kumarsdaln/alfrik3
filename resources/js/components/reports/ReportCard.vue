<script setup lang="ts">
    import { computed } from 'vue'
    import { Link } from '@inertiajs/vue3'
    import AppHeading from '@/Components/Ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'
    import AppBadge from '@/Components/Ui/AppBadge.vue'
    import type { Report } from '@/types'

    const props = defineProps<{
        report: Report
        href: string
        downloadHref: string
    }>()

    const cover = computed(() => props.report.cover_image || '/frontend/images/placeholder.jpg')

    const dateLabel = computed(() => {
        const d = props.report.published_at || props.report.created_at
        return d ? new Date(d).toLocaleDateString('en-US', { month: 'short', year: 'numeric' }) : ''
    })
</script>

<template>
    <div
        class="group bg-surface-light dark:bg-surface-dark rounded-2xl overflow-hidden border border-border-light dark:border-border-dark shadow-sm hover:shadow-2xl transition-all duration-300 flex flex-col">
        <Link :href="href" class="relative overflow-hidden block aspect-[4/3]">
            <img :src="cover" :alt="report.title"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" />
            <span v-if="report.category" class="absolute bottom-3 left-3">
                <AppBadge variant="primary" size="sm" class="uppercase tracking-widest">{{ report.category.name }}</AppBadge>
            </span>
            <span v-if="report.gated" class="absolute top-3 right-3">
                <AppBadge variant="default" size="sm" class="uppercase tracking-widest">GATED</AppBadge>
            </span>
        </Link>
        <div class="p-5 flex flex-col flex-1">
            <Link :href="href">
                <AppHeading tag="h3" font="prata" size="lg" weight="semibold" hover-brand :clamp="2">
                    {{ report.title }}
                </AppHeading>
            </Link>
            <AppText v-if="report.summary" font="lora" size="sm" color="muted" :clamp="2" class="mt-2 flex-1">
                {{ report.summary }}
            </AppText>
            <div class="mt-4 flex items-center justify-between">
                <AppText tag="span" size="xs" color="muted">{{ dateLabel }}</AppText>
                <a :href="downloadHref"
                    class="inline-flex items-center gap-1 font-semibold text-brand hover:gap-2 transition-all">
                    Download
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</template>
