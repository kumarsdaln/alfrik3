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

    import { index, store } from '@/routes/admin/survey'

    import type { FormOption } from '@/types'

    import { Form, Link } from '@inertiajs/vue3'

    interface Props {
        statusOptions: FormOption[]

        researches: {
            id: number
            title: string
        }[]
    }

    defineProps<Props>()
</script>

<template>
    <div class="flex gap-4 py-5">
        <BackButton />

        <Heading title="Create Survey"
            description="Create a survey and configure its basic settings. Questions, sections, and responses can be managed after creation." />
    </div>

    <Form v-slot="{ errors, processing }" v-bind="store.form()" class="space-y-4">
        <AppFormControl label="Title" required :error="errors.title">
            <AppInput name="title" placeholder="Enter survey title" />
        </AppFormControl>

        <AppFormControl label="Slug" required :error="errors.slug">
            <AppInput name="slug" placeholder="Enter survey slug" />
        </AppFormControl>

        <AppFormControl label="Research" :error="errors.research_id">
            <AppSelect name="research_id" placeholder="Select research" :options="researches.map((research) => ({
                value: research.id,
                label: research.title,
            }))
                " />
        </AppFormControl>

        <AppFormControl label="Description" :error="errors.description">
            <AppTextarea name="description" placeholder="Enter survey description" />
        </AppFormControl>

        <AppFormControl label="Survey Status" required :error="errors.status">
            <AppSelect name="status" placeholder="Select survey status" :options="statusOptions" />
        </AppFormControl>

        <div class="grid gap-4 md:grid-cols-2">
            <AppFormControl label="Starts At" :error="errors.starts_at">
                <AppInput name="starts_at" type="datetime-local" />
            </AppFormControl>

            <AppFormControl label="Ends At" :error="errors.ends_at">
                <AppInput name="ends_at" type="datetime-local" />
            </AppFormControl>
        </div>

        <div class="space-y-3">
            <AppCheckbox name="anonymous" true-value="1" false-value="0" label="Allow anonymous responses" />

            <AppCheckbox name="multiple_responses" true-value="1" false-value="0" label="Allow multiple responses" />

            <AppCheckbox name="featured" true-value="1" false-value="0" label="Feature this survey" />
        </div>

        <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
            <Button type="button" variant="outline" as-child>
                <Link :href="index.url()">
                    Cancel
                </Link>
            </Button>

            <Button type="submit" :disabled="processing">
                {{ processing ? 'Processing...' : 'Save & Next' }}
            </Button>
        </div>
    </Form>
</template>