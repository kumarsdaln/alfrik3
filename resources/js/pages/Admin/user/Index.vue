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
    import AppSelect from '@/components/form/AppSelect.vue'
    import FilterControl from '@/components/filters/FilterControl.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import { useFilters } from '@/composables/useFilters'
    import { create, edit, show, destroy } from '@/routes/admin/users'

    import type { FormOption, Pagination, User } from '@/types'
    import type { TableAction } from '@/components/ui/AppTableActions.vue'

    interface Props {
        users: Pagination<User>

        statusOptions: FormOption[]

        filters: {
            search?: string
            status?: string
            sort?: string
            direction?: string
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
            label: 'Total Users',
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
            key: 'email',
            label: 'Email',
        },
        {
            key: 'roles',
            label: 'Roles',
            width: '240px',
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

    function deleteUser(user: User) {
        if (!confirm(`Are you sure you want to delete "${user.name}"?`)) {
            return
        }

        router.delete(destroy(user.id).url, {
            preserveScroll: true,
        })
    }

    function getUserActions(user: User): TableAction[] {
        return [
            {
                label: 'View',
                icon: Eye,
                href: show(user.id).url,
            },
            {
                label: 'Edit',
                icon: Pencil,
                href: edit(user.id).url,
            },
            {
                label: 'Delete',
                icon: Trash2,
                danger: true,
                onClick: () => deleteUser(user),
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

                    <Heading
                        title="Users"
                        description="Manage and organize your users."
                    />
                </div>

                <Button as-child class="gap-2">
                    <Link :href="create()">
                        <Plus class="size-4" />
                        Create User
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
                <FilterControl
                    v-model:search="filters.search"
                    search-placeholder="Search users..."
                    :filter-count="filterCount"
                    @apply="applyFilters"
                    @clear="clearFilters"
                >
                    <div class="space-y-6">
                        <AppSelect
                            v-model="filters.status"
                            name="status"
                            label="Status"
                            placeholder="All statuses"
                            :options="statusOptions"
                        />
                    </div>
                </FilterControl>
            </section>

            <!-- Table -->
            <AppTable
                :columns="columns"
                :data="props.users.data"
            >
                <!-- Roles -->
                <template #cell-roles="{ value }">
                    <div class="flex flex-wrap gap-1.5">
                        <Badge
                            v-for="role in value"
                            :key="role.id"
                            variant="outline"
                        >
                            {{ role.name }}
                        </Badge>

                        <span
                            v-if="!value?.length"
                            class="text-sm text-muted-foreground"
                        >
                            —
                        </span>
                    </div>
                </template>

                <!-- Status -->
                <template #cell-status="{ value }">
                    <Badge :color="value.color">
                        {{ value.label }}
                    </Badge>
                </template>

                <!-- Created At -->
                <template #cell-created_at="{ value }">
                    <Date :value="value" />
                </template>

                <!-- Actions -->
                <template #cell-actions="{ row }">
                    <AppTableActions
                        :actions="getUserActions(row)"
                    />
                </template>
            </AppTable>
        </div>

        <!-- Footer -->
        <template #footer>
            <AppPagination :meta="props.users.meta" />
        </template>
    </TableLayout>
</template>