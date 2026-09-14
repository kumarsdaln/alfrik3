<script setup lang="ts">
    import { Form } from '@inertiajs/vue3'

    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'

    import { store } from '@/routes/admin/interviews/questions/answers'
    import { edit as questionEdit } from '@/routes/admin/interviews/questions'
    import AppSelect from '@/components/form/AppSelect.vue'
    import { InterviewParticipant } from '@/types'

    const props = defineProps<{
        question: {
            id: number
            question: string
            interview: {
                id: number
                title: string
            }
        }
        interviewees: {
            data: InterviewParticipant[]
        }
    }>()
</script>

<template>
    <div class="space-y-6">
        <Heading title="Add Answer" :description="`Add an answer to a question in ${question.interview.title}`" />

        <div class="rounded-lg border bg-card p-6">
            <div class="mb-6 rounded-md bg-muted p-4">
                <p class="text-sm font-medium">
                    Question
                </p>

                <p class="mt-2 whitespace-pre-wrap text-sm text-muted-foreground">
                    {{ question.question }}
                </p>
            </div>

            <Form v-slot="{ errors, processing }" v-bind="store.form(question.id)" class="space-y-6">
                <AppFormControl label="Answer By" :error="errors.answered_by" required>
                    <AppSelect name="answered_by" label="Answer By" placeholder="Select interviewee" :options="interviewees.data.map(participant => ({
                        label: participant.user.name,
                        value: participant.user.id,
                    }))" />
                </AppFormControl>
                <AppFormControl label="Answer" :error="errors.answer" required>
                    <AppTextarea name="answer" placeholder="Enter answer" :rows="8" />
                </AppFormControl>

                <div class="flex items-center justify-between">
                    <Button type="button" variant="outline" @click="$inertia.visit(questionEdit(question.id).url)">
                        Cancel
                    </Button>
                    <Button type="submit" :disabled="processing">
                        {{ processing ? 'Processing...' : 'Save Answer' }}
                    </Button>
                </div>
            </Form>
        </div>
    </div>
</template>