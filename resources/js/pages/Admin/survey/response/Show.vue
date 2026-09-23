<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import {
    ArrowLeft,
    CheckCircle2,
    Clock3,
    FileText,
    UserRound,
} from '@lucide/vue'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import Date from '@/components/datadisplay/Date.vue'

import { analytics as surveyAnalytics } from '@/routes/admin/survey'
import { SurveyResponseAnswer } from '@/types'

interface SurveyResponseStatus {
    value: string
    label: string
    color: string
}


interface SurveyResponseQuestion {
    id: number
    question: string
    type: {
        value: string
        label: string
    }
    position: number
}

interface SurveyResponse {
    id: number
    status: SurveyResponseStatus
    respondent_name: string | null
    respondent_email: string | null
    respondent_ip: string | null
    user_agent: string | null
    started_at: string | null
    submitted_at: string | null
    created_at: string
    answers: SurveyResponseAnswer[]
}

interface Survey {
    id: number
    title: string
    slug: string
}

defineProps<{
    survey: Survey
    response: SurveyResponse
    questions: SurveyResponseQuestion[]
}>()

function answerFor(
    response: SurveyResponse,
    questionId: number,
) {
    return response.answers.find(
        (answer) => answer.question_id === questionId,
    )
}

function multipleChoiceLabels(
    answer: SurveyResponseAnswer,
) {
    if (!answer.answer_json?.length) {
        return []
    }

    return answer.answer_json
        .map((optionId) => {
            const option = answer.option

            if (option && option.id === optionId) {
                return option.label
            }

            return `Option #${optionId}`
        })
}

function answerText(
    question: SurveyResponseQuestion,
    answer?: SurveyResponseAnswer,
) {
    if (!answer) {
        return 'No answer'
    }

    switch (question.type.value) {
        case 'single_choice':
            return answer.option?.label ?? 'No answer'

        case 'multiple_choice':
            return multipleChoiceLabels(answer).join(', ') || 'No answer'

        case 'yes_no':
            if (answer.answer_boolean === null) {
                return 'No answer'
            }

            return answer.answer_boolean ? 'Yes' : 'No'

        case 'number':
        case 'rating':
        case 'scale':
            return answer.answer_number !== null
                ? String(answer.answer_number)
                : 'No answer'

        case 'short_text':
        case 'long_text':
        case 'date':
            return answer.answer_text || 'No answer'

        default:
            return (
                answer.answer_text ??
                answer.answer_number?.toString() ??
                'No answer'
            )
    }
}
</script>

