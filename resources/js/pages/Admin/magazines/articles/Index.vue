<script setup lang="ts">
    import { Link, router } from '@inertiajs/vue3'
    import { Eye, Pencil, Plus, Trash2 } from '@lucide/vue'

    import Date from '@/components/datadisplay/Date.vue'
    import ProfileCell from '@/components/profile/ProfileCell.vue'
    import FilterControl from '@/components/filters/FilterControl.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import AppPagination from '@/components/ui/AppPagination.vue'
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
    } from '@/routes/admin/magazines/issues/articles'

    import {
        index as manageMedia,
    } from '@/routes/admin/media'

    import {
        index as manageSeo,
    } from '@/routes/admin/seo'

    import {
        index as manageCategories,
    } from '@/routes/admin/categories/assignment'

    import {
        index as manageTags,
    } from '@/routes/admin/tags/assignment'

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
            type: props.filters.type ?? '',
        },
        {
            url: window.location.pathname,
            searchKey: 'search',
            debounce: 500,
        },
    )

    /*
    |--------------------------------------------------------------------------
    | Table
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Actions
    |--------------------------------------------------------------------------
    */

    function deleteArticle(article: MagazineArticle) {
        if (!confirm(`Are you sure you want to delete "${article.title}"?`)) {
            return
        }

        router.delete(
            destroy({
                magazine: props.magazine.id,
                issue: props.issue.id,
                article: article.id,
            }).url,
            {
                preserveScroll: true,
            },
        )
    }

    function getArticleActions(
        article: MagazineArticle,
    ): TableAction[] {
        return [
            {
                label: 'View',
                icon: Eye,
                href: show({
                    magazine: props.magazine.id,
                    issue: props.issue.id,
                    article: article.id,
                }).url,
            },
            {
                label: 'Edit',
                icon: Pencil,
                href: edit({
                    magazine: props.magazine.id,
                    issue: props.issue.id,
                    article: article.id,
                }).url,
            },
            {
                label: 'Manage Media',
                icon: Pencil,
                href: manageMedia({
                    type: 'magazine-article',
                    id: article.id,
                }).url,
            },
            {
                label: 'Manage Categories',
                icon: Pencil,
                href: manageCategories({
                    type: 'magazine-article',
                    id: article.id,
                }).url,
            },
            {
                label: 'Manage Tags',
                icon: Pencil,
                href: manageTags({
                    type: 'magazine-article',
                    id: article.id,
                }).url,
            },
            {
                label: 'Manage SEO',
                icon: Pencil,
                href: manageSeo({
                    type: 'magazine-article',
                    id: article.id,
                }).url,
            },
            {
                label: 'Delete',
                icon: Trash2,
                danger: true,
                onClick: () => deleteArticle(article),
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
                        title="Magazine Articles"
                        :description="`Manage articles for ${issue.title}.`"
                    />
                </div>

                <Button
                    as-child
                    class="gap-2"
                >
                    <Link
                        :href="create({
                            magazine: magazine.id,
                            issue: issue.id,
                        }).url"
                    >
                        <Plus class="size-4" />
                        Add Article
                    </Link>
                </Button>
            </div>
        </template>

        <!-- Content -->
        <div class="py-6">
            <!-- Issue -->
            <section class="mb-8">
                <AppText class="font-medium">
                    {{ magazine.title }}
                </AppText>

                <AppText class="text-sm text-muted-foreground">
                    {{ issue.title }}

                    <span v-if="issue.issue_number">
                        · Issue {{ issue.issue_number }}
                    </span>
                </AppText>
            </section>

            <!-- Filters -->
            <section class="mb-8">
                <FilterControl
                    v-model:search="filters.search"
                    search-placeholder="Search articles..."
                    :filter-count="filterCount"
                    @apply="applyFilters"
                    @clear="clearFilters"
                >
                    <div class="space-y-6">
                        <AppSelect
                            v-model="filters.type"
                            name="type"
                            label="Type"
                            placeholder="All types"
                            :options="typeOptions"
                        />

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
                :data="props.articles.data"
            >
                <!-- Position -->
                <template #cell-position="{ value }">
                    {{ value }}
                </template>

                <!-- Article -->
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

                <!-- Type -->
                <template #cell-type="{ value }">
                    <Badge :color="value.color">
                        {{ value.label }}
                    </Badge>
                </template>

                <!-- Author -->
                <template #cell-author="{ value }">
                    <ProfileCell
                        v-if="value"
                        :profile="value"
                    />

                    <span v-else>—</span>
                </template>

                <!-- Categories -->
                <template #cell-categories="{ value }">
                    <div
                        v-if="value?.length"
                        class="flex flex-wrap gap-1.5"
                    >
                        <Badge
                            v-for="category in value"
                            :key="category.id"
                            variant="outline"
                        >
                            {{ category.name }}
                        </Badge>
                    </div>

                    <span v-else>—</span>
                </template>

                <!-- Tags -->
                <template #cell-tags="{ value }">
                    <div
                        v-if="value?.length"
                        class="flex flex-wrap gap-1.5"
                    >
                        <Badge
                            v-for="tag in value"
                            :key="tag.id"
                            variant="outline"
                        >
                            {{ tag.name }}
                        </Badge>
                    </div>

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

                <!-- Actions -->
                <template #cell-actions="{ row }">
                    <AppTableActions
                        :actions="getArticleActions(row)"
                    />
                </template>
            </AppTable>
        </div>

        <!-- Footer -->
        <template #footer>
            <AppPagination :meta="props.articles.meta" />
        </template>
    </TableLayout>
</template>