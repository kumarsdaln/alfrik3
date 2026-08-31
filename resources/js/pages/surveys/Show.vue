<script setup lang="ts">
    import { Head, useForm } from '@inertiajs/vue3'
    import { reactive } from 'vue'
    import { Check, ChevronRight, CircleCheck, Lock } from '@lucide/vue'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppButton from '@/components/ui/AppButton.vue'

    import {
        submit as surveySubmit,
        results as surveyResults,
    } from '@/routes/surveys'

    import type { Survey, SurveyQuestion } from '@/types'
    import { Button } from '@/components/ui/button'

    const props = defineProps<{
        survey: Survey
        isOpen: boolean
        hasResponded: boolean
    }>()

    /*
    |--------------------------------------------------------------------------
    | Answers
    |--------------------------------------------------------------------------
    */

    const answers = reactive<Record<number, unknown>>({})

    for (const question of props.survey.questions ?? []) {
        if (question.id == null) {
            continue
        }

        answers[question.id] =
            question.type === 'multiple_choice'
                ? []
                : ''
    }

    /*
    |--------------------------------------------------------------------------
    | Form
    |--------------------------------------------------------------------------
    */

    const form = useForm({
        answers,
    })

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    function ratingMax(question: SurveyQuestion) {
        return Number(question.settings?.max ?? 5)
    }

    function toggleMulti(questionId: number, optionId: number) {
        const selected = answers[questionId] as number[]

        const index = selected.indexOf(optionId)

        if (index === -1) {
            selected.push(optionId)
        } else {
            selected.splice(index, 1)
        }
    }

    function isSelected(questionId: number, optionId: number) {
        const selected = answers[questionId] as number[]

        return selected?.includes(optionId) ?? false
    }

    function submit() {
        form
            .transform(() => ({
                answers,
            }))
            .post(surveySubmit(props.survey.slug).url, {
                preserveScroll: true,
            })
    }
</script>

