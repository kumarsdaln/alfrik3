<script setup lang="ts">
    import { router } from '@inertiajs/vue3'
    import { Pencil, Trash2 } from '@lucide/vue'

    import {
        AppFormControl,
        AppInput,
        AppTextarea,
    } from '@/components/form'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'

    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import TableLayout from '@/layouts/table/Layout.vue'

    import type { SurveySection } from '@/types'

    import { Form } from '@inertiajs/vue3'

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

        router.delete(
            `/admin/survey/${surveyId}/sections/${sectionId}`
        )
    }
</script>

<template>
    <TableLayout>

        <template #header>
            <div class="flex gap-4 py-5">
                <BackButton :href="`/admin/survey/${survey.id}`" />

                <Heading title="Survey Sections" :description="`Organize ${survey.title} into sections.`" />
            </div>
        </template>

        <!-- Add Section -->

        <div class="mb-6 rounded-lg border border-border-light bg-background p-6 dark:border-border-dark">
            <h2 class="font-semibold">
                Add Section
            </h2>

            <p class="mt-1 text-sm text-muted-foreground">
                Create a section to organize survey questions.
            </p>

            <Form v-slot="{ errors, processing }" :action="`/admin/survey/${survey.id}/sections`" method="post"
                class="mt-6 space-y-4">
                <AppFormControl label="Title" required :error="errors.title">
                    <AppInput name="title" placeholder="Enter section title" />
                </AppFormControl>

                <AppFormControl label="Description" :error="errors.description">
                    <AppTextarea name="description" placeholder="Enter section description" />
                </AppFormControl>

                <AppFormControl label="Position" :error="errors.position">
                    <AppInput name="position" type="number" min="0" value="0" />
                </AppFormControl>

                <div class="flex justify-end">
                    <Button type="submit" :disabled="processing">
                        {{ processing
                            ? 'Processing...'
                            : 'Add Section'
                        }}
                    </Button>
                </div>
            </Form>
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
                        href: `/admin/survey/${survey.id}/sections/${row.id}/edit`,
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