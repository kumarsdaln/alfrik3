<script setup lang="ts">
import { Head, useForm } from '@inertiajs/vue3'
import { computed, reactive } from 'vue'
import {
    CalendarDays,
    Check,
    CheckCircle2,
    ChevronLeft,
    ChevronRight,
    CircleHelp,
    Lock,
    Minus,
    Plus,
} from '@lucide/vue'

import AppButton from '@/components/ui/AppButton.vue'
import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'

import { submit as surveySubmit } from '@/routes/surveys'
import type { Survey, SurveyQuestion } from '@/types'

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

    switch (question.type) {
        case 'multiple_choice':
            answers[question.id] = []
            break

        case 'yes_no':
            answers[question.id] = null
            break

        case 'number':
        case 'rating':
        case 'scale':
            answers[question.id] = null
            break

        default:
            answers[question.id] = ''
    }
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
| Computed
|--------------------------------------------------------------------------
*/

const questions = computed(() => props.survey.questions ?? [])

const answeredCount = computed(() => {
    return questions.value.filter((question) => {
        const answer = answers[question.id!]

        if (Array.isArray(answer)) {
            return answer.length > 0
        }

        return answer !== null && answer !== undefined && answer !== ''
    }).length
})

const progress = computed(() => {
    if (!questions.value.length) {
        return 0
    }

    return Math.round(
        (answeredCount.value / questions.value.length) * 100,
    )
})

const requiredCount = computed(() => {
    return questions.value.filter((question) => question.required).length
})

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function questionError(questionId: number) {
    return form.errors[
        `answers.${questionId}` as keyof typeof form.errors
    ]
}

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

function isSelected(questionId: number, optionId: number) {
    const selected = answers[questionId]

    if (!Array.isArray(selected)) {
        return false
    }

    return selected.includes(optionId)
}

function toggleMulti(questionId: number, optionId: number) {
    const selected = answers[questionId]

    if (!Array.isArray(selected)) {
        answers[questionId] = [optionId]
        return
    }

    const index = selected.indexOf(optionId)

    if (index === -1) {
        selected.push(optionId)
    } else {
        selected.splice(index, 1)
    }
}

function setAnswer(questionId: number, value: unknown) {
    answers[questionId] = value
}

function formatClosingDate(date?: string | null) {
    if (!date) {
        return null
    }

    return new Date(date).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    })
}

