
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

const interview = props.interview.data
</script>

<template>
    <div class="space-y-6 py-5">
        <!-- Header -->
        <div class="flex items-start gap-4">
            <BackButton />

            <Heading
                title="Edit Interview"
                description="Update the interview's basic information and editorial content. Participants, questions, answers, media, categories, tags, and SEO can be managed separately."
            />
        </div>

        <Form
            v-slot="{ errors, processing }"
            v-bind="update.form(interview.id)"
            :options="{ preserveScroll: true }"
            class="space-y-8"
        >
            <!-- Basic Information -->
            <section class="space-y-5">
                <div class="border-b border-border-light pb-3 dark:border-border-dark">
                    <h2 class="text-base font-semibold">
                        Basic Information
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Update the interview title and URL identifier.
                    </p>
                </div>

                <AppFormControl
                    label="Title"
                    required
                    :error="errors.title"
                >
                    <AppInput
                        name="title"
                        :default-value="interview.title"
                        placeholder="Enter interview title"
                        required
                    />
                </AppFormControl>

                <AppFormControl
                    label="Slug"
                    required
                    :error="errors.slug"
                    description="The unique, URL-friendly identifier for this interview."
                >
                    <AppInput
                        name="slug"
                        :default-value="interview.slug"
                        placeholder="Enter interview slug"
                        required
                    />
                </AppFormControl>

                <AppFormControl
                    label="Description"
                    :error="errors.description"
                    description="A short editorial summary displayed in listings and previews."
                >
                    <AppTextarea
                        name="description"
                        :default-value="interview.description ?? ''"
                        placeholder="Write a short interview summary..."
                        :rows="3"
                    />
                </AppFormControl>
            </section>

            <!-- Editorial Content -->
            <section class="space-y-5">
                <div class="border-b border-border-light pb-3 dark:border-border-dark">
                    <h2 class="text-base font-semibold">
                        Editorial Content
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Update the introduction and conclusion of the interview.
                    </p>
                </div>

                <AppFormControl
                    label="Introduction"
                    :error="errors.introduction"
                    description="Provide context about the interview and its subject."
                >
                    <AppTextarea
                        name="introduction"
                        :default-value="interview.introduction ?? ''"
                        placeholder="Write the interview introduction..."
                        :rows="6"
                    />
                </AppFormControl>

                <AppFormControl
                    label="Conclusion"
                    :error="errors.conclusion"
                    description="Write the closing editorial section of the interview."
                >
                    <AppTextarea
                        name="conclusion"
                        :default-value="interview.conclusion ?? ''"
                        placeholder="Write the interview conclusion..."
                        :rows="6"
                    />
                </AppFormControl>
            </section>

            <!-- Publication Settings -->
            <section class="space-y-5">
                <div class="border-b border-border-light pb-3 dark:border-border-dark">
                    <h2 class="text-base font-semibold">
                        Publication Settings
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Manage the interview format and publication status.
                    </p>
                </div>

                <AppFormControl
                    label="Interview Type"
                    required
                    :error="errors.interview_type"
                >
                    <AppSelect
                        name="interview_type"
                        :default-value="interview.interview_type.value"
                        placeholder="Select interview type"
                        :options="typeOptions"
                    />
                </AppFormControl>

                <AppFormControl
                    label="Status"
                    required
                    :error="errors.status"
                >
                    <AppSelect
                        name="status"
                        :default-value="interview.status.value"
                        placeholder="Select interview status"
                        :options="statusOptions"
                    />
                </AppFormControl>

                <AppFormControl
                    label="Published At"
                    :error="errors.published_at"
                    description="Set the publication date and time. Leave empty if publication has not been scheduled."
                >
                    <AppInput
                        name="published_at"
                        type="datetime-local"
                        :default-value="interview.published_at ?? ''"
                    />
                </AppFormControl>
            </section>

            <!-- Actions -->
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
                    {{ processing ? 'Saving...' : 'Save Changes' }}
                </Button>
            </div>
        </Form>
    </div>
</template>
