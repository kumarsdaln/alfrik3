<script setup lang="ts">
    import { Head, router, useForm } from '@inertiajs/vue3'
    import { ArrowLeft } from '@lucide/vue'

    import AppButton from '@/components/ui/AppButton.vue'
    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import Heading from '@/components/Heading.vue'
    import TableLayout from '@/layouts/table/Layout.vue'

    import {
        index,
        update,
    } from '@/routes/admin/report/sections'

    import type { ReportSection } from '@/types/report'

    interface Props {
        report: {
            id: number
            title: string
        }
        section: ReportSection
    }

    const props = defineProps<Props>()

    const form = useForm({
        title: props.section.title,
        subtitle: props.section.subtitle ?? '',
        content: props.section.content ?? '',
        position: props.section.position,
    })

    const submit = () => {
        form.put(
            update.url({
                report: props.report.id,
                section: props.section.id,
            }),
        )
    }

    const back = () => {
        router.visit(
            index.url({
                report: props.report.id,
            }),
        )
    }
</script>

<template>

    <Head :title="`Edit Section - ${report.title}`" />

    <TableLayout>
        <div class="space-y-6">
            <div class="flex items-center gap-3">
                <AppButton variant="ghost" size="icon" type="button" @click="back">
                    <ArrowLeft class="size-4" />
                </AppButton>

                <Heading title="Edit Report Section" :description="report.title" />
            </div>

            <form class="space-y-6" @submit.prevent="submit">
                <div class="rounded-lg border bg-background p-6">
                    <div class="mb-6">
                        <h2 class="text-base font-semibold">
                            Section Information
                        </h2>

                        <p class="mt-1 text-sm text-muted-foreground">
                            Update the content and ordering of this report section.
                        </p>
                    </div>

                    <div class="space-y-5">
                        <AppFormControl label="Title" :error="form.errors.title" required>
                            <AppInput v-model="form.title" name="title" placeholder="Enter section title" />
                        </AppFormControl>

                        <AppFormControl label="Subtitle" :error="form.errors.subtitle">
                            <AppInput v-model="form.subtitle" name="subtitle" placeholder="Enter section subtitle" />
                        </AppFormControl>

                        <AppFormControl label="Content" :error="form.errors.content">
                            <AppTextarea v-model="form.content" name="content"
                                placeholder="Write the section content..." rows="14" />
                        </AppFormControl>

                        <AppFormControl label="Position" :error="form.errors.position"
                            description="Lower numbers appear first in the report.">
                            <AppInput v-model.number="form.position" type="number" name="position" min="0" />
                        </AppFormControl>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <AppButton type="button" variant="outline" @click="back">
                        Cancel
                    </AppButton>

                    <AppButton type="submit" :disabled="form.processing">
                        Update Section
                    </AppButton>
                </div>
            </form>
        </div>
    </TableLayout>
</template>