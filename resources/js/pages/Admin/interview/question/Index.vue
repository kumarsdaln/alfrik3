<script setup lang="ts">
    import { Link, router } from '@inertiajs/vue3'

    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'
    import QuestionList from '@/components/interview/QuestionList.vue'

    import {
        create as questionCreate,
        edit as questionEdit,
    } from '@/routes/admin/interviews/questions'

    import type { InterviewQuestion } from '@/types'
    import { Plus } from '@lucide/vue'
import BackButton from '@/components/ui/BackButton.vue'

    interface Props {
        interview: {
            id: number
            title: string
        }

        questions: {
            data: InterviewQuestion[]
        }
    }

    const props = defineProps<Props>()

    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */
    function addQuestion(): void {
        router.visit(questionCreate(props.interview.id).url)
    }

    function editQuestion(question: InterviewQuestion): void {
        router.visit(questionEdit({
            interview: props.interview.id,
            question: question.id
        }).url)
    }
</script>

<template>
    <div class="mt-4 space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between gap-4 py-5">
            <div class="flex items-center gap-4">
                <BackButton />

                <Heading 
                    title="Interview Questions"
                    :description="`Manage questions and answers for ${props.interview.title}.`" />
            </div>

            <Button as-child class="gap-2">
                <Link :href="questionCreate(props.interview.id).url">
                <Plus class="size-4" />
                Add Question
                </Link>
            </Button>
        </div>

        <!-- Question List -->
        <QuestionList 
            :interview-id="props.interview.id" 
            :questions="props.questions.data" 
            @edit="editQuestion"
            @add="addQuestion" />
    </div>
</template>
