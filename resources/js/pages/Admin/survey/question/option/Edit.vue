<script setup lang="ts">
    import {
        AppCheckbox,
        AppFormControl,
        AppInput,
    } from '@/components/form'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'

    import { index, update } from '@/routes/admin/survey/questions/options'

    import type { SurveyQuestionOption } from '@/types'

    import { Form, Link } from '@inertiajs/vue3'

    interface Props {
        survey: {
            id: number
            title: string
        }

        question: {
            id: number
            survey_id: number
            question: string
        }

        option: SurveyQuestionOption
    }

    defineProps<Props>()
</script>

<template>
    <div class="flex gap-4 py-5">
        <BackButton :href="index.url({
            question: question.id,
        })" />

        <Heading title="Edit Question Option" :description="`Update the answer option for ${survey.title}.`" />
    </div>

    <Form v-slot="{ errors, processing }" v-bind="update({
        question: question.id,
        option: option.id,
    }).form()
        " class="space-y-4">
        <!-- Question -->
        <div class="rounded-lg border border-border-light p-4 dark:border-border-dark">
            <div class="text-sm text-muted-foreground">
                Question
            </div>

            <div class="mt-1 font-medium">
                {{ question.question }}
            </div>
        </div>

        <AppFormControl label="Label" required :error="errors.label">
            <AppInput name="label" :default-value="option.label" placeholder="e.g. Very Satisfied" />
        </AppFormControl>

        <AppFormControl label="Value" required :error="errors.value">
            <AppInput name="value" :default-value="option.value" placeholder="e.g. very_satisfied" />
        </AppFormControl>

        <AppFormControl label="Position" :error="errors.position">
            <AppInput name="position" type="number" min="0" :default-value="option.position" placeholder="0" />
        </AppFormControl>

        <AppCheckbox name="is_other" true-value="1" false-value="0" label="Allow respondent to enter another answer"
            :default-value="option.is_other ? '1' : '0'" />

        <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
            <Button type="button" variant="outline" as-child>
                <Link :href="index.url({
                    question: question.id,
                })
                    ">
                    Cancel
                </Link>
            </Button>

            <Button type="submit" :disabled="processing">
                {{ processing ? 'Processing...' : 'Update Option' }}
            </Button>
        </div>
    </Form>
</template>