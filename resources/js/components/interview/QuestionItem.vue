<script setup lang="ts">
    import { Form } from '@inertiajs/vue3'

    import ProfileCell from '@/components/profile/ProfileCell.vue'
    import Button from '@/components/ui/button/Button.vue'

    import {
        destroy as questionDestroy,
    } from '@/routes/admin/interviews/questions'

    import type { InterviewQuestion } from '@/types'
    import { Edit2, GripVertical, Trash2 } from '@lucide/vue'

    interface Props {
        question: InterviewQuestion
        index: number
    }

    const props = defineProps<Props>()

    const emit = defineEmits<{
        edit: [question: InterviewQuestion]
    }>()
</script>

<template>
    <div class="p-5 sm:p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <!-- Question -->
            <div class="flex min-w-0 items-start gap-3">
                <!-- Drag Handle -->
                <button type="button"
                    class="question-drag-handle mt-1 flex size-8 shrink-0 cursor-grab items-center justify-center rounded-md text-muted-foreground transition hover:bg-muted hover:text-foreground active:cursor-grabbing"
                    title="Drag to reorder" aria-label="Drag to reorder question">
                    <GripVertical class="size-4" />
                </button>

                <!-- Position -->
                <div
                    class="mt-1 flex size-8 shrink-0 items-center justify-center rounded-full bg-muted text-sm font-medium">
                    {{ index + 1 }}
                </div>

                <!-- Content -->
                <div class="min-w-0">
                    <p class="whitespace-pre-wrap text-sm font-medium">
                        {{ question.question }}
                    </p>

                    <!-- Asked By -->
                    <div v-if="question.asked_by" class="mt-3">
                        <ProfileCell :profile="question.asked_by" size="xs" />
                    </div>

                    <!-- Answers -->
                    <p v-if="question.answers?.length" class="mt-3 text-xs text-muted-foreground">
                        {{ question.answers.length }}
                        {{
                            question.answers.length === 1
                                ? 'answer'
                                : 'answers'
                        }}
                    </p>
                </div>
            </div>

            <!-- Actions -->
            <!-- Actions -->
            <div class="flex shrink-0 items-center gap-2">
                <!-- Edit -->
                <Button type="button" variant="outline" size="icon" title="Edit question" aria-label="Edit question"
                    @click="emit('edit', props.question)">
                    <Edit2 class="size-4" />
                </Button>

                <!-- Delete -->
                <Form :action="questionDestroy(props.question.id)" method="delete">
                    <Button type="submit" variant="destructive" size="icon" title="Delete question"
                        aria-label="Delete question">
                        <Trash2 class="size-4" />
                    </Button>
                </Form>
            </div>
        </div>
    </div>
</template>