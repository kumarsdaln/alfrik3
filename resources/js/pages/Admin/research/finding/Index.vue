<script setup lang="ts">
    import { router } from '@inertiajs/vue3'
    import { Eye, Pencil, Plus, Trash2 } from '@lucide/vue'

    import {
        AppFormControl,
        AppInput,
        AppTextarea,
        AppSelect,
    } from '@/components/form'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Badge from '@/components/ui/badge/Badge.vue'
    import Button from '@/components/ui/button/Button.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import { Form } from '@inertiajs/vue3'

    import type { FormOption } from '@/types'

    interface ResearchFinding {
        id: number
        title: string
        summary: string | null
        description: string | null
        type: {
            value: string
            label: string
        }
        confidence: number | null
        position: number
    }

    interface Props {
        research: {
            id: number
            title: string
        }

        findings: ResearchFinding[]

        findingTypeOptions: FormOption[]
    }

    const props = defineProps<Props>()

    const columns = [
        {
            key: 'position',
            label: '#',
            width: '70px',
        },
        {
            key: 'title',
            label: 'Finding',
        },
        {
            key: 'type',
            label: 'Type',
            width: '180px',
        },
        {
            key: 'confidence',
            label: 'Confidence',
            width: '140px',
        },
        {
            key: 'actions',
            label: '',
            width: '64px',
            align: 'right' as const,
        },
    ]

    function deleteFinding(finding: ResearchFinding) {
        if (!confirm(`Are you sure you want to delete "${finding.title}"?`)) {
            return
        }

        router.delete(
            `/admin/research/${props.research.id}/findings/${finding.id}`,
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

                <Heading title="Research Findings"
                    :description="`Manage findings and insights for ${research.title}.`" />
            </div>
        </template>

        <div class="space-y-8 py-6">
            <!-- Add Finding -->
            <section class="space-y-4">
                <div>
                    <h2 class="text-base font-semibold">
                        Add Finding
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Record a result, insight, observation, conclusion, or recommendation.
                    </p>
                </div>

                <Form v-slot="{ errors, processing }" :action="`/admin/research/${research.id}/findings`" method="post"
                    class="space-y-4">
                    <AppFormControl label="Title" required :error="errors.title">
                        <AppInput name="title" placeholder="Enter finding title" />
                    </AppFormControl>

                    <AppFormControl label="Summary" :error="errors.summary">
                        <AppTextarea name="summary" placeholder="Summarize the finding..." />
                    </AppFormControl>

                    <AppFormControl label="Description" :error="errors.description">
                        <AppTextarea name="description" placeholder="Describe the finding in detail..." />
                    </AppFormControl>

                    <AppFormControl label="Finding Type" required :error="errors.type">
                        <AppSelect name="type" placeholder="Select finding type" :options="findingTypeOptions" />
                    </AppFormControl>

                    <AppFormControl label="Confidence" :error="errors.confidence">
                        <AppInput name="confidence" type="number" min="0" max="100" step="0.01" placeholder="0 - 100" />
                    </AppFormControl>

                    <div class="flex justify-end">
                        <Button type="submit" :disabled="processing" class="gap-2">
                            <Plus class="size-4" />

                            {{ processing ? 'Processing...' : 'Add Finding' }}
                        </Button>
                    </div>
                </Form>
            </section>

            <!-- Findings -->
            <section class="space-y-4 border-t border-border-light pt-8 dark:border-border-dark">
                <div>
                    <h2 class="text-base font-semibold">
                        Findings
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Findings currently defined for this research.
                    </p>
                </div>

                <AppTable :columns="columns" :data="findings">
                    <template #cell-position="{ value }">
                        {{ value + 1 }}
                    </template>

                    <template #cell-title="{ row }">
                        <div class="min-w-0">
                            <div class="font-medium">
                                {{ row.title }}
                            </div>

                            <p v-if="row.summary" class="mt-0.5 truncate text-sm text-muted-foreground">
                                {{ row.summary }}
                            </p>
                        </div>
                    </template>

                    <template #cell-type="{ value }">
                        <Badge variant="outline">
                            {{ value.label }}
                        </Badge>
                    </template>

                    <template #cell-confidence="{ value }">
                        <span v-if="value !== null">
                            {{ value }}%
                        </span>

                        <span v-else class="text-sm text-muted-foreground">
                            —
                        </span>
                    </template>

                    <template #cell-actions="{ row }">
                        <AppTableActions :actions="[
                            {
                                label: 'View',
                                icon: Eye,
                                href: `/admin/research/${research.id}/findings/${row.id}`,
                            },
                            {
                                label: 'Edit',
                                icon: Pencil,
                                href: `/admin/research/${research.id}/findings/${row.id}/edit`,
                            },
                            {
                                label: 'Delete',
                                icon: Trash2,
                                danger: true,
                                onClick: () => deleteFinding(row),
                            },
                        ]" />
                    </template>
                </AppTable>
            </section>
        </div>
    </TableLayout>
</template>