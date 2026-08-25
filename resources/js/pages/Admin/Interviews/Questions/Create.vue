<script setup lang="ts">
import { ref } from 'vue'
import { Form } from '@inertiajs/vue3'

import AdminFormLayout from '@/Layouts/AdminFormLayout.vue'

import AppButton from '@/components/Ui/AppButton.vue'
import AppTextarea from '@/components/form/AppTextarea.vue'

import UserPicker from '../Partials/UserPicker.vue'

import {
    store,
} from '@/actions/App/Domains/Interview/Http/Controllers/AdminQuestionController'


/*
|--------------------------------------------------------------------------
| Types
|--------------------------------------------------------------------------
*/

interface ParticipantUser {
    id: number
    name: string
    email: string
    avatar?: string | null
}

interface Participant {
    id: number
    user_id: number
    role: 'interviewer' | 'interviewee'
    user: ParticipantUser
}

interface Interview {
    id: number
    participants: Participant[]
}

interface Props {
    interview: Interview
}

const props = defineProps<Props>()


/*
|--------------------------------------------------------------------------
| Form Values
|--------------------------------------------------------------------------
*/

const question = ref('')

const askedBy = ref<number | string>(
    props.interview.participants[0]?.user.id ?? '',
)


/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/

function goBack(): void {
    history.back()
}
</script>

<template>
    <Form :action="store(interview.id)" #default="{
        errors,
        processing,
        isDirty
    }">
        <AdminFormLayout title="Add Question" description="Add a question and select the participant who asked it.">
            <div class="max-w-4xl space-y-6">

                <!-- Asked By -->
                <UserPicker v-model="askedBy" name="asked_by" :participants="interview.participants"
                    :error="errors.asked_by" />


                <!-- Question -->
                <AppTextarea v-model="question" name="question" label="Question"
                    placeholder="Enter the interview question..." :error="errors.question" :rows="5" required />

            </div>


            <template #footer>
                <div class="
                            flex flex-col-reverse gap-3
                            sm:flex-row
                            sm:items-center
                            sm:justify-end
                        ">
                    <AppButton variant="cancel" type="button" :disabled="!isDirty || processing" @click="goBack">
                        Cancel
                    </AppButton>

                    <AppButton variant="submit" type="submit" :loading="processing" :disabled="!isDirty || processing">
                        Add Question
                    </AppButton>
                </div>
            </template>

        </AdminFormLayout>
    </Form>
</template>