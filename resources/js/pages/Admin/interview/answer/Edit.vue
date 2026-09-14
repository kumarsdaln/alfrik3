<script setup lang="ts">
    import { Form, router } from '@inertiajs/vue3'

    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'

    import { update, destroy } from '@/routes/admin/interviews/questions/answers'
    import { edit as questionEdit } from '@/routes/admin/interviews/questions'
import { InterviewParticipant } from '@/types'
import AppSelect from '@/components/form/AppSelect.vue'

    const props = defineProps<{
        answer: {
            id: number
            answer: string
            answered_by: {
                id: number
                name: string
                email: string
            } | null
            question: {
                id: number
                question: string
            }
            interview: {
                id: number
                title: string
            }
        }
        interviewees: {
            data: InterviewParticipant[]
        }
    }>()
    const backToQuestion = () => {
        router.visit(questionEdit(props.answer.question.id).url)
    }
</script>

<template>
    <div class="space-y-6 my-4">
        <Heading title="Edit Answer" :description="`Edit answer for ${answer.interview.title}`" />

        <!-- Question -->
        <div>
            <div class="mb-6">
                <h2 class="text-lg font-semibold">
                    Question
                </h2>

                <p class="mt-2 whitespace-pre-wrap text-sm text-muted-foreground">
                    {{ answer.question.question }}
                </p>
            </div>

            <Form v-slot="{ errors, processing }" v-bind="update.form(answer.id)" class="space-y-6">
                <AppFormControl label="Answer By" :error="errors.answered_by" required>
                    <AppSelect name="answered_by" label="Answer By" placeholder="Select interviewee" :options="interviewees.data.map(participant => ({
                        label: participant.user.name,
                        value: participant.user.id,
                    }))" />
                </AppFormControl>
                <AppFormControl label="Answer" :error="errors.answer" required>
                    <AppTextarea name="answer" v-model="answer.answer" placeholder="Enter answer" :rows="10" />
                </AppFormControl>

                <div class="flex items-center justify-between">
                    <Button type="button" variant="outline" @click="backToQuestion">
                        Cancel
                    </Button>

                    <Button type="submit" :disabled="processing">
                        {{ processing ? 'Processing...' : 'Update Answer' }}
                    </Button>
                </div>
            </Form>
        </div>

        <!-- Answer Information -->
        <div class="border bg-card p-6">
            <h2 class="text-lg font-semibold">
                Answer Information
            </h2>

            <div class="mt-4">
                <p class="text-sm text-muted-foreground">
                    Answered by
                </p>

                <p class="mt-1 text-sm font-medium">
                    {{ answer.answered_by?.name ?? 'Unknown' }}
                </p>

                <p v-if="answer.answered_by?.email" class="text-sm text-muted-foreground">
                    {{ answer.answered_by.email }}
                </p>
            </div>
        </div>

        <!-- Delete -->
        <div class="border border-destructive/30 bg-destructive/5 p-6">
            <div class="flex items-center justify-between gap-6">
                <div>
                    <h2 class="font-semibold">
                        Delete Answer
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        This action cannot be undone.
                    </p>
                </div>

                <Form :action="destroy(answer.id).url" method="delete">
                    <Button type="submit" variant="destructive">
                        Delete Answer
                    </Button>
                </Form>
            </div>
        </div>
    </div>
</template>