<script setup lang="ts">
    import { ref } from 'vue'
    import { Form } from '@inertiajs/vue3'
    import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import AppButton from '@/components/ui/AppButton.vue'
    import { update as categoryUpdate } from '@/routes/admin/reports/categories'
    import type { ReportCategory } from '@/types'

    const props = defineProps<{ category: ReportCategory }>()
    const name = ref(props.category.name)
    const description = ref(props.category.description ?? '')

    function goBack() { history.back() }
</script>

<template>
    <Form :action="categoryUpdate(category.id)">
        <template #default="{ errors, processing }">
            <AppFormLayout title="Edit Report Category">
                <AppInput name="name" label="Name" v-model="name" :error="errors.name" required />
                <AppTextarea name="description" label="Description" v-model="description" :error="errors.description" />
                <template #footer>
                    <div class="flex justify-end gap-3">
                        <AppButton variant="cancel" type="button" @click="goBack">Cancel</AppButton>
                        <AppButton variant="submit" type="submit" :loading="processing" :disabled="processing">Update
                            Category</AppButton>
                    </div>
                </template>
            </AppFormLayout>
        </template>
    </Form>
</template>
