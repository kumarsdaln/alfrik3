<script setup lang="ts">
    import { Form, Link } from '@inertiajs/vue3'

    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import {
        index,
        update,
    } from '@/routes/admin/magazines/issues'

    import type {
        FormOption,
        Magazine,
        MagazineIssue,
    } from '@/types'

    interface Props {
        magazine: Magazine
        issue: {
            data: MagazineIssue
        }
        statusOptions: FormOption[]
    }

    defineProps<Props>()
</script>

<template>
    <TableLayout>
        <template #header>
            <div class="flex items-center gap-4 py-5">
                <BackButton />

                <Heading
                    title="Edit Magazine Issue"
                    :description="`Update ${issue.data.title} for ${magazine.title}.`"
                />
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-4xl">
                <Form
                    v-bind="update.form({magazine:magazine.id, issue:issue.data.id})"
                    method="put"
                    class="space-y-6"
                    #default="{ errors, processing }"
                >
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <AppFormControl
                            label="Title"
                            :error="errors.title"
                            required
                        >
                            <AppInput
                                name="title"
                                :default-value="issue.data.title"
                                placeholder="Enter issue title"
                            />
                        </AppFormControl>

                        <AppFormControl
                            label="Slug"
                            :error="errors.slug"
                        >
                            <AppInput
                                name="slug"
                                :default-value="issue.data.slug"
                                placeholder="issue-slug"
                            />
                        </AppFormControl>
                    </div>

                    <AppFormControl
                        label="Subtitle"
                        :error="errors.subtitle"
                    >
                        <AppInput
                            name="subtitle"
                            :default-value="issue.data.subtitle??''"
                            placeholder="Enter issue subtitle"
                        />
                    </AppFormControl>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <AppFormControl
                            label="Volume"
                            :error="errors.volume"
                        >
                            <AppInput
                                name="volume"
                                type="number"
                                min="1"
                                :default-value="issue.data.volume??''"
                                placeholder="e.g. 1"
                            />
                        </AppFormControl>

                        <AppFormControl
                            label="Issue Number"
                            :error="errors.issue_number"
                        >
                            <AppInput
                                name="issue_number"
                                type="number"
                                min="1"
                                :default-value="issue.data.issue_number??''"
                                placeholder="e.g. 12"
                            />
                        </AppFormControl>
                    </div>

                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <AppFormControl
                            label="Cover Date"
                            :error="errors.cover_date"
                        >
                            <AppInput
                                name="cover_date"
                                type="date"
                                :default-value="issue.data.cover_date??''"
                            />
                        </AppFormControl>

                        <AppFormControl
                            label="Published At"
                            :error="errors.published_at"
                        >
                            <AppInput
                                name="published_at"
                                type="datetime-local"
                                :default-value="issue.data.published_at??''"
                            />
                        </AppFormControl>
                    </div>

                    <AppFormControl
                        label="Editor"
                        :error="errors.editor"
                    >
                        <AppInput
                            name="editor"
                            :default-value="issue.data.editor??''"
                            placeholder="Editor name"
                        />
                    </AppFormControl>

                    <AppFormControl
                        label="Description"
                        :error="errors.description"
                    >
                        <AppTextarea
                            name="description"
                            :default-value="issue.data.description??''"
                            :rows="6"
                            placeholder="Describe this issue.data..."
                        />
                    </AppFormControl>

                    <AppFormControl
                        label="Status"
                        :error="errors.status"
                        required
                    >
                        <AppSelect
                            name="status"
                            :default-value="issue.data.status.value"
                            :options="statusOptions"
                            placeholder="Select status"
                        />
                    </AppFormControl>

                    <div class="flex items-center gap-3">
                        <Button
                            type="submit"
                            :disabled="processing"
                        >
                            Update Issue
                        </Button>

                        <Button
                            type="button"
                            variant="outline"
                            as-child
                        >
                            <Link :href="index(magazine.id).url">
                                Cancel
                            </Link>
                        </Button>
                    </div>
                </Form>
            </div>
        </div>
    </TableLayout>
</template>