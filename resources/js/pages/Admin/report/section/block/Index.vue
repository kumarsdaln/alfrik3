
<script setup lang="ts">
import { Link, router } from '@inertiajs/vue3'
import { Eye, Pencil, Plus, Trash2 } from '@lucide/vue'

import AppPagination from '@/components/ui/AppPagination.vue'
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

import type { TableAction } from '@/components/ui/AppTableActions.vue'

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

/*
|--------------------------------------------------------------------------
| Table
|--------------------------------------------------------------------------
*/

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



/*
|--------------------------------------------------------------------------
| Actions
|--------------------------------------------------------------------------
*/

function deleteBlock(block: ReportContentBlock): void {
    const blockName = block.title || block.type.label

    if (!confirm(`Are you sure you want to delete "${blockName}"?`)) {
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

function getBlockActions(block: ReportContentBlock): TableAction[] {
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

/*
|--------------------------------------------------------------------------
| Create URL
|--------------------------------------------------------------------------
*/

const createBlockUrl = () =>
    create({
        report: props.report.id,
        section: props.section.id,
    }).url

const sectionListUrl = sectionIndex({
    report: props.report.id,
}).url
</script>

<template>
    <TableLayout>
        <!-- Header -->
        <template #header>
            <div class="flex items-center justify-between gap-4 py-5">
                <div class="flex items-center gap-4">
                    <BackButton :href="sectionListUrl" />

                    <Heading
                        title="Content Blocks"
                        :description="`${props.report.title} / ${props.section.title}`"
                    />
                </div>

                <Button as-child class="gap-2">
                    <Link :href="createBlockUrl()">
                        <Plus class="size-4" />
                        Add Block
                    </Link>
                </Button>
            </div>
        </template>

        <!-- Content -->
        <div class="py-6">
            <AppTable
                v-if="props.blocks.data.length"
                :columns="columns"
                :data="props.blocks.data"
            >
                <!-- Position -->
                <template #cell-position="{ value }">
                    <span class="text-sm text-muted-foreground">
                        {{ value }}
                    </span>
                </template>

                <!-- Type -->
                <template #cell-type="{ row }">
                    <Badge v-if="row.type">
                        {{ row.type.label }}
                    </Badge>

                    <span v-else>—</span>
                </template>

                <!-- Title -->
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

                <!-- Description -->
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

                <!-- Actions -->
                <template #cell-actions="{ row }">
                    <AppTableActions
                        :actions="getBlockActions(row)"
                    />
                </template>
            </AppTable>

            <!-- Empty State -->
            <div
                v-else
                class="border bg-background px-6 py-12 text-center"
            >
                <p class="font-medium">
                    No content blocks yet
                </p>

                <p class="mt-1 text-sm text-muted-foreground">
                    Add content blocks to build this report section.
                </p>

                <Button
                    as-child
                    class="mt-4"
                >
                    <Link :href="createBlockUrl()">
                        <Plus class="mr-2 size-4" />
                        Add Block
                    </Link>
                </Button>
            </div>
        </div>

        <!-- Footer -->
        <template #footer>
            <AppPagination :meta="props.blocks.meta" />
        </template>
    </TableLayout>
</template>
