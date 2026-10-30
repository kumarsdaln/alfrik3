<script setup lang="ts">
    import { Link, router } from '@inertiajs/vue3'
    import { Pencil, Plus, Trash2 } from '@lucide/vue'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import {
        create,
        edit,
        destroy,
    } from '@/routes/admin/survey/questions/options'
    import { SurveyQuestion, SurveyQuestionOption } from '@/types'

    interface Props {
        survey: {
            id: number
            title: string
        }

        question: {
            data: SurveyQuestion
        }

        options: {
            data: SurveyQuestionOption[]
        }
    }

    const props = defineProps<Props>()

    const editOption = (option: SurveyQuestionOption) => {
        router.get(
            edit({
                survey: props.survey.id,
                question: props.question.data.id,
                option: option.id,
            }).url
        )
    }

    const deleteOption = (option: SurveyQuestionOption) => {
        if (
            !confirm(
                `Are you sure you want to delete "${option.label}"?`
            )
        ) {
            return
        }

        router.delete(
            destroy({
                survey: props.survey.id,
                question: props.question.data.id,
                option: option.id,
            }).url,
            {
                preserveScroll: true,
            }
        )
    }
</script>

<template>
    <TableLayout>
        <div class="flex items-center justify-between gap-4 py-5">
            <div class="flex gap-4">
                <BackButton />

                <Heading title="Question Options"
                    description="Manage the answer options available for this survey question." />
            </div>

            <Button as-child class="gap-2">
                <Link :href="create({
                    survey: survey.id,
                    question: question.data.id
                })">
                    <Plus class="size-4" />
                    Create Option
                </Link>
            </Button>
        </div>

        <div class="space-y-6 py-6">
            <!-- Question -->
            <div>
                <div class="mb-2 flex items-center gap-2">
                    <Badge variant="outline" :style="{
                        borderColor: question.data.type.color,
                        color: question.data.type.color,
                    }">
                        {{ question.data.type.label }}
                    </Badge>
                </div>

                <h2 class="font-medium">
                    {{ question.data.question }}
                </h2>
            </div>


            <!-- Options -->
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
                    width: '110px',
                },
                {
                    key: 'actions',
                    label: '',
                    width: '64px',
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
                            danger: true,
                            onClick: () => deleteOption(row),
                        },
                    ]" />
                </template>
            </AppTable>
        </div>
    </TableLayout>
</template>