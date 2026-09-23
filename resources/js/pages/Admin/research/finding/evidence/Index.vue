<script setup lang="ts">
    import { router } from '@inertiajs/vue3'
    import { Pencil, Trash2 } from '@lucide/vue'

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

    import type {
        FormOption,
        ResearchEvidence,
    } from '@/types'

    import { Form } from '@inertiajs/vue3'

    interface Props {
        research: {
            id: number
            title: string
        }

        finding: {
            id: number
            title: string
        }

        evidence: {
            data: ResearchEvidence[]
        }

        evidenceTypeOptions: FormOption[]
    }

    defineProps<Props>()

    const deleteEvidence = (
        researchId: number,
        findingId: number,
        evidenceId: number,
    ) => {
        if (!confirm('Are you sure you want to delete this evidence?')) {
            return
        }

        router.delete(
            `/admin/research/${researchId}/findings/${findingId}/evidence/${evidenceId}`
        )
    }
</script>

<template>
    <TableLayout>

        <!-- Header -->

        <template #header>
            <div class="flex gap-4 py-5">
                <BackButton :href="`/admin/research/${research.id}/findings/${finding.id}`" />

                <Heading title="Research Evidence" :description="`Manage supporting evidence for ${finding.title}.`" />
            </div>
        </template>

        <!-- Add Evidence -->

        <div class="mb-6 rounded-lg border border-border-light bg-background p-6 dark:border-border-dark">
            <h2 class="font-semibold">
                Add Evidence
            </h2>

            <p class="mt-1 text-sm text-muted-foreground">
                Add a source, survey, dataset, question, or other evidence
                supporting this finding.
            </p>

            <Form v-slot="{ errors, processing }"
                :action="`/admin/research/${research.id}/findings/${finding.id}/evidence`" method="post"
                class="mt-6 space-y-4">
                <AppFormControl label="Evidence Type" required :error="errors.type">
                    <AppSelect name="type" placeholder="Select evidence type" :options="evidenceTypeOptions" />
                </AppFormControl>

                <AppFormControl label="Reference ID" :error="errors.reference_id">
                    <AppInput name="reference_id" type="number" min="1" placeholder="Enter referenced record ID" />
                </AppFormControl>

                <AppFormControl label="Title" :error="errors.title">
                    <AppInput name="title" placeholder="Enter evidence title" />
                </AppFormControl>

                <AppFormControl label="Description" :error="errors.description">
                    <AppTextarea name="description" placeholder="Describe how this evidence supports the finding" />
                </AppFormControl>

                <AppFormControl label="Citation" :error="errors.citation">
                    <AppTextarea name="citation" placeholder="Enter citation or reference" />
                </AppFormControl>

                <AppFormControl label="Position" :error="errors.position">
                    <AppInput name="position" type="number" min="0" value="0" />
                </AppFormControl>

                <div class="flex justify-end">
                    <Button type="submit" :disabled="processing">
                        {{ processing ? 'Processing...' : 'Add Evidence' }}
                    </Button>
                </div>
            </Form>
        </div>

        <!-- Evidence Table -->

        <AppTable :data="evidence.data" :columns="[
            {
                key: 'position',
                label: '#',
                width: '80px',
            },
            {
                key: 'type',
                label: 'Type',
            },
            {
                key: 'title',
                label: 'Title',
            },
            {
                key: 'reference_id',
                label: 'Reference',
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

            <template #cell-type="{ row }">
                <Badge>
                    {{ row.type.label }}
                </Badge>
            </template>

            <template #cell-title="{ row }">
                <div class="max-w-md">
                    <div class="font-medium">
                        {{ row.title || 'Untitled evidence' }}
                    </div>

                    <div v-if="row.description" class="mt-1 truncate text-sm text-muted-foreground">
                        {{ row.description }}
                    </div>
                </div>
            </template>

            <template #cell-reference_id="{ row }">
                {{ row.reference_id ?? '—' }}
            </template>

            <template #cell-actions="{ row }">
                <AppTableActions :actions="[
                    {
                        label: 'Edit',
                        icon: Pencil,
                        href: `/admin/research/${research.id}/findings/${finding.id}/evidence/${row.id}/edit`,
                    },
                    {
                        label: 'Delete',
                        icon: Trash2,
                        onClick: () =>
                            deleteEvidence(
                                research.id,
                                finding.id,
                                row.id
                            ),
                    },
                ]" />
            </template>
        </AppTable>
    </TableLayout>
</template>