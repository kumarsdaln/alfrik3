<script setup lang="ts">
    import {
        AppFormControl,
        AppInput,
        AppTextarea,
        AppSelect,
    } from '@/components/form'
    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'
    import { index, update } from '@/routes/admin/interviews'
    import type { FormOption, Interview } from '@/types'
    import { Form, Link } from '@inertiajs/vue3'

    interface Props {
        interview: {
            data: Interview
        }
        typeOptions: FormOption[]
        statusOptions: FormOption[]
    }

    const props = defineProps<Props>()
</script>

<template>
    <div class="mt-4">
        <Heading title="Edit Interview" description="Update the basic information of this interview." />

        <Form v-slot="{ errors, processing }" v-bind="update.form(interview.data.id)" class="space-y-4">
            <AppFormControl label="Title" required :error="errors.title">
                <AppInput name="title" v-model="interview.data.title" placeholder="Enter interview title" />
            </AppFormControl>

            <AppFormControl label="Slug" required :error="errors.slug">
                <AppInput name="slug" v-model="interview.data.slug" placeholder="Enter interview slug" />
            </AppFormControl>

            <AppFormControl label="Description" :error="errors.description">
                <AppTextarea name="description" v-model="interview.data.description" placeholder="Enter interview description" />
            </AppFormControl>

            <AppFormControl label="Interview Type" required :error="errors.interview_type">
                <AppSelect name="interview_type" v-model="interview.data.interview_type.value" placeholder="Select interview type" :options="typeOptions" />
            </AppFormControl>

            <AppFormControl label="Interview Status" required :error="errors.status">
                <AppSelect name="status" v-model="interview.data.status.value" placeholder="Select interview status" :options="statusOptions" />
            </AppFormControl>

            <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
                <Button type="button" variant="outline" as-child>
                    <Link :href="index.url()">
                        Back
                    </Link>
                </Button>

                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Processing...' : 'Save Changes' }}
                </Button>
            </div>
        </Form>
    </div>
</template>