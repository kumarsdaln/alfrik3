<script setup lang="ts">
    import { computed } from 'vue'
    import { Link, router } from '@inertiajs/vue3'
    import { Eye, Pencil, Plus, Trash2 } from '@lucide/vue'

    import Date from '@/components/datadisplay/Date.vue'
    import FilterControl from '@/components/filters/FilterControl.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import AppPagination from '@/components/ui/AppPagination.vue'
    import AppStats from '@/components/ui/AppStats.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import AppText from '@/components/ui/AppText.vue'
    import Badge from '@/components/ui/badge/Badge.vue'
    import Button from '@/components/ui/button/Button.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import { useFilters } from '@/composables/useFilters'

    import {
        create,
        show,
        edit,
        destroy,
    } from '@/routes/admin/magazines/issues'

    import {
        index as manageArticles,
    } from '@/routes/admin/magazines/issues/articles'

    import {
        index as manageMedia,
    } from '@/routes/admin/media'

    import {
        index as manageSeo,
    } from '@/routes/admin/seo'

    import type {
        FormOption,
        Magazine,
        MagazineIssue,
        Pagination,
    } from '@/types'

    import type { TableAction } from '@/components/ui/AppTableActions.vue'

    interface Props {
        magazine: Magazine
        issues: Pagination<MagazineIssue>
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
            label: 'Total Issues',
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
            label: 'Issue',
        },
        {
            key: 'volume',
            label: 'Volume',
            width: '100px',
        },
        {
            key: 'issue_number',
            label: 'Issue No.',
            width: '110px',
        },
        {
            key: 'cover_date',
            label: 'Cover Date',
            width: '160px',
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
            key: 'download_count',
            label: 'Downloads',
            width: '120px',
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

    function deleteIssue(issue: MagazineIssue) {
        if (!confirm(`Are you sure you want to delete "${issue.title}"?`)) {
            return
        }

        router.delete(
            destroy({
                magazine: props.magazine.id,
                issue: issue.id,
            }).url,
            {
                preserveScroll: true,
            },
        )
    }

    function getIssueActions(
        issue: MagazineIssue,
    ): TableAction[] {
        return [
            {
                label: 'View',
                icon: Eye,
                href: show({
                    magazine: props.magazine.id,
                    issue: issue.id,
                }).url,
            },
            {
                label: 'Edit',
                icon: Pencil,
                href: edit({
                    magazine: props.magazine.id,
                    issue: issue.id,
                }).url,
            },
            {
                label: 'Manage Media',
                icon: Pencil,
                href: manageMedia({
                    type: 'magazine-issue',
                    id: issue.id,
                }).url,
            },
            {
                label: 'Manage SEO',
                icon: Pencil,
                href: manageSeo({
                    type: 'magazine-issue',
                    id: issue.id,
                }).url,
            },
            {
                label: 'Manage Articles',
                icon: Pencil,
                href: manageArticles({
                    magazine: props.magazine.id,
                    issue: issue.id,
                }).url,
            },
            {
                label: 'Delete',
                icon: Trash2,
                danger: true,
                onClick: () => deleteIssue(issue),
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
                        :title="`${magazine.title} Issues`"
                        :description="`Manage issues for ${magazine.title}.`"
                    />
                </div>

                <Button
                    as-child
                    class="gap-2"
                >
                    <Link
                        :href="create({
                            magazine: magazine.id,
                        }).url"
                    >
                        <Plus class="size-4" />
                        Add Issue
                    </Link>
                </Button>
            </div>
        </template>

        <!-- Content -->
        <div class="py-6">
            <!-- Magazine -->
            <section class="mb-8">
                <AppText class="font-medium">
                    {{ magazine.title }}
                </AppText>

                <AppText class="text-sm text-muted-foreground">
                    {{ stats.total }} issues
                </AppText>
            </section>

            <!-- Stats -->
            <section class="mb-8">
                <AppStats :items="statItems" />
            </section>

            <!-- Filters -->
            <section class="mb-8">
                <FilterControl
                    v-model:search="filters.search"
                    search-placeholder="Search issues..."
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
                :data="props.issues.data"
            >
                <!-- Issue -->
                <template #cell-title="{ row }">
                    <div class="min-w-0">
                        <AppText class="font-medium truncate">
                            {{ row.title }}
                        </AppText>

                        <AppText
                            v-if="row.subtitle"
                            class="text-sm text-muted-foreground truncate"
                        >
                            {{ row.subtitle }}
                        </AppText>
                    </div>
                </template>

                <!-- Volume -->
                <template #cell-volume="{ value }">
                    {{ value ?? '—' }}
                </template>

                <!-- Issue Number -->
                <template #cell-issue_number="{ value }">
                    {{ value ?? '—' }}
                </template>

                <!-- Cover Date -->
                <template #cell-cover_date="{ value }">
                    <Date
                        v-if="value"
                        :value="value"
                    />

                    <span v-else>—</span>
                </template>

                <!-- Status -->
                <template #cell-status="{ value }">
                    <Badge :color="value.color">
                        {{ value.label }}
                    </Badge>
                </template>

                <!-- Published At -->
                <template #cell-published_at="{ value }">
                    <Date
                        v-if="value"
                        :value="value"
                    />

                    <span v-else>—</span>
                </template>

                <!-- Downloads -->
                <template #cell-download_count="{ value }">
                    {{ value ?? 0 }}
                </template>

                <!-- Actions -->
                <template #cell-actions="{ row }">
                    <AppTableActions
                        :actions="getIssueActions(row)"
                    />
                </template>
            </AppTable>
        </div>

        <!-- Footer -->
        <template #footer>
            <AppPagination :meta="props.issues.meta" />
        </template>
    </TableLayout>
</template>