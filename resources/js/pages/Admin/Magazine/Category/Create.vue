<script setup lang="ts">
    import { Form } from '@inertiajs/vue3'

    import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import AppButton from '@/components/ui/AppButton.vue'

    import { store as categoryStore } from '@/routes/admin/magazine/categories'

    function goBack() { history.back() }
</script>

<template>
        <Form :action="categoryStore()">
            <template #default="{ errors, processing }">
                <AppFormLayout title="New Magazine Category">
                    <AppInput name="name" label="Name" :error="errors.name" placeholder="e.g. Fashion & Lifestyle" required />
                    <AppInput name="icon" label="Icon (optional)" :error="errors.icon" placeholder="Icon name or SVG" />
                    <AppTextarea name="description" label="Description" :error="errors.description" />

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
                                Create Category
                            </AppButton>
                        </div>
                    </template>
                </AppFormLayout>
            </template>
        </Form>
</template>
