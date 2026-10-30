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

    import { index, store } from '@/routes/admin/interviews'

    import type { FormOption } from '@/types'

    import { Form, Link } from '@inertiajs/vue3'

    interface Props {
        typeOptions: FormOption[]
        statusOptions: FormOption[]
    }

    defineProps<Props>()
</script>

<template>
    <div class="space-y-6 py-5">
        <div class="flex gap-4">
            <BackButton />

            <Heading
                title="Create Interview"
                description="Create an interview with the basic information. Participants, questions, answers, and other details can be managed after creation."
            />
        </div>

        <Form
            v-slot="{ errors, processing }"
            v-bind="store.form()"
            class="space-y-4"
        >
            <AppFormControl
                label="Title"
                required
                :error="errors.title"
            >
                <AppInput
                    name="title"
                    placeholder="Enter interview title"
                />
            </AppFormControl>

            <AppFormControl
                label="Slug"
                required
                :error="errors.slug"
            >
                <AppInput
                    name="slug"
                    placeholder="Enter interview slug"
                />
            </AppFormControl>

            <AppFormControl
                label="Description"
                :error="errors.description"
            >
                <AppTextarea
                    name="description"
                    placeholder="Enter interview description"
                />
            </AppFormControl>

            <AppFormControl
                label="Interview Type"
                required
                :error="errors.interview_type"
            >
                <AppSelect
                    name="interview_type"
                    placeholder="Select interview type"
                    :options="typeOptions"
                />
            </AppFormControl>

            <AppFormControl
                label="Interview Status"
                required
                :error="errors.status"
            >
                <AppSelect
                    name="status"
                    placeholder="Select interview status"
                    :options="statusOptions"
                />
            </AppFormControl>

            <AppFormControl
                label="Published At"
                :error="errors.published_at"
            >
                <AppInput
                    name="published_at"
                    type="datetime-local"
                />
            </AppFormControl>

            <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
                <Button
                    type="button"
                    variant="outline"
                    as-child
                >
                    <Link :href="index.url()">
                        Cancel
                    </Link>
                </Button>

                <Button
                    type="submit"
                    :disabled="processing"
                >
                    {{ processing ? 'Processing...' : 'Save & Next' }}
                </Button>
            </div>
        </Form>
    </div>
</template>