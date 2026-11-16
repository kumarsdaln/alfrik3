<script setup lang="ts">
    import { Form, router } from '@inertiajs/vue3'

    import {
        AppFormControl,
        AppTextarea,
        AppSelect,
    } from '@/components/form'

    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'

    import { store } from '@/routes/admin/interviews/questions/answers'
    import { edit as questionEdit } from '@/routes/admin/interviews/questions'

    import type { Participant, InterviewQuestion, Interview } from '@/types'
import BackButton from '@/components/ui/BackButton.vue'

    interface Props {
        interview: {
            data: Interview
        }
        question: {
            data: InterviewQuestion
        }
        interviewees: {
            data: Participant[]
        }
    }

    const props = defineProps<Props>()

    /*
    |--------------------------------------------------------------------------
    | Participant Options
    |--------------------------------------------------------------------------
    */

    const intervieweeOptions = props.interviewees.data.map((participant) => ({
        label: participant.user.name,
        value: participant.id,
    }))

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */

    function backToQuestion(): void {
        router.visit(questionEdit({
            interview: props.interview.data.id,
            question: props.question.data.id
        }).url)
    }
</script>

<template>
    <div class="mt-4 space-y-6">

        <!-- Header -->
         <div class="flex items-start gap-4">
            <BackButton />
            <Heading title="Add Answer" :description="`Add an answer to a question in ${interview.data.title}.`" />
        </div>
        

        <div class="max-w-3xl space-y-6 bg-card">
            <!-- Question Context -->
            <div class="rounded-md bg-muted p-4">
                <p class="text-sm font-medium">
                    Question
                </p>

                <p class="mt-2 whitespace-pre-wrap text-sm leading-6 text-muted-foreground">
                    {{ question.data.question }}
                </p>
            </div>

            <!-- Answer Form -->
            <Form v-slot="{ errors, processing }" v-bind="store.form({
                interview: interview.data.id,
                question: question.data.id,
            })" :options="{ preserveScroll: true }" class="space-y-6">
                <AppFormControl label="Answered By" required :error="errors.answered_by"
                    description="Select the interviewee responsible for this answer.">
                    <AppSelect name="answered_by" placeholder="Select interviewee" :options="intervieweeOptions" />
                </AppFormControl>

                <AppFormControl label="Answer" required :error="errors.answer"
                    description="Enter the interviewee's complete response.">
                    <AppTextarea name="answer" placeholder="Enter the answer..." :rows="8" required />
                </AppFormControl>

                <!-- Actions -->
                <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
                    <Button type="button" variant="outline" @click="backToQuestion">
                        Cancel
                    </Button>

                    <Button type="submit" :disabled="processing">
                        {{ processing ? 'Saving...' : 'Save Answer' }}
                    </Button>
                </div>
            </Form>
        </div>
    </div>
</template>