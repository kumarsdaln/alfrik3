<script setup lang="ts">
import { computed, ref } from 'vue'
import { Form } from '@inertiajs/vue3'
import { CircleHelp } from '@lucide/vue'

import AdminFormLayout from '@/Layouts/AdminFormLayout.vue'

import AppButton from '@/components/ui/AppButton.vue'
import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import AppTextarea from '@/components/form/AppTextarea.vue'

import UserPicker from '../Partials/UserPicker.vue'

import {
    store,
} from '@/actions/App/Domains/Interview/Http/Controllers/AdminAnswerController'


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
    type: string
    asked_by: number | null
}

interface Props {
    interview: Interview
    question: Question
}


const props = defineProps<Props>()


// Form Values
const answer = ref('')

const answeredBy = ref<number | null>(
    props.interview.participants[0]?.user.id ?? null,
)


// Question Information
const askedByName = computed<string | null>(() => {
    if (!props.question.asked_by) {
        return null
    }

    return (
        props.interview.participants.find(
            participant =>
                participant.user.id === props.question.asked_by,
        )?.user.name ?? null
    )
})


// Navigation
function goBack(): void {
    history.back()
}
</script>


<template>
    <Form :action="store({ interview: interview.id, question: question.id, })">
        <template #default="{
            errors,
            processing,
            isDirty,
        }">
            <AdminFormLayout title="Submit Answer"
                description="Select the participant answering this question and add their response.">
                <div class="max-w-5xl space-y-6">

                    <!-- Question Preview -->
                    <section class="
                                rounded-xl border
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

                            <!-- Icon -->

                            <div class="
                                        flex h-10 w-10
                                        shrink-0
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-brand/10
                                        text-brand">
                                <CircleHelp class="h-5 w-5" />
                            </div>


                            <!-- Content -->
                            <div class="min-w-0 flex-1">
                                <AppText size="xs" weight="semibold" color="muted">
                                    Question
                                </AppText>

                                <AppHeading tag="h3" font="redhat" size="md" weight="medium" leading="relaxed"
                                    class="mt-1">
                                    {{ question.question }}
                                </AppHeading>


                                <!-- Metadata -->
                                <div class="
                                            mt-3 flex
                                            flex-wrap
                                            items-center
                                            gap-2
                                        ">
                                    <!-- Type -->
                                    <span class="
                                                inline-flex
                                                items-center
                                                rounded-md
                                                bg-zinc-200/70
                                                px-2.5 py-1
                                                dark:bg-zinc-800">
                                        <AppText size="xs" weight="medium" color="muted" class="capitalize">
                                            {{ question.type }}
                                        </AppText>
                                    </span>


                                    <!-- Asked By -->
                                    <span v-if="askedByName" class="
                                                inline-flex
                                                items-center gap-1
                                                rounded-md
                                                bg-zinc-200/70
                                                px-2.5 py-1
                                                dark:bg-zinc-800
                                            ">
                                        <AppText size="xs" color="muted">
                                            Asked by
                                        </AppText>

                                        <AppText size="xs" weight="semibold">
                                            {{ askedByName }}
                                        </AppText>
                                    </span>

                                </div>
                            </div>
                        </div>
                    </section>


                    <!-- Answered By -->
                    <UserPicker v-model="answeredBy" name="answered_by" label="Answered By"
                        :participants="interview.participants" :error="errors.answered_by" required />


                    <!-- Answer -->
                    <AppTextarea v-model="answer" name="answer" label="Answer"
                        placeholder="Write a thoughtful answer..." :error="errors.answer" :rows="8" required />

                </div>


                <!-- Footer -->

                <template #footer>
                    <div class="
                                flex flex-col-reverse
                                gap-3
                                sm:flex-row
                                sm:items-center
                                sm:justify-end">
                        <AppButton variant="cancel" type="button" :disabled="processing" @click="goBack">
                            Cancel
                        </AppButton>


                        <AppButton variant="submit" type="submit" :loading="processing"
                            :disabled="!isDirty || processing">
                            Submit Answer
                        </AppButton>
                    </div>
                </template>

            </AdminFormLayout>
        </template>
    </Form>
</template>