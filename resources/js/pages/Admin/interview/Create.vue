<script setup lang="ts">
    import {
        AppFormControl,
        AppInput,
        AppTextarea,
        AppSelect,
        AppCheckbox,
    } from '@/components/form'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import { index, store } from '@/routes/admin/research'
    import type { FormOption } from '@/types'
    import { Form, Link } from '@inertiajs/vue3'

    interface Props {
        typeOptions: FormOption[]
        statusOptions: FormOption[]
    }

    defineProps<Props>()
</script>

<template>
    <div class="flex gap-4 py-5">
        <BackButton />

        <Heading title="Create Research"
            description="Create a research project with the basic details. You can add methodology, sources, questions, findings, surveys, reports, and other details in the next steps." />
    </div>

    <Form v-slot="{ errors, processing }" v-bind="store.form()" class="space-y-4">
        <AppFormControl label="Title" required :error="errors.title">
            <AppInput name="title" placeholder="Enter research title" />
        </AppFormControl>

        <AppFormControl label="Slug" required :error="errors.slug">
            <AppInput name="slug" placeholder="Enter research slug" />
        </AppFormControl>

        <AppFormControl label="Subtitle" :error="errors.subtitle">
            <AppInput name="subtitle" placeholder="Enter research subtitle" />
        </AppFormControl>

        <AppFormControl label="Summary" :error="errors.summary">
            <AppTextarea name="summary" placeholder="Enter research summary" />
        </AppFormControl>

        <AppFormControl label="Description" :error="errors.description">
            <AppTextarea name="description" placeholder="Enter research description" />
        </AppFormControl>

        <AppFormControl label="Research Type" required :error="errors.type">
            <AppSelect name="type" placeholder="Select research type" :options="typeOptions" />
        </AppFormControl>

        <AppFormControl label="Research Status" required :error="errors.status">
            <AppSelect name="status" placeholder="Select research status" :options="statusOptions" />
        </AppFormControl>

        <AppFormControl label="Published At" :error="errors.published_at">
            <AppInput name="published_at" type="datetime-local" />
        </AppFormControl>

        <AppCheckbox name="featured" true-value="1" false-value="0" label="Feature this research" />

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