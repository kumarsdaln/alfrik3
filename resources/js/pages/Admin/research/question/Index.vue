<script setup lang="ts">
    import { router } from '@inertiajs/vue3'
    import { Pencil, Plus, Trash2 } from '@lucide/vue'

    import {
        AppFormControl,
        AppInput,
        AppTextarea,
        AppSelect,
    } from '@/components/form'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Badge from '@/components/ui/badge/Badge.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import { Form } from '@inertiajs/vue3'

    import type { FormOption } from '@/types'

    interface ResearchQuestion {
        id: number
        question: string
        description: string | null
        type: {
            value: string
            label: string
        }
        position: number
    }

    interface Props {
        research: {
            id: number
            title: string
        }

        questions: ResearchQuestion[]

        questionTypeOptions: FormOption[]
    }

    const props = defineProps<Props>()

    const columns = [
        {
            key: 'position',
            label: '#',
            width: '70px',
        },
        {
            key: 'question',
            label: 'Question',
        },
        {
            key: 'type',
            label: 'Type',
            width: '180px',
        },
        {
            key: 'actions',
            label: '',
            width: '64px',
            align: 'right' as const,
        },
    ]

    function deleteQuestion(question: ResearchQuestion) {
        if (!confirm(`Are you sure you want to delete this question?`)) {
            return
        }

        router.delete(
            `/admin/research/${props.research.id}/questions/${question.id}`,
            {
                preserveScroll: true,
            },
        )
    }
</script>

<template>
    <TableLayout>
        <template #header>
            <div class="flex items-center gap-4 py-5">
                <BackButton />

                <Heading
                    title="Research Questions"
                    :description="`Manage research questions for ${research.title}.`"
                />
            </div>
        </template>

        <div class="space-y-8 py-6">
            <!-- Add Question -->
            <section class="space-y-4">
                <div>
                    <h2 class="text-base font-semibold">
                        Add Question
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Define a research question or hypothesis.
                    </p>
                </div>

                <Form
                    v-slot="{ errors, processing }"
                    :action="`/admin/research/${research.id}/questions`"
                    method="post"
                    class="space-y-4"
                >
                    <AppFormControl
                        label="Question"
                        required
                        :error="errors.question"
                    >
                        <AppInput
                            name="question"
                            placeholder="Enter research question"
                        />
                    </AppFormControl>

                    <AppFormControl
                        label="Description"
                        :error="errors.description"
                    >
                        <AppTextarea
                            name="description"
                            placeholder="Add additional context..."
                        />
                    </AppFormControl>

                    <AppFormControl
                        label="Question Type"
                        required
                        :error="errors.type"
                    >
                        <AppSelect
                            name="type"
                            placeholder="Select question type"
                            :options="questionTypeOptions"
                        />
                    </AppFormControl>

                    <div class="flex justify-end">
                        <Button
                            type="submit"
                            :disabled="processing"
                            class="gap-2"
                        >
                            <Plus class="size-4" />

                            {{ processing ? 'Processing...' : 'Add Question' }}
                        </Button>
                    </div>
                </Form>
            </section>

            <!-- Questions -->
            <section
                class="space-y-4 border-t border-border-light pt-8 dark:border-border-dark"
            >
                <div>
                    <h2 class="text-base font-semibold">
                        Questions
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Questions currently defined for this research.
                    </p>
                </div>

                <AppTable
                    :columns="columns"
                    :data="questions"
                >
                    <template #cell-position="{ value }">
                        {{ value + 1 }}
                    </template>

                    <template #cell-question="{ row }">
                        <div class="min-w-0">
                            <div class="font-medium">
                                {{ row.question }}
                            </div>

                            <p
                                v-if="row.description"
                                class="mt-0.5 text-sm text-muted-foreground"
                            >
                                {{ row.description }}
                            </p>
                        </div>
                    </template>

                    <template #cell-type="{ value }">
                        <Badge variant="outline">
                            {{ value.label }}
                        </Badge>
                    </template>

                    <template #cell-actions="{ row }">
                        <AppTableActions
                            :actions="[
                                {
                                    label: 'Edit',
                                    icon: Pencil,
                                    href: `/admin/research/${research.id}/questions/${row.id}/edit`,
                                },
                                {
                                    label: 'Delete',
                                    icon: Trash2,
                                    danger: true,
                                    onClick: () => deleteQuestion(row),
                                },
                            ]"
                        />
                    </template>
                </AppTable>
            </section>
        </div>
    </TableLayout>
</template>