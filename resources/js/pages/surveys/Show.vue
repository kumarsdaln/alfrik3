<script setup lang="ts">
    import { Head, Form } from '@inertiajs/vue3'
    import { computed } from 'vue'

    import {
        Check,
        CheckCircle2,
        Lock,
    } from '@lucide/vue'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'

    import { submit as surveySubmit } from '@/routes/surveys'
    import Button from '@/components/ui/button/Button.vue'
    import type { Survey, SurveyQuestion } from '@/types'
    import MetaInfo from '@/components/ui/metainfo/MetaInfo.vue'


    interface Props {
        survey: Survey
        isOpen: boolean
        hasResponded: boolean
    }

    const props = defineProps<Props>()

    const questions = computed<SurveyQuestion[]>(() => [
        ...props.survey.unsectioned_questions,
        ...props.survey.sections.flatMap(
            (section) => section.questions,
        ),
    ])

    const requiredCount = computed(() =>
        questions.value.filter((question) => question.required).length,
    )

    function questionNumber(index: number) {
        return String(index + 1).padStart(2, '0')
    }

    function ratingMax(question: SurveyQuestion) {
        return Number(question.settings?.max ?? 5)
    }

    function ratingMin(question: SurveyQuestion) {
        return Number(question.settings?.min ?? 1)
    }

    function scaleMin(question: SurveyQuestion) {
        return Number(question.settings?.min ?? 1)
    }

    function scaleMax(question: SurveyQuestion) {
        return Number(question.settings?.max ?? 10)
    }

    function numberMin(question: SurveyQuestion) {
        return question.settings?.min != null
            ? Number(question.settings.min)
            : undefined
    }

    function numberMax(question: SurveyQuestion) {
        return question.settings?.max != null
            ? Number(question.settings.max)
            : undefined
    }

    function formatClosingDate(date: string | null) {
        if (!date) {
            return null
        }

        return new Date(date).toLocaleDateString('en-US', {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
        })
    }
</script>

