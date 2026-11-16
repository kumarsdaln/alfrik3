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
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'

    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'

    import {
        index as questionsIndex,
        update as questionUpdate,
        destroy as questionDestroy,
    } from '@/routes/admin/interviews/questions'

    import {
        create as answerCreate,
        edit as answerEdit,
        destroy as answerDestroy,
    } from '@/routes/admin/interviews/questions/answers'

    import type {
        Participant,
        InterviewQuestion,
        Interview,
    } from '@/types'
    import BackButton from '@/components/ui/BackButton.vue'

    interface Props {
        interview: {
            data: Interview
        }
        question: {
            data: InterviewQuestion
        }

        interviewers: {
            data: Participant[]
        }
    }

    const props = defineProps<Props>()
    /*
    |--------------------------------------------------------------------------
    | Participant Options
    |--------------------------------------------------------------------------
    */

    const interviewerOptions = props.interviewers.data.map((participant) => ({
        label: participant.user.name,
        value: participant.id,
    }))


    /*
    |--------------------------------------------------------------------------
    | Answer Actions
    |--------------------------------------------------------------------------
    */

    function deleteAnswer(answerId: number): void {
        if (!confirm('Are you sure you want to delete this answer? This action cannot be undone.')) {
            return
        }

        router.delete(answerDestroy({
            interview: props.interview.data.id,
            question: props.question.data.id,
            answer: answerId,
        }).url, {
            preserveScroll: true,
        })
    }

    /*
    |--------------------------------------------------------------------------
    | Question Actions
    |--------------------------------------------------------------------------
    */

    function deleteQuestion(): void {
        if (
            !confirm(
                'Are you sure you want to delete this question? All answers belonging to this question will also be deleted.',
            )
        ) {
            return
        }

        router.delete(questionDestroy({
            interview: props.interview.data.id,
            question: props.question.data.id,
        }).url, {
            preserveScroll: true,
            onSuccess: () => {
                router.visit(questionsIndex(props.interview.data.id).url)
            },
        })
    }
</script>

<template>
    <div class="my-4 space-y-8">
        <!-- Header -->
        <div class="flex items-start gap-4">
            <BackButton />

            <Heading title="Edit Question"
                :description="`Manage this question and its answers for ${props.interview.data.title}.`" />
        </div>

        <!-- Question Form -->
        <section>
            <Form v-slot="{ errors, processing }" v-bind="questionUpdate.form({
                interview: interview.data.id,
                question: question.data.id
            })" :options="{ preserveScroll: true }" class="space-y-6">
                <AppFormControl label="Question By" :error="errors.asked_by"
                    description="Select the interviewer who asked this question.">
                    <AppSelect name="asked_by" :default-value="props.question.data.asked_by?.id"
                        placeholder="Select interviewer" :options="interviewerOptions" />
                </AppFormControl>

                <AppFormControl label="Question" required :error="errors.question">
                    <AppTextarea name="question" :default-value="props.question.data.question"
                        placeholder="Enter interview question..." :rows="6" required />
                </AppFormControl>

                <AppFormControl label="Position" :error="errors.position"
                    description="Controls the order in which questions are displayed.">
                    <AppInput name="position" type="number" min="0" :default-value="props.question.data.position" />
                </AppFormControl>

                <!-- Form Actions -->
                <div
                    class="flex items-center justify-between gap-3 border-t border-border-light pt-6 dark:border-border-dark">
                    <Button type="button" variant="outline"
                        @click="router.visit(questionsIndex(interview.data.id).url)">
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
                    <Link :href="answerCreate({
                        interview: interview.data.id,
                        question: question.data.id,
                    })">
                        <Plus class="size-4" />
                        Add Answer
                    </Link>
                </Button>
            </div>

            <!-- Answer List -->
            <div v-if="question.data.answers" class="divide-y">
                <article v-for="answer in question.data.answers" :key="answer.id"
                    class="p-6 transition-colors hover:bg-muted/30">
                    <div class="flex items-start justify-between gap-6">
                        <!-- Answer Content -->
                        <div class="min-w-0 flex-1">
                            <p class="whitespace-pre-wrap text-sm leading-6">
                                {{ answer.answer }}
                            </p>

                            <div v-if="answer.answered_by"
                                class="mt-4 flex items-center gap-2 text-xs text-muted-foreground">
                                <span>Answered by</span>

                                <span class="font-medium text-foreground">
                                    {{ answer.answered_by.user.name }}
                                </span>
                            </div>
                        </div>

                        <!-- Answer Actions -->
                        <div class="flex shrink-0 items-center gap-2">
                            <Button as-child type="button" variant="outline" size="icon">
                                <Link :href="answerEdit({
                                    interview: interview.data.id,
                                    question: question.data.id,
                                    answer: answer.id,
                                })">
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

            <!-- Empty State -->
            <div v-else class="flex flex-col items-center justify-center px-6 py-16 text-center">
                <div class="mb-4 flex size-12 items-center justify-center rounded-full bg-muted">
                    <Plus class="size-5 text-muted-foreground" />
                </div>

                <h3 class="text-sm font-semibold">
                    No answers yet
                </h3>

                <p class="mt-1 max-w-sm text-sm text-muted-foreground">
                    Add the first answer to this interview question.
                </p>

                <Button as-child variant="outline" class="mt-5 gap-2">
                    <Link :href="answerCreate({
                        interview: interview.data.id,
                        question: question.data.id,
                    })">
                        <Plus class="size-4" />
                        Add Answer
                    </Link>
                </Button>
            </div>
        </section>

        <!-- Danger Zone -->
        <section class="border border-destructive/30 bg-destructive/5">
            <div class="flex items-center justify-between gap-6 p-6">
                <div class="min-w-0">
                    <h2 class="font-semibold text-destructive">
                        Delete Question
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Permanently delete this question and all its answers.
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