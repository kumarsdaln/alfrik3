<script setup lang="ts">
    import { computed } from 'vue'
    import { Link, router } from '@inertiajs/vue3'
    import {
        Eye,
        Pencil,
        Plus,
        Trash2,
    } from '@lucide/vue'

    import Date from '@/components/datadisplay/Date.vue'
    import ProfileCell from '@/components/profile/ProfileCell.vue'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppPagination from '@/components/ui/AppPagination.vue'
    import AppStats from '@/components/ui/AppStats.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import AppText from '@/components/ui/AppText.vue'
    import Badge from '@/components/ui/badge/Badge.vue'
    import Button from '@/components/ui/button/Button.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import FilterControl from '@/components/filters/FilterControl.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import { useFilters } from '@/composables/useFilters'

    import { create, edit as editMagazine } from '@/routes/admin/magazines'
    import { index as manageIssues } from '@/routes/admin/magazines/issues'
    import { index as manageCategories } from '@/routes/admin/categories/assignment'
    import { index as manageTags } from '@/routes/admin/tags/assignment'
    import { index as manageMedia } from '@/routes/admin/media'
    import { index as manageSeo } from '@/routes/admin/seo'

    import type {
        Magazine,
        FormOption,
        Pagination,
    } from '@/types'

    import type { TableAction } from '@/components/ui/AppTableActions.vue'

    interface Props {
        magazines: Pagination<Magazine>
        statusOptions: FormOption[]
        filters: {
            search?: string
            status?: string
        }
        stats: {
            total: number
            published: number
            draft: number
            this_month: number
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
            label: 'Total Magazines',
            value: props.stats.total,
        },
        {
            label: 'Published',
            value: props.stats.published,
        },
        {
            label: 'Drafts',
            value: props.stats.draft,
        },
        {
            label: 'This Month',
            value: props.stats.this_month,
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
            key: 'title',
            label: 'Title',
        },
        {
            key: 'author',
            label: 'Author',
            width: '220px',
        },
        {
            key: 'categories',
            label: 'Categories',
            width: '240px',
        },
        {
            key: 'tags',
            label: 'Tags',
            width: '240px',
        },
        {
            key: 'status',
            label: 'Status',
            width: '140px',
        },
        {
            key: 'published_at',
            label: 'Published At',
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

    function deleteMagazine(magazine: Magazine) {
        if (
            !confirm(
                `Are you sure you want to delete "${magazine.title}"?`,
            )
        ) {
            return
        }

        router.delete(
            `/admin/magazines/${magazine.id}`,
            {
                preserveScroll: true,
            },
        )
    }

    function getMagazineActions(
        magazine: Magazine,
    ): TableAction[] {
        return [
            {
                label: 'View',
                icon: Eye,
                href: `/admin/magazines/${magazine.slug}`,
            },
            {
                label: 'Edit',
                icon: Pencil,
                href: editMagazine(magazine.id).url,
            },
            {
                label: 'Manage Issues',
                icon: Pencil,
                href: manageIssues(magazine.id).url,
            },
            {
                label: 'Manage Category',
                icon: Pencil,
                href: manageCategories({
                    type: 'magazine',
                    id: magazine.id,
                }).url,
            },
            {
                label: 'Manage Tags',
                icon: Pencil,
                href: manageTags({
                    type: 'magazine',
                    id: magazine.id,
                }).url,
            },
            {
                label: 'Manage Media',
                icon: Pencil,
                href: manageMedia({
                    type: 'magazine',
                    id: magazine.id,
                }).url,
            },
            {
                label: 'Manage SEO',
                icon: Pencil,
                href: manageSeo({
                    type: 'magazine',
                    id: magazine.id,
                }).url,
            },
            {
                label: 'Delete',
                icon: Trash2,
                danger: true,
                onClick: () => deleteMagazine(magazine),
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
                <div>
                    <AppHeading tag="h1">
                        Magazines
                    </AppHeading>

                    <AppText>
                        Manage and organize your magazines.
                    </AppText>
                </div>

                <Button as-child class="gap-2">
                    <Link :href="create()">
                        <Plus class="size-4" />
                        Create Magazine
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
                <FilterControl v-model:search="filters.search" search-placeholder="Search magazines..."
                    :filter-count="filterCount" @apply="applyFilters" @clear="clearFilters">
                    <div class="space-y-6">
                        <AppSelect v-model="filters.status" name="status" label="Status" placeholder="All statuses"
                            :options="statusOptions" />
                    </div>
                </FilterControl>
            </section>

            <!-- Table -->
            <AppTable :columns="columns" :data="props.magazines.data">
                <!-- Title -->
                <template #cell-title="{ row }">
                    <div class="min-w-0">
                        <Link :href="`/admin/magazines/${row.id}/edit`" class="
                                font-medium
                                hover:underline
                            ">
                            {{ row.title }}
                        </Link>

                        <p v-if="row.subtitle" class="
                                mt-1 max-w-md truncate
                                text-sm text-muted-foreground
                            ">
                            {{ row.subtitle }}
                        </p>
                    </div>
                </template>

                <!-- Author -->
                <template #cell-author="{ value }">
                    <ProfileCell v-if="value" :profile="value" />

                    <span v-else class="text-sm text-muted-foreground">
                        —
                    </span>
                </template>

                <!-- Categories -->
                <template #cell-categories="{ value }">
                    <div class="flex flex-wrap gap-1.5">
                        <Badge v-for="category in value" :key="category.id" variant="outline">
                            {{ category.name }}
                        </Badge>

                        <span v-if="!value?.length" class="text-sm text-muted-foreground">
                            —
                        </span>
                    </div>
                </template>

                <!-- Tags -->
                <template #cell-tags="{ value }">
                    <div class="flex flex-wrap gap-1.5">
                        <Badge v-for="tag in value" :key="tag.id" variant="secondary">
                            {{ tag.name }}
                        </Badge>

                        <span v-if="!value?.length" class="text-sm text-muted-foreground">
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

                <!-- Published At -->
                <template #cell-published_at="{ value }">
                    <Date :value="value" />
                </template>

                <!-- Actions -->
                <template #cell-actions="{ row }">
                    <AppTableActions :actions="getMagazineActions(row)" />
                </template>
            </AppTable>
        </div>

        <!-- Footer -->
        <template #footer>
            <AppPagination :meta="props.magazines.meta" />
        </template>
    </TableLayout>
</template>