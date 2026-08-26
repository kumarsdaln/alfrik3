<script setup lang="ts">
    import { ref } from 'vue'
    import { Link, router } from '@inertiajs/vue3'

    import AppTableLayout from '@/layouts/dashboard/AppTableLayout.vue'
    import AppButton from '@/components/ui/AppButton.vue'
    import AppToggle from '@/components/ui/AppToggle.vue'
    import AppThreeDotOptions from '@/components/ui/AppThreeDotOptions.vue'
    import AppConfirmDialog from '@/components/ui/AppConfirmDialog.vue'
    import AppFilterLayout from '@/components/filters/layout/AppFilterLayout.vue'
    import FilterInput from '@/components/filters/fields/FilterInput.vue'
    import FilterSelect from '@/components/filters/fields/FilterSelect.vue'
    import useFilterSync from '@/composables/useFilterSync'
    import PlusIcon from '@/Icons/PlusIcon.vue'
    import Edit from '@/Icons/Edit.vue'
    import Trash from '@/Icons/Trash.vue'

    import { create as paperCreate, edit as paperEdit, destroy as paperDestroy } from '@/routes/admin/research'
    import { index as areasIndex } from '@/routes/admin/research/areas'
    import { status as paperStatus } from '@/routes/admin/research/update'
    import type { ResearchPaper, ResearchArea, ResearchFilters } from '@/types'

    const props = defineProps<{
        papers: { data: ResearchPaper[]; total: number; per_page: number; current_page: number; last_page: number; prev_page_url: string | null; next_page_url: string | null }
        areas: ResearchArea[]
        filters: ResearchFilters
    }>()

    const headers = [
        { key: 'sno', label: '#' },
        { key: 'title', label: 'Title' },
        { key: 'authors', label: 'Authors' },
        { key: 'area', label: 'Area' },
        { key: 'downloads', label: 'Downloads' },
        { key: 'status', label: 'Status' },
    ]
    const columns = [
        { key: 'sno', slot: 'sno' },
        { key: 'title', slot: 'title' },
        { key: 'authors', slot: 'authors' },
        { key: 'area', slot: 'area' },
        { key: 'downloads', slot: 'downloads' },
        { key: 'status', slot: 'status' },
    ]

    const { filters, applyFilters, resetFilters } = useFilterSync({
        initialFilters: {
            search: props.filters.search ?? '',
            area: props.filters.area ?? '',
            status: props.filters.status ?? '',
        },
    })
    const statusOptions = [{ value: 1, label: 'Published' }, { value: 0, label: 'Draft' }]

    function toggleStatus(p: ResearchPaper) {
        router.patch(paperStatus(p.id).url, { status: !p.status }, { preserveScroll: true, onSuccess: () => (p.status = !p.status) })
    }

    const toDelete = ref<ResearchPaper | null>(null)
    const deleting = ref(false)
    function confirmDelete() {
        if (!toDelete.value) return
        deleting.value = true
        router.delete(paperDestroy(toDelete.value.id).url, { preserveScroll: true, onFinish: () => { deleting.value = false; toDelete.value = null } })
    }
</script>

<template>
        <AppTableLayout title="Research Papers" :headers="headers" :columns="columns" :data="papers.data"
            :total="papers.total" :per-page="papers.per_page" :current-page="papers.current_page"
            :last-page="papers.last_page" :prev-page-url="papers.prev_page_url" :next-page-url="papers.next_page_url" primary-key="id">

            <template #header>
                <div class="flex items-center gap-2">
                    <AppButton variant="secondary" size="sm" :href="areasIndex().url">Areas</AppButton>
                    <AppButton variant="add" size="sm" :href="paperCreate().url">
                        <template #icon-left><PlusIcon class="w-3" /></template>
                        New Paper
                    </AppButton>
                </div>
            </template>

            <template #filter>
                <AppFilterLayout :filters="filters" @apply="applyFilters" @reset="resetFilters">
                    <template #search>
                        <FilterInput v-model="filters.search" :field="{ placeholder: 'Search title…' }" />
                    </template>
                    <template #inline-filters>
                        <FilterSelect v-model="filters.area" :field="{ placeholder: 'All areas' }"
                            :options="areas.map(a => ({ value: a.id, label: a.name }))" />
                        <FilterSelect v-model="filters.status" :field="{ placeholder: 'All status' }" :options="statusOptions" />
                    </template>
                </AppFilterLayout>
            </template>

            <template #sno="{ index }">{{ (papers.current_page - 1) * papers.per_page + index + 1 }}</template>
            <template #title="{ data }">
                <Link :href="paperEdit(data.id).url" class="text-[#e0006c] hover:underline font-medium">{{ data.title }}</Link>
            </template>
            <template #authors="{ data }"><span class="text-gray-500 dark:text-gray-400">{{ data.authors || '—' }}</span></template>
            <template #area="{ data }">{{ data.area ? data.area.name : '—' }}</template>
            <template #downloads="{ data }">{{ data.download_count ?? 0 }}</template>
            <template #status="{ data }">
                <AppToggle :model-value="!!data.status" @update:modelValue="toggleStatus(data)" true-label="Published" false-label="Draft" />
            </template>
            <template #threedot="{ data }">
                <AppThreeDotOptions :actions="[
                    { label: 'Edit', icon: Edit, href: paperEdit(data.id).url },
                    { label: 'Delete', icon: Trash, danger: true, action: () => (toDelete = data) },
                ]" />
            </template>
        </AppTableLayout>

        <AppConfirmDialog :open="!!toDelete" title="Delete paper?" :description="`This permanently removes “${toDelete?.title}” and its file.`"
            danger confirm-text="Delete" :loading="deleting" @confirm="confirmDelete" @cancel="toDelete = null" />
</template>
