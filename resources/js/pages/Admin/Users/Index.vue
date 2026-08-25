<script setup lang="ts">
import { computed, ref } from 'vue'
import { Link } from '@inertiajs/vue3'
import { Edit, Eye, Trash } from '@lucide/vue'

import AppTableLayout from '@/layouts/dashboard/AppTableLayout.vue'

import AppFilterLayout from '@/components/filters/layout/AppFilterLayout.vue'
import FilterInput from '@/components/filters/fields/FilterInput.vue'

import AppButton from '@/Components/Ui/AppButton.vue'
import AppStatusDropdown from '@/components/ui/AppStatusDropdown.vue'
import AppThreeDotOptions from '@/components/ui/AppThreeDotOptions.vue'
import DeleteConfirm from '@/components/ui/DeleteConfirm.vue'

import useFilterSync from '@/composables/useFilterSync'
import { usePatch } from '@/composables/usePatch'

import {
    create as adminUsersCreate,
    show as adminUsersShow,
    edit as adminUsersEdit,
    destroy as adminUsersDestroy,
    status as adminUsersStatus,
} from '@/routes/admin/users'

import type {
    RowId,
    TableColumn,
    TableHeader,
    Tablefilters,
} from '@/types/table'

import type {
    User,
    UserIndexProps,
    UserStatusOption,
} from '@/types/user'


/*
|--------------------------------------------------------------------------
| Props
|--------------------------------------------------------------------------
*/

const props = defineProps<UserIndexProps>()


/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

const routes = {
    create: (): string =>
        adminUsersCreate().url,

    show: (id: number): string =>
        adminUsersShow(id).url,

    edit: (id: number): string =>
        adminUsersEdit(id).url,

    destroy: (id: number): string =>
        adminUsersDestroy(id).url,

    status: (id: number): string =>
        adminUsersStatus(id).url,
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
        key: 'email',
        label: 'Email',
        sortable: true,
    },
    {
        key: 'roles',
        label: 'Roles',
    },
    {
        key: 'status',
        label: 'Status',
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
        key: 'email',
        slot: 'email',
    },
    {
        key: 'roles',
        slot: 'roles',
    },
    {
        key: 'status',
        slot: 'status',
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
| Status
|--------------------------------------------------------------------------
*/

const statusOptions: UserStatusOption[] = [
    {
        value: 'active',
        label: 'Active',
    },
    {
        value: 'inactive',
        label: 'Inactive',
    },
]


/*
|--------------------------------------------------------------------------
| Filters
|--------------------------------------------------------------------------
*/

const searchField = {
    placeholder: 'Search users',
}

const {
    filters,
    applyFilters,
    resetFilters,
} = useFilterSync({
    initialFilters: {
        search: '',
        status: '',
        sort: '',
        direction: '',
    },
})


const visiblefilters = computed(() => ({
    search: filters.search,
    status: filters.status,
}))


const tablefilters = computed<Tablefilters>(() => ({
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
        (props.users.current_page - 1) *
            props.users.per_page +
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
| Status Update
|--------------------------------------------------------------------------
*/

const { patch } = usePatch()

const updateStatus = (
    user: User,
    option: UserStatusOption,
): void => {
    if (user.status === option.value) {
        return
    }

    patch(
        routes.status(user.id),
        {
            status: option.value,
        },
        'User status updated successfully.',
        () => {
            user.status = option.value
        },
    )
}


/*
|--------------------------------------------------------------------------
| Delete Confirmation
|--------------------------------------------------------------------------
*/

const selectedUser = ref<User | null>(null)

const showDeleteConfirm = ref(false)


const openDeleteConfirm = (
    user: User,
): void => {
    selectedUser.value = user
    showDeleteConfirm.value = true
}


const closeDeleteConfirm = (): void => {
    selectedUser.value = null
    showDeleteConfirm.value = false
}


/*
|--------------------------------------------------------------------------
| Row Actions
|--------------------------------------------------------------------------
*/

const getActions = (
    user: User,
) => [
    {
        label: 'View',
        icon: Eye,
        href: routes.show(user.id),
    },
    {
        label: 'Edit',
        icon: Edit,
        href: routes.edit(user.id),
    },
    {
        label: 'Delete',
        icon: Trash,
        danger: true,
        action: () => openDeleteConfirm(user),
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
        title="Users"
        :headers="headers"
        :columns="columns"
        :data="users.data"
        :total="users.total"
        :per-page="users.per_page"
        :current-page="users.current_page"
        :last-page="users.last_page"
        :prev-page-url="users.prev_page_url"
        :next-page-url="users.next_page_url"
        :filters="tablefilters"
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
                Create User
            </AppButton>
        </template>


        <!-- Filters -->

        <template #filter>
            <AppFilterLayout
                :filters="visiblefilters"
                @apply="applyFilters"
                @reset="resetFilters"
            >
                <template #search>
                    <FilterInput
                        v-model="filters.search"
                        :field="searchField"
                    />
                </template>

                <template #inline-filters>
                    <AppStatusDropdown
                        v-model="filters.status"
                        :options="statusOptions"
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
            <Link
                :href="routes.show(data.id)"
                class="font-medium text-brand hover:underline"
            >
                {{ data.name }}
            </Link>
        </template>


        <!-- Email -->

        <template #email="{ data }">
            <span class="text-muted-foreground">
                {{ data.email }}
            </span>
        </template>


        <!-- Roles -->

        <template #roles="{ data }">
            <div
                v-if="data.roles?.length"
                class="flex flex-wrap gap-1.5"
            >
                <span
                    v-for="role in data.roles"
                    :key="role.id"
                    class="rounded-md bg-muted px-2 py-1 text-xs font-medium"
                >
                    {{ role.name }}
                </span>
            </div>

            <span
                v-else
                class="text-muted-foreground"
            >
                —
            </span>
        </template>


        <!-- Status -->

        <template #status="{ data }">
            <AppStatusDropdown
                v-model="data.status"
                :options="statusOptions"
                @change="updateStatus(data, $event)"
            />
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
            selectedUser
                ? routes.destroy(selectedUser.id)
                : ''
        "
        title="Delete User"
        message="Are you sure you want to delete this user?"
        @close="closeDeleteConfirm"
    />
</template>