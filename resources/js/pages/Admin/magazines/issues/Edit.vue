<script setup lang="ts">
    import { Form, Link } from '@inertiajs/vue3'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import Button from '@/components/ui/button/Button.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import { index } from '@/routes/admin/magazines/issues'

    import type {
        FormOption,
        Magazine,
        MagazineIssue,
    } from '@/types'

    interface Props {
        magazine: Magazine
        issue: MagazineIssue
        statusOptions: FormOption[]
    }

    const props = defineProps<Props>()
</script>

<template>
    <TableLayout>
        <template #header>
            <div>
                <AppHeading>
                    Edit Magazine Issue
                </AppHeading>

                <AppText class="mt-1">
                    Update {{ issue.title }} in {{ magazine.title }}.
                </AppText>
            </div>
        </template>

        <div class="max-w-4xl">
            <Form :action="`/admin/magazines/${magazine.id}/issues/${issue.id}`" method="put" class="space-y-6"
                #default="{ errors, processing }">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <AppFormControl label="Title" :error="errors.title" required>
                        <AppInput name="title" :default-value="issue.title" placeholder="Enter issue title" />
                    </AppFormControl>

                    <AppFormControl label="Slug" :error="errors.slug">
                        <AppInput name="slug" :default-value="issue.slug" placeholder="issue-slug" />
                    </AppFormControl>
                </div>

                <AppFormControl label="Subtitle" :error="errors.subtitle">
                    <AppInput name="subtitle" :default-value="issue.subtitle ?? ''"
                        placeholder="Enter issue subtitle" />
                </AppFormControl>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <AppFormControl label="Volume" :error="errors.volume">
                        <AppInput name="volume" type="number" min="1" :default-value="issue.volume ?? ''"
                            placeholder="e.g. 1" />
                    </AppFormControl>

                    <AppFormControl label="Issue Number" :error="errors.issue_number">
                        <AppInput name="issue_number" type="number" min="1" :default-value="issue.issue_number ?? ''"
                            placeholder="e.g. 12" />
                    </AppFormControl>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <AppFormControl label="Cover Date" :error="errors.cover_date">
                        <AppInput name="cover_date" type="date" :default-value="issue.cover_date ?? ''" />
                    </AppFormControl>

                    <AppFormControl label="Published At" :error="errors.published_at">
                        <AppInput name="published_at" type="datetime-local" :default-value="issue.published_at ?? ''" />
                    </AppFormControl>
                </div>

                <AppFormControl label="Editor" :error="errors.editor">
                    <AppInput name="editor" :default-value="issue.editor ?? ''" placeholder="Editor name" />
                </AppFormControl>

                <AppFormControl label="Description" :error="errors.description">
                    <AppTextarea name="description" rows="6" :default-value="issue.description ?? ''"
                        placeholder="Describe this issue..." />
                </AppFormControl>

                <AppFormControl label="Status" :error="errors.status" required>
                    <AppSelect name="status" :options="statusOptions" :default-value="issue.status"
                        placeholder="Select status" />
                </AppFormControl>

                <div class="flex items-center gap-3">
                    <Button type="submit" :disabled="processing">
                        Update Issue
                    </Button>

                    <Button type="button" variant="outline" as-child>
                        <Link :href="index(props.magazine.id).url">
                            Cancel
                        </Link>
                    </Button>
                </div>
            </Form>
        </div>
    </TableLayout>
</template>