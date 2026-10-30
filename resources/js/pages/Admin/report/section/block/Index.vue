<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import { Eye, Pencil, Plus, Trash2 } from '@lucide/vue'

import AppTable from '@/components/ui/AppTable.vue'
import AppTableActions from '@/components/ui/AppTableActions.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import Button from '@/components/ui/button/Button.vue'
import Heading from '@/components/Heading.vue'
import BackButton from '@/components/ui/BackButton.vue'
import TableLayout from '@/layouts/table/TableLayout.vue'

import {
    create,
    show,
    edit,
    destroy,
} from '@/routes/admin/report/sections/blocks'

import { index as sectionIndex } from '@/routes/admin/report/sections'

import type {
    Pagination,
    ReportContentBlock,
} from '@/types'
import AppPagination from '@/components/ui/AppPagination.vue'

interface Props {
    report: {
        id: number
        title: string
    }

    section: {
        id: number
        title: string
    }

    blocks: Pagination<ReportContentBlock>
}

const props = defineProps<Props>()

const columns = [
    {
        key: 'position',
        label: '#',
        width: '80px',
        align: 'center' as const,
    },
    {
        key: 'type',
        label: 'Type',
        width: '160px',
    },
    {
        key: 'title',
        label: 'Title',
    },
    {
        key: 'description',
        label: 'Description',
    },
    {
        key: 'actions',
        label: '',
        width: '64px',
        align: 'right' as const,
    },
]

const typeBadgeVariant = (
    type: string,
) => {
    switch (type) {
        case 'finding':
        case 'recommendation':
            return 'success'

        case 'statistic':
        case 'chart':
            return 'secondary'

        case 'quote':
            return 'outline'

        default:
            return 'secondary'
    }
}

function deleteBlock(block: ReportContentBlock) {
    if (
        !confirm(
            `Are you sure you want to delete "${block.title || block.type.label}"?`,
        )
    ) {
        return
    }

    router.delete(
        destroy({
            report: props.report.id,
            section: props.section.id,
            block: block.id,
        }).url,
        {
            preserveScroll: true,
        },
    )
}

function getBlockActions(block: ReportContentBlock) {
    return [
        {
            label: 'View',
            icon: Eye,
            href: show({
                report: props.report.id,
                section: props.section.id,
                block: block.id,
            }).url,
        },
        {
            label: 'Edit',
            icon: Pencil,
            href: edit({
                report: props.report.id,
                section: props.section.id,
                block: block.id,
            }).url,
        },
        {
            label: 'Delete',
            icon: Trash2,
            danger: true,
            onClick: () => deleteBlock(block),
        },
    ]
}
</script>

<template>
    <TableLayout>
        <template #header>
            <div
                class="flex items-center justify-between gap-4 py-5"
            >
                <div class="flex gap-4">
                    <BackButton
                        :href="
                            sectionIndex({
                                report: props.report.id,
                            }).url
                        "
                    />

                    <Heading
                        title="Content Blocks"
                        :description="`${props.report.title} / ${props.section.title}`"
                    />
                </div>

                <Button as-child class="gap-2">
                    <Link
                        :href="
                            create({
                                report: props.report.id,
                                section: props.section.id,
                            }).url
                        "
                    >
                        <Plus class="size-4" />
                        Add Block
                    </Link>
                </Button>
            </div>
        </template>

        <div class="py-6">
            <AppTable
                v-if="props.blocks.data.length"
                :columns="columns"
                :data="props.blocks.data"
            >
                <template #cell-position="{ row }">
                    <span class="text-sm text-muted-foreground">
                        {{ row.position + 1 }}
                    </span>
                </template>

                <template #cell-type="{ row }">
                    <Badge :variant="typeBadgeVariant(row.type.value)">
                        {{ row.type.label }}
                    </Badge>
                </template>

                <template #cell-title="{ row }">
                    <div class="min-w-0">
                        <Link
                            :href="
                                show({
                                    report: props.report.id,
                                    section: props.section.id,
                                    block: row.id,
                                }).url
                            "
                            class="block truncate font-medium hover:text-primary"
                        >
                            {{ row.title || 'Untitled block' }}
                        </Link>
                    </div>
                </template>

                <template #cell-description="{ row }">
                    <span
                        v-if="row.description"
                        class="line-clamp-1 text-sm text-muted-foreground"
                    >
                        {{ row.description }}
                    </span>

                    <span
                        v-else
                        class="text-sm text-muted-foreground"
                    >
                        —
                    </span>
                </template>

                <template #cell-actions="{ row }">
                    <AppTableActions
                        :actions="getBlockActions(row)"
                    />
                </template>
            </AppTable>

            <div
                v-else
                class="rounded-lg border bg-background px-6 py-12 text-center"
            >
                <p class="font-medium">
                    No content blocks yet
                </p>

                <p class="mt-1 text-sm text-muted-foreground">
                    Add content blocks to build the section.
                </p>

                <Button
                    as-child
                    class="mt-4"
                >
                    <Link
                        :href="
                            create({
                                report: props.report.id,
                                section: props.section.id,
                            }).url
                        "
                    >
                        <Plus class="mr-2 size-4" />
                        Add Block
                    </Link>
                </Button>
            </div>
        </div>

        <template #footer>
            <AppPagination
                :meta="props.blocks.meta"
            />
        </template>
    </TableLayout>
</template>