<script setup lang="ts">
import { router } from '@inertiajs/vue3'
import Sortable from 'sortablejs'
import { onBeforeUnmount, onMounted, ref } from 'vue'

import QuestionEmpty from './QuestionEmpty.vue'
import QuestionItem from './QuestionItem.vue'

import { reorder as questionReorder } from '@/routes/admin/interviews/questions'

import type { InterviewQuestion } from '@/types'

interface Props {
    interviewId: number
    questions: InterviewQuestion[]
}

const props = defineProps<Props>()

const emit = defineEmits<{
    edit: [question: InterviewQuestion]
    add: []
}>()

const questionList = ref<InterviewQuestion[]>([
    ...props.questions,
])

const listElement = ref<HTMLElement | null>(null)

let sortable: Sortable | null = null

const reorderQuestions = (oldIndex: number, newIndex: number) => {
    if (oldIndex === newIndex) {
        return
    }

    const [question] = questionList.value.splice(oldIndex, 1)

    if (!question) {
        return
    }

    questionList.value.splice(newIndex, 0, question)

    const questions = questionList.value.map(
        (question, position) => ({
            id: question.id,
            position,
        })
    )

    router.put(
        questionReorder(props.interviewId).url,
        {
            questions,
        },
        {
            preserveScroll: true,
        }
    )
}

onMounted(() => {
    if (!listElement.value) {
        return
    }

    sortable = Sortable.create(listElement.value, {
        animation: 150,

        handle: '.question-drag-handle',

        ghostClass: 'opacity-50',

        chosenClass: 'bg-muted/50',

        dragClass: 'shadow-lg',

        onEnd: (event) => {
            if (
                event.oldIndex === undefined ||
                event.newIndex === undefined
            ) {
                return
            }

            reorderQuestions(
                event.oldIndex,
                event.newIndex
            )
        },
    })
})

onBeforeUnmount(() => {
    sortable?.destroy()
    sortable = null
})
</script>

<template>
    <div class="border bg-card">
        <!-- Header -->
        <div class="border-b p-5 sm:p-6">
            <h2 class="text-lg font-semibold">
                Interview Questions
            </h2>

            <p class="mt-1 text-sm text-muted-foreground">
                {{ questionList.length }}
                {{
                    questionList.length === 1
                        ? 'question'
                        : 'questions'
                }}
            </p>
        </div>

        <!-- Questions -->
        <div
            v-if="questionList.length"
            ref="listElement"
            class="divide-y"
        >
            <div
                v-for="(question, index) in questionList"
                :key="question.id"
            >
                <QuestionItem
                    :question="question"
                    :index="index"
                    @edit="emit('edit', $event)"
                />
            </div>
        </div>

        <!-- Empty -->
        <QuestionEmpty
            v-else
            @add="emit('add')"
        />
    </div>
</template>