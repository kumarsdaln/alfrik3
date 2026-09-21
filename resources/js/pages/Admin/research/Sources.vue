<script setup lang="ts">
    import { Link, router } from '@inertiajs/vue3'
    import { Pencil, Plus, Trash2 } from '@lucide/vue'

    import Date from '@/components/datadisplay/Date.vue'
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

    interface ResearchSource {
        id: number
        title: string
        source_type: {
            value: string
            label: string
        }
        author: string | null
        publisher: string | null
        url: string | null
        published_at: string | null
        citation: string | null
        description: string | null
        position: number
    }

    interface Props {
        research: {
            id: number
            title: string
        }
        sources: ResearchSource[]
        sourceTypeOptions: FormOption[]
    }

    const props = defineProps<Props>()

    const columns = [
        {
            key: 'title',
            label: 'Source',
        },
        {
            key: 'source_type',
            label: 'Type',
            width: '180px',
        },
        {
            key: 'author',
            label: 'Author',
            width: '180px',
        },
        {
            key: 'publisher',
            label: 'Publisher',
            width: '180px',
        },
        {
            key: 'published_at',
            label: 'Published',
            width: '160px',
        },
        {
            key: 'actions',
            label: '',
            width: '64px',
            align: 'right' as const,
        },
    ]

    function deleteSource(source: ResearchSource) {
        if (!confirm(`Are you sure you want to delete "${source.title}"?`)) {
            return
        }

        router.delete(
            `/admin/research/${props.research.id}/sources/${source.id}`,
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

                <Heading title="Research Sources"
                    :description="`Manage sources and references for ${research.title}.`" />
            </div>
        </template>

        <div class="space-y-8 py-6">
            <!-- Add Source -->
            <section class="space-y-4">
                <div>
                    <h2 class="text-base font-semibold">
                        Add Source
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Add a source or reference used in this research.
                    </p>
                </div>

                <Form v-slot="{ errors, processing }" :action="`/admin/research/${research.id}/sources`" method="post"
                    class="space-y-4">
                    <AppFormControl label="Title" required :error="errors.title">
                        <AppInput name="title" placeholder="Enter source title" />
                    </AppFormControl>

                    <AppFormControl label="Source Type" required :error="errors.source_type">
                        <AppSelect name="source_type" placeholder="Select source type" :options="sourceTypeOptions" />
                    </AppFormControl>

                    <div class="grid gap-4 md:grid-cols-2">
                        <AppFormControl label="Author" :error="errors.author">
                            <AppInput name="author" placeholder="Enter author" />
                        </AppFormControl>

                        <AppFormControl label="Publisher" :error="errors.publisher">
                            <AppInput name="publisher" placeholder="Enter publisher" />
                        </AppFormControl>
                    </div>

                    <AppFormControl label="URL" :error="errors.url">
                        <AppInput name="url" placeholder="https://example.com/source" />
                    </AppFormControl>

                    <AppFormControl label="Published At" :error="errors.published_at">
                        <AppInput name="published_at" type="date" />
                    </AppFormControl>

                    <AppFormControl label="Citation" :error="errors.citation">
                        <AppTextarea name="citation" placeholder="Enter citation" />
                    </AppFormControl>

                    <AppFormControl label="Description" :error="errors.description">
                        <AppTextarea name="description" placeholder="Describe this source..." />
                    </AppFormControl>

                    <div class="flex justify-end">
                        <Button type="submit" :disabled="processing" class="gap-2">
                            <Plus class="size-4" />

                            {{ processing ? 'Processing...' : 'Add Source' }}
                        </Button>
                    </div>
                </Form>
            </section>

            <!-- Sources -->
            <section class="space-y-4 border-t border-border-light pt-8 dark:border-border-dark">
                <div>
                    <h2 class="text-base font-semibold">
                        Sources
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Sources currently attached to this research.
                    </p>
                </div>

                <AppTable :columns="columns" :data="sources">
                    <template #cell-title="{ row }">
                        <div class="min-w-0">
                            <a v-if="row.url" :href="row.url" target="_blank" rel="noopener noreferrer"
                                class="font-medium hover:underline">
                                {{ row.title }}
                            </a>

                            <span v-else class="font-medium">
                                {{ row.title }}
                            </span>
                        </div>
                    </template>

                    <template #cell-source_type="{ value }">
                        <Badge variant="outline">
                            {{ value.label }}
                        </Badge>
                    </template>

                    <template #cell-author="{ value }">
                        {{ value ?? '—' }}
                    </template>

                    <template #cell-publisher="{ value }">
                        {{ value ?? '—' }}
                    </template>

                    <template #cell-published_at="{ value }">
                        <Date :value="value" />
                    </template>

                    <template #cell-actions="{ row }">
                        <AppTableActions :actions="[
                            {
                                label: 'Edit',
                                icon: Pencil,
                                href: `/admin/research/${research.id}/sources/${row.id}/edit`,
                            },
                            {
                                label: 'Delete',
                                icon: Trash2,
                                danger: true,
                                onClick: () => deleteSource(row),
                            },
                        ]" />
                    </template>
                </AppTable>
            </section>
        </div>
    </TableLayout>
</template>