<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3'
    import { ArrowUpRight, ClipboardList } from '@lucide/vue'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'

    import { show as surveyShow } from '@/routes/surveys'
    import type { Survey } from '@/types'

    interface SurveyItem extends Survey {
        questions_count?: number
        responses_count?: number
    }

    interface Props {
        surveys: SurveyItem[]
    }

    defineProps<Props>()

    const closesLabel = (date?: string | null) => {
        if (!date) {
            return null
        }

        return `Closes ${new Date(date).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
        })}`
    }
</script>

<template>

    <Head title="Surveys — Alfrik">
        <meta name="description"
            content="Share your perspective and take part in Alfrik community surveys, research, and conversations." />
    </Head>

    <!-- =========================================================
         HERO
    ========================================================== -->
    <section class="border-b border-border-light dark:border-border-dark">
        <div class="container mx-auto px-4 pb-12 pt-12 sm:pb-16 sm:pt-16 lg:pb-20 lg:pt-20">
            <div class="max-w-4xl">
                <AppText tag="p" font="redhat" size="xs" weight="bold" tracking="wide" uppercase class="mb-5">
                    Your Voice
                </AppText>

                <AppHeading tag="h1" font="prata" size="5xl" weight="normal" leading="tight">
                    Surveys
                </AppHeading>

                <AppText tag="p" font="lora" size="lg" color="muted" leading="relaxed" class="mt-5 max-w-2xl">
                    Share your perspective and contribute to the research,
                    conversations, and ideas shaping the industries and
                    communities around us.
                </AppText>
            </div>
        </div>
    </section>

    <!-- =========================================================
         SURVEY LIST
    ========================================================== -->
    <section class="container mx-auto px-4 py-10 sm:py-14 lg:py-16">
        <div v-if="surveys.length" class="border-t border-border-light dark:border-border-dark">
            <Link v-for="(survey, index) in surveys" :key="survey.id" :href="surveyShow(survey.slug).url"
                class="group block border-b border-border-light py-8 transition-colors dark:border-border-dark sm:py-10 lg:py-12">
                <article class="
                        grid
                        gap-8
                        lg:grid-cols-[minmax(0,1fr)_220px]
                        lg:items-center
                    ">
                    <!-- Main -->
                    <div class="min-w-0">
                        <!-- Eyebrow -->
                        <div class="mb-4 flex items-center gap-3">
                            <span
                                class="flex size-8 shrink-0 items-center justify-center border border-border-light dark:border-border-dark">
                                <ClipboardList :size="15" :stroke-width="1.6" />
                            </span>

                            <AppText tag="span" size="xs" weight="semibold" tracking="wide" uppercase color="muted">
                                Survey {{ String(index + 1).padStart(2, '0') }}
                            </AppText>

                            <span class="h-px w-8 bg-border-light dark:bg-border-dark" aria-hidden="true" />

                            <AppText tag="span" size="xs" weight="semibold" tracking="wide" uppercase>
                                Open
                            </AppText>
                        </div>

                        <!-- Title -->
                        <AppHeading tag="h2" font="prata" size="3xl" weight="normal" leading="tight"
                            class="max-w-3xl transition-opacity duration-300 group-hover:opacity-70">
                            {{ survey.title }}
                        </AppHeading>

                        <!-- Description -->
                        <AppText v-if="survey.description" tag="p" font="lora" size="sm" color="muted" leading="relaxed"
                            :clamp="3" class="mt-4 max-w-2xl">
                            {{ survey.description }}
                        </AppText>

                        <!-- Meta -->
                        <div class="
                                mt-6
                                flex
                                flex-wrap
                                items-center
                                gap-x-4
                                gap-y-2
                            ">
                            <AppText tag="span" size="xs" color="muted">
                                {{ survey.questions_count ?? 0 }}
                                {{
                                    (survey.questions_count ?? 0) === 1
                                        ? 'question'
                                        : 'questions'
                                }}
                            </AppText>

                            <span class="text-border-light dark:text-border-dark" aria-hidden="true">
                                /
                            </span>

                            <AppText tag="span" size="xs" color="muted">
                                {{ survey.responses_count ?? 0 }}
                                {{
                                    (survey.responses_count ?? 0) === 1
                                        ? 'response'
                                        : 'responses'
                                }}
                            </AppText>

                            <template v-if="survey.ends_at">
                                <span class="text-border-light dark:text-border-dark" aria-hidden="true">
                                    /
                                </span>

                                <AppText tag="span" size="xs" color="muted">
                                    {{ closesLabel(survey.ends_at) }}
                                </AppText>
                            </template>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="
                            flex
                            items-center
                            justify-between
                            border-t
                            border-border-light
                            pt-5
                            dark:border-border-dark
                            lg:block
                            lg:border-t-0
                            lg:pt-0
                        ">
                        <AppText tag="span" size="sm" weight="medium" class="inline-flex items-center gap-2">
                            Take survey

                            <ArrowUpRight :size="15" :stroke-width="1.8" class="
                                    transition-transform
                                    duration-300
                                    group-hover:-translate-y-0.5
                                    group-hover:translate-x-0.5
                                " />
                        </AppText>

                        <AppText tag="span" size="xs" color="muted" class="hidden lg:mt-3 lg:block">
                            Share your perspective
                        </AppText>
                    </div>
                </article>
            </Link>
        </div>

        <!-- =====================================================
             EMPTY STATE
        ====================================================== -->
        <div v-else class="
                border-y
                border-border-light
                py-24
                text-center
                dark:border-border-dark
            ">
            <div
                class="mx-auto mb-5 flex size-12 items-center justify-center border border-border-light dark:border-border-dark">
                <ClipboardList :size="20" :stroke-width="1.5" />
            </div>

            <AppHeading tag="h2" font="prata" size="2xl" weight="normal" align="center">
                No open surveys
            </AppHeading>

            <AppText tag="p" size="sm" color="muted" align="center" class="mx-auto mt-2 max-w-md">
                There are no active surveys at the moment. Check back soon
                for new opportunities to share your perspective.
            </AppText>
        </div>
    </section>
</template>