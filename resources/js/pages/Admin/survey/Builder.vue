<script setup lang="ts">
    import { computed } from 'vue'
    import { Link, router } from '@inertiajs/vue3'
    import {
        ArrowLeft,
        ChevronDown,
        ChevronRight,
        Edit,
        Plus,
        Settings,
    } from '@lucide/vue'

    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import type {
        SurveyQuestion,
        SurveySection,
    } from '@/types'

    interface Props {
        survey: {
            id: number
            title: string
            status: {
                value: string
                label: string
                color: string
            }
        }

        sections: {
            data: SurveySection[]
        }

        questions: {
            data: SurveyQuestion[]
        }

        typeOptions: {
            value: string
            label: string
        }[]
    }

    const props = defineProps<Props>()

    const questionsWithoutSection = computed(() =>
        props.questions.data.filter(
            question => question.section_id === null
        )
    )

    const questionsForSection = (sectionId: number) =>
        props.questions.data.filter(
            question => question.section_id === sectionId
        )

    const editSection = (sectionId: number) => {
        router.get(
            `/admin/survey/${props.survey.id}/sections/${sectionId}/edit`
        )
    }

    const editQuestion = (questionId: number) => {
        router.get(
            `/admin/survey/${props.survey.id}/questions/${questionId}/edit`
        )
    }

    const manageOptions = (questionId: number) => {
        router.get(
            `/admin/survey/questions/${questionId}/options`
        )
    }

    const addSection = () => {
        router.get(
            `/admin/survey/${props.survey.id}/sections`
        )
    }

    const addQuestion = () => {
        router.get(
            `/admin/survey/${props.survey.id}/questions`
        )
    }
</script>

