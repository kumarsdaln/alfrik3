<script setup lang="ts">
    import { ref } from 'vue'
    import { Form } from '@inertiajs/vue3'

    import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'

    import AppInput from '@/components/form/AppInput.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppImageUpload from '@/components/form/AppImageUpload.vue'
    import AppRepeater from '@/components/form/AppRepeater.vue'
    import AppButton from '@/components/ui/AppButton.vue'

    import { update as adminMagazineUpdate } from '@/routes/admin/magazine'
    import type { Magazine, MagazineAuthor, MagazineCategory } from '@/types'

    const props = defineProps<{
        magazine: Magazine
        categories: MagazineCategory[]
        authors: MagazineAuthor[]
    }>()

    const title = ref(props.magazine.title)
    const subtitle = ref(props.magazine.subtitle ?? '')
    const category_id = ref(props.magazine.category_id ?? '')
    const author_id = ref(props.magazine.author_id ?? '')
    const status = ref(props.magazine.status ? 1 : 0)
    // ISO -> "YYYY-MM-DDTHH:mm" for the datetime-local input.
    const published_at = ref(props.magazine.published_at ? props.magazine.published_at.slice(0, 16) : '')
    const meta_title = ref(props.magazine.meta_title ?? '')
    const meta_description = ref(props.magazine.meta_description ?? '')
    const meta_keywords = ref(props.magazine.meta_keywords ?? '')
    const cover = ref<File | null>(null)

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
    <Form :action="adminMagazineUpdate(magazine.id)">
        <template #default="{ errors, processing }">
            <AppFormLayout title="Edit Magazine Issue">

                <AppInput name="title" label="Title" v-model="title" :error="errors.title" required />
                <AppTextarea name="subtitle" label="Subtitle / Dek" v-model="subtitle" :error="errors.subtitle" />

                <div class="grid sm:grid-cols-2 gap-4">
                    <AppSelect name="category_id" label="Category" v-model="category_id"
                        :options="categories.map(c => ({ value: c.id, label: c.name }))" :error="errors.category_id"
                        placeholder="Uncategorized" />
                    <AppSelect name="author_id" label="Author" v-model="author_id"
                        :options="authors.map(a => ({ value: a.id, label: a.name }))" :error="errors.author_id"
                        placeholder="No author credited" />
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <AppSelect name="status" label="Status" v-model="status" :options="statusOptions"
                        :error="errors.status" />
                    <AppInput name="published_at" type="datetime-local" label="Publish date (optional)"
                        v-model="published_at" :error="errors.published_at" />
                </div>
                <p class="-mt-2 text-xs text-zinc-500">
                    Leave blank to publish immediately when Published. Set a future date to schedule the issue.
                </p>

                <AppImageUpload name="cover_image" label="Cover Image" v-model="cover"
                    :existing-image="magazine.cover_image" :error="errors.cover_image" />

                <div class="pt-2">
                    <AppRepeater name="sections" title="Sections" :fields="sectionFields"
                        :model-value="magazine.sections ?? []" />
                    <p class="mt-2 text-xs text-zinc-500">Each section becomes a page in the digital reader.</p>
                </div>

                <div class="space-y-4 border-t border-zinc-200 dark:border-zinc-700 pt-6">
                    <h3 class="text-sm font-semibold text-zinc-500">SEO</h3>
                    <AppInput name="meta_title" label="Meta Title" v-model="meta_title" :error="errors.meta_title" />
                    <AppTextarea name="meta_description" label="Meta Description" v-model="meta_description"
                        :error="errors.meta_description" />
                    <AppInput name="meta_keywords" label="Meta Keywords" v-model="meta_keywords"
                        :error="errors.meta_keywords" />
                </div>

                <template #footer>
                    <div class="flex justify-end gap-3">
                        <AppButton variant="cancel" type="button" @click="goBack">Cancel</AppButton>
                        <AppButton variant="submit" type="submit" :loading="processing" :disabled="processing">
                            Update Issue
                        </AppButton>
                    </div>
                </template>
            </AppFormLayout>
        </template>
    </Form>
</template>
