<script setup lang="ts">
    import {
        AppFormControl,
        AppInput,
        AppTextarea,
        AppSelect,
    } from '@/components/form'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import { Form, Link } from '@inertiajs/vue3'

    import type { FormOption } from '@/types'

    interface Props {
        research: {
            id: number
            title: string
        }

        question: {
            id: number
            question: string
            description: string | null
            type: {
                value: string
                label: string
            }
            position: number
        }

        questionTypeOptions: FormOption[]
    }

    const props = defineProps<Props>()
</script>

<template>
    <div class="flex gap-4 py-5">
        <BackButton />

        <Heading title="Edit Research Question" :description="`Update the research question for ${research.title}.`" />
    </div>

    <Form v-slot="{ errors, processing }" :action="`/admin/research/${research.id}/questions/${question.id}`"
        method="put" class="space-y-4">
        <AppFormControl label="Question" required :error="errors.question">
            <AppInput name="question" placeholder="Enter research question" :default-value="question.question" />
        </AppFormControl>

        <AppFormControl label="Description" :error="errors.description">
            <AppTextarea name="description" placeholder="Add additional context..."
                :default-value="question.description ?? ''" />
        </AppFormControl>

        <AppFormControl label="Question Type" required :error="errors.type">
            <AppSelect name="type" placeholder="Select question type" :options="questionTypeOptions"
                :default-value="question.type.value" />
        </AppFormControl>

        <AppFormControl label="Position" :error="errors.position">
            <AppInput name="position" type="number" min="0" :default-value="question.position" />
        </AppFormControl>

        <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
            <Button type="button" variant="outline" as-child>
                <Link :href="`/admin/research/${research.id}/questions`">
                    Cancel
                </Link>
            </Button>

            <Button type="submit" :disabled="processing">
                {{ processing ? 'Processing...' : 'Update Question' }}
            </Button>
        </div>
    </Form>
</template>