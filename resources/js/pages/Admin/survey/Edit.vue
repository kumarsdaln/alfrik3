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

    import { edit, show, update } from '@/routes/admin/survey'

    import type {
        FormOption,
        Survey,
    } from '@/types'

    import { Form, Link } from '@inertiajs/vue3'

    interface Props {
        survey: Survey

        statusOptions: FormOption[]

        researches: {
            id: number
            title: string
        }[]
    }

    const props = defineProps<Props>()

    const formatDateTimeLocal = (
        value: string | null,
    ): string => {
        if (!value) {
            return ''
        }

        return value.slice(0, 16)
    }
</script>

<template>
    <div class="flex gap-4 py-5">
        <BackButton :href="show.url(survey.id)" />

        <Heading title="Edit Survey" :description="`Update the basic details and settings for ${survey.title}.`" />
    </div>

    <Form v-slot="{ errors, processing }" v-bind="update(survey.id).form()" class="space-y-4">
        <AppFormControl label="Title" required :error="errors.title">
            <AppInput name="title" :default-value="survey.title" placeholder="Enter survey title" />
        </AppFormControl>

        <AppFormControl label="Slug" required :error="errors.slug">
            <AppInput name="slug" :default-value="survey.slug" placeholder="Enter survey slug" />
        </AppFormControl>

        <AppFormControl label="Research" :error="errors.research_id">
            <AppSelect name="research_id" :default-value="survey.research_id ?? ''" placeholder="Select research"
                :options="researches.map((research) => ({
                    value: research.id,
                    label: research.title,
                }))
                    " />
        </AppFormControl>

        <AppFormControl label="Description" :error="errors.description">
            <AppTextarea name="description" :default-value="survey.description ?? ''"
                placeholder="Enter survey description" />
        </AppFormControl>

        <AppFormControl label="Survey Status" required :error="errors.status">
            <AppSelect name="status" :default-value="survey.status.value" placeholder="Select survey status"
                :options="statusOptions" />
        </AppFormControl>

        <div class="grid gap-4 md:grid-cols-2">
            <AppFormControl label="Starts At" :error="errors.starts_at">
                <AppInput name="starts_at" type="datetime-local" :default-value="formatDateTimeLocal(survey.starts_at)
                    " />
            </AppFormControl>

            <AppFormControl label="Ends At" :error="errors.ends_at">
                <AppInput name="ends_at" type="datetime-local" :default-value="formatDateTimeLocal(survey.ends_at)
                    " />
            </AppFormControl>
        </div>

        <div class="space-y-3">
            <AppCheckbox name="anonymous" true-value="1" false-value="0" :default-value="survey.anonymous ? '1' : '0'
                " label="Allow anonymous responses" />

            <AppCheckbox name="multiple_responses" true-value="1" false-value="0" :default-value="survey.multiple_responses ? '1' : '0'
                " label="Allow multiple responses" />

            <AppCheckbox name="featured" true-value="1" false-value="0" :default-value="survey.featured ? '1' : '0'
                " label="Feature this survey" />
        </div>

        <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
            <Button type="button" variant="outline" as-child>
                <Link :href="show.url(survey.id)">
                    Cancel
                </Link>
            </Button>

            <Button type="submit" :disabled="processing">
                {{ processing ? 'Processing...' : 'Update Survey' }}
            </Button>
        </div>
    </Form>
</template>