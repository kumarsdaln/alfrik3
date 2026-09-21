<script setup lang="ts">
    import { Head, router, useForm } from '@inertiajs/vue3'
    import { ArrowLeft } from '@lucide/vue'

    import AppButton from '@/components/ui/AppButton.vue'
    import AppCheckbox from '@/components/form/AppCheckbox.vue'
    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import Heading from '@/components/Heading.vue'
    import TableLayout from '@/layouts/table/Layout.vue'

    import {
        index as reportIndex,
        show as reportShow,
        update,
    } from '@/routes/admin/report'

    import type { FormOption } from '@/types'
    import type { Report, ReportStatus, ReportType } from '@/types/report'

    interface Props {
        report: Report
        statusOptions: FormOption<ReportStatus>[]
        typeOptions: FormOption<ReportType>[]
        researchOptions: FormOption<number>[]
        authorOptions: FormOption<number>[]
    }

    const props = defineProps<Props>()

    const formatDateTimeLocal = (
        value: string | null,
    ): string => {
        if (!value) {
            return ''
        }

        const date = new Date(value)

        if (Number.isNaN(date.getTime())) {
            return ''
        }

        const pad = (number: number) =>
            String(number).padStart(2, '0')

        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`
    }

    const formatDate = (
        value: string | null,
    ): string => {
        if (!value) {
            return ''
        }

        const date = new Date(value)

        if (Number.isNaN(date.getTime())) {
            return ''
        }

        const pad = (number: number) =>
            String(number).padStart(2, '0')

        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`
    }

    const form = useForm({
        research_id: props.report.research_id,
        title: props.report.title,
        slug: props.report.slug,
        subtitle: props.report.subtitle ?? '',
        description: props.report.description ?? '',
        summary: props.report.summary ?? '',
        type: props.report.type.value,
        status: props.report.status.value,
        author_id: props.report.author_id,
        featured: props.report.featured,
        published_at: formatDateTimeLocal(
            props.report.published_at,
        ),
        report_date: formatDate(
            props.report.report_date,
        ),
    })

    const submit = () => {
        form.put(
            update.url({
                report: props.report.id,
            }),
        )
    }

    const back = () => {
        router.visit(
            reportShow.url({
                report: props.report.id,
            }),
        )
    }

    const cancel = () => {
        router.visit(reportIndex.url())
    }
</script>

<template>

    <Head :title="`Edit ${report.title}`" />

    <TableLayout>
        <div class="space-y-6">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <AppButton variant="ghost" size="icon" type="button" @click="back">
                        <ArrowLeft class="size-4" />
                    </AppButton>

                    <Heading title="Edit Report" :description="`Update ${report.title}`" />
                </div>
            </div>

            <form class="space-y-6" @submit.prevent="submit">
                <div class="grid gap-6 lg:grid-cols-3">
                    <div class="space-y-6 lg:col-span-2">
                        <div class="rounded-lg border bg-background p-6">
                            <div class="mb-6">
                                <h2 class="text-base font-semibold">
                                    Report Information
                                </h2>

                                <p class="mt-1 text-sm text-muted-foreground">
                                    Update the main information about this report.
                                </p>
                            </div>

                            <div class="space-y-5">
                                <AppFormControl label="Title" :error="form.errors.title" required>
                                    <AppInput v-model="form.title" name="title" placeholder="Enter report title" />
                                </AppFormControl>

                                <AppFormControl label="Slug" :error="form.errors.slug" required>
                                    <AppInput v-model="form.slug" name="slug" placeholder="report-slug" />
                                </AppFormControl>

                                <AppFormControl label="Subtitle" :error="form.errors.subtitle">
                                    <AppInput v-model="form.subtitle" name="subtitle"
                                        placeholder="Enter report subtitle" />
                                </AppFormControl>

                                <AppFormControl label="Description" :error="form.errors.description">
                                    <AppTextarea v-model="form.description" name="description"
                                        placeholder="Describe the report..." rows="7" />
                                </AppFormControl>

                                <AppFormControl label="Summary" :error="form.errors.summary">
                                    <AppTextarea v-model="form.summary" name="summary"
                                        placeholder="Write a short summary of the report..." rows="5" />
                                </AppFormControl>
                            </div>
                        </div>

                        <div class="rounded-lg border bg-background p-6">
                            <div class="mb-6">
                                <h2 class="text-base font-semibold">
                                    Report Dates
                                </h2>

                                <p class="mt-1 text-sm text-muted-foreground">
                                    Define when the report belongs to and when it
                                    becomes publicly available.
                                </p>
                            </div>

                            <div class="grid gap-5 md:grid-cols-2">
                                <AppFormControl label="Report Date" :error="form.errors.report_date">
                                    <AppInput v-model="form.report_date" type="date" name="report_date" />
                                </AppFormControl>

                                <AppFormControl label="Published At" :error="form.errors.published_at">
                                    <AppInput v-model="form.published_at" type="datetime-local" name="published_at" />
                                </AppFormControl>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-6">
                        <div class="rounded-lg border bg-background p-6">
                            <div class="mb-6">
                                <h2 class="text-base font-semibold">
                                    Classification
                                </h2>

                                <p class="mt-1 text-sm text-muted-foreground">
                                    Organize and classify this report.
                                </p>
                            </div>

                            <div class="space-y-5">
                                <AppFormControl label="Research" :error="form.errors.research_id">
                                    <AppSelect v-model="form.research_id" :options="researchOptions"
                                        placeholder="Select research" clearable />
                                </AppFormControl>

                                <AppFormControl label="Report Type" :error="form.errors.type" required>
                                    <AppSelect v-model="form.type" :options="typeOptions"
                                        placeholder="Select report type" />
                                </AppFormControl>

                                <AppFormControl label="Status" :error="form.errors.status" required>
                                    <AppSelect v-model="form.status" :options="statusOptions"
                                        placeholder="Select status" />
                                </AppFormControl>

                                <AppFormControl label="Author" :error="form.errors.author_id">
                                    <AppSelect v-model="form.author_id" :options="authorOptions"
                                        placeholder="Select author" clearable />
                                </AppFormControl>
                            </div>
                        </div>

                        <div class="rounded-lg border bg-background p-6">
                            <div class="mb-6">
                                <h2 class="text-base font-semibold">
                                    Visibility
                                </h2>

                                <p class="mt-1 text-sm text-muted-foreground">
                                    Control how this report is highlighted.
                                </p>
                            </div>

                            <AppCheckbox id="report-featured" name="featured" v-model="form.featured" true-value="1"
                                false-value="0" label="Feature this report" />
                        </div>

                        <div class="rounded-lg border bg-background p-6">
                            <div class="flex justify-end gap-3">
                                <AppButton type="button" variant="outline" @click="cancel">
                                    Cancel
                                </AppButton>

                                <AppButton type="submit" :disabled="form.processing">
                                    Update Report
                                </AppButton>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </TableLayout>
</template>