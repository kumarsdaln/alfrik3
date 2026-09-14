<script setup lang="ts">
    import { router } from '@inertiajs/vue3'

    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'
    import QuestionList from '@/components/interview/QuestionList.vue'

    import { edit as interviewEdit } from '@/routes/admin/interviews'

    import {
        create as questionCreate,
        edit as questionEdit,
    } from '@/routes/admin/interviews/questions'

    import type { InterviewQuestion } from '@/types'

    const props = defineProps<{
        interview: {
            id: number
            title: string
        }

        questions: {
            data: InterviewQuestion[]
        }
    }>()

    const backToInterview = () => {
        router.visit(
            interviewEdit(props.interview.id).url
        )
    }

    const addQuestion = () => {
        router.visit(
            questionCreate(props.interview.id).url
        )
    }

    const editQuestion = (question: InterviewQuestion) => {
        router.visit(
            questionEdit(question.id).url
        )
    }
</script>

<template>
    <div class="space-y-6 mt-4">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <Heading title="Questions" :description="`Manage questions for ${interview.title}`" />

            <div class="flex w-full gap-2 sm:w-auto">
                <Button type="button" variant="outline" class="flex-1 sm:flex-none" @click="backToInterview">
                    Back
                </Button>

                <Button type="button" class="flex-1 sm:flex-none" @click="addQuestion">
                    Add Question
                </Button>
            </div>
        </div>

        <!-- Question List -->
        <QuestionList 
            :interview-id="interview.id"
            :questions="questions.data" 
            @edit="editQuestion" 
            @add="addQuestion" />
    </div>
</template>