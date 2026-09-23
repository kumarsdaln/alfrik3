<script setup lang="ts">
import { Head, Form, Link } from '@inertiajs/vue3'

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
    show,
    update,
} from '@/routes/admin/report'

import type { FormOption } from '@/types'
import type {
    Report,
    ReportStatus,
    ReportType,
} from '@/types/report'


interface Props {
    report: Report
    statusOptions: FormOption<ReportStatus>[]
    typeOptions: FormOption<ReportType>[]
    authorOptions: FormOption<number>[]
}

defineProps<Props>()
</script>


<template>

    <Head :title="`Edit ${report.title}`" />

    <div class="space-y-6">

        <!-- ========================================================= -->
        <!-- Header -->
        <!-- ========================================================= -->

        <div class="flex items-center gap-4 py-5">

            <BackButton />

            <Heading title="Edit Report" :description="`Update ${report.title}`" />

        </div>


        <!-- ========================================================= -->
        <!-- Form -->
        <!-- ========================================================= -->

        <Form v-slot="{ errors, processing }" v-bind="update.form({
            report: report.id,
        })" :options="{
                preserveScroll: true,
            }" class="space-y-6">

            <div class="grid gap-6 lg:grid-cols-3">

                <!-- ================================================= -->
                <!-- Main -->
                <!-- ================================================= -->

                <div class="space-y-6 lg:col-span-2">

                    <!-- Report Information -->

                    <div class="
                                rounded-lg
                                border
                                bg-background
                                p-6
                            ">

                        <div class="mb-6">

                            <h2 class="text-base font-semibold">
                                Report Information
                            </h2>

                            <p class="
                                        mt-1
                                        text-sm
                                        text-muted-foreground
                                    ">
                                Update the main information about this
                                report.
                            </p>

                        </div>


                        <div class="space-y-5">

                            <!-- Title -->

                            <AppFormControl label="Title" required :error="errors.title">
                                <AppInput name="title" :default-value="report.title" placeholder="Enter report title" />
                            </AppFormControl>


                            <!-- Slug -->

                            <AppFormControl label="Slug" required :error="errors.slug">
                                <AppInput name="slug" :default-value="report.slug" placeholder="report-slug" />
                            </AppFormControl>


                            <!-- Subtitle -->

                            <AppFormControl label="Subtitle" :error="errors.subtitle">
                                <AppInput name="subtitle" :default-value="report.subtitle ?? ''"
                                    placeholder="Enter report subtitle" />
                            </AppFormControl>


                            <!-- Description -->

                            <AppFormControl label="Description" :error="errors.description">
                                <AppTextarea name="description" :default-value="report.description ?? ''
                                    " placeholder="Describe the report..." :rows="7" />
                            </AppFormControl>


                            <!-- Summary -->

                            <AppFormControl label="Summary" :error="errors.summary">
                                <AppTextarea name="summary" :default-value="report.summary ?? ''
                                    " placeholder="Write a short summary of the report..." :rows="5" />
                            </AppFormControl>

                        </div>
                    </div>


                    <!-- Report Dates -->

                    <div class="
                                rounded-lg
                                border
                                bg-background
                                p-6
                            ">

                        <div class="mb-6">

                            <h2 class="text-base font-semibold">
                                Report Dates
                            </h2>

                            <p class="
                                        mt-1
                                        text-sm
                                        text-muted-foreground
                                    ">
                                Define when the report belongs to and
                                when it becomes publicly available.
                            </p>

                        </div>


                        <div class="grid gap-5 md:grid-cols-2">

                            <!-- Report Date -->

                            <AppFormControl label="Report Date" :error="errors.report_date">
                                <AppInput name="report_date" type="date" :default-value="report.report_date
                                    ? report.report_date.substring(
                                        0,
                                        10,
                                    )
                                    : ''
                                    " />
                            </AppFormControl>


                            <!-- Published At -->

                            <AppFormControl label="Published At" :error="errors.published_at">
                                <AppInput name="published_at" type="datetime-local" :default-value="report.published_at
                                    ? report.published_at.substring(
                                        0,
                                        16,
                                    )
                                    : ''
                                    " />
                            </AppFormControl>

                        </div>

                    </div>

                </div>


                <!-- ================================================= -->
                <!-- Sidebar -->
                <!-- ================================================= -->

                <div class="space-y-6">

                    <!-- Classification -->

                    <div class="
                                rounded-lg
                                border
                                bg-background
                                p-6
                            ">

                        <div class="mb-6">

                            <h2 class="text-base font-semibold">
                                Classification
                            </h2>

                            <p class="
                                        mt-1
                                        text-sm
                                        text-muted-foreground
                                    ">
                                Organize and classify this report.
                            </p>

                        </div>


                        <div class="space-y-5">

                            <!-- Report Type -->
                            <AppFormControl label="Report Type" required :error="errors.type">
                                <AppSelect name="type" :options="typeOptions" :default-value="report.type.value
                                    " placeholder="Select report type" />
                            </AppFormControl>

                            <!-- Status -->
                            <AppFormControl label="Status" required :error="errors.status">
                                <AppSelect name="status" :options="statusOptions" :default-value="report.status.value
                                    " placeholder="Select status" />
                            </AppFormControl>

                            <!-- Author -->
                            <AppFormControl label="Author" :error="errors.author_id">
                                <AppSelect name="author_id" :options="authorOptions" :default-value="report.author_id
                                    " placeholder="Select author" clearable />
                            </AppFormControl>

                        </div>
                    </div>


                    <!-- Visibility -->
                    <div class="
                                rounded-lg
                                border
                                bg-background
                                p-6
                            ">

                        <div class="mb-6">

                            <h2 class="text-base font-semibold">
                                Visibility
                            </h2>

                            <p class="
                                        mt-1
                                        text-sm
                                        text-muted-foreground
                                    ">
                                Control how this report is highlighted.
                            </p>

                        </div>


                        <AppFormControl :error="errors.featured">
                            <AppCheckbox name="featured" :default-value="report.featured" true-value="1" false-value="0"
                                label="Feature this report" />
                        </AppFormControl>

                    </div>


                    <!-- Actions -->

                    <div class="
                                rounded-lg
                                border
                                bg-background
                                p-6
                            ">

                        <div class="flex justify-end gap-3">

                            <Button type="button" variant="outline" as-child>
                                <Link :href="show({
                                    report: report.id,
                                })
                                    ">
                                    Cancel
                                </Link>
                            </Button>


                            <Button type="submit" :disabled="processing">
                                {{
                                    processing
                                        ? 'Processing...'
                                        : 'Update Report'
                                }}
                            </Button>

                        </div>

                    </div>

                </div>

            </div>

        </Form>

    </div>
</template>