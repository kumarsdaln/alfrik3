<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import {
    ArrowLeft,
    BarChart3,
    CheckCircle2,
    Clock3,
    FileQuestion,
    Users,
} from '@lucide/vue'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import AppStats from '@/components/ui/AppStats.vue'
import Badge from '@/components/ui/Badge.vue'
import Date from '@/components/datadisplay/Date.vue'

import { index as surveyIndex } from '@/routes/admin/survey'
import { show as surveyShow } from '@/routes/admin/survey'

interface SurveyAnalyticsSurvey {
    id: number
    title: string
    slug: string
    status: {
        value: string
        label: string
        color: string
    }
}

interface AnalyticsStats {
    total_responses: number
    submitted_responses: number
    in_progress_responses: number
    abandoned_responses: number
    completion_rate: number
}

interface QuestionOption {
    id: number
    label: string
    value: string
    count: number
}

interface QuestionAnalytics {
    id: number
    question: string
    type: {
        value: string
        label: string
    }
    total_answers: number
    options: QuestionOption[]
    numbers: (number | string)[]
}

interface RecentResponse {
    id: number
    status: {
        value: string
        label: string
        color: string
    }
    respondent_name: string | null
    respondent_email: string | null
    submitted_at: string | null
    created_at: string
}

const props = defineProps<{
    survey: SurveyAnalyticsSurvey
    stats: AnalyticsStats
    questions: QuestionAnalytics[]
    recent_responses: RecentResponse[]
}>()

function percentage(
    count: number,
    total: number,
): number {
    if (!total) {
        return 0
    }

    return Math.round((count / total) * 100)
}

function numericValues(question: QuestionAnalytics) {
    return question.numbers
        .map((value) => Number(value))
        .filter((value) => !Number.isNaN(value))
}

function numericAverage(question: QuestionAnalytics) {
    const values = numericValues(question)

    if (!values.length) {
        return null
    }

    return (
        values.reduce((sum, value) => sum + value, 0) /
        values.length
    ).toFixed(1)
}

function numericMin(question: QuestionAnalytics) {
    const values = numericValues(question)

    return values.length ? Math.min(...values) : null
}

function numericMax(question: QuestionAnalytics) {
    const values = numericValues(question)

    return values.length ? Math.max(...values) : null
}
</script>

