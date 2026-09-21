<script setup lang="ts">
    import {
        AppCheckbox,
        AppFormControl,
        AppInput,
        AppSelect,
        AppTextarea,
    } from '@/components/form'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'

    import { edit, index, update } from '@/routes/admin/survey/questions'

    import type {
        FormOption,
        SurveyQuestion,
        SurveySection,
    } from '@/types'

    import { Form, Link } from '@inertiajs/vue3'

    interface Props {
        survey: {
            id: number
            title: string
        }

        question: SurveyQuestion

        sections: SurveySection[]

        typeOptions: FormOption[]
    }

    const props = defineProps<Props>()

    const formatSectionOptions = () => {
        return props.sections.map((section) => ({
            value: String(section.id),
            label: section.title,
        }))
    }
</script>

<template>
    <div class="flex gap-4 py-5">
        <BackButton :href="index.url({ survey: survey.id })" />

        <Heading title="Edit Survey Question" :description="`Update the question for ${survey.title}.`" />
    </div>

    <Form v-slot="{ errors, processing }" v-bind="update({
        survey: survey.id,
        question: question.id,
    }).form()" class="space-y-4">
        <AppFormControl label="Question" required :error="errors.question">
            <AppInput name="question" :default-value="question.question" placeholder="Enter survey question" />
        </AppFormControl>

        <AppFormControl label="Description" :error="errors.description">
            <AppTextarea name="description" :default-value="question.description ?? ''"
                placeholder="Add additional instructions or context" />
        </AppFormControl>

        <div class="grid gap-4 md:grid-cols-2">
            <AppFormControl label="Question Type" required :error="errors.type">
                <AppSelect name="type" :default-value="question.type.value" :options="typeOptions"
                    placeholder="Select question type" />
            </AppFormControl>

            <AppFormControl label="Section" :error="errors.section_id">
                <AppSelect name="section_id" :default-value="question.section_id
                        ? String(question.section_id)
                        : undefined
                    " :options="formatSectionOptions()" placeholder="Select section" />
            </AppFormControl>
        </div>

        <div class="grid gap-4 md:grid-cols-2">
            <AppFormControl label="Category" :error="errors.category">
                <AppInput name="category" :default-value="question.category ?? ''" placeholder="Optional category" />
            </AppFormControl>

            <AppFormControl label="Position" :error="errors.position">
                <AppInput name="position" type="number" min="0" :default-value="question.position" />
            </AppFormControl>
        </div>

        <AppCheckbox name="required" true-value="1" false-value="0" label="Required question"
            :default-value="question.required ? '1' : '0'" />

        <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
            <Button type="button" variant="outline" as-child>
                <Link :href="index.url({ survey: survey.id })">
                    Cancel
                </Link>
            </Button>

            <Button type="submit" :disabled="processing">
                {{ processing ? 'Processing...' : 'Update Question' }}
            </Button>
        </div>
    </Form>
</template>