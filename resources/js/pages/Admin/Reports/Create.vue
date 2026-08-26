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

    import { store as reportStore } from '@/routes/admin/reports'
    import type { ReportAuthor, ReportCategory } from '@/types'

    defineProps<{ categories: ReportCategory[]; authors: ReportAuthor[] }>()

    const featured = ref(false)
    const gated = ref(false)
    const statusOptions = [{ value: 1, label: 'Published' }, { value: 0, label: 'Draft' }]

    function goBack() { history.back() }
</script>

<template>
    <Form :action="reportStore()">
        <template #default="{ errors, processing }">
            <AppFormLayout title="New Report">
                <AppInput name="title" label="Title" :error="errors.title" placeholder="Report title" required />
                <AppTextarea name="summary" label="Summary / Abstract" :error="errors.summary"
                    placeholder="What this report covers, key findings…" />

                <div class="grid sm:grid-cols-2 gap-4">
                    <AppSelect name="category_id" label="Category"
                        :options="categories.map(c => ({ value: c.id, label: c.name }))" :error="errors.category_id"
                        placeholder="Uncategorized" />
                    <AppSelect name="author_id" label="Author"
                        :options="authors.map(a => ({ value: a.id, label: a.name }))" :error="errors.author_id"
                        placeholder="No author" />
                </div>

                <div class="grid sm:grid-cols-3 gap-4">
                    <AppInput name="report_year" type="number" label="Report year" :error="errors.report_year"
                        placeholder="2026" />
                    <AppSelect name="status" label="Status" :options="statusOptions" :error="errors.status" />
                    <AppInput name="published_at" type="datetime-local" label="Publish date"
                        :error="errors.published_at" />
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

                <AppImageUpload name="cover_image" label="Cover Image" :error="errors.cover_image" />
                <AppFileUpload name="file" label="Report file (PDF, DOC, PPT, XLS · max 50MB)"
                    accept=".pdf,.doc,.docx,.ppt,.pptx,.xls,.xlsx" :error="errors.file" />

                <div class="space-y-4 border-t border-zinc-200 dark:border-zinc-700 pt-6">
                    <h3 class="text-sm font-semibold text-zinc-500">SEO</h3>
                    <AppInput name="meta_title" label="Meta Title" :error="errors.meta_title" />
                    <AppTextarea name="meta_description" label="Meta Description" :error="errors.meta_description" />
                    <AppInput name="meta_keywords" label="Meta Keywords" :error="errors.meta_keywords" />
                </div>

                <template #footer>
                    <div class="flex justify-end gap-3">
                        <AppButton variant="cancel" type="button" @click="goBack">Cancel</AppButton>
                        <AppButton variant="submit" type="submit" :loading="processing" :disabled="processing">Create
                            Report</AppButton>
                    </div>
                </template>
            </AppFormLayout>
        </template>
    </Form>
</template>
