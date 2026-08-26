<script setup lang="ts">
import { computed, ref } from 'vue'
import { Form } from '@inertiajs/vue3'

import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'

import AppButton from '@/components/ui/AppButton.vue'
import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import AppTextarea from '@/components/form/AppTextarea.vue'

import UserPicker from '../Partials/UserPicker.vue'

import {
    update,
} from '@/actions/App/Http/Controllers/Admin/Interview/AnswerController'


/*
|--------------------------------------------------------------------------
| Types
|--------------------------------------------------------------------------
*/

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


interface QuestionInterviewer {
    id: number
    name: string
}


interface Question {
    id: number
    order: number
    question: string
    asked_by: number | null
    interviewer?: QuestionInterviewer | null
}


interface Answer {
    id: number
    answer: string
    answered_by: number | null
}


interface Props {
    interview: Interview
    question: Question
    answer: Answer
}


const props = defineProps<Props>()


/*
|--------------------------------------------------------------------------
| Form Values
|--------------------------------------------------------------------------
*/

const answerText = ref(
    props.answer.answer ?? '',
)

const answeredBy = ref<number | null>(
    props.answer.answered_by ?? null,
)


/*
|--------------------------------------------------------------------------
| Asked By
|--------------------------------------------------------------------------
*/

const askedByName = computed<string | null>(() => {
    return props.question.interviewer?.name ?? null
})


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
    <Form :action="update({
        interview: interview.id,
        question: question.id,
        answer: answer.id,
    })
        ">
        <template #default="{
            errors,
            processing,
            isDirty,
        }">
            <AppFormLayout title="Edit Answer" description="Update the interviewee and their response.">
                <div class="max-w-5xl space-y-6">

                    <!-- Question Preview -->

                    <section class="
                                rounded-xl
                                border
                                border-zinc-200

                                bg-zinc-50/70
                                p-4

                                shadow-none
                                ring-0

                                sm:p-5

                                dark:border-zinc-800
                                dark:bg-zinc-900
                            ">
                        <div class="flex items-start gap-3">

                            <!-- Question Number -->

                            <div class="
                                        flex h-10 min-w-10
                                        shrink-0
                                        items-center
                                        justify-center

                                        rounded-xl

                                        bg-brand/10
                                        px-2

                                        text-brand
                                    ">
                                <AppText size="xs" weight="semibold" class="text-brand">
                                    Q{{ question.order }}
                                </AppText>
                            </div>


                            <!-- Question Content -->

                            <div class="min-w-0 flex-1">

                                <AppText size="xs" weight="semibold" color="muted">
                                    Question
                                </AppText>


                                <AppHeading tag="h3" font="redhat" size="md" weight="medium" leading="relaxed"
                                    class="mt-1">
                                    {{ question.question }}
                                </AppHeading>


                                <!-- Question Metadata -->

                                <div v-if="askedByName" class="
                                            mt-3
                                            flex flex-wrap
                                            items-center
                                            gap-2
                                        ">
                                    <div class="
                                                inline-flex
                                                items-center
                                                gap-1.5

                                                rounded-md

                                                bg-zinc-200/70
                                                px-2.5
                                                py-1

                                                dark:bg-zinc-800
                                            ">
                                        <AppText size="xs" color="muted">
                                            Asked by
                                        </AppText>

                                        <AppText size="xs" weight="semibold">
                                            {{ askedByName }}
                                        </AppText>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </section>


                    <!-- Answered By -->

                    <UserPicker v-model="answeredBy" name="answered_by" label="Answered By"
                        :participants="interview.participants" :error="errors.answered_by" required />


                    <!-- Answer -->

                    <AppTextarea v-model="answerText" name="answer" label="Answer"
                        placeholder="Write a thoughtful answer..." :error="errors.answer" :rows="8" required />

                </div>


                <!-- Footer -->

                <template #footer>
                    <div class="
                                flex flex-col-reverse
                                gap-3

                                sm:flex-row
                                sm:items-center
                                sm:justify-end
                            ">
                        <AppButton variant="cancel" type="button" :disabled="processing" @click="goBack">
                            Cancel
                        </AppButton>


                        <AppButton variant="submit" type="submit" :loading="processing"
                            :disabled="processing || !isDirty">
                            Update Answer
                        </AppButton>
                    </div>
                </template>

            </AppFormLayout>
        </template>
    </Form>
</template>