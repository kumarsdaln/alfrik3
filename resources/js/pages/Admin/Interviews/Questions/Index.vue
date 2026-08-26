<script setup lang="ts">
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'

import {
    ChevronDown,
    CircleHelp,
    GripVertical,
    MessageCircle,
    Pencil,
    Plus,
    Trash2,
    UserRound,
} from '@lucide/vue'

import PageHeader from '@/components/dashboard/PageHeader.vue'
import AppButton from '@/components/ui/AppButton.vue'
import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'

import AdminQuestionController from '@/actions/App/Http/Controllers/Admin/Interview/QuestionController'

import AdminAnswerController from '@/actions/App/Http/Controllers/Admin/Interview/AnswerController'

interface User {
    id: number
    name: string
    avatar?: string | null
}

interface Answer {
    id: number
    answer: string
    answered_by?: User | null
}

interface Question {
    id: number
    question: string
    type: string
    interviewer?: User | null
    answers?: Answer[]
}

interface Interview {
    id: number
    title: string
    questions?: Question[]
}

interface Props {
    interview: Interview
}

const props = defineProps<Props>()


// State
const questions = ref<Question[]>(
    [...(props.interview.questions ?? [])],
)

const expandedAnswers = ref<Record<number, boolean>>({})
const draggingIndex = ref<number | null>(null)
const dragOverIndex = ref<number | null>(null)
const reordering = ref(false)


// Answers
function toggleAnswers(questionId: number): void {
    expandedAnswers.value[questionId] =
        !expandedAnswers.value[questionId]
}

// Drag and Drop
function onDragStart(
    event: DragEvent,
    index: number,
): void {
    draggingIndex.value = index

    if (event.dataTransfer) {
        event.dataTransfer.effectAllowed = 'move'
    }
}

function onDragOver(index: number): void {
    if (index === draggingIndex.value) {
        return
    }

    dragOverIndex.value = index
}

function onDrop(index: number): void {
    if (
        draggingIndex.value === null ||
        draggingIndex.value === index
    ) {
        resetDragState()

        return
    }

    const previousQuestions = [...questions.value]

    const reorderedQuestions = [...questions.value]

    const [movedQuestion] = reorderedQuestions.splice(
        draggingIndex.value,
        1,
    )

    reorderedQuestions.splice(
        index,
        0,
        movedQuestion,
    )

    questions.value = reorderedQuestions

    resetDragState()

    saveOrder(previousQuestions)
}

function onDragEnd(): void {
    resetDragState()
}

function resetDragState(): void {
    draggingIndex.value = null
    dragOverIndex.value = null
}


// Save Order
function saveOrder(
    previousQuestions: Question[],
): void {
    reordering.value = true

    router.patch(
        AdminQuestionController.reorder(props.interview.id).url,
        {
            questions: questions.value.map(
                (question, index) => ({
                    id: question.id,
                    order: index + 1,
                }),
            ),
        },
        {
            preserveScroll: true,
            preserveState: true,

            onError: () => {
                questions.value = previousQuestions
            },

            onFinish: () => {
                reordering.value = false
            },
        },
    )
}
</script>


