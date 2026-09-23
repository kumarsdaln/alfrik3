<script setup lang="ts">
import { computed } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import { BookOpen, CircleHelp, ClipboardList, Eye, FolderTree, Image, Lightbulb, Mail, Pencil, Plus, Tags, Trash2, Users } from '@lucide/vue'

import Date from '@/components/datadisplay/Date.vue'
import Heading from '@/components/Heading.vue'
import BackButton from '@/components/ui/BackButton.vue'
import AppPagination from '@/components/ui/AppPagination.vue'
import AppStats from '@/components/ui/AppStats.vue'
import AppTable from '@/components/ui/AppTable.vue'
import AppTableActions from '@/components/ui/AppTableActions.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import Button from '@/components/ui/button/Button.vue'
import Badge from '@/components/ui/badge/Badge.vue'
import FilterControl from '@/components/filters/FilterControl.vue'
import TableLayout from '@/layouts/table/TableLayout.vue'

    import { index as manageMedia } from '@/routes/admin/media'
    import { index as manageCategory } from '@/routes/admin/categories/assignment'
    import { index as manageTags } from '@/routes/admin/tags/assignment'
    import { edit as manageMethodology } from '@/routes/admin/research/methodology';
    import { index as manageSources } from '@/routes/admin/research/sources';
    import { index as manageQuestions } from '@/routes/admin/research/questions';
    import { index as manageFindings } from '@/routes/admin/research/findings';
    import { index as manageTeam } from '@/routes/admin/research/team';
    import { index as manageInvitations } from '@/routes/admin/research/invitations';


import { useFilters } from '@/composables/useFilters'

import {
    create,
    show,
    edit,
    destroy,
} from '@/routes/admin/research'

import type {
    FormOption,
    Pagination,
    Research,
} from '@/types'

import type { TableAction } from '@/components/ui/AppTableActions.vue'

interface Props {
    researches: Pagination<Research>

    typeOptions: FormOption[]
    statusOptions: FormOption[]

    filters: {
        search?: string
        type?: string
        status?: string
    }

    stats: {
        total: number
        published: number
        draft: number
        archived: number
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
        type: props.filters.type ?? '',
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
        label: 'Total Research',
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
        label: 'Research',
    },
    {
        key: 'type',
        label: 'Type',
        width: '180px',
    },
    {
        key: 'author',
        label: 'Author',
        width: '180px',
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

function deleteResearch(research: Research) {
    if (!confirm(`Are you sure you want to delete "${research.title}"?`)) {
        return
    }

    router.delete(destroy(research.id).url, {
        preserveScroll: true,
    })
}

function getResearchActions(research: Research): TableAction[] {
    return [
        {
            label: 'View',
            icon: Eye,
            href: show(research.id).url,
        },

        {
            label: 'Edit',
            icon: Pencil,
            href: edit(research.id).url,
        },

        {
            label: 'Manage Media',
            icon: Image,
            href: manageMedia({type:'resource', id:research.id}).url,
        },

        {
            label: 'Manage Categories',
            icon: FolderTree,
            href: manageCategory({type:'resource', id:research.id}).url,
        },

        {
            label: 'Manage Tags',
            icon: Tags,
            href: manageTags({type:'resource', id:research.id}).url,
        },

        {
            label: 'Manage Methodology',
            icon: ClipboardList,
            href: manageMethodology(research.id).url,
        },

        {
            label: 'Manage Sources',
            icon: BookOpen,
            href: manageSources(research.id).url,
        },

        {
            label: 'Manage Questions',
            icon: CircleHelp,
            href: manageQuestions(research.id).url,
        },

        {
            label: 'Manage Findings',
            icon: Lightbulb,
            href: manageFindings(research.id).url,
        },

        {
            label: 'Manage Team',
            icon: Users,
            href: manageTeam(research.id).url,
        },

        {
            label: 'Manage Invitations',
            icon: Mail,
            href: manageInvitations(research.id).url,
        },

        {
            label: 'Delete',
            icon: Trash2,
            danger: true,
            onClick: () => deleteResearch(research),
        },
    ]
}
</script>

<template>
    <TableLayout>
        <!-- Header -->
        <template #header>
            <div class="flex items-center justify-between gap-4 py-5">
                <div class="flex min-w-0 gap-4">
                    <BackButton />
                    <Heading title="Research" description="Manage and organize your research projects." />
                </div>

                <Button as-child class="shrink-0 gap-2">
                    <Link :href="create()">
                        <Plus class="size-4" />
                        <span class="hidden sm:inline">
                            Create Research
                        </span>

                        <span class="sm:hidden">
                            Create
                        </span>
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
                <FilterControl v-model:search="filters.search" search-placeholder="Search research..."
                    :filter-count="filterCount" @apply="applyFilters" @clear="clearFilters">
                    <div class="space-y-6">
                        <AppSelect v-model="filters.type" name="type" label="Type" placeholder="All types"
                            :options="typeOptions" />

                        <AppSelect v-model="filters.status" name="status" label="Status" placeholder="All statuses"
                            :options="statusOptions" />
                    </div>
                </FilterControl>
            </section>

            <!-- Table -->
            <AppTable :columns="columns" :data="props.researches.data">
                <!-- Title -->
                <template #cell-title="{ row }">
                    <div class="min-w-0">
                        <Link :href="show(row.id).url" class="font-medium hover:underline">
                            {{ row.title }}
                        </Link>

                        <p v-if="row.subtitle" class="mt-0.5 truncate text-sm text-muted-foreground">
                            {{ row.subtitle }}
                        </p>
                    </div>
                </template>

                <!-- Type -->
                <template #cell-type="{ value }">
                    <Badge v-if="value" :color="value.color">
                        {{ value.label }}
                    </Badge>

                    <span v-else class="text-sm text-muted-foreground">
                        —
                    </span>
                </template>

                <!-- Author -->
                <template #cell-author="{ value }">
                    <span v-if="value">
                        {{ value.name }}
                    </span>

                    <span v-else class="text-sm text-muted-foreground">
                        —
                    </span>
                </template>

                <!-- Status -->
                <template #cell-status="{ value }">
                    <Badge v-if="value" :color="value.color">
                        {{ value.label }}
                    </Badge>

                    <span v-else class="text-sm text-muted-foreground">
                        —
                    </span>
                </template>

                <!-- Published At -->
                <template #cell-published_at="{ value }">
                    <Date :value="value" />
                </template>

                <!-- Actions -->
                <template #cell-actions="{ row }">
                    <AppTableActions :actions="getResearchActions(row)" />
                </template>
            </AppTable>
        </div>

        <!-- Footer -->
        <template #footer>
            <AppPagination :meta="props.researches.meta" />
        </template>
    </TableLayout>
</template>