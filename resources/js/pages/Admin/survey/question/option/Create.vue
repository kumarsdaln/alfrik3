<script setup lang="ts">
    import { Link, Form } from '@inertiajs/vue3'

    import {
        AppCheckbox,
        AppFormControl,
        AppInput,
    } from '@/components/form'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import {
        index,
        store,
    } from '@/routes/admin/survey/questions/options'
    import { SurveyQuestion } from '@/types'

    interface Props {
        survey: {
            id: number
            title: string
        }

        question: {
            data: SurveyQuestion
        }
    }

    const props = defineProps<Props>()
</script>

<template>
    <div class="flex items-center gap-4 py-5">
        <BackButton />
        <Heading title="Question Options" description="Manage the answer options available for this survey question." />
    </div>

    <div class="space-y-6 py-6">
        <!-- Question -->
        <div class="mb-2 flex items-center gap-2">
            <Badge variant="outline" :color="question.data.type.color">
                {{ question.data.type.label }}
            </Badge>
        </div>

        <h2 class="font-medium">
            {{ question.data.question }}
        </h2>

        <Form v-slot="{ errors, processing }" v-bind="store.form({
            survey: survey.id,
            question: question.data.id,
        })" class="space-y-4">
            <div class="grid gap-4 md:grid-cols-2">
                <AppFormControl label="Label" required :error="errors.label">
                    <AppInput name="label" placeholder="e.g. Very Satisfied" />
                </AppFormControl>

                <AppFormControl label="Value" required :error="errors.value">
                    <AppInput name="value" placeholder="e.g. very_satisfied" />
                </AppFormControl>
            </div>

            <AppFormControl label="Position" :error="errors.position">
                <AppInput name="position" type="number" min="0" placeholder="0" />
            </AppFormControl>

            <AppCheckbox name="is_other" true-value="1" false-value="0"
                label="Allow respondent to enter another answer" />

            <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
                <Button type="button" variant="outline" as-child>
                    <Link :href="index.url({
                        survey: survey.id,
                        question: question.data.id,
                    })">
                        Cancel
                    </Link>
                </Button>

                <Button type="submit" :disabled="processing">
                    {{
                        processing
                            ? 'Processing...'
                            : 'Add Option'
                    }}
                </Button>
            </div>
        </Form>
    </div>
</template>