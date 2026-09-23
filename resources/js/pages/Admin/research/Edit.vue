<script setup lang="ts">
import {
    AppCheckbox,
    AppFormControl,
    AppInput,
    AppTextarea,
    AppSelect,
} from '@/components/form'
import Heading from '@/components/Heading.vue'
import BackButton from '@/components/ui/BackButton.vue'
import Button from '@/components/ui/button/Button.vue'
import { index, update } from '@/routes/admin/research'
import type { FormOption, Research } from '@/types'
import { Form, Link } from '@inertiajs/vue3'

interface Props {
    research: {
        data: Research
    }
    typeOptions: FormOption[]
    statusOptions: FormOption[]
}

const props = defineProps<Props>()
</script>

<template>
    <div class="flex gap-4 py-5">
        <BackButton />

        <Heading title="Edit Research"
            description="Update the basic details of this research.data. Methodology, sources, questions, findings, surveys, reports, and other details are managed separately." />
    </div>

    <div class="max-w-5xl pb-4">
        <Form v-slot="{ errors, processing }" v-bind="update.form(research.data.id)" :options="{
            preserveScroll: true,
        }" class="space-y-4">

            <AppFormControl label="Title" required :error="errors.title">
                <AppInput name="title" placeholder="Enter research title" :default-value="research.data.title" />
            </AppFormControl>

            <AppFormControl label="Slug" required :error="errors.slug">
                <AppInput name="slug" placeholder="Enter research slug" :default-value="research.data.slug" />
            </AppFormControl>

            <AppFormControl label="Subtitle" :error="errors.subtitle">
                <AppInput name="subtitle" placeholder="Enter research subtitle"
                    :default-value="research.data.subtitle ?? ''" />
            </AppFormControl>

            <AppFormControl label="Summary" :error="errors.summary">
                <AppTextarea name="summary" placeholder="Enter research summary"
                    :default-value="research.data.summary ?? ''" />
            </AppFormControl>

            <AppFormControl label="Description" :error="errors.description">
                <AppTextarea name="description" placeholder="Enter research description"
                    :default-value="research.data.description ?? ''" />
            </AppFormControl>

            <AppFormControl label="Research Type" required :error="errors.type">
                <AppSelect name="type" placeholder="Select research type" :options="typeOptions"
                    :default-value="research.data.type.value" />
            </AppFormControl>

            <AppFormControl label="Research Status" required :error="errors.status">
                <AppSelect name="status" placeholder="Select research status" :options="statusOptions"
                    :default-value="research.data.status.value" />
            </AppFormControl>

            <AppFormControl label="Published At" :error="errors.published_at">
                <AppInput name="published_at" type="datetime-local" :default-value="research.data.published_at
                    ? research.data.published_at.slice(0, 16)
                    : ''
                    " />
            </AppFormControl>

            <AppCheckbox name="featured" true-value="1" false-value="0" label="Feature this research"
                :default-value="research.data.featured ? true : false" />

            <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
                <Button type="button" variant="outline" as-child>
                    <Link :href="index()">
                        Cancel
                    </Link>
                </Button>

                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Processing...' : 'Update Research' }}
                </Button>
            </div>
        </Form>
    </div>
</template>