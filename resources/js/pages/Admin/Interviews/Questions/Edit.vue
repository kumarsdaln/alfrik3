<script setup lang="ts">
import { ref } from 'vue'
import { Form } from '@inertiajs/vue3'

import AdminLayout from '@/Layouts/AdminLayout.vue'
import AdminFormLayout from '@/Layouts/AdminFormLayout.vue'

import AppButton from '@/components/Ui/AppButton.vue'
import AppTextarea from '@/components/form/AppTextarea.vue'

import UserPicker from '../Partials/UserPicker.vue'

import {
    update,
} from '@/actions/App/Domains/Interview/Http/Controllers/AdminQuestionController'


// Types
type ParticipantRole =
    | 'interviewer'
    | 'interviewee'


interface ParticipantUser {
    id: number
    name: string
    email?: string
    avatar?: string | null
}


interface Participant {
    id: number
    user_id: number
    role: ParticipantRole
    user: ParticipantUser
}


interface Interview {
    id: number
    participants: Participant[]
}


interface Question {
    id: number
    question: string
    asked_by: number | null
}


interface Props {
    interview: Interview
    question: Question
}


const props = defineProps<Props>()


// Form Values
const questionText = ref(
    props.question.question ?? '',
)

const askedBy = ref<number | null>(
    props.question.asked_by ?? null,
)


// Navigation
function goBack(): void {
    history.back()
}
</script>


<template>
    <Form :action="update({
        interview: interview.id,
        question: question.id,
    })
        ">
        <template #default="{
            errors,
            processing,
            isDirty,
        }">
            <AdminFormLayout title="Edit Question" description="Update the question and select who asked it.">

                <div class="max-w-4xl space-y-6">

                    <!-- Asked By -->

                    <UserPicker v-model="askedBy" name="asked_by" label="Asked By"
                        :participants="interview.participants" :error="errors.asked_by" required />


                    <!-- Question -->

                    <AppTextarea v-model="questionText" name="question" label="Question"
                        placeholder="Enter the interview question..." :error="errors.question" :rows="6" required />

                </div>


                <!-- Footer -->

                <template #footer>
                    <div class="
                                flex flex-col-reverse gap-3

                                sm:flex-row
                                sm:items-center
                                sm:justify-end
                            ">
                        <AppButton variant="cancel" type="button" :disabled="processing" @click="goBack">
                            Cancel
                        </AppButton>


                        <AppButton variant="submit" type="submit" :loading="processing"
                            :disabled="!isDirty || processing">
                            Update Question
                        </AppButton>
                    </div>
                </template>

            </AdminFormLayout>
        </template>
    </Form>
</template>