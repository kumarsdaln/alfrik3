<script setup lang="ts">
    import { computed, ref } from 'vue'
    import { router } from '@inertiajs/vue3'
    import { Edit, Trash } from '@lucide/vue'

    import AppTableLayout from '@/layouts/dashboard/AppTableLayout.vue'

    import AppFilterLayout from '@/components/filters/layout/AppFilterLayout.vue'
    import FilterInput from '@/components/filters/fields/FilterInput.vue'

    import AppButton from '@/components/ui/AppButton.vue'
    import AppThreeDotOptions from '@/components/ui/AppThreeDotOptions.vue'
    import DeleteConfirm from '@/components/ui/DeleteConfirm.vue'

    import useFilterSync from '@/composables/useFilterSync'

    import {
        index as adminPermissionssIndex,
        create as adminPermissionssCreate,
        edit as adminPermissionssEdit,
        destroy as adminPermissionssDestroy,
    } from '@/routes/admin/permissions'

    import type {
        RowId,
        TableColumn,
        TableHeader,
        TableFilters,
    } from '@/types/table'
import { Pagination } from '@/types/pagination'
import { Permission } from '@/types/user'

    /*
    |--------------------------------------------------------------------------
    | Props
    |--------------------------------------------------------------------------
    */

    interface Props {
        permissions: Pagination<Permission>
    }
    const props = defineProps<Props>()


    /*
    |--------------------------------------------------------------------------
    | Routes
    |--------------------------------------------------------------------------
    */

    const routes = {
        index: (): string =>
            adminPermissionssIndex().url,

        create: (): string =>
            adminPermissionssCreate().url,

        edit: (id: number): string =>
            adminPermissionssEdit(id).url,

        destroy: (id: number): string =>
            adminPermissionssDestroy(id).url,
    }


    /*
    |--------------------------------------------------------------------------
    | Table Headers
    |--------------------------------------------------------------------------
    */

    const headers: TableHeader[] = [
        {
            key: 'sno',
            label: '#',
        },
        {
            key: 'name',
            label: 'Name',
            sortable: true,
        },
        {
            key: 'slug',
            label: 'Slug',
            sortable: true,
        },
        {
            key: 'description',
            label: 'Description',
        },
        {
            key: 'created_at',
            label: 'Created At',
            sortable: true,
        },
        {
            key: 'updated_at',
            label: 'Updated At',
            sortable: true,
        },
    ]


    /*
    |--------------------------------------------------------------------------
    | Table Columns
    |--------------------------------------------------------------------------
    */

    const columns: TableColumn[] = [
        {
            key: 'sno',
            slot: 'sno',
        },
        {
            key: 'name',
            slot: 'name',
        },
        {
            key: 'slug',
            slot: 'slug',
        },
        {
            key: 'description',
            slot: 'description',
        },
        {
            key: 'created_at',
            type: 'datetime',
        },
        {
            key: 'updated_at',
            type: 'datetime',
        },
    ]


    /*
    |--------------------------------------------------------------------------
    | Filters
    |--------------------------------------------------------------------------
    */

    const searchField = {
        placeholder: 'Search permissions',
    }

    const {
        filters,
        applyFilters,
        resetFilters,
    } = useFilterSync({
        url: routes.index(),

        initialFilters: {
            search: '',
            sort: '',
            direction: '',
        },

        debounce: 500,
        autoApply: true,
        preserveState: true,
        preserveScroll: true,
    })


    /*
    |--------------------------------------------------------------------------
    | Table Filters
    |--------------------------------------------------------------------------
    */

    const tableFilters = computed<TableFilters>(() => ({
        ...filters,
    }))


    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    const serialNumber = (
        index: number,
    ): number => {
        return (
            (props.permissions.current_page - 1) *
            props.permissions.per_page +
            index +
            1
        )
    }


    /*
    |--------------------------------------------------------------------------
    | Sorting
    |--------------------------------------------------------------------------
    */

    const handleSort = (
        key: string,
    ): void => {
        if (filters.sort === key) {
            filters.direction =
                filters.direction === 'asc'
                    ? 'desc'
                    : 'asc'
        } else {
            filters.sort = key
            filters.direction = 'asc'
        }

        applyFilters()
    }


    /*
    |--------------------------------------------------------------------------
    | Delete Confirmation
    |--------------------------------------------------------------------------
    */

    const selectedRole = ref<Permission | null>(null)

    const showDeleteConfirm = ref(false)

    const openDeleteConfirm = (
        role: Permission,
    ): void => {
        selectedRole.value = role
        showDeleteConfirm.value = true
    }

    const closeDeleteConfirm = (): void => {
        selectedRole.value = null
        showDeleteConfirm.value = false
    }


    /*
    |--------------------------------------------------------------------------
    | Row Actions
    |--------------------------------------------------------------------------
    */

    const getActions = (
        role: Permission,
    ) => [
        {
            label: 'Edit',
            icon: Edit,
            href: routes.edit(role.id),
        },
        {
            label: 'Delete',
            icon: Trash,
            danger: true,
            action: () => openDeleteConfirm(role),
        },
    ]


    /*
    |--------------------------------------------------------------------------
    | Bulk Actions
    |--------------------------------------------------------------------------
    */

    const handleBulkExport = (
        _ids: RowId[],
    ): void => {
        // Implement when the bulk export endpoint is available.
    }

    const handleBulkDelete = (
        _ids: RowId[],
    ): void => {
        // Implement when the bulk delete endpoint is available.
    }