/*
|--------------------------------------------------------------------------
| Submit
|--------------------------------------------------------------------------
*/

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
        <meta
            name="description"
            :content="survey.description ?? ''"
        />
    </Head>

    <!-- ============================================================
         HERO
    ============================================================= -->

    <section class="border-b border-border-light dark:border-border-dark">
        <div
            class="
                container
                mx-auto
                max-w-5xl
                px-4
                pb-10
                pt-10
                sm:px-6
                sm:pb-14
                sm:pt-14
                lg:px-8
            "
        >
            <div class="max-w-3xl">
                <div class="mb-6 flex items-center gap-3">
                    <span
                        class="
                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center
                            border
                            border-primary/20
                            bg-primary/5
                            text-primary
                        "
                    >
                        <CircleHelp
                            :size="18"
                            :stroke-width="1.7"
                        />
                    </span>

                    <AppText
                        tag="span"
                        size="xs"
                        weight="bold"
                        tracking="wide"
                        uppercase
                        color="primary"
                    >
                        Community Survey
                    </AppText>
                </div>

                <AppHeading
                    tag="h1"
                    font="prata"
                    size="4xl"
                    weight="normal"
                    leading="tight"
                    class="sm:text-5xl"
                >
                    {{ survey.title }}
                </AppHeading>

                <AppText
                    v-if="survey.description"
                    tag="p"
                    font="lora"
                    size="lg"
                    color="muted"
                    leading="relaxed"
                    class="mt-5 max-w-3xl"
                >
                    {{ survey.description }}
                </AppText>

                <!-- Survey metadata -->
                <div
                    class="
                        mt-7
                        flex
                        flex-wrap
                        items-center
                        gap-x-5
                        gap-y-3
                    "
                >
                    <div class="flex items-center gap-2">
                        <AppText
                            tag="span"
                            size="xs"
                            weight="semibold"
                            color="muted"
                        >
                            {{ questions.length }}
                            {{ questions.length === 1 ? 'question' : 'questions' }}
                        </AppText>
                    </div>

                    <span
                        class="text-border-light dark:text-border-dark"
                        aria-hidden="true"
                    >
                        /
                    </span>

                    <AppText
                        tag="span"
                        size="xs"
                        color="muted"
                    >
                        Anonymous responses
                    </AppText>

                    <template v-if="survey.ends_at">
                        <span
                            class="text-border-light dark:text-border-dark"
                            aria-hidden="true"
                        >
                            /
                        </span>

                        <div class="flex items-center gap-2">
                            <CalendarDays
                                :size="14"
                                :stroke-width="1.7"
                                class="text-content-lightMuted dark:text-content-darkMuted"
                            />

                            <AppText
                                tag="span"
                                size="xs"
                                color="muted"
                            >
                                Closes {{ formatClosingDate(survey.ends_at) }}
                            </AppText>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         MAIN
    ============================================================= -->

    <main
        class="
            container
            mx-auto
            max-w-5xl
            px-4
            py-8
            pb-20
            sm:px-6
            sm:py-12
            lg:px-8
        "
    >
        <!-- ========================================================
             ALREADY RESPONDED
        ========================================================= -->

        <section
            v-if="hasResponded"
            class="
                border
                border-border-light
                px-6
                py-16
                text-center
                dark:border-border-dark
                sm:px-10
            "
        >
            <div
                class="
                    mx-auto
                    mb-6
                    flex
                    h-14
                    w-14
                    items-center
                    justify-center
                    border
                    border-primary/20
                    bg-primary/5
                    text-primary
                "
            >
                <CheckCircle2
                    :size="26"
                    :stroke-width="1.6"
                />
            </div>

            <AppHeading
                tag="h2"
                font="prata"
                size="2xl"
                weight="normal"
                align="center"
            >
                Thank you for participating.
            </AppHeading>

            <AppText
                tag="p"
                color="muted"
                align="center"
                class="mx-auto mt-3 max-w-md"
            >
                Your response has already been recorded for this survey.
            </AppText>
        </section>

        <!-- ========================================================
             CLOSED
        ========================================================= -->

        <section
            v-else-if="!isOpen"
            class="
                border
                border-border-light
                px-6
                py-16
                text-center
                dark:border-border-dark
                sm:px-10
            "
        >
            <div
                class="
                    mx-auto
                    mb-6
                    flex
                    h-14
                    w-14
                    items-center
                    justify-center
                    border
                    border-border-light
                    text-content-lightMuted
                    dark:border-border-dark
                    dark:text-content-darkMuted
                "
            >
                <Lock
                    :size="22"
                    :stroke-width="1.7"
                />
            </div>

            <AppHeading
                tag="h2"
                font="prata"
                size="2xl"
                weight="normal"
                align="center"
            >
                This survey is closed.
            </AppHeading>

            <AppText
                tag="p"
                color="muted"
                align="center"
                class="mx-auto mt-3 max-w-md"
            >
                Thank you for your interest in taking part.
            </AppText>

            <AppText
                v-if="survey.ends_at"
                tag="p"
                size="xs"
                color="muted"
                align="center"
                class="mt-5"
            >
                This survey closed on
                {{ formatClosingDate(survey.ends_at) }}.
            </AppText>
        </section>

        <!-- ========================================================
             SURVEY FORM
        ========================================================= -->

        <form
            v-else
            @submit.prevent="submit"
        >
            <!-- Progress -->
            <div
                class="
                    mb-10
                    border-y
                    border-border-light
                    py-4
                    dark:border-border-dark
                "
            >
                <div
                    class="
                        flex
                        flex-col
                        gap-3
                        sm:flex-row
                        sm:items-center
                        sm:justify-between
                    "
                >
                    <div class="flex items-center gap-3">
                        <AppText
                            tag="span"
                            size="xs"
                            weight="semibold"
                            tracking="wide"
                            uppercase
                            color="muted"
                        >
                            Your progress
                        </AppText>

                        <span
                            class="
                                h-1
                                w-1
                                rounded-full
                                bg-content-lightMuted
                                dark:bg-content-darkMuted
                            "
                        />

                        <AppText
                            tag="span"
                            size="xs"
                            color="muted"
                        >
                            {{ answeredCount }} of {{ questions.length }}
                            answered
                        </AppText>
                    </div>

                    <AppText
                        tag="span"
                        size="xs"
                        weight="semibold"
                        color="primary"
                    >
                        {{ progress }}%
                    </AppText>
                </div>

                <div
                    class="
                        mt-3
                        h-1
                        w-full
                        overflow-hidden
                        bg-content-light/10
                        dark:bg-white/10
                    "
                >
                    <div
                        class="h-full bg-primary transition-all duration-300"
                        :style="{ width: `${progress}%` }"
                    />
                </div>
            </div>

            <!-- Questions -->
            <div class="space-y-8">
                <section
                    v-for="(question, index) in questions"
                    :key="question.id"
                    class="
                        border
                        border-border-light
                        bg-background
                        dark:border-border-dark
                    "
                >
                    <div class="p-5 sm:p-7">
                        <!-- Question header -->
                        <div
                            class="
                                mb-6
                                flex
                                items-start
                                gap-4
                            "
                        >
                            <span
                                class="
                                    flex
                                    h-9
                                    w-9
                                    shrink-0
                                    items-center
                                    justify-center
                                    border
                                    border-primary/20
                                    bg-primary/5
                                    font-redhat
                                    text-xs
                                    font-semibold
                                    text-primary
                                "
                            >
                                {{ questionNumber(index) }}
                            </span>

                            <div class="min-w-0 flex-1">
                                <div
                                    class="
                                        mb-2
                                        flex
                                        flex-wrap
                                        items-center
                                        gap-x-3
                                        gap-y-1
                                    "
                                >
                                    <AppText
                                        tag="span"
                                        size="xs"
                                        weight="semibold"
                                        tracking="wide"
                                        uppercase
                                        color="muted"
                                    >
                                        Question
                                    </AppText>

                                    <span
                                        v-if="question.required"
                                        class="
                                            text-[11px]
                                            font-medium
                                            text-red-500
                                        "
                                    >
                                        Required
                                    </span>
                                </div>

                                <AppHeading
                                    tag="h2"
                                    font="lora"
                                    size="lg"
                                    weight="medium"
                                    leading="relaxed"
                                >
                                    {{ question.question }}
                                </AppHeading>

                                <AppText
                                    v-if="question.description"
                                    tag="p"
                                    size="sm"
                                    color="muted"
                                    leading="relaxed"
                                    class="mt-2"
                                >
                                    {{ question.description }}
                                </AppText>
                            </div>
                        </div>

                        <!-- =================================================
                             SHORT TEXT
                        ================================================== -->

                        <input
                            v-if="question.type === 'short_text'"
                            v-model="answers[question.id!]"
                            type="text"
                            :name="`q${question.id}`"
                            placeholder="Write your answer..."
                            class="
                                w-full
                                border
                                border-border-light
                                bg-transparent
                                px-4
                                py-3
                                font-lora
                                text-sm
                                leading-relaxed
                                outline-none
                                transition-colors
                                placeholder:text-content-lightMuted
                                focus:border-primary
                                dark:border-border-dark
                                dark:placeholder:text-content-darkMuted
                            "
                        />

                        <!-- =================================================
                             LONG TEXT
                        ================================================== -->

                        <textarea
                            v-else-if="question.type === 'long_text'"
                            v-model="answers[question.id!]"
                            :name="`q${question.id}`"
                            rows="6"
                            placeholder="Write your answer..."
                            class="
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
                                outline-none
                                transition-colors
                                placeholder:text-content-lightMuted
                                focus:border-primary
                                dark:border-border-dark
                                dark:placeholder:text-content-darkMuted
                            "
                        />

                        <!-- =================================================
                             SINGLE CHOICE
                        ================================================== -->

                        <div
                            v-else-if="question.type === 'single_choice'"
                            class="space-y-2"
                        >
                            <label
                                v-for="option in question.options"
                                :key="option.id"
                                class="
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
                                    hover:border-primary
                                    dark:border-border-dark
                                "
                                :class="{
                                    'border-primary bg-primary/5':
                                        answers[question.id!] === option.id,
                                }"
                            >
                                <input
                                    v-model="answers[question.id!]"
                                    type="radio"
                                    :name="`q${question.id}`"
                                    :value="option.id"
                                    class="
                                        h-4
                                        w-4
                                        border-border-light
                                        text-primary
                                        focus:ring-primary
                                    "
                                />

                                <AppText
                                    tag="span"
                                    font="lora"
                                    size="sm"
                                >
                                    {{ option.label }}
                                </AppText>
                            </label>
                        </div>

                        <!-- =================================================
                             MULTIPLE CHOICE
                        ================================================== -->

                        <div
                            v-else-if="question.type === 'multiple_choice'"
                            class="space-y-2"
                        >
                            <label
                                v-for="option in question.options"
                                :key="option.id"
                                class="
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
                                    hover:border-primary
                                    dark:border-border-dark
                                "
                                :class="{
                                    'border-primary bg-primary/5':
                                        isSelected(
                                            question.id!,
                                            option.id!,
                                        ),
                                }"
                            >
                                <input
                                    type="checkbox"
                                    :checked="
                                        isSelected(
                                            question.id!,
                                            option.id!,
                                        )
                                    "
                                    class="
                                        h-4
                                        w-4
                                        rounded
                                        border-border-light
                                        text-primary
                                        focus:ring-primary
                                    "
                                    @change="
                                        toggleMulti(
                                            question.id!,
                                            option.id!,
                                        )
                                    "
                                />

                                <AppText
                                    tag="span"
                                    font="lora"
                                    size="sm"
                                >
                                    {{ option.label }}
                                </AppText>
                            </label>
                        </div>

                        <!-- =================================================
                             YES / NO
                        ================================================== -->

                        <div
                            v-else-if="question.type === 'yes_no'"
                            class="grid gap-2 sm:grid-cols-2"
                        >
                            <button
                                type="button"
                                class="
                                    flex
                                    items-center
                                    justify-center
                                    gap-2
                                    border
                                    px-5
                                    py-4
                                    font-redhat
                                    text-sm
                                    font-medium
                                    transition-colors
                                "
                                :class="
                                    answers[question.id!] === true
                                        ? 'border-primary bg-primary text-white'
                                        : 'border-border-light hover:border-primary dark:border-border-dark'
                                "
                                @click="
                                    setAnswer(
                                        question.id!,
                                        true,
                                    )
                                "
                            >
                                <Check :size="16" />
                                Yes
                            </button>

                            <button
                                type="button"
                                class="
                                    flex
                                    items-center
                                    justify-center
                                    gap-2
                                    border
                                    px-5
                                    py-4
                                    font-redhat
                                    text-sm
                                    font-medium
                                    transition-colors
                                "
                                :class="
                                    answers[question.id!] === false
                                        ? 'border-primary bg-primary text-white'
                                        : 'border-border-light hover:border-primary dark:border-border-dark'
                                "
                                @click="
                                    setAnswer(
                                        question.id!,
                                        false,
                                    )
                                "
                            >
                                <Minus :size="16" />
                                No
                            </button>
                        </div>

                        <!-- =================================================
                             NUMBER
                        ================================================== -->

                        <div
                            v-else-if="question.type === 'number'"
                            class="max-w-sm"
                        >
                            <input
                                v-model.number="answers[question.id!]"
                                type="number"
                                :min="numberMin(question)"
                                :max="numberMax(question)"
                                :name="`q${question.id}`"
                                placeholder="Enter a number"
                                class="
                                    w-full
                                    border
                                    border-border-light
                                    bg-transparent
                                    px-4
                                    py-3
                                    font-redhat
                                    text-sm
                                    outline-none
                                    transition-colors
                                    focus:border-primary
                                    dark:border-border-dark
                                "
                            />
                        </div>

                        <!-- =================================================
                             RATING
                        ================================================== -->

                        <div
                            v-else-if="question.type === 'rating'"
                            class="flex flex-wrap gap-2"
                        >
                            <button
                                v-for="number in ratingMax(question) -
                                ratingMin(question) +
                                1"
                                :key="number"
                                type="button"
                                class="
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
                                "
                                :class="
                                    Number(answers[question.id!]) ===
                                    number + ratingMin(question) - 1
                                        ? 'border-primary bg-primary text-white'
                                        : 'border-border-light hover:border-primary hover:text-primary dark:border-border-dark'
                                "
                                @click="
                                    setAnswer(
                                        question.id!,
                                        number +
                                            ratingMin(question) -
                                            1,
                                    )
                                "
                            >
                                {{
                                    number +
                                    ratingMin(question) -
                                    1
                                }}
                            </button>
                        </div>

                        <!-- =================================================
                             SCALE
                        ================================================== -->

                        <div
                            v-else-if="question.type === 'scale'"
                            class="space-y-4"
                        >
                            <div
                                class="
                                    flex
                                    flex-wrap
                                    gap-2
                                "
                            >
                                <button
                                    v-for="number in scaleMax(question) -
                                    scaleMin(question) +
                                    1"
                                    :key="number"
                                    type="button"
                                    class="
                                        flex
                                        h-11
                                        min-w-11
                                        items-center
                                        justify-center
                                        border
                                        px-3
                                        font-redhat
                                        text-sm
                                        font-semibold
                                        transition-colors
                                    "
                                    :class="
                                        Number(
                                            answers[question.id!],
                                        ) ===
                                        number +
                                            scaleMin(question) -
                                            1
                                            ? 'border-primary bg-primary text-white'
                                            : 'border-border-light hover:border-primary hover:text-primary dark:border-border-dark'
                                    "
                                    @click="
                                        setAnswer(
                                            question.id!,
                                            number +
                                                scaleMin(
                                                    question,
                                                ) -
                                                1,
                                        )
                                    "
                                >
                                    {{
                                        number +
                                        scaleMin(question) -
                                        1
                                    }}
                                </button>
                            </div>

                            <div
                                class="
                                    flex
                                    justify-between
                                    text-xs
                                    text-content-lightMuted
                                    dark:text-content-darkMuted
                                "
                            >
                                <span>
                                    {{ scaleMin(question) }}
                                </span>

                                <span>
                                    {{ scaleMax(question) }}
                                </span>
                            </div>
                        </div>

                        <!-- =================================================
                             DATE
                        ================================================== -->

                        <div
                            v-else-if="question.type === 'date'"
                            class="max-w-sm"
                        >
                            <input
                                v-model="answers[question.id!]"
                                type="date"
                                :name="`q${question.id}`"
                                class="
                                    w-full
                                    border
                                    border-border-light
                                    bg-transparent
                                    px-4
                                    py-3
                                    font-redhat
                                    text-sm
                                    outline-none
                                    transition-colors
                                    focus:border-primary
                                    dark:border-border-dark
                                "
                            />
                        </div>

                        <!-- =================================================
                             FALLBACK
                        ================================================== -->

                        <textarea
                            v-else
                            v-model="answers[question.id!]"
                            :name="`q${question.id}`"
                            rows="5"
                            placeholder="Write your answer..."
                            class="
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
                                outline-none
                                transition-colors
                                placeholder:text-content-lightMuted
                                focus:border-primary
                                dark:border-border-dark
                                dark:placeholder:text-content-darkMuted
                            "
                        />

                        <!-- Error -->
                        <AppText
                            v-if="questionError(question.id!)"
                            tag="p"
                            size="sm"
                            color="danger"
                            class="mt-3"
                        >
                            {{ questionError(question.id!) }}
                        </AppText>
                    </div>
                </section>
            </div>

            <!-- ========================================================
                 SUBMIT AREA
            ========================================================= -->

            <div
                class="
                    mt-10
                    border-t
                    border-border-light
                    pt-7
                    dark:border-border-dark
                "
            >
                <div
                    class="
                        flex
                        flex-col
                        gap-5
                        sm:flex-row
                        sm:items-center
                        sm:justify-between
                    "
                >
                    <div>
                        <AppText
                            tag="p"
                            size="xs"
                            weight="semibold"
                            color="muted"
                        >
                            {{ answeredCount }} of {{ questions.length }}
                            questions answered
                        </AppText>

                        <AppText
                            v-if="requiredCount"
                            tag="p"
                            size="xs"
                            color="muted"
                            class="mt-1"
                        >
                            {{ requiredCount }}
                            {{
                                requiredCount === 1
                                    ? 'required question'
                                    : 'required questions'
                            }}
                        </AppText>
                    </div>

                    <AppButton
                        type="submit"
                        :disabled="form.processing"
                        class="w-full sm:w-auto"
                    >
                        <span class="flex items-center gap-2">
                            {{
                                form.processing
                                    ? 'Submitting…'
                                    : 'Submit Response'
                            }}

                            <ChevronRight
                                v-if="!form.processing"
                                :size="16"
                                :stroke-width="1.8"
                            />
                        </span>
                    </AppButton>
                </div>

                <div
                    class="
                        mt-6
                        flex
                        items-start
                        gap-3
                        border
                        border-border-light
                        bg-content-light/[0.02]
                        px-4
                        py-3
                        dark:border-border-dark
                        dark:bg-white/[0.02]
                    "
                >
                    <Lock
                        :size="14"
                        :stroke-width="1.7"
                        class="
                            mt-0.5
                            shrink-0
                            text-content-lightMuted
                            dark:text-content-darkMuted
                        "
                    />

                    <AppText
                        tag="p"
                        size="xs"
                        color="muted"
                        leading="relaxed"
                    >
                        Your response will be handled according to the
                        survey's privacy settings. Please review your answers
                        before submitting.
                    </AppText>
                </div>
            </div>
        </form>
    </main>
</template>