<script setup lang="ts">
    import {
        AppFormControl,
        AppInput,
    } from '@/components/form'
import AppSelect from '@/components/form/AppSelect.vue'
import AppTextarea from '@/components/form/AppTextarea.vue'
    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'
    import {
        index as interviewIndex,
    } from '@/routes/admin/interviews'
    import { store } from '@/routes/admin/interviews/questions'
    import type { Interview, InterviewParticipant } from '@/types'
    import { Form, Link } from '@inertiajs/vue3'

    interface Props {
        interview: {
            data: Interview
        },
        interviewers: {
            data: InterviewParticipant[]
        }
    }
    const props = defineProps<Props>()
</script>

<template>
    <div class="mt-4">
        <Heading title="Add Question" :description="`Add a question to ${interview.data.title}.`" />

        <Form v-slot="{ errors, processing }" v-bind="store.form(interview.data.id)" class="mt-6 max-w-3xl space-y-4">
           <AppFormControl label="Question By" required :error="errors.asked_by">
                <AppSelect name="asked_by" placeholder="Select interviewer" :options="interviewers.data.map(participant => ({
                    label: participant.user.name,
                    value: participant.user.id,
                }))" />
            </AppFormControl>
            <AppFormControl label="Question" required :error="errors.question">
                <AppTextarea name="question" placeholder="Enter interview question" />
            </AppFormControl>
           

            <AppFormControl label="Position" :error="errors.position">
                <AppInput name="position" type="number" min="0" placeholder="Enter question position" />
            </AppFormControl>

            <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
                <Button type="button" variant="outline" as-child>
                    <Link :href="interviewIndex.url()">
                        Cancel
                    </Link>
                </Button>

                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Processing...' : 'Save Question' }}
                </Button>
            </div>
        </Form>
    </div>
</template>