<script setup lang="ts">
    import { Link, router } from '@inertiajs/vue3'
    import { Pencil, Plus, Trash2 } from '@lucide/vue'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'

    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import { create, edit, destroy } from '@/routes/admin/survey/sections'
    import { index as manageQuestions } from '@/routes/admin/survey/questions'

    import type { SurveySection } from '@/types'
    import Button from '@/components/ui/button/Button.vue'

    interface Props {
        survey: {
            id: number
            title: string
        }

        sections: {
            data: SurveySection[]
        }
    }

    defineProps<Props>()

    const deleteSection = (
        surveyId: number,
        sectionId: number,
    ) => {
        if (!confirm('Are you sure you want to delete this section?')) {
            return
        }

        router.delete(destroy({
            survey: surveyId,
            section: sectionId,
        }).url)
    }
</script>

<template>
    <TableLayout>
        <div class="flex items-center justify-between gap-4 py-5">
            <div class="flex gap-4">
                <BackButton />

                <Heading title="Survey Sections" :description="`Organize ${survey.title} into sections.`" />
            </div>

            <Button as-child class="gap-2">
                <Link :href="create(survey.id)">
                    <Plus class="size-4" />
                    Create Section
                </Link>
            </Button>
        </div>

        <!-- Sections -->
        <AppTable :data="sections.data" :columns="[
            {
                key: 'position',
                label: '#',
                width: '80px',
            },
            {
                key: 'title',
                label: 'Section',
            },
            {
                key: 'description',
                label: 'Description',
            },
            {
                key: 'actions',
                label: 'Actions',
                align: 'right',
            },
        ]">
            <template #cell-position="{ row }">
                {{ row.position + 1 }}
            </template>

            <template #cell-title="{ row }">
                <span class="font-medium">
                    {{ row.title }}
                </span>
            </template>

            <template #cell-description="{ row }">
                <span class="text-sm text-muted-foreground">
                    {{ row.description || '—' }}
                </span>
            </template>

            <template #cell-actions="{ row }">
                <AppTableActions :actions="[
                    {
                        label: 'Edit',
                        icon: Pencil,
                        href: edit({
                            survey: survey.id,
                            section: row.id,
                        }).url,
                    },
                    {
                        label: 'Manage Questions',
                        icon: Pencil,
                        href: manageQuestions(survey.id).url,
                    },
                    {
                        label: 'Delete',
                        icon: Trash2,
                        onClick: () =>
                            deleteSection(
                                survey.id,
                                row.id
                            ),
                    },
                ]" />
            </template>
        </AppTable>
    </TableLayout>
</template>