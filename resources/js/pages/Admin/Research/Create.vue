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

    import { store as researchStore } from '@/routes/admin/research'
    import type { ResearchArea, ResearchAuthorUser } from '@/types'

    defineProps<{ areas: ResearchArea[]; authors: ResearchAuthorUser[] }>()

    const featured = ref(false)
    const statusOptions = [{ value: 1, label: 'Published' }, { value: 0, label: 'Draft' }]

    function goBack() { history.back() }
</script>

<template>
    <Form :action="researchStore()">
        <template #default="{ errors, processing }">
            <AppFormLayout title="New Research Paper">
                <AppInput name="title" label="Title" :error="errors.title" required />
                <AppInput name="authors" label="Authors" :error="errors.authors"
                    placeholder="e.g. J. Doe, A. Smith, R. Lee" />
                <AppTextarea name="abstract" label="Abstract" :error="errors.abstract"
                    placeholder="Summary of the study, findings and contribution…" />

                <div class="grid sm:grid-cols-2 gap-4">
                    <AppSelect name="area_id" label="Research area"
                        :options="areas.map(a => ({ value: a.id, label: a.name }))" :error="errors.area_id"
                        placeholder="Unassigned" />
                    <AppSelect name="author_id" label="Published by"
                        :options="authors.map(a => ({ value: a.id, label: a.name }))" :error="errors.author_id"
                        placeholder="—" />
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <AppInput name="institution" label="Institution / Affiliation" :error="errors.institution" />
                    <AppInput name="doi" label="DOI" :error="errors.doi" placeholder="10.xxxx/xxxxx" />
                </div>

                <AppTextarea name="methodology" label="Methodology" :error="errors.methodology" />
                <AppTextarea name="citation" label="Citation" :error="errors.citation"
                    placeholder="Preferred citation format" />
                <AppInput name="keywords" label="Keywords (comma-separated)" :error="errors.keywords" />

                <div class="grid sm:grid-cols-3 gap-4">
                    <AppSelect name="status" label="Status" :options="statusOptions" :error="errors.status" />
                    <AppInput name="published_at" type="date" label="Publication date" :error="errors.published_at" />
                    <div class="pt-6">
                        <AppToggle v-model="featured" true-label="Featured" false-label="Not featured" />
                        <input type="hidden" name="featured" :value="featured ? 1 : 0" />
                    </div>
                </div>

                <AppImageUpload name="cover_image" label="Cover Image" :error="errors.cover_image" />
                <AppFileUpload name="file" label="Paper file (PDF/DOC · max 50MB)" accept=".pdf,.doc,.docx"
                    :error="errors.file" />

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
                            Paper</AppButton>
                    </div>
                </template>
            </AppFormLayout>
        </template>
    </Form>
</template>
