<script setup lang="ts">
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import { Form } from '@inertiajs/vue3'

    import type {
        ResearchQuestion,
    } from '@/types'

    interface Props {
        research: {
            id: number
            title: string
        }

        finding: {
            id: number
            title: string
        }

        questions: ResearchQuestion[]

        selectedQuestionIds: number[]
    }

    const props = defineProps<Props>()
</script>

<template>
    <div class="space-y-6 py-5">

        <!-- Header -->

        <div class="flex gap-4">
            <BackButton :href="`/admin/research/${research.id}/findings/${finding.id}`" />

            <Heading title="Related Research Questions"
                :description="`Select the research questions related to “${finding.title}”.`" />
        </div>

        <Form v-slot="{ errors, processing }"
            :action="`/admin/research/${research.id}/findings/${finding.id}/questions`" method="put" class="space-y-6">
            <div class="rounded-lg border border-border-light bg-background dark:border-border-dark">
                <div class="border-b border-border-light px-6 py-4 dark:border-border-dark">
                    <h2 class="font-semibold">
                        Research Questions
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Select the questions that this finding answers or
                        relates to.
                    </p>
                </div>

                <div v-if="questions.length" class="divide-y divide-border-light dark:divide-border-dark">
                    <label v-for="question in questions" :key="question.id"
                        class="flex cursor-pointer items-start gap-4 px-6 py-4 hover:bg-muted/40">
                        <input type="checkbox" name="question_ids[]" :value="question.id"
                            :checked="selectedQuestionIds.includes(question.id)"
                            class="mt-1 h-4 w-4 rounded border-border" />

                        <div class="min-w-0 flex-1">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-medium text-muted-foreground">
                                    #{{ question.position + 1 }}
                                </span>

                                <span class="text-xs text-muted-foreground">
                                    {{ question.type.label }}
                                </span>
                            </div>

                            <p class="mt-1 text-sm font-medium">
                                {{ question.question }}
                            </p>

                            <p v-if="question.description" class="mt-1 text-sm text-muted-foreground">
                                {{ question.description }}
                            </p>
                        </div>
                    </label>
                </div>

                <div v-else class="px-6 py-10 text-center">
                    <p class="text-sm text-muted-foreground">
                        No research questions have been created yet.
                    </p>

                    <Button class="mt-4" variant="outline" type="button" as-child>
                        <a :href="`/admin/research/${research.id}/questions`">
                            Create Research Question
                        </a>
                    </Button>
                </div>

                <p v-if="errors.question_ids"
                    class="border-t border-border-light px-6 py-3 text-sm text-destructive dark:border-border-dark">
                    {{ errors.question_ids }}
                </p>
            </div>

            <!-- Actions -->

            <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
                <Button type="button" variant="outline" as-child>
                    <a :href="`/admin/research/${research.id}/findings/${finding.id}`">
                        Cancel
                    </a>
                </Button>

                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Processing...' : 'Save Questions' }}
                </Button>
            </div>
        </Form>
    </div>
</template>