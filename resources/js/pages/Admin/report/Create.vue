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

import { Form, Link } from '@inertiajs/vue3'

import {
    index,
    store,
} from '@/routes/admin/report'

import type { FormOption } from '@/types'


interface Props {
    authorOptions: FormOption[]
    typeOptions: FormOption[]
    statusOptions: FormOption[]
}

defineProps<Props>()
</script>


<template>
    <div class="space-y-6">

        <!-- Header -->

        <div class="flex gap-4 py-5">
            <BackButton />

            <Heading title="Create Report"
                description="Create the basic details of this report. Sections and content blocks are managed separately." />
        </div>


        <!-- Form -->

        <Form v-slot="{ errors, processing }" v-bind="store.form()" :options="{
            preserveScroll: true,
        }" class="space-y-6">

            <div class="grid gap-6 lg:grid-cols-2">

                <!-- ===================================================== -->
                <!-- Basic Information -->
                <!-- ===================================================== -->
                <div class="space-y-5 rounded-lg border bg-background p-6">

                    <div class="mb-6">
                        <h2 class="text-base font-semibold">
                            Report Information
                        </h2>

                        <p class="mt-1 text-sm text-muted-foreground">
                            Define the basic information for this report.
                        </p>
                    </div>


                    <!-- Title -->
                    <AppFormControl label="Title" required :error="errors.title">
                        <AppInput name="title" placeholder="Enter report title" />
                    </AppFormControl>


                    <!-- Slug -->
                    <AppFormControl label="Slug" required :error="errors.slug">
                        <AppInput name="slug" placeholder="Enter report slug" />
                    </AppFormControl>


                    <!-- Subtitle -->
                    <AppFormControl label="Subtitle" :error="errors.subtitle">
                        <AppInput name="subtitle" placeholder="Enter report subtitle" />
                    </AppFormControl>


                    <!-- Summary -->
                    <AppFormControl label="Summary" :error="errors.summary">
                        <AppTextarea name="summary" placeholder="Enter report summary" :rows="5" />
                    </AppFormControl>


                    <!-- Description -->
                    <AppFormControl label="Description" :error="errors.description">
                        <AppTextarea name="description" placeholder="Enter report description" :rows="8" />
                    </AppFormControl>

                </div>


                <!-- ===================================================== -->
                <!-- Publishing -->
                <!-- ===================================================== -->
                <div class="space-y-5 rounded-lg border bg-background p-6">

                    <div class="mb-6">
                        <h2 class="text-base font-semibold">
                            Publishing
                        </h2>

                        <p class="mt-1 text-sm text-muted-foreground">
                            Configure the report type, status, author and
                            publication information.
                        </p>
                    </div>


                    <!-- Report Type -->
                    <AppFormControl label="Report Type" required :error="errors.type">
                        <AppSelect name="type" :options="typeOptions" placeholder="Select report type" />
                    </AppFormControl>


                    <!-- Report Status -->
                    <AppFormControl label="Report Status" required :error="errors.status">
                        <AppSelect name="status" :options="statusOptions" placeholder="Select report status" />
                    </AppFormControl>


                    <!-- Author -->
                    <AppFormControl label="Author" :error="errors.author_id">
                        <AppSelect name="author_id" :options="authorOptions" placeholder="Select author" />
                    </AppFormControl>


                    <!-- Published At -->
                    <AppFormControl label="Published At" :error="errors.published_at">
                        <AppInput name="published_at" type="datetime-local" />
                    </AppFormControl>


                    <!-- Featured -->
                    <AppFormControl :error="errors.featured">
                        <AppCheckbox name="featured" true-value="1" false-value="0" label="Feature this report" />
                    </AppFormControl>

                </div>

            </div>


            <!-- ========================================================= -->
            <!-- Actions -->
            <!-- ========================================================= -->

            <div class="
                    flex
                    justify-end
                    gap-3
                    border-t
                    border-border-light
                    pt-6
                    dark:border-border-dark
                ">

                <Button type="button" variant="outline" as-child>
                    <Link :href="index()">
                        Cancel
                    </Link>
                </Button>


                <Button type="submit" :disabled="processing">
                    {{
                        processing
                            ? 'Processing...'
                            : 'Create Report'
                    }}
                </Button>

            </div>

        </Form>
    </div>
</template>