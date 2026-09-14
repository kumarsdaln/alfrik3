<script setup lang="ts">
    import { Link, router } from '@inertiajs/vue3'

    import Heading from '@/components/Heading.vue'
    import AppStats from '@/components/ui/AppStats.vue'
    import Date from '@/components/datadisplay/Date.vue'
    import AppPagination from '@/components/ui/AppPagination.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions, { TableAction } from '@/components/ui/AppTableActions.vue'
    import Badge from '@/components/ui/badge/Badge.vue'
    import Button from '@/components/ui/button/Button.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import FilterControl from '@/components/filters/FilterControl.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import { useFilters } from '@/composables/useFilters'

    import {
        create as tagCreate,
        edit as tagEdit,
        destroy as tagDestroy,
    } from '@/routes/admin/tags'

    import type { Pagination, Tag } from '@/types'
    import { Pencil, Plus, Trash2 } from '@lucide/vue'

    interface Props {
        tags: Pagination<Tag>
        filters: {
            search?: string
            status?: string
        }
        stats: {
            total: number
            active: number
            inactive: number
        }
    }

    const props = defineProps<Props>()

    const {
        filters,
        filterCount,
        applyFilters,
        clearFilters,
    } = useFilters(
        {
            search: props.filters.search ?? '',
            status: props.filters.status ?? '',
        },
        {
            url: window.location.pathname,
            searchKey: 'search',
            debounce: 500,
        },
    )

    const columns = [
        {
            key: 'id',
            label: 'ID',
            width: '80px',
        },
        {
            key: 'name',
            label: 'Name',
        },
        {
            key: 'slug',
            label: 'Slug',
        },
        {
            key: 'status',
            label: 'Status',
            width: '120px',
        },
        {
            key: 'created_at',
            label: 'Created',
            width: '160px',
        },
        {
            key: 'actions',
            label: '',
            width: '64px',
            align: 'right' as const,
        },
    ]

    function deleteTag(tag: Tag) {
        if (!confirm(`Are you sure you want to delete "${tag.name}"?`)) {
            return
        }

        router.delete(tagDestroy(tag.id).url)
    }

    function getActions(tag: Tag): TableAction[] {
        return [
            {
                label: 'Edit',
                icon: Pencil,
                href: tagEdit(tag.id).url,
            },
            {
                label: 'Delete',
                icon: Trash2,
                danger: true,
                onClick: () => deleteTag(tag),
            },
        ]
    }
</script>

<template>
    <TableLayout>
        <!-- Header -->
        <template #header>
            <div class="flex items-center justify-between gap-4 py-5">
                <Heading title="Tags" description="Manage and organize content tags." />
                <Button as-child class="gap-2">
                    <Link :href="tagCreate()">
                    <Plus class="size-4" />
                    Create Tag
                    </Link>
                </Button>
            </div>
        </template>

        <!-- Content -->
        <div class="py-6">
            <section class="mb-8">
                <AppStats :items="[
                    {
                        label: 'Total',
                        value: props.stats.total,
                    },
                    {
                        label: 'Active',
                        value: props.stats.active,
                    },
                    {
                        label: 'Inactive',
                        value: props.stats.inactive,
                    },
                ]" />
            </section>
            <FilterControl v-model:search="filters.search" search-placeholder="Search tags..."
                :filter-count="filterCount" @apply="applyFilters" @clear="clearFilters">
                <div class="space-y-6">
                    <AppSelect v-model="filters.status" name="status" label="Status" placeholder="All statuses"
                        :options="[
                            {
                                label: 'Active',
                                value: '1',
                            },
                            {
                                label: 'Inactive',
                                value: '0',
                            },
                        ]" />
                </div>
            </FilterControl>
        </div>
        <AppTable :columns="columns" :data="tags.data">
            <template #cell-id="{ value }">
                {{ value }}
            </template>

            <template #cell-name="{ value }">
                <span class="font-medium">
                    {{ value }}
                </span>
            </template>

            <template #cell-slug="{ value }">
                <span class="text-muted-foreground">
                    {{ value }}
                </span>
            </template>

            <template #cell-status="{ value }">
                <Badge :color="value?'green':'gray'">
                    {{ value?'Active':'Inactive' }}
                </Badge>
            </template>

            <template #cell-created_at="{ value }">
                <Date :value="value" />
            </template>

            <template #cell-actions="{ row }">
                <AppTableActions :actions="getActions(row)" />
            </template>
        </AppTable>

        <template #footer>
            <AppPagination :meta="tags.meta" />
        </template>
    </TableLayout>
</template>