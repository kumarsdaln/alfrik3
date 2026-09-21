<script setup lang="ts">
    import { router } from '@inertiajs/vue3'
    import { Pencil, Plus, Trash2 } from '@lucide/vue'

    import {
        AppFormControl,
        AppInput,
        AppSelect,
        AppTextarea,
        AppCheckbox,
    } from '@/components/form'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import TableLayout from '@/layouts/table/Layout.vue'

    import { useForm } from '@inertiajs/vue3'

    import type {
        FormOption,
        SurveyQuestion,
        SurveyQuestionType,
        SurveySection,
    } from '@/types'

    interface Props {
        survey: {
            id: number
            title: string
        }

        questions: {
            data: SurveyQuestion[]
        }

        sections: SurveySection[]

        typeOptions: FormOption[]
    }

    const props = defineProps<Props>()

    const form = useForm({
        section_id: '',
        question: '',
        description: '',
        type: '',
        category: '',
        required: '0',
        position: '',
    })

    const submit = () => {
        form.post(`/admin/survey/${props.survey.id}/questions`, {
            preserveScroll: true,
            onSuccess: () => {
                form.reset()
            },
        })
    }

    const editQuestion = (question: SurveyQuestion) => {
        router.get(
            `/admin/survey/${props.survey.id}/questions/${question.id}/edit`
        )
    }

    const deleteQuestion = (question: SurveyQuestion) => {
        if (!confirm('Are you sure you want to delete this question?')) {
            return
        }

        router.delete(
            `/admin/survey/${props.survey.id}/questions/${question.id}`,
            {
                preserveScroll: true,
            }
        )
    }
</script>

<template>
    <TableLayout>
        <template #header>
            <div class="flex items-center gap-4 py-5">
                <BackButton :href="`/admin/survey/${survey.id}`" />

                <Heading :title="`Questions — ${survey.title}`"
                    description="Create and manage the questions used in this survey." />
            </div>
        </template>

        <!-- Create Question -->
        <div class="mb-6 rounded-lg border border-border-light p-5 dark:border-border-dark">
            <div class="mb-5 flex items-center gap-2">
                <Plus class="size-4" />

                <h2 class="font-medium">
                    Add Question
                </h2>
            </div>

            <form class="space-y-4" @submit.prevent="submit">
                <AppFormControl label="Question" required :error="form.errors.question">
                    <AppInput v-model="form.question" placeholder="Enter survey question" />
                </AppFormControl>

                <AppFormControl label="Description" :error="form.errors.description">
                    <AppTextarea v-model="form.description" placeholder="Add additional instructions or context" />
                </AppFormControl>

                <div class="grid gap-4 md:grid-cols-2">
                    <AppFormControl label="Question Type" required :error="form.errors.type">
                        <AppSelect v-model="form.type" :options="typeOptions" placeholder="Select question type" />
                    </AppFormControl>

                    <AppFormControl label="Section" :error="form.errors.section_id">
                        <AppSelect v-model="form.section_id" :options="sections.map(section => ({
                            value: String(section.id),
                            label: section.title,
                        }))
                            " placeholder="Select section" />
                    </AppFormControl>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <AppFormControl label="Category" :error="form.errors.category">
                        <AppInput v-model="form.category" placeholder="Optional category" />
                    </AppFormControl>

                    <AppFormControl label="Position" :error="form.errors.position">
                        <AppInput v-model="form.position" type="number" min="0" placeholder="0" />
                    </AppFormControl>
                </div>

                <AppCheckbox v-model="form.required" name="required" true-value="1" false-value="0"
                    label="Required question" />

                <div class="flex justify-end border-t border-border-light pt-5 dark:border-border-dark">
                    <Button type="submit" :disabled="form.processing">
                        {{ form.processing ? 'Saving...' : 'Add Question' }}
                    </Button>
                </div>
            </form>
        </div>

        <!-- Questions -->
        <div class="overflow-hidden rounded-lg border border-border-light dark:border-border-dark">
            <AppTable :columns="[
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
                },
                {
                    key: 'section',
                    label: 'Section',
                },
                {
                    key: 'required',
                    label: 'Required',
                },
                {
                    key: 'actions',
                    label: 'Actions',
                    align: 'right',
                },
            ]" :data="questions.data">
                <template #cell-position="{ row }">
                    {{ row.position + 1 }}
                </template>

                <template #cell-question="{ row }">
                    <div class="max-w-xl">
                        <div class="font-medium">
                            {{ row.question }}
                        </div>

                        <div v-if="row.description" class="mt-1 line-clamp-2 text-sm text-muted-foreground">
                            {{ row.description }}
                        </div>
                    </div>
                </template>

                <template #cell-type="{ row }">
                    <Badge :style="{
                        borderColor: row.type.color,
                        color: row.type.color,
                    }" variant="outline">
                        {{ row.type.label }}
                    </Badge>
                </template>

                <template #cell-section="{ row }">
                    {{ row.section?.title ?? 'No section' }}
                </template>

                <template #cell-required="{ row }">
                    <Badge :variant="row.required ? 'default' : 'secondary'">
                        {{ row.required ? 'Yes' : 'No' }}
                    </Badge>
                </template>

                <template #cell-actions="{ row }">
                    <AppTableActions :actions="[
                        {
                            label: 'Edit',
                            icon: Pencil,
                            onClick: () => editQuestion(row),
                        },
                        {
                            label: 'Delete',
                            icon: Trash2,
                            onClick: () => deleteQuestion(row),
                            destructive: true,
                        },
                    ]" />
                </template>
            </AppTable>
        </div>
    </TableLayout>
</template>