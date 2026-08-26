<script setup lang="ts">
    import { Form } from '@inertiajs/vue3'
    import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import AppButton from '@/components/ui/AppButton.vue'
    import { store as areaStore } from '@/routes/admin/research/areas'

    function goBack() { history.back() }
</script>

<template>
    <Form :action="areaStore()">
        <template #default="{ errors, processing }">
            <AppFormLayout title="New Research Area">
                <AppInput name="name" label="Name" :error="errors.name" placeholder="e.g. Behavioral Economics"
                    required />
                <AppTextarea name="description" label="Description" :error="errors.description" />
                <template #footer>
                    <div class="flex justify-end gap-3">
                        <AppButton variant="cancel" type="button" @click="goBack">Cancel</AppButton>
                        <AppButton variant="submit" type="submit" :loading="processing" :disabled="processing">Create
                            Area</AppButton>
                    </div>
                </template>
            </AppFormLayout>
        </template>
    </Form>
</template>