<template>
    <div class="
                flex h-[calc(100vh-142px)]
                flex-col overflow-hidden
                border
                border-zinc-200
                bg-white
                shadow-none
                ring-0
                dark:border-zinc-800
                dark:bg-zinc-950
            ">
        <!-- Header -->

        <PageHeader :title="`${interview.title} — Questions`"
            description="Manage questions, responses, and interview order.">
            <template #header-actions>
                <AppButton :href="AdminQuestionController.create(interview.id)" variant="add" auto-icon size="sm">
                    Add Question
                </AppButton>
            </template>
        </PageHeader>


        <!-- Content -->

        <div class="
                    flex-1 overflow-y-auto
                    p-3 sm:p-4 lg:p-6

                    scrollbar-thin
                    scrollbar-track-transparent
                    scrollbar-thumb-zinc-300

                    dark:scrollbar-thumb-zinc-700
                ">
            <div v-if="questions.length" class="space-y-3">
                <!-- Question -->

                <article v-for="(question, index) in questions" :key="question.id" class="
                            overflow-hidden rounded-xl
                            border bg-white

                            shadow-none
                            ring-0

                            transition-[border-color,background-color,opacity]
                            duration-200

                            dark:bg-zinc-900
                        " :class="[
                            dragOverIndex === index &&
                                draggingIndex !== index
                                ? [
                                    'border-brand',
                                    'bg-brand/[0.02]',
                                    'dark:bg-brand/[0.04]',
                                ]
                                : [
                                    'border-zinc-200',
                                    'dark:border-zinc-800',
                                ],

                            draggingIndex === index
                                ? 'opacity-50'
                                : 'opacity-100',
                        ]" @dragover.prevent="onDragOver(index)" @drop="onDrop(index)">
                    <!-- Question Main Row -->

                    <div class="
                                flex flex-col gap-4
                                p-4

                                sm:p-5

                                lg:flex-row
                                lg:items-start
                            ">
                        <!-- Drag Handle -->

                        <div draggable="true" class="
                                    flex shrink-0 cursor-grab
                                    items-center gap-2

                                    active:cursor-grabbing

                                    lg:flex-col
                                " @dragstart="
                                    onDragStart(
                                        $event,
                                        index,
                                    )
                                    " @dragend="onDragEnd">
                            <GripVertical class="
                                        h-5 w-5
                                        text-zinc-400

                                        transition-colors

                                        hover:text-zinc-700

                                        dark:text-zinc-600
                                        dark:hover:text-zinc-300
                                    " />

                            <AppText size="xs" weight="semibold" color="muted">
                                Q{{ index + 1 }}
                            </AppText>
                        </div>


                        <!-- Question Information -->

                        <div class="min-w-0 flex-1">
                            <AppHeading tag="h3" font="redhat" size="md" weight="semibold" leading="relaxed">
                                {{ question.question }}
                            </AppHeading>


                            <div v-if="question.interviewer?.name" class="
                                        mt-3 flex flex-wrap
                                        items-center gap-2
                                    ">
                                <div class="
                                            inline-flex items-center
                                            gap-1.5 rounded-lg

                                            bg-zinc-100
                                            px-2.5 py-1.5

                                            dark:bg-zinc-800
                                        ">
                                    <UserRound class="
                                                h-3.5 w-3.5
                                                text-zinc-500
                                            " />

                                    <AppText size="xs" weight="medium" color="muted">
                                        {{
                                            question.interviewer.name
                                        }}
                                    </AppText>
                                </div>
                            </div>
                        </div>


                        <!-- Actions -->

                        <div class="
                                    flex shrink-0
                                    items-center gap-1.5

                                    border-t
                                    border-zinc-100
                                    pt-3

                                    lg:border-0
                                    lg:pt-0

                                    dark:border-zinc-800
                                ">
                            <!-- Answers -->

                            <button type="button" class="
                                        inline-flex items-center
                                        gap-2 rounded-lg
                                        px-3 py-2
                                        text-xs font-semibold
                                        shadow-none
                                        ring-0
                                        transition-colors
                                        focus:outline-none
                                        focus:ring-0
                                        focus-visible:ring-0" :class="expandedAnswers[question.id]
                                            ? [
                                                'bg-zinc-900',
                                                'text-white',
                                                'dark:bg-white',
                                                'dark:text-zinc-900',
                                            ]
                                            : [
                                                'bg-zinc-100',
                                                'text-zinc-700',
                                                'hover:bg-zinc-200',
                                                'dark:bg-zinc-800',
                                                'dark:text-zinc-300',
                                                'dark:hover:bg-zinc-700',
                                            ]" @click="toggleAnswers(question.id)">
                                <MessageCircle class="h-4 w-4" />

                                <span>
                                    {{
                                        question.answers?.length ?? 0
                                    }}
                                </span>

                                <span class="hidden sm:inline">
                                    Answers
                                </span>

                                <ChevronDown class="
                                            h-4 w-4
                                            transition-transform
                                            duration-200
                                        " :class="expandedAnswers[question.id]
                                            ? 'rotate-180'
                                            : ''
                                            " />
                            </button>


                            <!-- Edit -->
                            <AppButton :href="AdminQuestionController.edit({
                                interview: interview.id,
                                question: question.id,
                            })" variant="edit" icon-only auto-icon>
                            </AppButton>

                            <!-- Delete -->
                            <AppButton :href="AdminQuestionController.destroy({
                                interview: interview.id,
                                question: question.id,
                            })" variant="delete" method="delete" auto-icon icon-only>
                            </AppButton>
                        </div>
                    </div>


                    <!-- Answers -->

                    <Transition enter-active-class="
                                transition-all
                                duration-300 ease-out
                                overflow-hidden
                            " enter-from-class="
                                max-h-0 opacity-0
                            " enter-to-class="
                                max-h-[1200px] opacity-100
                            " leave-active-class="
                                transition-all
                                duration-200 ease-in
                                overflow-hidden
                            " leave-from-class="
                                max-h-[1200px] opacity-100
                            " leave-to-class="
                                max-h-0 opacity-0
                            ">
                        <section v-if="
                            expandedAnswers[question.id]
                        " class="
                                    border-t
                                    border-zinc-100

                                    bg-zinc-50/70

                                    dark:border-zinc-800
                                    dark:bg-zinc-950/40
                                ">
                            <div class="p-4 sm:p-5">
                                <!-- Answer Header -->

                                <div class="
                                            mb-4 flex
                                            items-center
                                            justify-between
                                            gap-3
                                        ">
                                    <div>
                                        <AppHeading tag="h4" font="redhat" size="sm" weight="semibold">
                                            Responses
                                        </AppHeading>

                                        <AppText size="xs" color="muted" class="mt-1">
                                            {{
                                                question.answers
                                                    ?.length ?? 0
                                            }}
                                            responses recorded
                                        </AppText>
                                    </div>


                                    <AppButton :href="AdminAnswerController.create({
                                        interview: interview.id,
                                        question: question.id
                                    })" variant="add" auto-icon size="sm">
                                        Add Answer
                                    </AppButton>
                                </div>


                                <!-- Answer List -->
                                <div v-if="question.answers?.length" class="space-y-2">
                                    <div v-for="(answer, answerIndex) in question.answers" :key="answer.id" class="
                                                group flex items-start gap-3
                                                rounded-xl border
                                                border-zinc-200
                                                bg-white p-3
                                                sm:p-4
                                                dark:border-zinc-800
                                                dark:bg-zinc-900
                                            ">
                                        <!-- Avatar -->
                                        <div class="
                                                    flex h-9 w-9 shrink-0
                                                    items-center justify-center
                                                    rounded-full
                                                    bg-brand/10
                                                    text-sm font-semibold
                                                    text-brand
                                                ">
                                            {{
                                                answer.answered_by?.name
                                                    ?.charAt(0)
                                                    ?.toUpperCase()
                                                ?? answerIndex + 1
                                            }}
                                        </div>

                                        <!-- Answer Content -->
                                        <div class="min-w-0 flex-1">

                                            <!-- Header -->
                                            <div class="flex items-start justify-between gap-3">

                                                <AppText size="xs" weight="semibold" color="muted">
                                                    {{ answer.answered_by?.name ?? 'Anonymous User' }}
                                                </AppText>

                                                <!-- Actions -->
                                                <div class="flex shrink-0 items-center gap-2">

                                                    <AppButton :href="AdminAnswerController.edit({
                                                        interview: interview.id,
                                                        question: question.id,
                                                        answer: answer.id,
                                                    })" variant="edit" size="xs" icon-only auto-icon />

                                                    <AppButton :href="AdminAnswerController.destroy({
                                                        interview: interview.id,
                                                        question: question.id,
                                                        answer: answer.id,
                                                    }).url" variant="delete" size="xs" icon-only auto-icon
                                                        method="delete" />

                                                </div>
                                            </div>

                                            <!-- Answer -->
                                            <AppText tag="p" size="sm" leading="relaxed"
                                                class="mt-2 whitespace-pre-wrap">
                                                {{ answer.answer }}
                                            </AppText>

                                        </div>
                                    </div>
                                </div>


                                <!-- No Answers -->

                                <div v-else class="
                                            flex flex-col
                                            items-center
                                            justify-center
                                            rounded-xl
                                            border border-dashed
                                            border-zinc-300
                                            px-4 py-8
                                            text-center
                                            shadow-none
                                            ring-0
                                            dark:border-zinc-700 ">

                                    <MessageCircle class="h-7 w-7 text-zinc-300 dark:text-zinc-600" />
                                    <AppHeading tag="h4" font="redhat" size="sm" weight="medium" class="mt-3">
                                        No responses yet
                                    </AppHeading>

                                    <AppText size="xs" color="muted" leading="relaxed" class="
                                                mt-1 max-w-sm
                                            ">
                                        Add the first response to
                                        this interview question.
                                    </AppText>
                                </div>
                            </div>
                        </section>
                    </Transition>
                </article>
            </div>


            <!-- Empty Questions State -->

            <div v-else class="
                        flex min-h-[420px]
                        flex-col items-center
                        justify-center
                        rounded-2xl border
                        border-dashed
                        border-zinc-300
                        px-6 text-center
                        shadow-none
                        ring-0
                        dark:border-zinc-700
                    ">
                <div class="
                            flex h-14 w-14
                            items-center justify-center
                            rounded-2xl
                            bg-zinc-100
                            text-zinc-400
                            dark:bg-zinc-800
                            dark:text-zinc-500
                        ">
                    <CircleHelp class="h-7 w-7" />
                </div>


                <AppHeading tag="h3" font="redhat" size="lg" weight="semibold" class="mt-4">
                    No Questions Added
                </AppHeading>

                <AppText size="sm" color="muted" leading="relaxed" class="mt-2 max-w-sm">
                    Start building the interview by adding the first question.
                </AppText>

                <AppButton :href="AdminQuestionController.create(interview.id)" variant="add" auto-icon size="sm"
                    class="mt-5">
                    Add First Question
                </AppButton>
            </div>
        </div>
    </div>
</template>