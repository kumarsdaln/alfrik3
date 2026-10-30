<script setup lang="ts">
    import { Link, Form } from '@inertiajs/vue3'

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

    import {
        index,
        store,
    } from '@/routes/admin/survey/questions'

    import type {
        FormOption,
        SurveySection,
    } from '@/types'

    interface Props {
        survey: {
            id: number
            title: string
        }

        sections: SurveySection[]

        typeOptions: FormOption[]
    }

    const props = defineProps<Props>()
</script>

<template>
    <div class="flex items-center gap-4 py-5">
        <BackButton />

        <Heading :title="`Questions — ${survey.title}`"
            description="Create and manage the questions used in this survey." />
    </div>

    <Form v-slot="{ errors, processing }" 
          v-bind="store.form({
            survey: survey.id,
          })" 
          class="space-y-4">

        <AppFormControl label="Question" required :error="errors.question">
            <AppInput name="question" placeholder="Enter survey question" />
        </AppFormControl>

        <AppFormControl label="Description" :error="errors.description">
            <AppTextarea name="description" placeholder="Add additional instructions or context" />
        </AppFormControl>

        <div class="grid gap-4 md:grid-cols-2">
            <AppFormControl label="Question Type" required :error="errors.type">
                <AppSelect name="type" placeholder="Select question type" :options="typeOptions" />
            </AppFormControl>

            <AppFormControl label="Section" :error="errors.section_id">
                <AppSelect name="section_id" placeholder="Select section" :options="sections.map((section) => ({
                    value: section.id,
                    label: section.title,
                }))" />
            </AppFormControl>
        </div>

        <AppFormControl label="Position" :error="errors.position">
            <AppInput name="position" type="number" min="0" placeholder="0" />
        </AppFormControl>

        <AppCheckbox name="required" true-value="1" false-value="0" label="Required question" />

        <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
            <Button type="button" variant="outline" as-child>
                <Link :href="index.url({
                    survey: survey.id,
                })">
                    Cancel
                </Link>
            </Button>

            <Button type="submit" :disabled="processing">
                {{
                    processing
                        ? 'Processing...'
                        : 'Save Question'
                }}
            </Button>
        </div>
    </Form>
</template>