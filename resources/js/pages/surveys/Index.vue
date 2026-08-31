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

    const props = defineProps<{
        surveys: SurveyItem[]
    }>()

    const closesLabel = (date?: string | null) => {
        if (!date) {
            return ''
        }

        return `Closes ${new Date(date).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
        })}`
    }

    console.log(props.surveys)
</script>

<template>

    <Head title="Surveys — Alfrik">
        <meta name="description" content="Share your voice and take part in Alfrik community surveys." />
    </Head>

    <!-- Header -->
    <section class="border-b border-border-light dark:border-border-dark">
        <div class="container mx-auto px-4 pb-10 pt-10 sm:pb-14 sm:pt-14 lg:pb-16">
            <div class="max-w-3xl">
                <AppText tag="p" font="redhat" size="xs" weight="bold" tracking="wide" uppercase class="mb-5">
                    Your Voice
                </AppText>

                <AppHeading tag="h1" font="prata" size="5xl" weight="normal" leading="tight">
                    Surveys
                </AppHeading>

                <AppText tag="p" font="lora" size="lg" color="muted" leading="relaxed" class="mt-5 max-w-2xl">
                    Share your perspective and help shape the conversations,
                    research, and ideas that matter.
                </AppText>
            </div>
        </div>
    </section>

    <!-- Surveys -->
    <section class="container mx-auto px-4 py-10 pb-20 sm:py-14">
        <div v-if="surveys.data.length" class="divide-y divide-border-light dark:divide-border-dark">
            <Link v-for="survey in surveys.data" :key="survey.id" :href="surveyShow(survey.slug).url"
                class="group block py-7 first:pt-0 last:pb-0">
                <article class="
                        grid
                        gap-6
                        lg:grid-cols-[1fr_auto]
                        lg:items-center
                    ">
                    <!-- Main content -->
                    <div class="min-w-0">
                        <div class="mb-3 flex items-center gap-2">
                            <ClipboardList :size="14" :stroke-width="1.7" />

                            <AppText tag="span" size="xs" weight="semibold" tracking="wide" uppercase>
                                Open Survey
                            </AppText>
                        </div>

                        <AppHeading tag="h2" font="prata" size="2xl" weight="normal" leading="tight">
                            {{ survey.title }}
                        </AppHeading>

                        <AppText v-if="survey.description" tag="p" font="lora" size="sm" color="muted" leading="relaxed"
                            :clamp="2" class="mt-3 max-w-2xl">
                            {{ survey.description }}
                        </AppText>

                        <!-- Meta -->
                        <div class="
                                mt-4
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

                            <template v-if="survey.closes_at">
                                <span class="text-border-light dark:text-border-dark" aria-hidden="true">
                                    /
                                </span>

                                <AppText tag="span" size="xs" color="muted">
                                    {{ closesLabel(survey.closes_at) }}
                                </AppText>
                            </template>
                        </div>
                    </div>

                    <!-- CTA -->
                    <div class="
                            inline-flex
                            items-center
                            gap-2
                            self-start
                            border-b
                            border-content-light
                            pb-1.5
                            text-content-light
                            transition-colors
                            duration-300
                            dark:border-content-dark
                            dark:text-content-dark
                            lg:self-center
                        ">
                        <AppText tag="span" size="sm" weight="medium">
                            Take survey
                        </AppText>

                        <ArrowUpRight :size="15" :stroke-width="1.8" class="
                                transition-transform
                                duration-300
                                group-hover:-translate-y-0.5
                                group-hover:translate-x-0.5
                            " />
                    </div>
                </article>
            </Link>
        </div>

        <!-- Empty -->
        <div v-else class="
                border-y
                border-border-light
                py-24
                text-center
                dark:border-border-dark
            ">
            <AppText tag="p" font="lora" size="xl" color="muted" align="center" class="italic">
                No open surveys right now.
            </AppText>

            <AppText tag="p" size="sm" color="muted" align="center" class="mt-2">
                Check back soon for new surveys.
            </AppText>
        </div>
    </section>
</template>