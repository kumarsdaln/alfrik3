<script setup lang="ts">
    import { Form } from '@inertiajs/vue3'

    import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'

    import AppInput from '@/components/form/AppInput.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppImageUpload from '@/components/form/AppImageUpload.vue'
    import AppRepeater from '@/components/form/AppRepeater.vue'
    import AppButton from '@/components/ui/AppButton.vue'

    import { store as adminMagazineStore } from '@/routes/admin/magazine'
    import type { MagazineAuthor, MagazineCategory } from '@/types'

    defineProps<{ categories: MagazineCategory[]; authors: MagazineAuthor[] }>()

    const sectionFields = [
        { label: 'Section Title', key: 'section', type: 'text' as const },
        { label: 'Content', key: 'content', type: 'editor' as const },
    ]

    const statusOptions = [
        { value: 1, label: 'Published' },
        { value: 0, label: 'Draft' },
    ]

    function goBack() { history.back() }
</script>

<template>
    <Form :action="adminMagazineStore()">
        <template #default="{ errors, processing }">
            <AppFormLayout title="Create Magazine Issue">

                <AppInput name="title" label="Title" :error="errors.title" placeholder="Issue title" required />
                <AppTextarea name="subtitle" label="Subtitle / Dek" :error="errors.subtitle"
                    placeholder="Short standfirst shown on cards" />

                <div class="grid sm:grid-cols-2 gap-4">
                    <AppSelect name="category_id" label="Category"
                        :options="categories.map(c => ({ value: c.id, label: c.name }))" :error="errors.category_id"
                        placeholder="Uncategorized" />
                    <AppSelect name="author_id" label="Author"
                        :options="authors.map(a => ({ value: a.id, label: a.name }))" :error="errors.author_id"
                        placeholder="No author credited" />
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <AppSelect name="status" label="Status" :options="statusOptions" :error="errors.status" />
                    <AppInput name="published_at" type="datetime-local" label="Publish date (optional)"
                        :error="errors.published_at" />
                </div>
                <p class="-mt-2 text-xs text-zinc-500">
                    Leave blank to publish immediately when Published. Set a future date to schedule the issue.
                </p>

                <AppImageUpload name="cover_image" label="Cover Image" :error="errors.cover_image" />

                <div class="pt-2">
                    <AppRepeater name="sections" title="Sections" :fields="sectionFields" />
                    <p class="mt-2 text-xs text-zinc-500">Each section becomes a page in the digital reader.</p>
                </div>

                <div class="space-y-4 border-t border-zinc-200 dark:border-zinc-700 pt-6">
                    <h3 class="text-sm font-semibold text-zinc-500">SEO</h3>
                    <AppInput name="meta_title" label="Meta Title" :error="errors.meta_title" />
                    <AppTextarea name="meta_description" label="Meta Description" :error="errors.meta_description" />
                    <AppInput name="meta_keywords" label="Meta Keywords" :error="errors.meta_keywords" />
                </div>

                <template #footer>
                    <div class="flex justify-end gap-3">
                        <AppButton variant="cancel" type="button" @click="goBack">Cancel</AppButton>
                        <AppButton variant="submit" type="submit" :loading="processing" :disabled="processing">
                            Create Issue
                        </AppButton>
                    </div>
                </template>
            </AppFormLayout>
        </template>
    </Form>
</template>
