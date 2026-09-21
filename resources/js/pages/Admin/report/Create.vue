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

    import { index as reportIndex, store } from '@/routes/admin/report'

    import type { FormOption } from '@/types'
    import type { ReportStatus, ReportType } from '@/types/report'

    interface Props {
        statusOptions: FormOption<ReportStatus>[]
        typeOptions: FormOption<ReportType>[]
        researchOptions: FormOption<number>[]
        authorOptions: FormOption<number>[]
    }

    const props = defineProps<Props>()

    const form = useForm({
        research_id: null as number | null,
        title: '',
        slug: '',
        subtitle: '',
        description: '',
        summary: '',
        type: props.typeOptions[0]?.value ?? '',
        status: 'draft' as ReportStatus,
        author_id: null as number | null,
        featured: false,
        published_at: '',
        report_date: '',
    })

    const submit = () => {
        form.post(store.url())
    }

    const back = () => {
        router.visit(reportIndex.url())
    }
</script>

<template>

    <Head title="Create Report" />

    <TableLayout>
        <div class="space-y-6">
            <div class="flex items-center justify-between gap-4">
                <div class="flex items-center gap-3">
                    <AppButton variant="ghost" size="icon" type="button" @click="back">
                        <ArrowLeft class="size-4" />
                    </AppButton>

                    <Heading title="Create Report"
                        description="Create a new report for the Alfrik research platform." />
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
                                    Add the main information about this report.
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
                                <AppButton type="button" variant="outline" @click="back">
                                    Cancel
                                </AppButton>

                                <AppButton type="submit" :disabled="form.processing">
                                    Create Report
                                </AppButton>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </TableLayout>
</template>
