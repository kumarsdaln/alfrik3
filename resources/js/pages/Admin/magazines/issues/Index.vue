<script setup lang="ts">
    import { computed } from 'vue'
    import { Link, router } from '@inertiajs/vue3'
    import { Eye, Pencil, Plus, Trash2 } from '@lucide/vue'

    import Date from '@/components/datadisplay/Date.vue'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppPagination from '@/components/ui/AppPagination.vue'
    import AppStats from '@/components/ui/AppStats.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import AppText from '@/components/ui/AppText.vue'
    import Button from '@/components/ui/button/Button.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import FilterControl from '@/components/filters/FilterControl.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import { useFilters } from '@/composables/useFilters'

    import { create } from '@/routes/admin/magazines/issues'

    import type {
        FormOption,
        Magazine,
        MagazineIssue,
        Pagination,
    } from '@/types'

    import type { TableAction } from '@/components/ui/AppTableActions.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    interface Props {
        magazine: Magazine
        issues: Pagination<MagazineIssue>
        statusOptions: FormOption[]
        filters: {
            search?: string
            status?: string
        }
    }

    const props = defineProps<Props>()

    const { filters, applyFilters, clearFilters } = useFilters({
        search: props.filters.search ?? '',
        status: props.filters.status ?? '',
    })

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
            key: 'downloads',
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

    const hasFilters = computed(() => {
        return Boolean(
            filters.search ||
            filters.status
        )
    })

    const getIssueActions = (issue: MagazineIssue): TableAction[] => [
        {
            label: 'View',
            icon: Eye,
            onClick: () => {
                router.visit(`/admin/magazines/${props.magazine.id}/issues/${issue.id}`)
            },
        },
        {
            label: 'Edit',
            icon: Pencil,
            href: `/admin/magazines/${props.magazine.id}/issues/${issue.id}/edit`,
        },
        {
            label: 'Manage Media',
            href: `/admin/magazines/${props.magazine.id}/issues/${issue.id}/media`,
        },
        {
            label: 'Manage SEO',
            href: `/admin/magazines/${props.magazine.id}/issues/${issue.id}/seo`,
        },
        {
            label: 'Delete',
            icon: Trash2,
            variant: 'destructive',
            onClick: () => {
                if (confirm('Are you sure you want to delete this issue?')) {
                    router.delete(
                        `/admin/magazines/${props.magazine.id}/issues/${issue.id}`
                    )
                }
            },
        },
    ]
</script>

<template>
    <TableLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4">
                <div>
                    <AppHeading>
                        Magazine Issues
                    </AppHeading>

                    <AppText class="mt-1">
                        Manage issues for {{ magazine.title }}.
                    </AppText>
                </div>

                <Button as-child>
                    <Link :href="create(magazine.id).url">
                        <Plus class="size-4" />
                        Add Issue
                    </Link>
                </Button>
            </div>
        </template>

        <div class="space-y-6">
            <div class="flex items-center justify-between gap-4">
                <div>
                    <AppText class="font-medium">
                        {{ magazine.title }}
                    </AppText>

                    <AppText class="text-sm text-muted-foreground">
                        {{ issues.total }} issues
                    </AppText>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <FilterControl v-model="filters.search" placeholder="Search issues..." @keyup.enter="applyFilters" />

                <AppSelect v-model="filters.status" :options="statusOptions" placeholder="Status" class="w-[180px]" />

                <Button variant="outline" @click="applyFilters">
                    Filter
                </Button>

                <Button v-if="hasFilters" variant="ghost" @click="clearFilters">
                    Clear
                </Button>
            </div>

            <AppTable :columns="columns" :data="issues.data">
                <template #cell-title="{ row }">
                    <div class="min-w-0">
                        <AppText class="font-medium truncate">
                            {{ row.title }}
                        </AppText>

                        <AppText v-if="row.subtitle" class="text-sm text-muted-foreground truncate">
                            {{ row.subtitle }}
                        </AppText>
                    </div>
                </template>

                <template #cell-volume="{ value }">
                    <span>
                        {{ value ?? '—' }}
                    </span>
                </template>

                <template #cell-issue_number="{ value }">
                    <span>
                        {{ value ?? '—' }}
                    </span>
                </template>

                <template #cell-cover_date="{ value }">
                    <Date v-if="value" :value="value" />

                    <span v-else>—</span>
                </template>

                <template #cell-status="{ value }">
                    <Badge :color="value.color">
                        {{ value.label }}
                    </Badge>
                </template>

                <template #cell-published_at="{ value }">
                    <Date v-if="value" :value="value" />

                    <span v-else>—</span>
                </template>

                <template #cell-downloads="{ row }">
                    {{ row.download_count }}
                </template>

                <template #cell-actions="{ row }">
                    <AppTableActions :actions="getIssueActions(row)" />
                </template>
            </AppTable>

            <AppPagination :meta="issues.meta" />
        </div>
    </TableLayout>
</template>