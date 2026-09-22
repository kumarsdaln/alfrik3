<script setup lang="ts">
    import { computed } from 'vue'
    import { Link } from '@inertiajs/vue3'
    import { ArrowUpRight, CalendarDays } from '@lucide/vue'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import { MetaInfo } from '@/components/ui/metainfo'

    import type { Report } from '@/types'


    /*
    |--------------------------------------------------------------------------
    | Props
    |--------------------------------------------------------------------------
    */

    interface Props {
        report: Report
        href: string
    }

    const props = defineProps<Props>()


    /*
    |--------------------------------------------------------------------------
    | Date
    |--------------------------------------------------------------------------
    */

    const dateLabel = computed(() => {
        const date =
            props.report.published_at ||
            props.report.report_date ||
            props.report.created_at

        if (!date) {
            return ''
        }

        return new Date(date).toLocaleDateString('en-US', {
            day: 'numeric',
            month: 'short',
            year: 'numeric',
        })
    })
</script>


<template>
    <article class="group">
        <Link :href="href" class="block">
            <!-- ========================================================== -->
            <!-- Visual -->
            <!-- ========================================================== -->

            <div class="
                    relative
                    aspect-[16/10]
                    overflow-hidden
                    border
                    border-border-light
                    bg-muted
                    dark:border-border-dark
                ">
                <div class="
                        flex
                        h-full
                        w-full
                        items-center
                        justify-center
                        bg-muted
                        transition-transform
                        duration-500
                        group-hover:scale-[1.02]
                    ">
                    <div class="text-center px-8">
                        <div class="
                                mx-auto
                                flex
                                h-12
                                w-12
                                items-center
                                justify-center
                                border
                                border-border-light
                                dark:border-border-dark
                            ">
                            <span class="
                                    font-prata
                                    text-lg
                                    text-foreground
                                ">
                                A
                            </span>
                        </div>

                        <AppText v-if="report.type" size="xs" weight="bold" tracking="wide" uppercase color="muted"
                            class="mt-3">
                            {{ report.type.label }}
                        </AppText>
                    </div>
                </div>

                <!-- Arrow -->

                <div class="
                        absolute
                        right-4
                        top-4
                        flex
                        h-9
                        w-9
                        items-center
                        justify-center
                        border
                        border-border-light
                        bg-background
                        opacity-0
                        transition-all
                        duration-300
                        group-hover:opacity-100
                        dark:border-border-dark
                    ">
                    <ArrowUpRight :size="16" :stroke-width="1.8" />
                </div>
            </div>


            <!-- ========================================================== -->
            <!-- Content -->
            <!-- ========================================================== -->

            <div class="pt-5">

                <!-- Type -->

                <AppText v-if="report.type" tag="p" size="xs" weight="bold" tracking="wide" uppercase color="primary">
                    {{ report.type.label }}
                </AppText>


                <!-- Title -->

                <AppHeading tag="h2" font="prata" size="xl" weight="normal" leading="tight" class="
                        mt-2
                        transition-colors
                        duration-200
                        group-hover:text-primary
                    ">
                    {{ report.title }}
                </AppHeading>


                <!-- Subtitle -->

                <AppText v-if="report.subtitle" tag="p" size="sm" color="muted" leading="relaxed"
                    class="mt-2 line-clamp-2">
                    {{ report.subtitle }}
                </AppText>


                <!-- Summary -->

                <AppText v-else-if="report.summary" tag="p" size="sm" color="muted" leading="relaxed"
                    class="mt-2 line-clamp-3">
                    {{ report.summary }}
                </AppText>


                <!-- Meta -->

                <MetaInfo v-if="dateLabel || report.research" class="mt-4" :items="[
                    ...(dateLabel
                        ? [
                            {
                                value: dateLabel,
                                icon: CalendarDays,
                            },
                        ]
                        : []),
                    ...(report.research
                        ? [
                            {
                                value: report.research.title,
                            },
                        ]
                        : []),
                ]" />
            </div>
        </Link>
    </article>
</template>