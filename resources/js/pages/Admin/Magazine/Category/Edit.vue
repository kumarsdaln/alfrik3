<script setup lang="ts">
    import { ref } from 'vue'
    import { Form } from '@inertiajs/vue3'

    import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import AppButton from '@/components/ui/AppButton.vue'

    import { update as categoryUpdate } from '@/routes/admin/magazine/categories'
    import type { MagazineCategory } from '@/types'

    const props = defineProps<{ category: MagazineCategory }>()

    const name = ref(props.category.name)
    const icon = ref(props.category.icon ?? '')
    const description = ref(props.category.description ?? '')
    const meta_title = ref(props.category.meta_title ?? '')
    const meta_description = ref(props.category.meta_description ?? '')
    const meta_keywords = ref(props.category.meta_keywords ?? '')

    function goBack() { history.back() }
</script>

<template>
    <Form :action="categoryUpdate(category.id)">
        <template #default="{ errors, processing }">
            <AppFormLayout title="Edit Magazine Category">
                <AppInput name="name" label="Name" v-model="name" :error="errors.name" required />
                <AppInput name="icon" label="Icon (optional)" v-model="icon" :error="errors.icon" />
                <AppTextarea name="description" label="Description" v-model="description" :error="errors.description" />

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
                            Update Category
                        </AppButton>
                    </div>
                </template>
            </AppFormLayout>
        </template>
    </Form>
</template>
