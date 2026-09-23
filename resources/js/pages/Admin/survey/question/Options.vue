<script setup lang="ts">
    import { router, useForm } from '@inertiajs/vue3'
    import { Pencil, Plus, Trash2 } from '@lucide/vue'

    import {
        AppCheckbox,
        AppFormControl,
        AppInput,
    } from '@/components/form'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import type { SurveyQuestionOption } from '@/types'

    interface Props {
        survey: {
            id: number
            title: string
        }

        question: {
            id: number
            survey_id: number
            question: string
            type: {
                value: string
                label: string
                color: string
            }
        }

        options: {
            data: SurveyQuestionOption[]
        }
    }

    const props = defineProps<Props>()

    const form = useForm({
        label: '',
        value: '',
        position: '',
        is_other: '0',
    })

    const submit = () => {
        form.post(
            `/admin/survey/questions/${props.question.id}/options`,
            {
                preserveScroll: true,

                onSuccess: () => {
                    form.reset()
                },
            }
        )
    }

    const editOption = (option: SurveyQuestionOption) => {
        router.get(
            `/admin/survey/questions/${props.question.id}/options/${option.id}/edit`
        )
    }

    const deleteOption = (option: SurveyQuestionOption) => {
        if (!confirm('Are you sure you want to delete this option?')) {
            return
        }

        router.delete(
            `/admin/survey/questions/${props.question.id}/options/${option.id}`,
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
                <BackButton :href="`/admin/survey/${survey.id}/questions`" />

                <Heading title="Question Options"
                    description="Manage the answer options available for this survey question." />
            </div>
        </template>

        <!-- Question -->
        <div class="mb-6 rounded-lg border border-border-light p-5 dark:border-border-dark">
            <div class="mb-2 text-sm text-muted-foreground">
                {{ question.type.label }}
            </div>

            <h2 class="font-medium">
                {{ question.question }}
            </h2>
        </div>

        <!-- Add Option -->
        <div class="mb-6 rounded-lg border border-border-light p-5 dark:border-border-dark">
            <div class="mb-5 flex items-center gap-2">
                <Plus class="size-4" />

                <h2 class="font-medium">
                    Add Option
                </h2>
            </div>

            <form class="space-y-4" @submit.prevent="submit">
                <div class="grid gap-4 md:grid-cols-2">
                    <AppFormControl label="Label" required :error="form.errors.label">
                        <AppInput v-model="form.label" placeholder="e.g. Very Satisfied" />
                    </AppFormControl>

                    <AppFormControl label="Value" required :error="form.errors.value">
                        <AppInput v-model="form.value" placeholder="e.g. very_satisfied" />
                    </AppFormControl>
                </div>

                <div class="grid gap-4 md:grid-cols-2">
                    <AppFormControl label="Position" :error="form.errors.position">
                        <AppInput v-model="form.position" type="number" min="0" placeholder="0" />
                    </AppFormControl>

                    <div class="flex items-end pb-2">
                        <AppCheckbox v-model="form.is_other" name="is_other" true-value="1" false-value="0"
                            label="Allow respondent to enter another answer" />
                    </div>
                </div>

                <div class="flex justify-end border-t border-border-light pt-5 dark:border-border-dark">
                    <Button type="submit" :disabled="form.processing">
                        {{
                            form.processing
                                ? 'Saving...'
                                : 'Add Option'
                        }}
                    </Button>
                </div>
            </form>
        </div>

        <!-- Options -->
        <div class="overflow-hidden rounded-lg border border-border-light dark:border-border-dark">
            <AppTable :columns="[
                {
                    key: 'position',
                    label: '#',
                    width: '70px',
                },
                {
                    key: 'label',
                    label: 'Label',
                },
                {
                    key: 'value',
                    label: 'Value',
                },
                {
                    key: 'is_other',
                    label: 'Other',
                },
                {
                    key: 'actions',
                    label: 'Actions',
                    align: 'right',
                },
            ]" :data="options.data">
                <template #cell-position="{ row }">
                    {{ row.position + 1 }}
                </template>

                <template #cell-label="{ row }">
                    <span class="font-medium">
                        {{ row.label }}
                    </span>
                </template>

                <template #cell-value="{ row }">
                    <code class="text-sm">
                        {{ row.value }}
                    </code>
                </template>

                <template #cell-is_other="{ row }">
                    <Badge :variant="row.is_other
                            ? 'default'
                            : 'secondary'
                        ">
                        {{ row.is_other ? 'Yes' : 'No' }}
                    </Badge>
                </template>

                <template #cell-actions="{ row }">
                    <AppTableActions :actions="[
                        {
                            label: 'Edit',
                            icon: Pencil,
                            onClick: () => editOption(row),
                        },
                        {
                            label: 'Delete',
                            icon: Trash2,
                            onClick: () => deleteOption(row),
                            destructive: true,
                        },
                    ]" />
                </template>
            </AppTable>
        </div>
    </TableLayout>
</template>