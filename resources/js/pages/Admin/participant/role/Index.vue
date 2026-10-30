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
    import Heading from '@/components/Heading.vue'

    import { useFilters } from '@/composables/useFilters'

    import {
        create as participantRoleCreate,
        edit as participantRoleEdit,
        destroy as participantRoleDestroy,
    } from '@/routes/admin/participants/roles'

    import type { Pagination } from '@/types'
    import type { TableAction } from '@/components/ui/AppTableActions.vue'

    interface ParticipantRole {
        id: number
        name: string
        slug: string
        description: string | null
        is_active: boolean
        position: number
        content_participants_count: number
        created_at: string
        updated_at: string
    }

    interface Props {
        roles: Pagination<ParticipantRole>

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
            label: 'Total Roles',
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
            label: 'Role',
        },
        {
            key: 'slug',
            label: 'Slug',
        },
        {
            key: 'content_participants_count',
            label: 'Assigned',
            width: '110px',
        },
        {
            key: 'position',
            label: 'Order',
            width: '100px',
        },
        {
            key: 'is_active',
            label: 'Status',
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

    function deleteParticipantRole(role: ParticipantRole) {
        if (role.content_participants_count > 0) {
            alert(
                'This role is assigned to participants. Deactivate it instead of deleting it.',
            )
            return
        }

        if (
            !confirm(
                `Are you sure you want to delete the "${role.name}" role?`,
            )
        ) {
            return
        }

        router.delete(
            participantRoleDestroy(role.id).url,
            {
                preserveScroll: true,
            },
        )
    }

    function getActions(role: ParticipantRole): TableAction[] {
        return [
            {
                label: 'Edit',
                icon: Pencil,
                href: participantRoleEdit(role.id).url,
            },
            {
                label: 'Delete',
                icon: Trash2,
                danger: true,
                onClick: () => deleteParticipantRole(role),
            },
        ]
    }
</script>

<template>
    <TableLayout>
        <!-- Header -->
        <template #header>
            <div class="flex items-center justify-between gap-4 px-6 py-5">
                <Heading title="Participant Roles"
                    description="Manage roles assigned to people across research, reports, interviews, and events." />

                <Button as-child class="gap-2">
                    <Link :href="participantRoleCreate().url">
                        <Plus class="size-4" />
                        Create Role
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
                <FilterControl v-model:search="filters.search" search-placeholder="Search participant roles..."
                    :filter-count="filterCount" @apply="applyFilters" @clear="clearFilters">
                    <div class="space-y-6">
                        <AppSelect v-model="filters.status" name="status" label="Status" placeholder="All statuses"
                            :options="[
                                {
                                    label: 'Active',
                                    value: 'active',
                                },
                                {
                                    label: 'Inactive',
                                    value: 'inactive',
                                },
                            ]" />
                    </div>
                </FilterControl>
            </section>

            <!-- Table -->
            <AppTable :columns="columns" :data="roles.data">
                <!-- Role -->
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

                <!-- Assigned participants -->
                <template #cell-content_participants_count="{ value }">
                    <span class="text-sm tabular-nums">
                        {{ value }}
                    </span>
                </template>

                <!-- Position -->
                <template #cell-position="{ value }">
                    <span class="text-sm tabular-nums">
                        {{ value }}
                    </span>
                </template>

                <!-- Status -->
                <template #cell-is_active="{ value }">
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
            <AppPagination :meta="roles.meta" />
        </template>
    </TableLayout>
</template>