<template>
    <!-- Header -->
    <section class="border-b border-border-light dark:border-border-dark">
        <div class="container max-w-5xl py-10">
            <div class="max-w-3xl">
                <div class="mb-5 flex items-center gap-3">
                    <AppText tag="span" size="xs" weight="bold" tracking="wide" uppercase color="brand">
                        Community Survey
                    </AppText>
                </div>

                <AppHeading tag="h1" font="prata" size="4xl" weight="normal" leading="tight" class="sm:text-5xl">
                    {{ survey.title }}
                </AppHeading>

                <AppText v-if="survey.description" tag="p" font="lora" size="lg" color="muted" leading="relaxed"
                    class="mt-4 max-w-2xl">
                    {{ survey.description }}
                </AppText>

                <MetaInfo :items="[
                    {
                        label: 'Questions',
                        value: questions.length,
                    },
                    {
                        label: 'Required',
                        value: requiredCount,
                    },
                    {
                        label: 'Responses',
                        value: survey.response_count,
                    },
                    {
                        label: 'Closes',
                        value: `${formatClosingDate(survey.ends_at)}`
                    }
                ]" class="mt-6" />
            </div>
        </div>
    </section>

    <main class="py-10 pb-20">
        <!-- Already Responded -->
        <section v-if="hasResponded" class="border-y border-border-light py-14 text-center dark:border-border-dark">
            <div
                class="mx-auto mb-5 flex h-11 w-11 items-center justify-center rounded-full bg-primary/10 text-primary">
                <CheckCircle2 :size="22" :stroke-width="1.7" />
            </div>

            <AppHeading tag="h2" font="prata" size="2xl" weight="normal" align="center">
                Thank you for participating.
            </AppHeading>

            <AppText tag="p" color="muted" align="center" class="mx-auto mt-3 max-w-md">
                You have already submitted a response to this survey.
            </AppText>
        </section>

        <!-- Closed -->
        <section v-else-if="!isOpen" class="py-14 text-center">
            <div
                class="mx-auto mb-5 flex h-11 w-11 items-center justify-center rounded-full bg-content-light/5 text-content-lightMuted dark:bg-white/5 dark:text-content-darkMuted">
                <Lock :size="20" :stroke-width="1.7" />
            </div>

            <AppHeading tag="h2" font="prata" size="2xl" weight="normal" align="center">
                This survey is closed.
            </AppHeading>

            <AppText tag="p" color="muted" align="center" class="mt-3">
                Thank you for your interest.
            </AppText>
        </section>

        <!-- Survey -->
        <Form v-else v-slot="{ errors, processing }" :action="surveySubmit(survey.slug).url" method="post">
            <!-- Unsectioned Questions -->
            <section v-if="survey.unsectioned_questions.length" class="space-y-9">
                <div v-for="(question, index) in survey.unsectioned_questions" :key="question.id"
                    class="border-b border-border-light pb-9 last:border-0 dark:border-border-dark">
                    <div class="mb-5">
                        <div class="flex items-start gap-3">
                            <span class="mt-1 text-xs font-semibold text-primary">
                                {{ questionNumber(index) }}
                            </span>

                            <div>
                                <AppHeading tag="h2" font="prata" size="lg" weight="normal" leading="tight">
                                    {{ question.question }}
                                </AppHeading>

                                <AppText v-if="question.description" tag="p" size="sm" color="muted" leading="relaxed"
                                    class="mt-2">
                                    {{ question.description }}
                                </AppText>

                                <AppText v-if="question.required" tag="span" size="xs" color="default"
                                    class="mt-2 inline-block">
                                    Required
                                </AppText>
                            </div>
                        </div>
                    </div>

                    <!-- Short / Long Text -->
                    <textarea v-if="
                        question.type.value === 'short_text' ||
                        question.type.value === 'long_text'
                    " :name="`answers[${question.id}]`" :rows="question.type.value === 'long_text'
                        ? 5
                        : 3
                        " placeholder="Write your answer..."
                        class="w-full resize-y border border-border-light bg-transparent px-4 py-3 text-sm outline-none transition-colors placeholder:text-content-lightMuted focus:border-primary dark:border-border-dark dark:placeholder:text-content-darkMuted" />

                    <!-- Single Choice -->
                    <div v-else-if="
                        question.type.value === 'single_choice'
                    " class="space-y-2">
                        <label v-for="option in question.options" :key="option.id"
                            class="flex cursor-pointer items-center gap-3 border border-border-light px-4 py-3 transition-colors hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-primary/[0.03] dark:border-border-dark">
                            <input :name="`answers[${question.id}]`" type="radio" :value="option.id"
                                class="h-4 w-4 accent-primary" />

                            <span class="text-sm">
                                {{ option.label }}
                            </span>
                        </label>
                    </div>

                    <!-- Multiple Choice -->
                    <div v-else-if="
                        question.type.value === 'multiple_choice'
                    " class="space-y-2">
                        <label v-for="option in question.options" :key="option.id"
                            class="flex cursor-pointer items-center gap-3 border border-border-light px-4 py-3 transition-colors hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-primary/[0.03] dark:border-border-dark">
                            <input :name="`answers[${question.id}][]`" type="checkbox" :value="option.id"
                                class="h-4 w-4 accent-primary" />

                            <span class="text-sm">
                                {{ option.label }}
                            </span>
                        </label>
                    </div>

                    <!-- Yes / No -->
                    <div v-else-if="question.type.value === 'yes_no'" class="flex gap-2">
                        <label
                            class="flex min-w-24 cursor-pointer items-center justify-center border border-border-light px-5 py-3 transition-colors hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-primary/[0.03] dark:border-border-dark">
                            <input :name="`answers[${question.id}]`" type="radio" value="1" class="sr-only" />

                            <span class="text-sm">
                                Yes
                            </span>
                        </label>

                        <label
                            class="flex min-w-24 cursor-pointer items-center justify-center border border-border-light px-5 py-3 transition-colors hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-primary/[0.03] dark:border-border-dark">
                            <input :name="`answers[${question.id}]`" type="radio" value="0" class="sr-only" />

                            <span class="text-sm">
                                No
                            </span>
                        </label>
                    </div>

                    <!-- Number -->
                    <input v-else-if="question.type.value === 'number'" :name="`answers[${question.id}]`" type="number"
                        :min="numberMin(question)" :max="numberMax(question)"
                        class="w-full max-w-sm border border-border-light bg-transparent px-4 py-3 text-sm outline-none focus:border-primary dark:border-border-dark" />

                    <!-- Rating -->
                    <div v-else-if="question.type.value === 'rating'" class="flex flex-wrap gap-2">
                        <label v-for="number in ratingMax(question) -
                            ratingMin(question) +
                            1" :key="number" class="cursor-pointer">
                            <input :name="`answers[${question.id}]`" type="radio" :value="number +
                                ratingMin(question) -
                                1
                                " class="peer sr-only" />

                            <span
                                class="flex h-10 min-w-10 items-center justify-center border border-border-light px-2.5 text-sm transition-colors hover:border-primary peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white dark:border-border-dark">
                                {{
                                    number +
                                    ratingMin(question) -
                                    1
                                }}
                            </span>
                        </label>
                    </div>

                    <!-- Scale -->
                    <div v-else-if="question.type.value === 'scale'" class="space-y-2">
                        <div class="flex flex-wrap gap-1.5">
                            <label v-for="number in scaleMax(question) -
                                scaleMin(question) +
                                1" :key="number" class="cursor-pointer">
                                <input :name="`answers[${question.id}]`" type="radio" :value="number +
                                    scaleMin(question) -
                                    1
                                    " class="peer sr-only" />

                                <span
                                    class="flex h-9 min-w-9 items-center justify-center border border-border-light px-2 text-xs transition-colors hover:border-primary peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white dark:border-border-dark">
                                    {{
                                        number +
                                        scaleMin(question) -
                                        1
                                    }}
                                </span>
                            </label>
                        </div>

                        <div class="flex justify-between">
                            <AppText tag="span" size="xs" color="muted">
                                {{ question.settings?.min_label ?? 'Low' }}
                            </AppText>

                            <AppText tag="span" size="xs" color="muted">
                                {{ question.settings?.max_label ?? 'High' }}
                            </AppText>
                        </div>
                    </div>

                    <!-- Date -->
                    <input v-else-if="question.type.value === 'date'" :name="`answers[${question.id}]`" type="date"
                        class="w-full max-w-sm border border-border-light bg-transparent px-4 py-3 text-sm outline-none focus:border-primary dark:border-border-dark" />

                    <!-- Fallback -->
                    <input v-else :name="`answers[${question.id}]`" type="text"
                        class="w-full border border-border-light bg-transparent px-4 py-3 text-sm outline-none focus:border-primary dark:border-border-dark" />

                    <p v-if="errors[`answers.${question.id}`]" class="mt-2 text-sm text-red-600 dark:text-red-400">
                        {{ errors[`answers.${question.id}`] }}
                    </p>
                </div>
            </section>

            <!-- Sections -->
            <section v-for="(section, sectionIndex) in survey.sections" :key="section.id" class="mt-12">
                <!-- Small Section Heading -->
                <div class="mb-7 border-b border-border-light pb-3 dark:border-border-dark">
                    <div class="flex items-center gap-3">
                        <AppText tag="span" size="xs" weight="bold" uppercase tracking="wide" color="default">
                            Section {{ sectionIndex + 1 }}
                        </AppText>

                        <span class="h-3 w-px bg-border-light dark:bg-border-dark" />

                        <AppText tag="span" size="sm" weight="medium">
                            {{ section.title }}
                        </AppText>
                    </div>

                    <AppText v-if="section.description" tag="p" size="xs" color="muted" class="mt-2 pl-0">
                        {{ section.description }}
                    </AppText>
                </div>

                <div class="space-y-9">
                    <div v-for="(question, questionIndex) in section.questions" :key="question.id"
                        class="border-b border-border-light pb-9 last:border-0 dark:border-border-dark">
                        <div class="mb-5">
                            <div class="flex items-start gap-3">
                                <span class="mt-1 text-xs font-semibold text-primary">
                                    {{
                                        questionNumber(
                                            survey.unsectioned_questions.length +
                                            survey.sections
                                                .slice(0, sectionIndex)
                                                .reduce(
                                                    (total, item) =>
                                                        total +
                                                        item.questions
                                                            .length,
                                                    0,
                                                ) +
                                            questionIndex,
                                        )
                                    }}
                                </span>

                                <div>
                                    <AppHeading tag="h3" font="prata" size="lg" weight="normal" leading="tight">
                                        {{ question.question }}
                                    </AppHeading>

                                    <AppText v-if="question.description" tag="p" size="sm" color="muted"
                                        leading="relaxed" class="mt-2">
                                        {{ question.description }}
                                    </AppText>

                                    <AppText v-if="question.required" tag="span" size="xs" color="default"
                                        class="mt-2 inline-block">
                                        Required
                                    </AppText>
                                </div>
                            </div>
                        </div>

                        <!-- Use the same answer controls here -->
                        <textarea v-if="
                            question.type.value === 'short_text' ||
                            question.type.value === 'long_text'
                        " :name="`answers[${question.id}]`" :rows="question.type.value === 'long_text'
                            ? 5
                            : 3
                            " placeholder="Write your answer..."
                            class="w-full resize-y border border-border-light bg-transparent px-4 py-3 text-sm outline-none placeholder:text-content-lightMuted focus:border-primary dark:border-border-dark" />

                        <div v-else-if="
                            question.type.value === 'single_choice' ||
                            question.type.value === 'multiple_choice'
                        " class="space-y-2">
                            <label v-for="option in question.options" :key="option.id"
                                class="flex cursor-pointer items-center gap-3 border border-border-light px-4 py-3 transition-colors hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-primary/[0.03] dark:border-border-dark">
                                <input :name="question.type.value ===
                                    'multiple_choice'
                                    ? `answers[${question.id}][]`
                                    : `answers[${question.id}]`
                                    " :type="question.type.value ===
                                        'multiple_choice'
                                        ? 'checkbox'
                                        : 'radio'
                                        " :value="option.id" class="h-4 w-4 accent-primary" />

                                <span class="text-sm">
                                    {{ option.label }}
                                </span>
                            </label>
                        </div>

                        <div v-else-if="question.type.value === 'yes_no'" class="flex gap-2">
                            <label v-for="option in [
                                { label: 'Yes', value: '1' },
                                { label: 'No', value: '0' },
                            ]" :key="option.value"
                                class="flex min-w-24 cursor-pointer items-center justify-center border border-border-light px-5 py-3 transition-colors hover:border-primary has-[:checked]:border-primary has-[:checked]:bg-primary/[0.03] dark:border-border-dark">
                                <input :name="`answers[${question.id}]`" type="radio" :value="option.value"
                                    class="sr-only" />

                                <span class="text-sm">
                                    {{ option.label }}
                                </span>
                            </label>
                        </div>

                        <input v-else-if="question.type.value === 'number'" :name="`answers[${question.id}]`"
                            type="number" :min="numberMin(question)" :max="numberMax(question)"
                            class="w-full max-w-sm border border-border-light bg-transparent px-4 py-3 text-sm outline-none focus:border-primary dark:border-border-dark" />

                        <div v-else-if="
                            question.type.value === 'rating' ||
                            question.type.value === 'scale'
                        " class="flex flex-wrap gap-2">
                            <label v-for="number in (
                                question.type.value === 'rating'
                                    ? ratingMax(question) -
                                    ratingMin(question) +
                                    1
                                    : scaleMax(question) -
                                    scaleMin(question) +
                                    1
                            )" :key="number" class="cursor-pointer">
                                <input :name="`answers[${question.id}]`" type="radio" :value="number +
                                    (question.type.value === 'rating'
                                        ? ratingMin(question)
                                        : scaleMin(question)) -
                                    1
                                    " class="peer sr-only" />

                                <span
                                    class="flex h-10 min-w-10 items-center justify-center border border-border-light px-2.5 text-sm transition-colors hover:border-primary peer-checked:border-primary peer-checked:bg-primary peer-checked:text-white dark:border-border-dark">
                                    {{
                                        number +
                                        (question.type.value === 'rating'
                                            ? ratingMin(question)
                                            : scaleMin(question)) -
                                        1
                                    }}
                                </span>
                            </label>
                        </div>

                        <input v-else-if="question.type.value === 'date'" :name="`answers[${question.id}]`" type="date"
                            class="w-full max-w-sm border border-border-light bg-transparent px-4 py-3 text-sm outline-none focus:border-primary dark:border-border-dark" />

                        <input v-else :name="`answers[${question.id}]`" type="text"
                            class="w-full border border-border-light bg-transparent px-4 py-3 text-sm outline-none focus:border-primary dark:border-border-dark" />

                        <p v-if="errors[`answers.${question.id}`]" class="mt-2 text-sm text-red-600 dark:text-red-400">
                            {{ errors[`answers.${question.id}`] }}
                        </p>
                    </div>
                </div>
            </section>

            <!-- Submit -->
            <div class="mt-10 border-t border-border-light pt-7 dark:border-border-dark">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <AppText tag="p" size="xs" color="muted" leading="relaxed" class="max-w-lg">
                        {{
                            survey.anonymous
                                ? 'Your response is collected anonymously.'
                                : 'Your response is associated with your account.'
                        }}
                    </AppText>

                    <Button type="submit" :disabled="processing" class="w-full sm:w-auto">
                        <Check :size="16" :stroke-width="2" />

                        {{
                            processing
                                ? 'Submitting...'
                                : 'Submit response'
                        }}
                    </Button>
                </div>
            </div>
        </Form>
    </main>
</template>