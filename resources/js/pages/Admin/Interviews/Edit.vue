<script setup lang="ts">
import { ref } from 'vue'
import { Form } from '@inertiajs/vue3'

import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'

import AppButton from '@/components/Ui/AppButton.vue'
import AppInput from '@/components/form/AppInput.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import AppTextarea from '@/components/form/AppTextarea.vue'
import AppImageUpload from '@/components/form/AppImageUpload.vue'
import AppRadioGroup from '@/components/form/AppRadioGroup.vue'

import type { FormOption } from '@/types/forms'

import { update } from '@/actions/App/Http/Controllers/Admin/Interview/InterviewController'

interface Interview {
    id: number
    title: string
    interview_type: string
    status: string
    description: string
    thumbnail: string
    duration: number
    published_at: string
}

interface Props {
    interview: Interview
    statuses: FormOption[]
    types: FormOption[]
}

const props = defineProps<Props>()

function goBack(): void {
    history.back()
}

const publishedAt = ref(props.interview.published_at ? props.interview.published_at.slice(0, 10) : '')
</script>

<template>
    <Form :action="update(interview.id)">
        <template #default="{
            errors,
            processing,
            isDirty,
        }">
            <AppFormLayout title="Edit Interview" description="Update interview details and publishing information.">
                <div class="max-w-5xl space-y-6">

                    <AppInput name="title" v-model="interview.title" label="Title" placeholder="Enter interview title"
                        :error="errors.title" />

                    <AppSelect name="interview_type" v-model="interview.interview_type" label="Interview Type"
                        :options="types" :error="errors.interview_type" />

                    <AppImageUpload name="thumbnail" :existing-image="interview.thumbnail" preview-aspect="16/9"
                        :error="errors.thumbnail" />

                    <AppTextarea name="description" v-model="interview.description" label="Description"
                        placeholder="Enter description" :error="errors.description" />

                    <AppInput name="duration" v-model="interview.duration" type="number" label="Duration (seconds)"
                        placeholder="Enter duration" :error="errors.duration" />
                    <AppInput name="published_at" v-model="publishedAt" type="date" label="Published At"
                        :error="errors.published_at" />

                    <AppRadioGroup name="status" v-model="interview.status" label="Status" :options="statuses"
                        :error="errors.status" inline />

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
                            Update Interview
                        </AppButton>
                    </div>
                </template>

            </AppFormLayout>
        </template>
    </Form>
</template>