<script setup lang="ts">
    import { Form, Link } from '@inertiajs/vue3'

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

    interface Props {
        typeOptions: FormOption[]
        statusOptions: FormOption[]
    }

    defineProps<Props>()
</script>

<template>
    <div class="space-y-6 py-5">
        <!-- Page Header -->
        <div class="flex items-start gap-4">
            <BackButton />

            <Heading title="Create Interview"
                description="Create an interview publication. You can manage participants, questions, answers, media, categories, tags, and SEO after creation." />
        </div>

        <Form v-slot="{ errors, processing }" v-bind="store.form()" :options="{
            preserveScroll: true,
        }" class="space-y-8">
            <!-- Basic Information -->
            <section class="space-y-5">
                <div class="border-b border-border-light pb-3 dark:border-border-dark">
                    <h2 class="text-base font-semibold">
                        Basic Information
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Define the interview title and its URL identifier.
                    </p>
                </div>

                <AppFormControl label="Title" required :error="errors.title">
                    <AppInput name="title" placeholder="Enter interview title" required />
                </AppFormControl>

                <AppFormControl label="Slug" required :error="errors.slug"
                    description="A unique, URL-friendly identifier for this interview.">
                    <AppInput name="slug" placeholder="enter-interview-slug" required />
                </AppFormControl>

                <AppFormControl label="Description" :error="errors.description"
                    description="A short editorial summary used in listings and previews.">
                    <AppTextarea name="description" placeholder="Write a concise summary of this interview..."
                        :rows="3" />
                </AppFormControl>
            </section>

            <!-- Editorial Content -->
            <section class="space-y-5">
                <div class="border-b border-border-light pb-3 dark:border-border-dark">
                    <h2 class="text-base font-semibold">
                        Editorial Content
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Establish the editorial context and structure of the interview.
                    </p>
                </div>

                <AppFormControl label="Introduction" :error="errors.introduction"
                    description="An introductory section providing context about the interview and its subject.">
                    <AppTextarea name="introduction" placeholder="Write the interview introduction..." :rows="6" />
                </AppFormControl>

                <AppFormControl label="Conclusion" :error="errors.conclusion"
                    description="A closing editorial section summarizing the discussion or highlighting its key message.">
                    <AppTextarea name="conclusion" placeholder="Write the interview conclusion..." :rows="6" />
                </AppFormControl>
            </section>

            <!-- Publication Settings -->
            <section class="space-y-5">
                <div class="border-b border-border-light pb-3 dark:border-border-dark">
                    <h2 class="text-base font-semibold">
                        Publication Settings
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Configure the interview format and publication schedule.
                    </p>
                </div>

                <AppFormControl label="Interview Type" required :error="errors.interview_type">
                    <AppSelect name="interview_type" placeholder="Select interview type" :options="typeOptions" />
                </AppFormControl>

                <AppFormControl label="Status" required :error="errors.status"
                    description="New interviews should normally remain drafts until their content is ready.">
                    <AppSelect name="status" placeholder="Select interview status" :options="statusOptions" />
                </AppFormControl>

                <AppFormControl label="Publication Date" :error="errors.published_at"
                    description="Set the publication date and time. Leave empty when publication has not been scheduled.">
                    <AppInput name="published_at" type="datetime-local" />
                </AppFormControl>
            </section>

            <!-- Form Actions -->
            <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
                <Button type="button" variant="outline" as-child>
                    <Link :href="index.url()">
                        Cancel
                    </Link>
                </Button>

                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Creating...' : 'Create Interview' }}
                </Button>
            </div>
        </Form>
    </div>
</template>