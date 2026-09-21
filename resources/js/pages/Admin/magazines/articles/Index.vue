<script setup lang="ts">
    import { computed } from 'vue'
    import { Link, router } from '@inertiajs/vue3'
    import { Eye, Pencil, Plus, Trash2 } from '@lucide/vue'

    import Date from '@/components/datadisplay/Date.vue'
    import ProfileCell from '@/components/profile/ProfileCell.vue'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppPagination from '@/components/ui/AppPagination.vue'
    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import AppText from '@/components/ui/AppText.vue'
    import Button from '@/components/ui/button/Button.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import FilterControl from '@/components/filters/FilterControl.vue'
    import Badge from '@/components/ui/badge/Badge.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import { useFilters } from '@/composables/useFilters'

    import {
        create,
    } from '@/routes/admin/magazines/issues/articles'

    import type {
        FormOption,
        Magazine,
        MagazineArticle,
        MagazineIssue,
        Pagination,
    } from '@/types'

    import type { TableAction } from '@/components/ui/AppTableActions.vue'

    interface Props {
        magazine: Magazine
        issue: MagazineIssue
        articles: Pagination<MagazineArticle>
        statusOptions: FormOption[]
        typeOptions: FormOption[]
        filters: {
            search?: string
            status?: string
            type?: string
        }
    }

    const props = defineProps<Props>()

    const { filters, applyFilters, clearFilters } = useFilters({
        search: props.filters.search ?? '',
        status: props.filters.status ?? '',
        type: props.filters.type ?? '',
    })

    const hasFilters = computed(() => {
        return Boolean(
            filters.search ||
            filters.status ||
            filters.type
        )
    })

    const columns = [
        {
            key: 'position',
            label: '#',
            width: '80px',
        },
        {
            key: 'title',
            label: 'Article',
        },
        {
            key: 'type',
            label: 'Type',
            width: '150px',
        },
        {
            key: 'author',
            label: 'Author',
            width: '220px',
        },
        {
            key: 'categories',
            label: 'Categories',
            width: '220px',
        },
        {
            key: 'tags',
            label: 'Tags',
            width: '220px',
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

    const getArticleActions = (
        article: MagazineArticle
    ): TableAction[] => [
            {
                label: 'View',
                icon: Eye,
                onClick: () => {
                    router.visit(
                        `/admin/magazines/${props.magazine.id}/issues/${props.issue.id}/articles/${article.id}`
                    )
                },
            },
            {
                label: 'Edit',
                icon: Pencil,
                href: `/admin/magazines/${props.magazine.id}/issues/${props.issue.id}/articles/${article.id}/edit`,
            },
            {
                label: 'Manage Media',
                href: `/admin/magazines/${props.magazine.id}/issues/${props.issue.id}/articles/${article.id}/media`,
            },
            {
                label: 'Manage Taxonomy',
                href: `/admin/magazines/${props.magazine.id}/issues/${props.issue.id}/articles/${article.id}/taxonomy`,
            },
            {
                label: 'Manage SEO',
                href: `/admin/magazines/${props.magazine.id}/issues/${props.issue.id}/articles/${article.id}/seo`,
            },
            {
                label: 'Delete',
                icon: Trash2,
                variant: 'destructive',
                onClick: () => {
                    if (confirm('Are you sure you want to delete this article?')) {
                        router.delete(
                            `/admin/magazines/${props.magazine.id}/issues/${props.issue.id}/articles/${article.id}`
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
                        Magazine Articles
                    </AppHeading>

                    <AppText class="mt-1">
                        Manage articles for {{ issue.title }}.
                    </AppText>
                </div>

                <Button as-child>
                    <Link :href="create(magazine.id, issue.id).url">
                        <Plus class="size-4" />
                        Add Article
                    </Link>
                </Button>
            </div>
        </template>

        <div class="space-y-6">
            <div>
                <AppText class="font-medium">
                    {{ magazine.title }}
                </AppText>

                <AppText class="text-sm text-muted-foreground">
                    {{ issue.title }}
                    <span v-if="issue.issue_number">
                        · Issue {{ issue.issue_number }}
                    </span>
                </AppText>
            </div>

            <div class="flex flex-wrap items-center gap-3">
                <FilterControl v-model="filters.search" placeholder="Search articles..." @keyup.enter="applyFilters" />

                <AppSelect v-model="filters.type" :options="typeOptions" placeholder="Type" class="w-[180px]" />

                <AppSelect v-model="filters.status" :options="statusOptions" placeholder="Status" class="w-[180px]" />

                <Button variant="outline" @click="applyFilters">
                    Filter
                </Button>

                <Button v-if="hasFilters" variant="ghost" @click="clearFilters">
                    Clear
                </Button>
            </div>

            <AppTable :columns="columns" :data="articles.data">
                <template #cell-position="{ value }">
                    {{ value }}
                </template>

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

                <template #cell-type="{ value }">
                    <Badge :color="value.color">
                        {{ value.label }}
                    </Badge>
                </template>

                <template #cell-author="{ value }">
                    <ProfileCell v-if="value" :profile="value" />

                    <span v-else>—</span>
                </template>

                <template #cell-categories="{ value }">
                    <div v-if="value?.length" class="flex flex-wrap gap-1.5">
                        <Badge v-for="category in value" :key="category.id" variant="outline">
                            {{ category.name }}
                        </Badge>
                    </div>

                    <span v-else>—</span>
                </template>

                <template #cell-tags="{ value }">
                    <div v-if="value?.length" class="flex flex-wrap gap-1.5">
                        <Badge v-for="tag in value" :key="tag.id" variant="outline">
                            {{ tag.name }}
                        </Badge>
                    </div>

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

                <template #cell-actions="{ row }">
                    <AppTableActions :actions="getArticleActions(row)" />
                </template>
            </AppTable>

            <AppPagination :meta="articles.meta" />
        </div>
    </TableLayout>
</template>