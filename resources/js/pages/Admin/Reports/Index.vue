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

    import { create as reportCreate, edit as reportEdit, destroy as reportDestroy } from '@/routes/admin/reports'
    import { index as categoriesIndex } from '@/routes/admin/reports/categories'
    import { status as reportStatus } from '@/routes/admin/reports/update'
    import type { Report, ReportCategory, ReportFilters } from '@/types'

    const props = defineProps<{
        reports: { data: Report[]; total: number; per_page: number; current_page: number; last_page: number; prev_page_url: string | null; next_page_url: string | null }
        categories: ReportCategory[]
        filters: ReportFilters
    }>()

    const headers = [
        { key: 'sno', label: '#' },
        { key: 'cover', label: 'Cover' },
        { key: 'title', label: 'Title' },
        { key: 'category', label: 'Category' },
        { key: 'downloads', label: 'Downloads' },
        { key: 'status', label: 'Status' },
    ]
    const columns = [
        { key: 'sno', slot: 'sno' },
        { key: 'cover', slot: 'cover' },
        { key: 'title', slot: 'title' },
        { key: 'category', slot: 'category' },
        { key: 'downloads', slot: 'downloads' },
        { key: 'status', slot: 'status' },
    ]

    const { filters, applyFilters, resetFilters } = useFilterSync({
        initialFilters: {
            search: props.filters.search ?? '',
            category: props.filters.category ?? '',
            status: props.filters.status ?? '',
        },
    })
    const statusOptions = [{ value: 1, label: 'Published' }, { value: 0, label: 'Draft' }]

    function isScheduled(r: Report) {
        return !!r.status && !!r.published_at && new Date(r.published_at) > new Date()
    }
    function toggleStatus(r: Report) {
        router.patch(reportStatus(r.id).url, { status: !r.status }, { preserveScroll: true, onSuccess: () => (r.status = !r.status) })
    }

    const toDelete = ref<Report | null>(null)
    const deleting = ref(false)
    function confirmDelete() {
        if (!toDelete.value) return
        deleting.value = true
        router.delete(reportDestroy(toDelete.value.id).url, { preserveScroll: true, onFinish: () => { deleting.value = false; toDelete.value = null } })
    }
</script>

<template>
        <AppTableLayout title="Reports" :headers="headers" :columns="columns" :data="reports.data"
            :total="reports.total" :per-page="reports.per_page" :current-page="reports.current_page"
            :last-page="reports.last_page" :prev-page-url="reports.prev_page_url" :next-page-url="reports.next_page_url" primary-key="id">

            <template #header>
                <div class="flex items-center gap-2">
                    <AppButton variant="secondary" size="sm" :href="categoriesIndex().url">Categories</AppButton>
                    <AppButton variant="add" size="sm" :href="reportCreate().url">
                        <template #icon-left><PlusIcon class="w-3" /></template>
                        New Report
                    </AppButton>
                </div>
            </template>

            <template #filter>
                <AppFilterLayout :filters="filters" @apply="applyFilters" @reset="resetFilters">
                    <template #search>
                        <FilterInput v-model="filters.search" :field="{ placeholder: 'Search title…' }" />
                    </template>
                    <template #inline-filters>
                        <FilterSelect v-model="filters.category" :field="{ placeholder: 'All categories' }"
                            :options="categories.map(c => ({ value: c.id, label: c.name }))" />
                        <FilterSelect v-model="filters.status" :field="{ placeholder: 'All status' }" :options="statusOptions" />
                    </template>
                </AppFilterLayout>
            </template>

            <template #sno="{ index }">{{ (reports.current_page - 1) * reports.per_page + index + 1 }}</template>
            <template #cover="{ data }">
                <img v-if="data.cover_image" :src="data.cover_image" :alt="data.title" class="w-12 h-9 object-cover rounded" />
                <div v-else class="w-12 h-9 rounded bg-gray-100 dark:bg-zinc-800"></div>
            </template>
            <template #title="{ data }">
                <Link :href="reportEdit(data.id).url" class="text-[#e0006c] hover:underline font-medium">{{ data.title }}</Link>
                <span v-if="data.gated" class="ml-2 px-1.5 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">GATED</span>
            </template>
            <template #category="{ data }">{{ data.category ? data.category.name : '—' }}</template>
            <template #downloads="{ data }">{{ data.download_count ?? 0 }}</template>
            <template #status="{ data }">
                <div class="flex items-center gap-2">
                    <AppToggle :model-value="!!data.status" @update:modelValue="toggleStatus(data)" true-label="Published" false-label="Draft" />
                    <span v-if="isScheduled(data)" class="whitespace-nowrap px-2 py-0.5 rounded-full text-[10px] font-bold uppercase bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">Scheduled</span>
                </div>
            </template>
            <template #threedot="{ data }">
                <AppThreeDotOptions :actions="[
                    { label: 'Edit', icon: Edit, href: reportEdit(data.id).url },
                    { label: 'Delete', icon: Trash, danger: true, action: () => (toDelete = data) },
                ]" />
            </template>
        </AppTableLayout>

        <AppConfirmDialog :open="!!toDelete" title="Delete report?" :description="`This permanently removes “${toDelete?.title}” and its file.`"
            danger confirm-text="Delete" :loading="deleting" @confirm="confirmDelete" @cancel="toDelete = null" />
</template>
