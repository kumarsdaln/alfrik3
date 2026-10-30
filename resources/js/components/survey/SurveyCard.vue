<script setup lang="ts">
    import { Link } from '@inertiajs/vue3'
    import { ArrowUpRight, ClipboardList } from '@lucide/vue'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import { show as surveyShow } from '@/routes/surveys'
    import { Survey } from '@/types'

    defineProps<{
        survey: Survey
        index: number
    }>()

    const closesLabel = (date: string): string => {
        const endDate = new Date(date)

        if (Number.isNaN(endDate.getTime())) {
            return ''
        }

        return `Closes ${endDate.toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
        })}`
    }
</script>

<template>
    <Link :href="surveyShow(survey.slug).url"
        class="group block border-b border-border-light py-8 transition-colors dark:border-border-dark sm:py-10 lg:py-12">
        <article class="grid gap-8 lg:grid-cols-[minmax(0,1fr)_220px] lg:items-center">
            <!-- Survey information -->
            <div class="min-w-0">
                <div class="mb-4 flex items-center gap-3">
                    <span
                        class="flex size-8 shrink-0 items-center justify-center border border-border-light dark:border-border-dark"
                        aria-hidden="true">
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

                <AppHeading tag="h2" font="prata" size="3xl" weight="normal" leading="tight"
                    class="max-w-3xl transition-opacity duration-300 group-hover:opacity-70">
                    {{ survey.title }}
                </AppHeading>

                <AppText v-if="survey.description" tag="p" font="lora" size="sm" color="muted" leading="relaxed"
                    :clamp="3" class="mt-4 max-w-2xl">
                    {{ survey.description }}
                </AppText>

                <!-- Survey statistics -->
                <div class="mt-6 flex flex-wrap items-center gap-x-4 gap-y-2">
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
                        {{ survey.response_count }}
                        {{
                            survey.response_count === 1
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

            <!-- Call to action -->
            <div
                class="flex items-center justify-between border-t border-border-light pt-5 dark:border-border-dark lg:block lg:border-t-0 lg:pt-0">
                <AppText tag="span" size="sm" weight="medium" class="inline-flex items-center gap-2">
                    Take survey

                    <ArrowUpRight :size="15" :stroke-width="1.8"
                        class="transition-transform duration-300 group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
                </AppText>

                <AppText tag="span" size="xs" color="muted" class="hidden lg:mt-3 lg:block">
                    Share your perspective
                </AppText>
            </div>
        </article>
    </Link>
</template>