<template>
    <Head :title="`Analytics — ${survey.title}`" />

    <div class="space-y-6">
        <!-- ============================================================
             HEADER
        ============================================================= -->

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
            <div class="min-w-0">
                <Link
                    :href="surveyIndex.url()"
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
                    Surveys
                </Link>

                <div class="flex flex-wrap items-center gap-3">
                    <AppHeading
                        tag="h1"
                        size="2xl"
                        weight="semibold"
                    >
                        Survey Analytics
                    </AppHeading>

                    <Badge
                        :variant="survey.status.color"
                    >
                        {{ survey.status.label }}
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

            <div class="flex flex-wrap gap-2">
                <Link
                    :href="surveyShow(survey.id).url"
                    class="
                        inline-flex
                        items-center
                        gap-2
                        border
                        border-border-light
                        px-4
                        py-2
                        text-sm
                        font-medium
                        transition-colors
                        hover:bg-muted
                        dark:border-border-dark
                    "
                >
                    View Survey
                </Link>
            </div>
        </div>

        <!-- ============================================================
             STATS
        ============================================================= -->

        <div
            class="
                grid
                gap-4
                sm:grid-cols-2
                xl:grid-cols-4
            "
        >
            <AppStats
                title="Total Responses"
                :value="stats.total_responses"
                :icon="Users"
            />

            <AppStats
                title="Completed"
                :value="stats.submitted_responses"
                :icon="CheckCircle2"
            />

            <AppStats
                title="In Progress"
                :value="stats.in_progress_responses"
                :icon="Clock3"
            />

            <AppStats
                title="Completion Rate"
                :value="`${stats.completion_rate}%`"
                :icon="BarChart3"
            />
        </div>

        <!-- ============================================================
             RESPONSE SUMMARY
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
                    border-b
                    border-border-light
                    px-5
                    py-4
                    dark:border-border-dark
                "
            >
                <AppHeading
                    tag="h2"
                    size="lg"
                    weight="semibold"
                >
                    Response Summary
                </AppHeading>

                <AppText
                    tag="p"
                    size="sm"
                    color="muted"
                    class="mt-1"
                >
                    Overview of how respondents progressed through the survey.
                </AppText>
            </div>

            <div class="grid sm:grid-cols-3">
                <!-- Completed -->

                <div
                    class="
                        border-b
                        border-border-light
                        p-5
                        sm:border-b-0
                        sm:border-r
                        dark:border-border-dark
                    "
                >
                    <div class="flex items-center justify-between">
                        <AppText
                            tag="span"
                            size="sm"
                            color="muted"
                        >
                            Completed
                        </AppText>

                        <CheckCircle2
                            :size="17"
                            class="text-green-600"
                        />
                    </div>

                    <AppText
                        tag="p"
                        size="2xl"
                        weight="semibold"
                        class="mt-3"
                    >
                        {{ stats.submitted_responses }}
                    </AppText>

                    <AppText
                        tag="p"
                        size="xs"
                        color="muted"
                        class="mt-1"
                    >
                        {{
                            percentage(
                                stats.submitted_responses,
                                stats.total_responses,
                            )
                        }}%
                        of responses
                    </AppText>
                </div>

                <!-- In progress -->

                <div
                    class="
                        border-b
                        border-border-light
                        p-5
                        sm:border-b-0
                        sm:border-r
                        dark:border-border-dark
                    "
                >
                    <div class="flex items-center justify-between">
                        <AppText
                            tag="span"
                            size="sm"
                            color="muted"
                        >
                            In Progress
                        </AppText>

                        <Clock3
                            :size="17"
                            class="text-amber-600"
                        />
                    </div>

                    <AppText
                        tag="p"
                        size="2xl"
                        weight="semibold"
                        class="mt-3"
                    >
                        {{ stats.in_progress_responses }}
                    </AppText>

                    <AppText
                        tag="p"
                        size="xs"
                        color="muted"
                        class="mt-1"
                    >
                        {{
                            percentage(
                                stats.in_progress_responses,
                                stats.total_responses,
                            )
                        }}%
                        of responses
                    </AppText>
                </div>

                <!-- Abandoned -->

                <div class="p-5">
                    <div class="flex items-center justify-between">
                        <AppText
                            tag="span"
                            size="sm"
                            color="muted"
                        >
                            Abandoned
                        </AppText>

                        <FileQuestion
                            :size="17"
                            class="text-muted-foreground"
                        />
                    </div>

                    <AppText
                        tag="p"
                        size="2xl"
                        weight="semibold"
                        class="mt-3"
                    >
                        {{ stats.abandoned_responses }}
                    </AppText>

                    <AppText
                        tag="p"
                        size="xs"
                        color="muted"
                        class="mt-1"
                    >
                        {{
                            percentage(
                                stats.abandoned_responses,
                                stats.total_responses,
                            )
                        }}%
                        of responses
                    </AppText>
                </div>
            </div>
        </section>

        <!-- ============================================================
             QUESTION ANALYTICS
        ============================================================= -->

        <section>
            <div class="mb-4">
                <AppHeading
                    tag="h2"
                    size="lg"
                    weight="semibold"
                >
                    Question Analysis
                </AppHeading>

                <AppText
                    tag="p"
                    size="sm"
                    color="muted"
                    class="mt-1"
                >
                    Response distribution for each survey question.
                </AppText>
            </div>

            <div
                v-if="!questions.length"
                class="
                    border
                    border-dashed
                    border-border-light
                    px-6
                    py-12
                    text-center
                    dark:border-border-dark
                "
            >
                <FileQuestion
                    :size="24"
                    class="mx-auto text-muted-foreground"
                />

                <AppText
                    tag="p"
                    color="muted"
                    class="mt-3"
                >
                    No questions have been added to this survey.
                </AppText>
            </div>

            <div
                v-else
                class="space-y-4"
            >
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
                    <!-- Question header -->

                    <div
                        class="
                            border-b
                            border-border-light
                            px-5
                            py-4
                            dark:border-border-dark
                        "
                    >
                        <div class="flex items-start gap-3">
                            <span
                                class="
                                    flex
                                    h-7
                                    w-7
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
                                <AppText
                                    tag="p"
                                    size="sm"
                                    weight="semibold"
                                    leading="relaxed"
                                >
                                    {{ question.question }}
                                </AppText>

                                <div
                                    class="
                                        mt-2
                                        flex
                                        flex-wrap
                                        items-center
                                        gap-3
                                    "
                                >
                                    <AppText
                                        tag="span"
                                        size="xs"
                                        color="muted"
                                    >
                                        {{ question.type.label }}
                                    </AppText>

                                    <span
                                        class="
                                            h-1
                                            w-1
                                            rounded-full
                                            bg-muted-foreground
                                        "
                                    />

                                    <AppText
                                        tag="span"
                                        size="xs"
                                        color="muted"
                                    >
                                        {{ question.total_answers }}
                                        answers
                                    </AppText>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Choice results -->

                    <div
                        v-if="
                            question.options.length &&
                            (
                                question.type.value === 'single_choice' ||
                                question.type.value === 'multiple_choice'
                            )
                        "
                        class="divide-y divide-border-light dark:divide-border-dark"
                    >
                        <div
                            v-for="option in question.options"
                            :key="option.id"
                            class="px-5 py-4"
                        >
                            <div
                                class="
                                    mb-2
                                    flex
                                    items-center
                                    justify-between
                                    gap-4
                                "
                            >
                                <AppText
                                    tag="span"
                                    size="sm"
                                >
                                    {{ option.label }}
                                </AppText>

                                <AppText
                                    tag="span"
                                    size="xs"
                                    weight="semibold"
                                    color="muted"
                                >
                                    {{ option.count }}
                                    ·
                                    {{
                                        percentage(
                                            option.count,
                                            question.total_answers,
                                        )
                                    }}%
                                </AppText>
                            </div>

                            <div
                                class="
                                    h-2
                                    w-full
                                    overflow-hidden
                                    bg-muted
                                "
                            >
                                <div
                                    class="h-full bg-primary transition-all"
                                    :style="{
                                        width: `${percentage(
                                            option.count,
                                            question.total_answers,
                                        )}%`,
                                    }"
                                />
                            </div>
                        </div>
                    </div>

                    <!-- Numeric results -->

                    <div
                        v-else-if="
                            ['number', 'rating', 'scale'].includes(
                                question.type.value,
                            )
                        "
                        class="grid sm:grid-cols-3"
                    >
                        <div
                            class="
                                border-b
                                border-border-light
                                p-5
                                sm:border-b-0
                                sm:border-r
                                dark:border-border-dark
                            "
                        >
                            <AppText
                                tag="p"
                                size="xs"
                                color="muted"
                            >
                                Average
                            </AppText>

                            <AppText
                                tag="p"
                                size="xl"
                                weight="semibold"
                                class="mt-2"
                            >
                                {{ numericAverage(question) ?? '—' }}
                            </AppText>
                        </div>

                        <div
                            class="
                                border-b
                                border-border-light
                                p-5
                                sm:border-b-0
                                sm:border-r
                                dark:border-border-dark
                            "
                        >
                            <AppText
                                tag="p"
                                size="xs"
                                color="muted"
                            >
                                Minimum
                            </AppText>

                            <AppText
                                tag="p"
                                size="xl"
                                weight="semibold"
                                class="mt-2"
                            >
                                {{ numericMin(question) ?? '—' }}
                            </AppText>
                        </div>

                        <div class="p-5">
                            <AppText
                                tag="p"
                                size="xs"
                                color="muted"
                            >
                                Maximum
                            </AppText>

                            <AppText
                                tag="p"
                                size="xl"
                                weight="semibold"
                                class="mt-2"
                            >
                                {{ numericMax(question) ?? '—' }}
                            </AppText>
                        </div>
                    </div>

                    <!-- Text / other -->

                    <div
                        v-else
                        class="px-5 py-5"
                    >
                        <AppText
                            tag="p"
                            size="sm"
                            color="muted"
                        >
                            {{ question.total_answers }}
                            responses recorded.
                        </AppText>
                    </div>
                </article>
            </div>
        </section>

        <!-- ============================================================
             RECENT RESPONSES
        ============================================================= -->

        <section>
            <div
                class="
                    mb-4
                    flex
                    flex-col
                    gap-2
                    sm:flex-row
                    sm:items-end
                    sm:justify-between
                "
            >
                <div>
                    <AppHeading
                        tag="h2"
                        size="lg"
                        weight="semibold"
                    >
                        Recent Responses
                    </AppHeading>

                    <AppText
                        tag="p"
                        size="sm"
                        color="muted"
                        class="mt-1"
                    >
                        The latest responses submitted to this survey.
                    </AppText>
                </div>
            </div>

            <div
                v-if="!recent_responses.length"
                class="
                    border
                    border-dashed
                    border-border-light
                    px-6
                    py-12
                    text-center
                    dark:border-border-dark
                "
            >
                <Users
                    :size="24"
                    class="mx-auto text-muted-foreground"
                />

                <AppText
                    tag="p"
                    color="muted"
                    class="mt-3"
                >
                    No responses have been submitted yet.
                </AppText>
            </div>

            <div
                v-else
                class="
                    overflow-hidden
                    border
                    border-border-light
                    dark:border-border-dark
                "
            >
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[700px]">
                        <thead
                            class="
                                border-b
                                border-border-light
                                bg-muted/30
                                dark:border-border-dark
                            "
                        >
                            <tr>
                                <th
                                    class="
                                        px-4
                                        py-3
                                        text-left
                                        text-xs
                                        font-semibold
                                    "
                                >
                                    Respondent
                                </th>

                                <th
                                    class="
                                        px-4
                                        py-3
                                        text-left
                                        text-xs
                                        font-semibold
                                    "
                                >
                                    Status
                                </th>

                                <th
                                    class="
                                        px-4
                                        py-3
                                        text-left
                                        text-xs
                                        font-semibold
                                    "
                                >
                                    Submitted
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            <tr
                                v-for="response in recent_responses"
                                :key="response.id"
                                class="
                                    border-b
                                    border-border-light
                                    last:border-b-0
                                    dark:border-border-dark
                                "
                            >
                                <td class="px-4 py-4">
                                    <div>
                                        <AppText
                                            tag="p"
                                            size="sm"
                                            weight="medium"
                                        >
                                            {{
                                                response.respondent_name ||
                                                'Anonymous'
                                            }}
                                        </AppText>

                                        <AppText
                                            v-if="response.respondent_email"
                                            tag="p"
                                            size="xs"
                                            color="muted"
                                            class="mt-1"
                                        >
                                            {{ response.respondent_email }}
                                        </AppText>
                                    </div>
                                </td>

                                <td class="px-4 py-4">
                                    <Badge
                                        :variant="response.status.color"
                                    >
                                        {{ response.status.label }}
                                    </Badge>
                                </td>

                                <td class="px-4 py-4">
                                    <Date
                                        v-if="response.submitted_at"
                                        :date="response.submitted_at"
                                    />

                                    <AppText
                                        v-else
                                        tag="span"
                                        size="sm"
                                        color="muted"
                                    >
                                        —
                                    </AppText>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</template>