</script>


<template>
    <AppTableLayout
        title="permissions"
        :headers="headers"
        :columns="columns"
        :data="permissions.data"
        :total="permissions.total"
        :per-page="permissions.per_page"
        :current-page="permissions.current_page"
        :last-page="permissions.last_page"
        :prev-page-url="permissions.prev_page_url"
        :next-page-url="permissions.next_page_url"
        :filters="tableFilters"
        selectable
        primary-key="id"
        @sort="handleSort"
        @bulk-export="handleBulkExport"
        @bulk-delete="handleBulkDelete"
    >
        <!-- Header -->

        <template #header>
            <AppButton
                variant="add"
                :href="routes.create()"
                size="sm"
                autoIcon
            >
                Create Permission
            </AppButton>
        </template>


        <!-- Filters -->

        <template #filter>
            <AppFilterLayout
                :filters="tableFilters"
                @apply="applyFilters"
                @reset="resetFilters"
            >
                <template #search>
                    <FilterInput
                        v-model="filters.search"
                        :field="searchField"
                    />
                </template>
            </AppFilterLayout>
        </template>


        <!-- Serial Number -->

        <template #sno="{ index }">
            {{ serialNumber(index) }}
        </template>


        <!-- Name -->

        <template #name="{ data }">
            <span class="font-medium">
                {{ data.name }}
            </span>
        </template>


        <!-- Slug -->

        <template #slug="{ data }">
            <span class="font-mono text-sm text-muted-foreground">
                {{ data.slug }}
            </span>
        </template>


        <!-- Description -->

        <template #description="{ data }">
            <span
                v-if="data.description"
                class="text-muted-foreground"
            >
                {{ data.description }}
            </span>

            <span
                v-else
                class="text-muted-foreground"
            >
                —
            </span>
        </template>


        <!-- Actions -->

        <template #threedot="{ data }">
            <AppThreeDotOptions
                :actions="getActions(data)"
            />
        </template>
    </AppTableLayout>


    <!-- Delete Confirmation -->

    <DeleteConfirm
        :show="showDeleteConfirm"
        :delete-url="
            selectedRole
                ? routes.destroy(selectedRole.id)
                : ''
        "
        title="Delete Role"
        message="Are you sure you want to delete this role?"
        @close="closeDeleteConfirm"
    />
</template>