<template>
    <div class="space-y-6">
        <!-- Header -->
        <div
            class="flex flex-col gap-4 border-b border-border-light pb-6 dark:border-border-dark md:flex-row md:items-center md:justify-between">
            <div class="flex items-center gap-4">
                <Button variant="outline" size="icon" as-child>
                    <Link :href="`/admin/survey/${survey.id}`">
                        <ArrowLeft class="size-4" />
                    </Link>
                </Button>

                <Heading title="Survey Builder" :description="survey.title" />
            </div>

            <div class="flex items-center gap-2">
                <Badge :style="{
                    borderColor: survey.status.color,
                    color: survey.status.color,
                }" variant="outline">
                    {{ survey.status.label }}
                </Badge>

                <Button variant="outline" as-child>
                    <Link :href="`/admin/survey/${survey.id}/edit`">
                        <Settings class="mr-2 size-4" />
                        Survey Settings
                    </Link>
                </Button>
            </div>
        </div>

        <!-- Builder toolbar -->
        <div
            class="flex flex-wrap items-center justify-between gap-3 rounded-lg border border-border-light p-4 dark:border-border-dark">
            <div>
                <h2 class="font-medium">
                    Survey Structure
                </h2>

                <p class="text-sm text-muted-foreground">
                    Organize sections, questions, and answer options.
                </p>
            </div>

            <div class="flex gap-2">
                <Button variant="outline" @click="addSection">
                    <Plus class="mr-2 size-4" />
                    Add Section
                </Button>

                <Button @click="addQuestion">
                    <Plus class="mr-2 size-4" />
                    Add Question
                </Button>
            </div>
        </div>

        <!-- Sections -->
        <div class="space-y-4">
            <div v-for="section in sections.data" :key="section.id"
                class="rounded-lg border border-border-light dark:border-border-dark">
                <!-- Section header -->
                <div
                    class="flex items-center justify-between gap-4 border-b border-border-light px-5 py-4 dark:border-border-dark">
                    <div class="flex min-w-0 items-center gap-3">
                        <ChevronDown class="size-4 shrink-0" />

                        <div class="min-w-0">
                            <div class="font-medium">
                                {{ section.position + 1 }}.
                                {{ section.title }}
                            </div>

                            <p v-if="section.description" class="mt-1 text-sm text-muted-foreground">
                                {{ section.description }}
                            </p>
                        </div>
                    </div>

                    <Button variant="ghost" size="sm" @click="editSection(section.id)">
                        <Edit class="mr-2 size-4" />
                        Edit
                    </Button>
                </div>

                <!-- Section questions -->
                <div class="divide-y divide-border-light dark:divide-border-dark">
                    <div v-for="question in questionsForSection(section.id)" :key="question.id"
                        class="flex flex-col gap-4 px-5 py-4 md:flex-row md:items-center md:justify-between">
                        <div class="flex min-w-0 items-start gap-3">
                            <div class="mt-1 flex size-7 shrink-0 items-center justify-center rounded border text-xs">
                                {{ question.position + 1 }}
                            </div>

                            <div class="min-w-0">
                                <div class="font-medium">
                                    {{ question.question }}
                                </div>

                                <div class="mt-2 flex flex-wrap gap-2">
                                    <Badge variant="outline">
                                        {{ question.type.label }}
                                    </Badge>

                                    <Badge v-if="question.required" variant="secondary">
                                        Required
                                    </Badge>

                                    <Badge v-if="
                                        question.options &&
                                        question.options.length
                                    " variant="secondary">
                                        {{ question.options.length }}
                                        options
                                    </Badge>
                                </div>
                            </div>
                        </div>

                        <div class="flex shrink-0 gap-2">
                            <Button variant="ghost" size="sm" @click="editQuestion(question.id)">
                                <Edit class="mr-2 size-4" />
                                Edit
                            </Button>

                            <Button v-if="
                                question.type.value ===
                                'single_choice' ||
                                question.type.value ===
                                'multiple_choice'
                            " variant="outline" size="sm" @click="manageOptions(question.id)">
                                Options
                            </Button>
                        </div>
                    </div>

                    <div v-if="!questionsForSection(section.id).length"
                        class="px-5 py-8 text-center text-sm text-muted-foreground">
                        No questions in this section.
                    </div>
                </div>
            </div>

            <!-- Questions without section -->
            <div v-if="questionsWithoutSection.length"
                class="rounded-lg border border-border-light dark:border-border-dark">
                <div class="flex items-center gap-3 border-b border-border-light px-5 py-4 dark:border-border-dark">
                    <ChevronRight class="size-4" />

                    <div>
                        <div class="font-medium">
                            Questions without section
                        </div>

                        <p class="text-sm text-muted-foreground">
                            These questions have not been assigned to a section.
                        </p>
                    </div>
                </div>

                <div class="divide-y divide-border-light dark:divide-border-dark">
                    <div v-for="question in questionsWithoutSection" :key="question.id"
                        class="flex items-center justify-between gap-4 px-5 py-4">
                        <div>
                            <div class="font-medium">
                                {{ question.question }}
                            </div>

                            <div class="mt-2 flex gap-2">
                                <Badge variant="outline">
                                    {{ question.type.label }}
                                </Badge>

                                <Badge v-if="question.required" variant="secondary">
                                    Required
                                </Badge>
                            </div>
                        </div>

                        <Button variant="ghost" size="sm" @click="editQuestion(question.id)">
                            <Edit class="mr-2 size-4" />
                            Edit
                        </Button>
                    </div>
                </div>
            </div>

            <!-- Empty -->
            <div v-if="
                !sections.data.length &&
                !questions.data.length
            " class="rounded-lg border border-dashed border-border-light p-12 text-center dark:border-border-dark">
                <h3 class="font-medium">
                    Your survey is empty
                </h3>

                <p class="mt-1 text-sm text-muted-foreground">
                    Start by creating a section or adding a question.
                </p>

                <div class="mt-5 flex justify-center gap-2">
                    <Button variant="outline" @click="addSection">
                        <Plus class="mr-2 size-4" />
                        Add Section
                    </Button>

                    <Button @click="addQuestion">
                        <Plus class="mr-2 size-4" />
                        Add Question
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>