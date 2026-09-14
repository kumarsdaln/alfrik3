<script setup lang="ts">
    import { Form, Link, router } from '@inertiajs/vue3'
    import {
        ArrowLeft,
        Edit2,
        Plus,
        Trash2,
    } from '@lucide/vue'

    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'

    import { edit as interviewEdit } from '@/routes/admin/interviews'

    import {
        create as answerCreate,
        edit as answerEdit,
        destroy as answerDestroy,
    } from '@/routes/admin/interviews/questions/answers'

    import {
        update as questionUpdate,
        destroy as questionDestroy,
    } from '@/routes/admin/interviews/questions'

    import type { InterviewParticipant, InterviewQuestion } from '@/types'
    import AppSelect from '@/components/form/AppSelect.vue'
import { ref } from 'vue'

    const props = defineProps<{
        question: {
            data: InterviewQuestion
        },
        interviewers: {
            data: InterviewParticipant[]
        }
    }>()

    function backToInterview() {
        router.visit(
            interviewEdit(props.question.data.interview.id).url
        )
    }

    function deleteAnswer(answerId: number) {
        if (
            !confirm(
                'Are you sure you want to delete this answer? This action cannot be undone.'
            )
        ) {
            return
        }

        router.delete(
            answerDestroy(answerId).url,
            {
                preserveScroll: true,
            }
        )
    }

    function deleteQuestion() {
        if (
            !confirm(
                'Are you sure you want to delete this question? All answers belonging to this question will also be deleted.'
            )
        ) {
            return
        }

        router.delete(
            questionDestroy(props.question.data.id).url,
            {
                onSuccess: () => {
                    backToInterview()
                },
            }
        )
    }

    const askedBY = ref<number|string|undefined>(props.question.data.asked_by?.id)
</script>

<template>
    <div class="space-y-8 my-4">

        <!-- Header -->
        <div class="flex items-start gap-4">
            <Button type="button" variant="outline" size="icon" class="shrink-0" @click="backToInterview">
                <ArrowLeft class="size-4" />
                <span class="sr-only">
                    Back to interview
                </span>
            </Button>

            <Heading title="Edit Question"
                :description="`Manage this question and its answers for ${question.data.interview.title}.`" />
        </div>

        <!-- Question -->
        <section>
            <Form v-bind="questionUpdate.form(question.data.id)" :options="{
                preserveScroll: true,
            }" #default="{ errors, processing }" class="space-y-6">
                <AppFormControl label="Question By" required :error="errors.asked_by">
                    <AppSelect name="asked_by" v-model="askedBY" placeholder="Select interviewer" :options="interviewers.data.map(participant => ({
                        label: participant.user.name,
                        value: participant.user.id,
                    }))" />
                </AppFormControl>
                <AppFormControl label="Question" :error="errors.question" required>
                    <AppTextarea name="question" v-model="question.data.question"
                        placeholder="Enter interview question..." :rows="6" />
                </AppFormControl>

                <AppFormControl label="Position" :error="errors.position"
                    description="Controls the order in which questions are displayed.">
                    <AppInput name="position" type="number" min="0" v-model="question.data.position" />
                </AppFormControl>

                <div class="flex items-center justify-between gap-3 border-t pt-6">
                    <Button type="button" variant="outline" @click="backToInterview">
                        Cancel
                    </Button>

                    <Button type="submit" :disabled="processing">
                        {{ processing ? 'Updating...' : 'Update Question' }}
                    </Button>
                </div>
            </Form>
        </section>

        <!-- Answers -->
        <section class="overflow-hidden border bg-card">

            <!-- Answers Header -->
            <div class="flex items-center justify-between gap-4 border-b px-6 py-5">
                <div>
                    <h2 class="text-base font-semibold">
                        Answers
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Manage answers submitted for this question.
                    </p>
                </div>

                <Button as-child class="gap-2">
                    <Link :href="answerCreate(question.data.id)">
                        <Plus class="size-4" />
                        Add Answer
                    </Link>
                </Button>
            </div>

            <!-- Answer List -->
            <div v-if="question.data.answers.length" class="divide-y">
                <article v-for="answer in question.data.answers" :key="answer.id"
                    class="p-6 transition-colors hover:bg-muted/30">
                    <div class="flex items-start justify-between gap-6">

                        <!-- Answer Content -->
                        <div class="min-w-0 flex-1">

                            <p class="
                                    whitespace-pre-wrap
                                    text-sm
                                    leading-6
                                ">
                                {{ answer.answer }}
                            </p>

                            <div v-if="answer.answered_by"
                                class="mt-4 flex items-center gap-2 text-xs text-muted-foreground">
                                <span>
                                    Answered by
                                </span>

                                <span class="font-medium text-foreground">
                                    {{ answer.answered_by.name }}
                                </span>
                            </div>

                        </div>

                        <!-- Answer Actions -->
                        <div class="flex shrink-0 items-center gap-2">

                            <Button as-child type="button" variant="outline" size="icon">
                                <Link :href="answerEdit(
                                    answer.id
                                )
                                    ">
                                    <Edit2 class="size-4" />

                                    <span class="sr-only">
                                        Edit answer
                                    </span>
                                </Link>
                            </Button>

                            <Button type="button" variant="destructive" size="icon" @click="deleteAnswer(answer.id)">
                                <Trash2 class="size-4" />

                                <span class="sr-only">
                                    Delete answer
                                </span>
                            </Button>

                        </div>
                    </div>
                </article>
            </div>

            <!-- Empty -->
            <div v-else class="flex flex-col items-center justify-center px-6 py-16 text-center">
                <div class="
                        mb-4 flex size-12 items-center justify-center
                        rounded-full bg-muted
                    ">
                    <Plus class="size-5 text-muted-foreground" />
                </div>

                <h3 class="text-sm font-semibold">
                    No answers yet
                </h3>

                <p class="mt-1 max-w-sm text-sm text-muted-foreground">
                    Add the first answer to this interview question.
                </p>

                <Button as-child variant="outline" class="mt-5 gap-2">
                    <Link :href="answerCreate(question.data.id)">
                        <Plus class="size-4" />
                        Add Answer
                    </Link>
                </Button>
            </div>
        </section>

        <!-- Danger Zone -->
        <section class="
                border
                border-destructive/30
                bg-destructive/5
            ">
            <div class="flex items-center justify-between gap-6 p-6">
                <div class="min-w-0">
                    <h2 class="font-semibold text-destructive">
                        Delete Question
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Permanently delete this question and all of its answers.
                        This action cannot be undone.
                    </p>
                </div>

                <Button type="button" variant="destructive" class="shrink-0 gap-2" @click="deleteQuestion">
                    <Trash2 class="size-4" />
                    Delete Question
                </Button>
            </div>
        </section>

    </div>
</template>