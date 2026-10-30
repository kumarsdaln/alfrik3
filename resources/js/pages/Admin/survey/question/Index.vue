<script setup lang="ts">
    import { Link, router } from '@inertiajs/vue3'
    import { Pencil, Plus, Trash2 } from '@lucide/vue'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import {
        create,
        edit,
        destroy,
    } from '@/routes/admin/survey/questions'

    import {
        index as manageOptions
    } from '@/routes/admin/survey/questions/options'

    import type {
        FormOption,
        SurveyQuestion,
        SurveySection,
    } from '@/types'
import Button from '@/components/ui/button/Button.vue'

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

    const editQuestion = (question: SurveyQuestion) => {
        router.get(
            edit({
                survey: props.survey.id,
                question: question.id,
            }).url
        )
    }

    const deleteQuestion = (question: SurveyQuestion) => {
        if (
            !confirm(
                `Are you sure you want to delete "${question.question}"?`
            )
        ) {
            return
        }

        router.delete(
            destroy({
                survey: props.survey.id,
                question: question.id,
            }).url,
            {
                preserveScroll: true,
            }
        )
    }

    const getSectionTitle = (question: SurveyQuestion) => {
        return question.section?.title ?? 'No section'
    }
</script>

<template>
    <TableLayout>
        <div class="flex items-center justify-between gap-4 py-5">
            <div class="flex gap-4">
                <BackButton />

                <Heading :title="`Questions — ${survey.title}`"
                    description="Create and manage the questions used in this survey." />
            </div>

            <Button as-child class="gap-2">
                <Link :href="create(survey.id)">
                <Plus class="size-4" />
                Create Question
                </Link>
            </Button>
        </div>

        <!-- Questions Table -->
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
                width: '160px',
            },
            {
                key: 'section',
                label: 'Section',
                width: '200px',
            },
            {
                key: 'required',
                label: 'Required',
                width: '110px',
            },
            {
                key: 'actions',
                label: '',
                width: '64px',
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
                <Badge variant="outline" :style="{
                    borderColor: row.type.color,
                    color: row.type.color,
                }">
                    {{ row.type.label }}
                </Badge>
            </template>

            <template #cell-section="{ row }">
                {{ getSectionTitle(row) }}
            </template>

            <template #cell-required="{ row }">
                <Badge :variant="row.required
                    ? 'default'
                    : 'secondary'
                    ">
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
                        label: 'Options',
                        icon: Pencil,
                        href: manageOptions({ survey: survey.id, question: row.id }).url,
                    },
                    {
                        label: 'Delete',
                        icon: Trash2,
                        danger: true,
                        onClick: () => deleteQuestion(row),
                    },
                ]" />
            </template>
        </AppTable>
    </TableLayout>
</template>