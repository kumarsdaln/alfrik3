<script setup lang="ts">
    import { ref } from 'vue'
    import { Form } from '@inertiajs/vue3'
    import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import AppButton from '@/components/ui/AppButton.vue'
    import { update as areaUpdate } from '@/routes/admin/research/areas'
    import type { ResearchArea } from '@/types'

    const props = defineProps<{ area: ResearchArea }>()
    const name = ref(props.area.name)
    const description = ref(props.area.description ?? '')

    function goBack() { history.back() }
</script>

<template>
    <AdminLayout>
        <Form :action="areaUpdate(area.id)">
            <template #default="{ errors, processing }">
                <AppFormLayout title="Edit Research Area">
                    <AppInput name="name" label="Name" v-model="name" :error="errors.name" required />
                    <AppTextarea name="description" label="Description" v-model="description" :error="errors.description" />
                    <template #footer>
                        <div class="flex justify-end gap-3">
                            <AppButton variant="cancel" type="button" @click="goBack">Cancel</AppButton>
                            <AppButton variant="submit" type="submit" :loading="processing" :disabled="processing">Update Area</AppButton>
                        </div>
                    </template>
                </AppFormLayout>
            </template>
        </Form>
    </AdminLayout>
</template>
