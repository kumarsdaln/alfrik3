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

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'

    import { useFilters } from '@/composables/useFilters'
    import {
        create,
        edit,
        show,
    } from '@/routes/admin/roles'

    import type { TableAction } from '@/components/ui/AppTableActions.vue'
    import { Pagination, Role } from '@/types'

    interface Props {
        roles: Pagination<Role>
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
            label: 'Total Roles',
            value: props.roles.meta.total,
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
            label: 'Role',
        },
        {
            key: 'slug',
            label: 'Slug',
            width: '220px',
        },
        {
            key: 'users_count',
            label: 'Users',
            width: '120px',
        },
        {
            key: 'permissions_count',
            label: 'Permissions',
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

    function deleteRole(role: Role) {
        if (!confirm(`Are you sure you want to delete "${role.name}"?`)) {
            return
        }

        router.delete(`/admin/roles/${role.id}`, {
            preserveScroll: true,
        })
    }

    function getRoleActions(role: Role): TableAction[] {
        return [
            {
                label: 'View',
                icon: Eye,
                href: show(role.id).url,
            },
            {
                label: 'Edit',
                icon: Pencil,
                href: edit(role.id).url,
            },
            {
                label: 'Delete',
                icon: Trash2,
                danger: true,
                onClick: () => deleteRole(role),
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
                    <BackButton />

                    <Heading title="Roles" description="Manage user roles and their permissions." />
                </div>

                <Button as-child class="gap-2">
                    <Link :href="create()">
                        <Plus class="size-4" />
                        Create Role
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
                <FilterControl v-model:search="filters.search" search-placeholder="Search roles..."
                    :filter-count="filterCount" @apply="applyFilters" @clear="clearFilters" />
            </section>

            <!-- Table -->

            <AppTable :columns="columns" :data="props.roles.data">
                <!-- Slug -->

                <template #cell-slug="{ value }">
                    <code class="text-sm">
                        {{ value }}
                    </code>
                </template>

                <!-- Users -->

                <template #cell-users_count="{ value }">
                    {{ value ?? 0 }}
                </template>

                <!-- Permissions -->

                <template #cell-permissions_count="{ value }">
                    {{ value ?? 0 }}
                </template>

                <!-- Created At -->

                <template #cell-created_at="{ value }">
                    <Date :value="value" />
                </template>

                <!-- Actions -->

                <template #cell-actions="{ row }">
                    <AppTableActions :actions="getRoleActions(row)" />
                </template>
            </AppTable>
        </div>

        <!-- Footer -->

        <template #footer>
            <AppPagination :meta="props.roles.meta" />
        </template>
    </TableLayout>
</template>