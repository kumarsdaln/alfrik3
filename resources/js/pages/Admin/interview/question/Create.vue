<script setup lang="ts">
    import { Form, Link } from '@inertiajs/vue3'

    import {
        AppFormControl,
        AppInput,
        AppSelect,
        AppTextarea,
    } from '@/components/form'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'

    import { index as questionsIndex, store } from '@/routes/admin/interviews/questions'

    import type { Participant, Interview } from '@/types'

    interface Props {
        interview: {
            data: Interview
        }
        interviewers: {
            data: Participant[]
        }
    }

    const props = defineProps<Props>()

    const interview = props.interview.data

    const interviewerOptions = props.interviewers.data.map((participant) => ({
        label: participant.user.name,
        value: participant.id,
    }))
</script>

<template>
    <div class="mt-4 space-y-6">
        <!-- Header -->
        <div class="flex items-start gap-4">
            <BackButton />

            <Heading title="Add Interview Question" :description="`Add a question to ${interview.title}.`" />
        </div>

        <Form v-slot="{ errors, processing }" v-bind="store.form(interview.id)" :options="{ preserveScroll: true }"
            class="max-w-3xl space-y-6">
            <!-- Question Details -->
            <section class="space-y-4">
                <div class="border-b border-border-light pb-3 dark:border-border-dark">
                    <h2 class="text-base font-semibold">
                        Question Details
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Add the question and select the interviewer who asked it.
                    </p>
                </div>

                <AppFormControl label="Asked By" :error="errors.asked_by"
                    description="Select the interviewer responsible for asking this question.">
                    <AppSelect name="asked_by" placeholder="Select interviewer" :options="interviewerOptions" />
                </AppFormControl>

                <AppFormControl label="Question" required :error="errors.question">
                    <AppTextarea name="question" placeholder="Enter the interview question..." :rows="4" required />
                </AppFormControl>

                <AppFormControl label="Position" :error="errors.position"
                    description="Controls the display order of this question.">
                    <AppInput name="position" type="number" min="0" :default-value="0" placeholder="0" />
                </AppFormControl>
            </section>

            <!-- Actions -->
            <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
                <Button type="button" variant="outline" as-child>
                    <Link :href="questionsIndex(interview.id).url">
                        Cancel
                    </Link>
                </Button>

                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Saving...' : 'Save Question' }}
                </Button>
            </div>
        </Form>
    </div>
</template>