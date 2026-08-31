<script setup lang="ts">
    import { computed } from 'vue'
    import { ArrowUpRight, CalendarDays, Download, Lock } from '@lucide/vue'
    import { Link } from '@inertiajs/vue3'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'

    import type { Report } from '@/types'
    import { Badge } from '../ui/badge'

    const props = defineProps<{
        report: Report
        href: string
        downloadHref: string
    }>()

    const cover = computed(
        () => props.report.cover_image || '/frontend/images/placeholder.jpg',
    )

    const dateLabel = computed(() => {
        const date = props.report.published_at || props.report.created_at

        if (!date) {
            return ''
        }

        return new Date(date).toLocaleDateString('en-US', {
            month: 'short',
            year: 'numeric',
        })
    })
</script>

<template>
    <article class="group flex flex-col">
        <!-- Image -->
        <Link :href="href" class="relative block overflow-hidden bg-muted">
            <div class="aspect-[4/3] overflow-hidden">
                <img :src="cover" :alt="report.title" class="
                        h-full
                        w-full
                        object-cover
                        transition-transform
                        duration-500
                        ease-out
                        group-hover:scale-[1.025]
                    " />
            </div>

            <!-- Gated -->
            <div v-if="report.gated" class="
                    absolute
                    right-3
                    top-3
                ">
                <Badge variant="secondary">
                    <component :is="Lock" :size="13" :stroke-width="1.8" />
                    Gated
                </Badge>
            </div>
        </Link>


        <!-- Content -->
        <div class="flex flex-1 flex-col pt-5">
            <!-- Meta -->
            <div class="
                    mb-3
                    flex
                    flex-wrap
                    items-center
                    justify-between
                    gap-3
                ">
                <AppText v-if="report.category" tag="span" size="xs" weight="semibold" uppercase
                    tracking="wide">
                    {{ report.category.name }}
                </AppText>

                <AppText v-if="dateLabel" tag="span" size="xs" color="muted" class="inline-flex items-center gap-1.5">
                    <CalendarDays :size="12" :stroke-width="1.7" aria-hidden="true" />
                    {{ dateLabel }}
                </AppText>
            </div>


            <!-- Title -->
            <Link :href="href" class="block">
                <AppHeading tag="h3" font="prata" size="lg" weight="semibold" :clamp="2">
                    {{ report.title }}
                </AppHeading>
            </Link>


            <!-- Summary -->
            <AppText v-if="report.summary" font="lora" size="sm" color="muted" :clamp="3" class="mt-3">
                {{ report.summary }}
            </AppText>


            <!-- Footer -->
            <div class="
                    mt-5
                    flex
                    items-center
                    justify-between
                    gap-4
                    border-t
                    border-border-light
                    pt-4
                    dark:border-border-dark
                ">
                <Link :href="href" class="
                        group/read
                        inline-flex
                        items-center
                        gap-1.5
                        text-xs
                        font-medium
                        text-content-light
                        transition-colors
                        dark:text-content-dark
                    ">
                    Read report

                    <ArrowUpRight :size="13" :stroke-width="1.8" class="
                            transition-transform
                            duration-200
                            group-hover/read:-translate-y-0.5
                            group-hover/read:translate-x-0.5
                        " aria-hidden="true" />
                </Link>

                <a :href="downloadHref" class="
                        inline-flex
                        items-center
                        gap-1.5
                        text-xs
                        font-medium
                        text-content-lightMuted
                        transition-colors
                        dark:text-content-darkMuted
                    ">
                    <Download :size="13" :stroke-width="1.8" aria-hidden="true" />

                    {{ report.gated ? 'Get report' : 'Download' }}
                </a>
            </div>
        </div>
    </article>
</template>