<template>

    <Head :title="survey.title">
        <meta name="description" :content="survey.description ?? ''" />
    </Head>

    <!-- Header -->
    <section class="
            border-b
            border-border-light
            dark:border-border-dark
        ">
        <div class="
                container
                mx-auto
                max-w-4xl
                px-4
                pb-10
                pt-10
                sm:pb-14
                sm:pt-14
            ">
            <!-- Eyebrow -->
            <AppText tag="p" size="xs" weight="bold" tracking="wide" uppercase color="primary" class="mb-5">
                Community Survey
            </AppText>

            <!-- Title -->
            <AppHeading tag="h1" font="prata" size="4xl" weight="normal" leading="tight">
                {{ survey.title }}
            </AppHeading>

            <!-- Description -->
            <AppText v-if="survey.description" tag="p" font="lora" size="lg" color="muted" leading="relaxed"
                class="mt-5 max-w-3xl">
                {{ survey.description }}
            </AppText>

            <!-- Survey meta -->
            <div class="
                    mt-6
                    flex
                    flex-wrap
                    items-center
                    gap-x-4
                    gap-y-2
                ">
                <AppText tag="span" size="xs" color="muted">
                    {{ survey.questions?.length ?? 0 }}
                    {{
                        (survey.questions?.length ?? 0) === 1
                            ? 'question'
                            : 'questions'
                    }}
                </AppText>

                <span class="text-border-light dark:text-border-dark" aria-hidden="true">
                    /
                </span>

                <AppText tag="span" size="xs" color="muted">
                    Your responses are anonymous
                </AppText>
            </div>
        </div>
    </section>

    <!-- Content -->
    <main class="container mx-auto max-w-4xl px-4 py-10 pb-20 sm:py-14">
        <!-- Already responded -->
        <section v-if="hasResponded" class="
                border-y
                border-border-light
                py-16
                text-center
                dark:border-border-dark
            ">
            <div class="
                    mx-auto
                    mb-5
                    flex
                    h-12
                    w-12
                    items-center
                    justify-center
                    rounded-full
                    bg-primary/10
                    text-primary
                ">
                <CircleCheck :size="24" :stroke-width="1.7" />
            </div>

            <AppHeading tag="h2" font="prata" size="2xl" weight="normal" align="center">
                Thank you for participating.
            </AppHeading>

            <AppText tag="p" color="muted" align="center" class="mx-auto mt-3 max-w-md">
                Your response has already been recorded for this survey.
            </AppText>

            <AppButton v-if="survey.show_results" :href="surveyResults(survey.slug).url" variant="outline" class="mt-7">
                View results
            </AppButton>
        </section>

        <!-- Closed -->
        <section v-else-if="!isOpen" class="
                border-y
                border-border-light
                py-16
                text-center
                dark:border-border-dark
            ">
            <div class="
                    mx-auto
                    mb-5
                    flex
                    h-12
                    w-12
                    items-center
                    justify-center
                    rounded-full
                    bg-content-light/5
                    text-content-lightMuted
                    dark:bg-white/5
                    dark:text-content-darkMuted
                ">
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
        <form v-else @submit.prevent="submit">
            <div class="
                    divide-y
                    divide-border-light
                    dark:divide-border-dark
                ">
                <section v-for="(question, index) in survey.questions" :key="question.id"
                    class="py-9 first:pt-0 last:pb-0">
                    <!-- Question -->
                    <div class="mb-6">
                        <div class="mb-3 flex items-center gap-3">
                            <span class="
                                    flex
                                    h-7
                                    w-7
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-full
                                    bg-primary/10
                                    font-redhat
                                    text-xs
                                    font-semibold
                                    text-primary
                                ">
                                {{ index + 1 }}
                            </span>

                            <AppText tag="span" size="xs" weight="semibold" tracking="wide" uppercase color="muted">
                                Question
                            </AppText>

                            <span v-if="question.required" class="text-xs text-red-500">
                                Required
                            </span>
                        </div>

                        <AppHeading tag="h2" font="lora" size="lg" weight="medium" leading="relaxed">
                            {{ question.question }}
                        </AppHeading>
                    </div>

                    <!-- Single choice -->
                    <div v-if="question.type === 'single_choice'" class="space-y-2">
                        <label v-for="option in question.options" :key="option.id" class="
                                group
                                flex
                                cursor-pointer
                                items-center
                                gap-4
                                border
                                border-border-light
                                px-4
                                py-3.5
                                transition-colors
                                duration-200
                                hover:border-primary
                                dark:border-border-dark
                            " :class="{
                                'border-primary bg-primary/5':
                                    answers[question.id!] === option.id,
                            }">
                            <input v-model="answers[question.id!]" type="radio" :name="`q${question.id}`"
                                :value="option.id" class="
                                    h-4
                                    w-4
                                    border-border-light
                                    text-primary
                                    focus:ring-primary
                                " />

                            <AppText tag="span" font="lora" size="sm">
                                {{ option.label }}
                            </AppText>
                        </label>
                    </div>

                    <!-- Multiple choice -->
                    <div v-else-if="question.type === 'multiple_choice'" class="space-y-2">
                        <label v-for="option in question.options" :key="option.id" class="
                                group
                                flex
                                cursor-pointer
                                items-center
                                gap-4
                                border
                                border-border-light
                                px-4
                                py-3.5
                                transition-colors
                                duration-200
                                hover:border-primary
                                dark:border-border-dark
                            " :class="{
                                'border-primary bg-primary/5':
                                    isSelected(
                                        question.id!,
                                        option.id!,
                                    ),
                            }">
                            <input type="checkbox" :checked="isSelected(
                                question.id!,
                                option.id!,
                            )
                                " @change="
                                    toggleMulti(
                                        question.id!,
                                        option.id!,
                                    )
                                    " class="
                                    h-4
                                    w-4
                                    rounded
                                    border-border-light
                                    text-primary
                                    focus:ring-primary
                                " />

                            <AppText tag="span" font="lora" size="sm">
                                {{ option.label }}
                            </AppText>
                        </label>
                    </div>

                    <!-- Rating -->
                    <div v-else-if="question.type === 'rating'" class="flex flex-wrap gap-2">
                        <button v-for="number in ratingMax(question)" :key="number" type="button" class="
                                flex
                                h-11
                                w-11
                                items-center
                                justify-center
                                border
                                font-redhat
                                text-sm
                                font-semibold
                                transition-colors
                                duration-200
                            " :class="Number(answers[question.id!]) === number
                                    ? 'border-primary bg-primary text-white'
                                    : 'border-border-light text-content-lightMuted hover:border-primary hover:text-primary dark:border-border-dark dark:text-content-darkMuted'
                                " @click="
                                answers[question.id!] =
                                number
                                ">
                            {{ number }}
                        </button>
                    </div>

                    <!-- Text -->
                    <textarea v-else v-model="answers[question.id!]" rows="5" placeholder="Write your answer..." class="
                            w-full
                            resize-y
                            border
                            border-border-light
                            bg-transparent
                            px-4
                            py-3
                            font-lora
                            text-sm
                            leading-relaxed
                            text-content-light
                            outline-none
                            transition-colors
                            placeholder:text-content-lightMuted
                            focus:border-primary
                            focus:ring-0
                            dark:border-border-dark
                            dark:text-content-dark
                            dark:placeholder:text-content-darkMuted
                        " />

                    <!-- Error -->
                    <AppText v-if="
                        form.errors[
                        `answers.${question.id}` as keyof typeof form.errors
                        ]
                    " tag="p" size="sm" color="danger" class="mt-2">
                        {{
                            form.errors[
                            `answers.${question.id}` as keyof typeof form.errors
                            ]
                        }}
                    </AppText>
                </section>
            </div>

            <!-- Submit -->
            <div class="
                    mt-10
                    flex
                    flex-col
                    gap-4
                    border-t
                    border-border-light
                    pt-7
                    dark:border-border-dark
                    sm:flex-row
                    sm:items-center
                    sm:justify-between
                ">
                <AppText tag="p" size="xs" color="muted">
                    Please review your answers before submitting.
                </AppText>

                <Button type="submit" :loading="form.processing" :disabled="form.processing" class="w-full sm:w-auto">
                    {{ form.processing ? 'Submitting…' : 'Submit Response' }}
                </Button>
            </div>
        </form>
    </main>
</template>