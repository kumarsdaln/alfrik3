<script setup lang="ts">
    import { ref } from 'vue'
    import { Form } from '@inertiajs/vue3'

    import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppImageUpload from '@/components/form/AppImageUpload.vue'
    import AppFileUpload from '@/components/form/AppFileUpload.vue'
    import AppToggle from '@/components/ui/AppToggle.vue'
    import AppButton from '@/components/ui/AppButton.vue'

    import { update as reportUpdate } from '@/routes/admin/reports'
    import type { Report, ReportAuthor, ReportCategory } from '@/types'

    const props = defineProps<{ report: Report; categories: ReportCategory[]; authors: ReportAuthor[] }>()

    const title = ref(props.report.title)
    const summary = ref(props.report.summary ?? '')
    const category_id = ref(props.report.category_id ?? '')
    const author_id = ref(props.report.author_id ?? '')
    const report_year = ref(props.report.report_year ?? '')
    const status = ref(props.report.status ? 1 : 0)
    const published_at = ref(props.report.published_at ? props.report.published_at.slice(0, 16) : '')
    const featured = ref(!!props.report.featured)
    const gated = ref(!!props.report.gated)
    const cover = ref<File | null>(null)
    const meta_title = ref(props.report.meta_title ?? '')
    const meta_description = ref(props.report.meta_description ?? '')
    const meta_keywords = ref(props.report.meta_keywords ?? '')

    const statusOptions = [{ value: 1, label: 'Published' }, { value: 0, label: 'Draft' }]
    const existingFileName = props.report.file_path ? props.report.file_path.split('/').pop() : null

    function goBack() { history.back() }
</script>

<template>
        <Form :action="reportUpdate(report.id)">
            <template #default="{ errors, processing }">
                <AppFormLayout title="Edit Report">
                    <AppInput name="title" label="Title" v-model="title" :error="errors.title" required />
                    <AppTextarea name="summary" label="Summary / Abstract" v-model="summary" :error="errors.summary" />

                    <div class="grid sm:grid-cols-2 gap-4">
                        <AppSelect name="category_id" label="Category" v-model="category_id" :options="categories.map(c => ({ value: c.id, label: c.name }))" :error="errors.category_id" placeholder="Uncategorized" />
                        <AppSelect name="author_id" label="Author" v-model="author_id" :options="authors.map(a => ({ value: a.id, label: a.name }))" :error="errors.author_id" placeholder="No author" />
                    </div>

                    <div class="grid sm:grid-cols-3 gap-4">
                        <AppInput name="report_year" type="number" label="Report year" v-model="report_year" :error="errors.report_year" />
                        <AppSelect name="status" label="Status" v-model="status" :options="statusOptions" :error="errors.status" />
                        <AppInput name="published_at" type="datetime-local" label="Publish date" v-model="published_at" :error="errors.published_at" />
                    </div>

                    <div class="flex flex-wrap gap-8 py-2">
                        <div>
                            <AppToggle v-model="featured" true-label="Featured" false-label="Not featured" />
                            <input type="hidden" name="featured" :value="featured ? 1 : 0" />
                        </div>
                        <div>
                            <AppToggle v-model="gated" true-label="Members-only download" false-label="Public download" />
                            <input type="hidden" name="gated" :value="gated ? 1 : 0" />
                        </div>
                    </div>

                    <AppImageUpload name="cover_image" label="Cover Image" v-model="cover" :existing-image="report.cover_image" :error="errors.cover_image" />
                    <AppFileUpload name="file" label="Replace report file (leave blank to keep current)"
                        accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx" :existing-file-name="existingFileName" :error="errors.file" />

                    <div class="space-y-4 border-t border-zinc-200 dark:border-zinc-700 pt-6">
                        <h3 class="text-sm font-semibold text-zinc-500">SEO</h3>
                        <AppInput name="meta_title" label="Meta Title" v-model="meta_title" :error="errors.meta_title" />
                        <AppTextarea name="meta_description" label="Meta Description" v-model="meta_description" :error="errors.meta_description" />
                        <AppInput name="meta_keywords" label="Meta Keywords" v-model="meta_keywords" :error="errors.meta_keywords" />
                    </div>

                    <template #footer>
                        <div class="flex justify-end gap-3">
                            <AppButton variant="cancel" type="button" @click="goBack">Cancel</AppButton>
                            <AppButton variant="submit" type="submit" :loading="processing" :disabled="processing">Update Report</AppButton>
                        </div>
                    </template>
                </AppFormLayout>
            </template>
        </Form>
</template>
