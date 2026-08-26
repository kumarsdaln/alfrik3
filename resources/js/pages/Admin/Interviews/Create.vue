<script setup lang="ts">
import { Form } from '@inertiajs/vue3'
import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'

import AppButton from '@/components/ui/AppButton.vue'
import AppInput from '@/components/form/AppInput.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import AppTextarea from '@/components/form/AppTextarea.vue'
import AppImageUpload from '@/components/form/AppImageUpload.vue'
import AppRadioGroup from '@/components/form/AppRadioGroup.vue'

import type { FormOption } from '@/types/forms'

import { store } from '@/actions/App/Http/Controllers/Admin/Interview/InterviewController'

interface Props {
    statuses: FormOption[]
    types: FormOption[]
}

defineProps<Props>()

function goBack(): void {
    history.back()
}
</script>

<template>
    <Form :action="store()">
        <template #default="{
            errors,
            processing,
            isDirty,
        }">
            <AppFormLayout title="Create Interview">

                <div class="max-w-5xl space-y-6">

                    <AppInput name="title" label="Title" placeholder="Enter interview title" :error="errors.title" />

                    <AppSelect name="interview_type" label="Interview Type" :options="types"
                        :error="errors.interview_type" />

                    <AppImageUpload name="thumbnail" preview-aspect="16/9" :error="errors.thumbnail" />

                    <AppTextarea name="description" label="Description" placeholder="Enter description"
                        :error="errors.description" />

                    <AppInput name="published_at" type="date" label="Published At" :error="errors.published_at" />

                    <AppInput name="duration" type="number" label="Duration (seconds)" placeholder="3600"
                        :error="errors.duration" />

                    <AppRadioGroup name="status" label="Status" :options="statuses" :error="errors.status" inline />

                </div>

                <template #footer>
                    <div class="
                                flex flex-col-reverse gap-3
                                sm:flex-row
                                sm:items-center
                                sm:justify-end
                            ">
                        <AppButton variant="cancel" type="button" @click="goBack">
                            Cancel
                        </AppButton>

                        <AppButton variant="submit" type="submit" :loading="processing"
                            :disabled="!isDirty || processing">
                            Save Interview
                        </AppButton>
                    </div>
                </template>

            </AppFormLayout>
        </template>
    </Form>
</template>