<template>
    <Head :title="`Response #${response.id} — ${survey.title}`" />

    <div class="space-y-6">
        <!-- ============================================================
             HEADER
        ============================================================= -->

        <div>
            <Link
                :href="surveyAnalytics(survey.id).url"
                class="
                    mb-4
                    inline-flex
                    items-center
                    gap-2
                    text-sm
                    text-muted-foreground
                    transition-colors
                    hover:text-foreground
                "
            >
                <ArrowLeft :size="15" />
                Survey Analytics
            </Link>

            <div
                class="
                    flex
                    flex-col
                    gap-4
                    lg:flex-row
                    lg:items-start
                    lg:justify-between
                "
            >
                <div>
                    <div class="flex flex-wrap items-center gap-3">
                        <AppHeading
                            tag="h1"
                            size="2xl"
                            weight="semibold"
                        >
                            Response #{{ response.id }}
                        </AppHeading>

                        <Badge :variant="response.status.color">
                            {{ response.status.label }}
                        </Badge>
                    </div>

                    <AppText
                        tag="p"
                        color="muted"
                        class="mt-1"
                    >
                        {{ survey.title }}
                    </AppText>
                </div>
            </div>
        </div>

        <!-- ============================================================
             RESPONDENT
        ============================================================= -->

        <section
            class="
                border
                border-border-light
                bg-background
                dark:border-border-dark
            "
        >
            <div
                class="
                    flex
                    items-center
                    gap-3
                    border-b
                    border-border-light
                    px-5
                    py-4
                    dark:border-border-dark
                "
            >
                <div
                    class="
                        flex
                        h-9
                        w-9
                        items-center
                        justify-center
                        bg-muted
                    "
                >
                    <UserRound
                        :size="17"
                        class="text-muted-foreground"
                    />
                </div>

                <div>
                    <AppHeading
                        tag="h2"
                        size="base"
                        weight="semibold"
                    >
                        Respondent
                    </AppHeading>

                    <AppText
                        tag="p"
                        size="xs"
                        color="muted"
                        class="mt-0.5"
                    >
                        Response information
                    </AppText>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4">
                <div
                    class="
                        border-b
                        border-border-light
                        p-5
                        sm:border-r
                        lg:border-b-0
                        dark:border-border-dark
                    "
                >
                    <AppText
                        tag="p"
                        size="xs"
                        color="muted"
                    >
                        Name
                    </AppText>

                    <AppText
                        tag="p"
                        size="sm"
                        weight="medium"
                        class="mt-2"
                    >
                        {{ response.respondent_name || 'Anonymous' }}
                    </AppText>
                </div>

                <div
                    class="
                        border-b
                        border-border-light
                        p-5
                        lg:border-r
                        lg:border-b-0
                        dark:border-border-dark
                    "
                >
                    <AppText
                        tag="p"
                        size="xs"
                        color="muted"
                    >
                        Email
                    </AppText>

                    <AppText
                        tag="p"
                        size="sm"
                        weight="medium"
                        class="mt-2 break-all"
                    >
                        {{ response.respondent_email || '—' }}
                    </AppText>
                </div>

                <div
                    class="
                        border-b
                        border-border-light
                        p-5
                        sm:border-r
                        sm:border-b-0
                        dark:border-border-dark
                    "
                >
                    <AppText
                        tag="p"
                        size="xs"
                        color="muted"
                    >
                        Started
                    </AppText>

                    <Date
                        v-if="response.started_at"
                        :value="response.started_at"
                        class="mt-2"
                    />

                    <AppText
                        v-else
                        tag="p"
                        size="sm"
                        color="muted"
                        class="mt-2"
                    >
                        —
                    </AppText>
                </div>

                <div class="p-5">
                    <AppText
                        tag="p"
                        size="xs"
                        color="muted"
                    >
                        Submitted
                    </AppText>

                    <Date
                        v-if="response.submitted_at"
                        :value="response.submitted_at"
                        class="mt-2"
                    />

                    <AppText
                        v-else
                        tag="p"
                        size="sm"
                        color="muted"
                        class="mt-2"
                    >
                        Not submitted
                    </AppText>
                </div>
            </div>
        </section>

        <!-- ============================================================
             ANSWERS
        ============================================================= -->

        <section>
            <div class="mb-4">
                <AppHeading
                    tag="h2"
                    size="lg"
                    weight="semibold"
                >
                    Responses
                </AppHeading>

                <AppText
                    tag="p"
                    size="sm"
                    color="muted"
                    class="mt-1"
                >
                    Answers submitted by this respondent.
                </AppText>
            </div>

            <div class="space-y-4">
                <article
                    v-for="(question, index) in questions"
                    :key="question.id"
                    class="
                        border
                        border-border-light
                        bg-background
                        dark:border-border-dark
                    "
                >
                    <div class="p-5 sm:p-6">
                        <div class="flex items-start gap-4">
                            <span
                                class="
                                    flex
                                    h-8
                                    w-8
                                    shrink-0
                                    items-center
                                    justify-center
                                    bg-muted
                                    text-xs
                                    font-semibold
                                "
                            >
                                {{ index + 1 }}
                            </span>

                            <div class="min-w-0 flex-1">
                                <div
                                    class="
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
                                        color="muted"
                                    >
                                        {{ question.type.label }}
                                    </AppText>
                                </div>

                                <AppText
                                    tag="p"
                                    size="sm"
                                    weight="semibold"
                                    leading="relaxed"
                                    class="mt-2"
                                >
                                    {{ question.question }}
                                </AppText>

                                <div
                                    class="
                                        mt-5
                                        border-l-2
                                        border-primary/30
                                        pl-4
                                    "
                                >
                                    <AppText
                                        tag="p"
                                        size="sm"
                                        leading="relaxed"
                                    >
                                        {{
                                            answerText(
                                                question,
                                                answerFor(
                                                    response,
                                                    question.id,
                                                ),
                                            )
                                        }}
                                    </AppText>
                                </div>
                            </div>
                        </div>
                    </div>
                </article>
            </div>
        </section>

        <!-- ============================================================
             TECHNICAL INFORMATION
        ============================================================= -->

        <section
            class="
                border
                border-border-light
                dark:border-border-dark
            "
        >
            <div
                class="
                    border-b
                    border-border-light
                    px-5
                    py-4
                    dark:border-border-dark
                "
            >
                <div class="flex items-center gap-3">
                    <Clock3
                        :size="17"
                        class="text-muted-foreground"
                    />

                    <AppHeading
                        tag="h2"
                        size="base"
                        weight="semibold"
                    >
                        Response Details
                    </AppHeading>
                </div>
            </div>

            <div class="grid sm:grid-cols-2">
                <div
                    class="
                        border-b
                        border-border-light
                        p-5
                        sm:border-r
                        sm:border-b-0
                        dark:border-border-dark
                    "
                >
                    <AppText
                        tag="p"
                        size="xs"
                        color="muted"
                    >
                        Response ID
                    </AppText>

                    <AppText
                        tag="p"
                        size="sm"
                        weight="medium"
                        class="mt-2"
                    >
                        #{{ response.id }}
                    </AppText>
                </div>

                <div class="p-5">
                    <AppText
                        tag="p"
                        size="xs"
                        color="muted"
                    >
                        Created
                    </AppText>

                    <Date
                        :value="response.created_at"
                        class="mt-2"
                    />
                </div>
            </div>
        </section>
    </div>
</template>