<script setup lang="ts">
    import { computed } from 'vue'
    import { Link, router } from '@inertiajs/vue3'
    import { Eye, Pencil, Plus, Trash2 } from '@lucide/vue'

    import Date from '@/components/datadisplay/Date.vue'
    import AppPagination from '@/components/ui/AppPagination.vue'
    import AppStats from '@/components/ui/AppStats.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import Button from '@/components/ui/button/Button.vue'
    import FilterControl from '@/components/filters/FilterControl.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import { useFilters } from '@/composables/useFilters'
    import {
        create,
        edit,
        show,
    } from '@/routes/admin/permissions'

    import type { Pagination, Permission } from '@/types'
    import type { TableAction } from '@/components/ui/AppTableActions.vue'

    interface Props {
        permissions: Pagination<Permission>
        filters: {
            search?: string
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
            label: 'Total Permissions',
            value: props.permissions.meta.total,
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
            label: 'Permission',
        },
        {
            key: 'slug',
            label: 'Slug',
            width: '240px',
        },
        {
            key: 'roles_count',
            label: 'Roles',
            width: '120px',
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

    function deletePermission(permission: Permission) {
        if (
            !confirm(
                `Are you sure you want to delete "${permission.name}"?`,
            )
        ) {
            return
        }

        router.delete(
            `/admin/permissions/${permission.id}`,
            {
                preserveScroll: true,
            },
        )
    }

    function getPermissionActions(
        permission: Permission,
    ): TableAction[] {
        return [
            {
                label: 'View',
                icon: Eye,
                href: show(permission.id).url,
            },
            {
                label: 'Edit',
                icon: Pencil,
                href: edit(permission.id).url,
            },
            {
                label: 'Delete',
                icon: Trash2,
                danger: true,
                onClick: () => deletePermission(permission),
            },
        ]
    }
</script>

<template>
    <TableLayout>
        <!-- Header -->
        <template #header>
            <div class="flex items-center justify-between gap-4 py-5">
                <div class="flex gap-4">
                    <Heading title="Permissions" description="Manage system permissions." />
                </div>

                <Button as-child class="gap-2">
                    <Link :href="create()">
                        <Plus class="size-4" />
                        Create Permission
                    </Link>
                </Button>
            </div>
        </template>

        <!-- Content -->

        <div class="py-6">
            <!-- Stats -->

            <section class="mb-8">
                <AppStats :items="statItems" />
            </section>

            <!-- Filters -->

            <section class="mb-8">
                <FilterControl v-model:search="filters.search" search-placeholder="Search permissions..."
                    :filter-count="filterCount" @apply="applyFilters" @clear="clearFilters" />
            </section>

            <!-- Table -->

            <AppTable :columns="columns" :data="props.permissions.data">
                <!-- Slug -->

                <template #cell-slug="{ value }">
                    <code class="text-sm">
                        {{ value }}
                    </code>
                </template>

                <!-- Roles -->

                <template #cell-roles_count="{ value }">
                    {{ value ?? 0 }}
                </template>

                <!-- Created At -->

                <template #cell-created_at="{ value }">
                    <Date :value="value" />
                </template>

                <!-- Actions -->

                <template #cell-actions="{ row }">
                    <AppTableActions :actions="getPermissionActions(row)
                        " />
                </template>
            </AppTable>
        </div>

        <!-- Footer -->

        <template #footer>
            <AppPagination :meta="props.permissions.meta" />
        </template>
    </TableLayout>
</template>