<script setup lang="ts">
    import { computed } from 'vue'
    import { Link, router } from '@inertiajs/vue3'
    import { Pencil, Plus, Trash2 } from '@lucide/vue'

    import Date from '@/components/datadisplay/Date.vue'
    import AppPagination from '@/components/ui/AppPagination.vue'
    import AppStats from '@/components/ui/AppStats.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import AppText from '@/components/ui/AppText.vue'
    import Button from '@/components/ui/button/Button.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import FilterControl from '@/components/filters/FilterControl.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import { useFilters } from '@/composables/useFilters'

    import {
        create as categoryCreate,
        edit as categoryEdit,
        destroy as categoryDestroy,
    } from '@/routes/admin/categories'

    import type { Category, Pagination } from '@/types'
    import type { TableAction } from '@/components/ui/AppTableActions.vue'
    import Heading from '@/components/Heading.vue'

    interface Props {
        categories: Pagination<Category>

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

    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Statistics
    |--------------------------------------------------------------------------
    */

    const statItems = computed(() => [
        {
            label: 'Total Categories',
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
    ])

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

    const columns = [
        {
            key: 'id',
            label: '#',
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
            key: 'parent',
            label: 'Parent',
            width: '200px',
        },
        {
            key: 'sort_order',
            label: 'Order',
            width: '100px',
        },
        {
            key: 'status',
            label: 'Status',
            width: '140px',
        },
        {
            key: 'created_at',
            label: 'Created At',
            width: '180px',
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

    function deleteCategory(category: Category) {
        if (
            !confirm(
                `Are you sure you want to delete "${category.name}"?`
            )
        ) {
            return
        }

        router.delete(
            categoryDestroy(category.id).url,
            {
                preserveScroll: true,
            }
        )
    }

    function getActions(category: Category): TableAction[] {
        return [
            {
                label: 'Edit',
                icon: Pencil,
                href: categoryEdit(category.id).url,
            },
            {
                label: 'Delete',
                icon: Trash2,
                danger: true,
                onClick: () => deleteCategory(category),
            },
        ]
    }
</script>

<template>
    <TableLayout>
        <!-- Header -->
        <template #header>
            <div class="
                    flex items-center justify-between
                    gap-4
                    px-6 py-5
                ">
                <Heading title="Categories" description="Manage and organize content categories." />
                <Button as-child class="gap-2">
                    <Link :href="categoryCreate()">
                        <Plus class="size-4" />
                        Create Category
                    </Link>
                </Button>
            </div>
        </template>

        <!-- Content -->
        <div class="px-6 py-6">
            <!-- Stats -->
            <section class="mb-8">
                <AppStats :items="statItems" />
            </section>

            <!-- Filters -->
            <section class="mb-8">
                <FilterControl v-model:search="filters.search" search-placeholder="Search categories..."
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
            </section>

            <!-- Table -->
            <AppTable :columns="columns" :data="categories.data">
                <!-- Name -->
                <template #cell-name="{ row }">
                    <div class="min-w-0">
                        <div class="truncate font-medium">
                            {{ row.name }}
                        </div>

                        <div v-if="row.description" class="mt-1 line-clamp-1 text-xs text-muted-foreground">
                            {{ row.description }}
                        </div>
                    </div>
                </template>

                <!-- Slug -->
                <template #cell-slug="{ value }">
                    <AppText>
                        {{ value }}
                    </AppText>
                </template>

                <!-- Parent -->
                <template #cell-parent="{ value }">
                    <span class="text-sm">
                        {{ value?.name ?? '—' }}
                    </span>
                </template>

                <!-- Order -->
                <template #cell-sort_order="{ value }">
                    <span class="text-sm">
                        {{ value }}
                    </span>
                </template>

                <!-- Status -->
                <template #cell-status="{ value }">
                    <Badge :color="value ? 'green' : 'gray'">
                        {{ value ? 'Active' : 'Inactive' }}
                    </Badge>
                </template>

                <!-- Created -->
                <template #cell-created_at="{ value }">
                    <Date :value="value" />
                </template>

                <!-- Actions -->
                <template #cell-actions="{ row }">
                    <AppTableActions :actions="getActions(row)" />
                </template>
            </AppTable>
        </div>

        <!-- Footer -->
        <template #footer>
            <AppPagination :meta="props.categories.meta" />
        </template>
    </TableLayout>
</template>