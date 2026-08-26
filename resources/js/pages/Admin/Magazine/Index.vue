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

    import { create as magazineCreate, edit as magazineEdit, destroy as magazineDestroy } from '@/routes/admin/magazine'
    import { index as categoriesIndex } from '@/routes/admin/magazine/categories'
    import { status as magazineStatus } from '@/routes/admin/magazine/update'
    import type { Magazine, MagazineCategory, MagazineFilters } from '@/types'

    const props = defineProps<{
        magazines: {
            data: Magazine[]
            total: number
            per_page: number
            current_page: number
            last_page: number
            prev_page_url: string | null
            next_page_url: string | null
        }
        categories: MagazineCategory[]
        filters: MagazineFilters
    }>()

    const headers = [
        { key: 'sno', label: '#' },
        { key: 'cover', label: 'Cover' },
        { key: 'title', label: 'Title' },
        { key: 'category', label: 'Category' },
        { key: 'status', label: 'Status' },
        { key: 'created_at', label: 'Created' },
    ]
    const columns = [
        { key: 'sno', slot: 'sno' },
        { key: 'cover', slot: 'cover' },
        { key: 'title', slot: 'title' },
        { key: 'category', slot: 'category' },
        { key: 'status', slot: 'status' },
        { key: 'created_at', type: 'date' },
    ]

    const { filters, applyFilters, resetFilters } = useFilterSync({
        initialFilters: {
            search: props.filters.search ?? '',
            category: props.filters.category ?? '',
            status: props.filters.status ?? '',
        },
    })

    const statusOptions = [{ value: 1, label: 'Published' }, { value: 0, label: 'Draft' }]

    function isScheduled(m: Magazine) {
        return !!m.status && !!m.published_at && new Date(m.published_at) > new Date()
    }

    function toggleStatus(m: Magazine) {
        router.patch(magazineStatus(m.id).url, { status: !m.status }, {
            preserveScroll: true,
            onSuccess: () => (m.status = !m.status),
        })
    }

    // Delete confirmation
    const toDelete = ref<Magazine | null>(null)
    const deleting = ref(false)
    function confirmDelete() {
        if (!toDelete.value) return
        deleting.value = true
        router.delete(magazineDestroy(toDelete.value.id).url, {
            preserveScroll: true,
            onFinish: () => { deleting.value = false; toDelete.value = null },
        })
    }
</script>

<template>
    <AppTableLayout title="Magazine Issues" :headers="headers" :columns="columns" :data="magazines.data"
        :total="magazines.total" :per-page="magazines.per_page" :current-page="magazines.current_page"
        :last-page="magazines.last_page" :prev-page-url="magazines.prev_page_url"
        :next-page-url="magazines.next_page_url" primary-key="id">

        <template #header>
            <div class="flex items-center gap-2">
                <AppButton variant="secondary" size="sm" :href="categoriesIndex().url">Categories</AppButton>
                <AppButton variant="add" size="sm" :href="magazineCreate().url">
                    <template #icon-left>
                        <PlusIcon class="w-3" />
                    </template>
                    New Issue
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
                    <FilterSelect v-model="filters.status" :field="{ placeholder: 'All status' }"
                        :options="statusOptions" />
                </template>
            </AppFilterLayout>
        </template>

        <template #sno="{ index }">
            {{ (magazines.current_page - 1) * magazines.per_page + index + 1 }}
        </template>

        <template #cover="{ data }">
            <img v-if="data.cover_image" :src="data.cover_image" :alt="data.title"
                class="w-10 h-14 object-cover rounded" />
            <div v-else class="w-10 h-14 rounded bg-gray-100 dark:bg-zinc-800"></div>
        </template>

        <template #title="{ data }">
            <Link :href="magazineEdit(data.id).url" class="text-[#e0006c] hover:underline font-medium">
                {{ data.title }}
            </Link>
        </template>

        <template #category="{ data }">
            {{ data.category ? data.category.name : '—' }}
        </template>

        <template #status="{ data }">
            <div class="flex items-center gap-2">
                <AppToggle :model-value="!!data.status" @update:modelValue="toggleStatus(data)" true-label="Published"
                    false-label="Draft" />
                <span v-if="isScheduled(data)"
                    class="whitespace-nowrap px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide bg-amber-100 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300">
                    Scheduled
                </span>
            </div>
        </template>

        <template #threedot="{ data }">
            <AppThreeDotOptions :actions="[
                { label: 'Edit', icon: Edit, href: magazineEdit(data.id).url },
                { label: 'Delete', icon: Trash, danger: true, action: () => (toDelete = data) },
            ]" />
        </template>
    </AppTableLayout>

    <AppConfirmDialog :open="!!toDelete" title="Delete issue?"
        :description="`This permanently removes “${toDelete?.title}”.`" danger confirm-text="Delete" :loading="deleting"
        @confirm="confirmDelete" @cancel="toDelete = null" />
</template>
