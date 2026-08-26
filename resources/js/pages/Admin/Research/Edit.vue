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

    import { update as researchUpdate } from '@/routes/admin/research'
    import type { ResearchPaper, ResearchArea, ResearchAuthorUser } from '@/types'

    const props = defineProps<{ paper: ResearchPaper; areas: ResearchArea[]; authors: ResearchAuthorUser[] }>()

    const title = ref(props.paper.title)
    const authors = ref(props.paper.authors ?? '')
    const abstract = ref(props.paper.abstract ?? '')
    const area_id = ref(props.paper.area_id ?? '')
    const author_id = ref(props.paper.author_id ?? '')
    const institution = ref(props.paper.institution ?? '')
    const doi = ref(props.paper.doi ?? '')
    const methodology = ref(props.paper.methodology ?? '')
    const citation = ref(props.paper.citation ?? '')
    const keywords = ref(props.paper.keywords ?? '')
    const status = ref(props.paper.status ? 1 : 0)
    const published_at = ref(props.paper.published_at ? props.paper.published_at.slice(0, 10) : '')
    const featured = ref(!!props.paper.featured)
    const cover = ref<File | null>(null)
    const meta_title = ref(props.paper.meta_title ?? '')
    const meta_description = ref(props.paper.meta_description ?? '')
    const meta_keywords = ref(props.paper.meta_keywords ?? '')

    const statusOptions = [{ value: 1, label: 'Published' }, { value: 0, label: 'Draft' }]
    const existingFileName = props.paper.file_path ? props.paper.file_path.split('/').pop() : null

    function goBack() { history.back() }
</script>

<template>
        <Form :action="researchUpdate(paper.id)">
            <template #default="{ errors, processing }">
                <AppFormLayout title="Edit Research Paper">
                    <AppInput name="title" label="Title" v-model="title" :error="errors.title" required />
                    <AppInput name="authors" label="Authors" v-model="authors" :error="errors.authors" />
                    <AppTextarea name="abstract" label="Abstract" v-model="abstract" :error="errors.abstract" />

                    <div class="grid sm:grid-cols-2 gap-4">
                        <AppSelect name="area_id" label="Research area" v-model="area_id" :options="areas.map(a => ({ value: a.id, label: a.name }))" :error="errors.area_id" placeholder="Unassigned" />
                        <AppSelect name="author_id" label="Published by" v-model="author_id" :options="props.authors.map(a => ({ value: a.id, label: a.name }))" :error="errors.author_id" placeholder="—" />
                    </div>

                    <div class="grid sm:grid-cols-2 gap-4">
                        <AppInput name="institution" label="Institution / Affiliation" v-model="institution" :error="errors.institution" />
                        <AppInput name="doi" label="DOI" v-model="doi" :error="errors.doi" />
                    </div>

                    <AppTextarea name="methodology" label="Methodology" v-model="methodology" :error="errors.methodology" />
                    <AppTextarea name="citation" label="Citation" v-model="citation" :error="errors.citation" />
                    <AppInput name="keywords" label="Keywords (comma-separated)" v-model="keywords" :error="errors.keywords" />

                    <div class="grid sm:grid-cols-3 gap-4">
                        <AppSelect name="status" label="Status" v-model="status" :options="statusOptions" :error="errors.status" />
                        <AppInput name="published_at" type="date" label="Publication date" v-model="published_at" :error="errors.published_at" />
                        <div class="pt-6">
                            <AppToggle v-model="featured" true-label="Featured" false-label="Not featured" />
                            <input type="hidden" name="featured" :value="featured ? 1 : 0" />
                        </div>
                    </div>

                    <AppImageUpload name="cover_image" label="Cover Image" v-model="cover" :existing-image="paper.cover_image" :error="errors.cover_image" />
                    <AppFileUpload name="file" label="Replace paper file (leave blank to keep current)" accept=".pdf,.doc,.docx" :existing-file-name="existingFileName" :error="errors.file" />

                    <div class="space-y-4 border-t border-zinc-200 dark:border-zinc-700 pt-6">
                        <h3 class="text-sm font-semibold text-zinc-500">SEO</h3>
                        <AppInput name="meta_title" label="Meta Title" v-model="meta_title" :error="errors.meta_title" />
                        <AppTextarea name="meta_description" label="Meta Description" v-model="meta_description" :error="errors.meta_description" />
                        <AppInput name="meta_keywords" label="Meta Keywords" v-model="meta_keywords" :error="errors.meta_keywords" />
                    </div>

                    <template #footer>
                        <div class="flex justify-end gap-3">
                            <AppButton variant="cancel" type="button" @click="goBack">Cancel</AppButton>
                            <AppButton variant="submit" type="submit" :loading="processing" :disabled="processing">Update Paper</AppButton>
                        </div>
                    </template>
                </AppFormLayout>
            </template>
        </Form>
</template>
