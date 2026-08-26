<script setup lang="ts">
    import { Head, router } from '@inertiajs/vue3'
    import { Pencil, Eye, EyeOff, Trash2 } from '@lucide/vue'
    import type { Sponsor } from '@/types/sponsor'

    import AppTableLayout from '@/layouts/dashboard/AppTableLayout.vue'
    import AppFilterLayout from '@/components/filters/layout/AppFilterLayout.vue'
    import FilterInput from '@/components/filters/fields/FilterInput.vue'
    import FilterSelect from '@/components/filters/fields/FilterSelect.vue'
    import AppButton from '@/components/ui/AppButton.vue'
    import AppBadge from '@/components/ui/AppBadge.vue'
    import AppThreeDotOptions from '@/components/ui/AppThreeDotOptions.vue'
    import PlusIcon from '@/Icons/PlusIcon.vue'
    import useFilterSync from '@/composables/useFilterSync'

    interface Paginated<T> {
        data: T[]
        total?: number
        per_page?: number
        current_page?: number
        last_page?: number
        prev_page_url?: string | null
        next_page_url?: string | null
    }

    const props = withDefaults(defineProps<{
        sponsors?: Paginated<Sponsor>
        filters?: { search?: string; tier?: string }
        tiers?: string[]
    }>(), {
        sponsors: () => ({ data: [] }),
        filters: () => ({ search: '', tier: '' }),
        tiers: () => [],
    })

    const headers = [
        { key: 'name', label: 'Sponsor' },
        { key: 'tier', label: 'Tier' },
        { key: 'website', label: 'Website' },
        { key: 'order', label: 'Order' },
        { key: 'status', label: 'Status' },
    ]
    const columns = [
        { key: 'name', slot: 'name' },
        { key: 'tier', slot: 'tier' },
        { key: 'website', slot: 'website' },
        { key: 'order', slot: 'order' },
        { key: 'status', slot: 'status' },
    ]

    const { filters, applyFilters, resetFilters } = useFilterSync({
        initialFilters: {
            search: props.filters?.search ?? '',
            tier: props.filters?.tier ?? '',
        },
    })

    const tierOptions = props.tiers.map((t) => ({ value: t, label: t.charAt(0).toUpperCase() + t.slice(1) }))

    const tierVariant = (t: string) =>
        (({ platinum: 'info', gold: 'warning', silver: 'default', bronze: 'default', partner: 'default' } as Record<string, 'info' | 'warning' | 'default'>)[t] ?? 'default')

    const toggle = (s: Sponsor) =>
        router.post(route('admin.sponsors.toggle', s.id), {}, { preserveScroll: true })

    const destroy = (s: Sponsor) => {
        if (!window.confirm(`Delete sponsor "${s.name}"? This cannot be undone.`)) return
        router.delete(route('admin.sponsors.destroy', s.id), { preserveScroll: true })
    }

    const rowActions = (data: Sponsor) => [
        { label: 'Edit', icon: Pencil, href: route('admin.sponsors.edit', data.id) },
        {
            label: data.is_active ? 'Deactivate' : 'Activate',
            icon: data.is_active ? EyeOff : Eye,
            action: () => toggle(data),
        },
        { label: 'Delete', icon: Trash2, danger: true, action: () => destroy(data) },
    ]
</script>

<template>
    <Head title="Sponsors" />
        <AppTableLayout
            title="Sponsors"
            :headers="headers"
            :columns="columns"
            :data="sponsors.data"
            :total="sponsors.total"
            :per-page="sponsors.per_page"
            :current-page="sponsors.current_page"
            :last-page="sponsors.last_page"
            :prev-page-url="sponsors.prev_page_url"
            :next-page-url="sponsors.next_page_url"
            primary-key="id"
        >
            <template #header>
                <AppButton variant="add" size="sm" :href="route('admin.sponsors.create')">
                    <template #icon-left>
                        <PlusIcon class="w-3" />
                    </template>
                    New sponsor
                </AppButton>
            </template>

            <template #filter>
                <AppFilterLayout :filters="filters" @apply="applyFilters" @reset="resetFilters">
                    <template #search>
                        <FilterInput v-model="filters.search" :field="{ placeholder: 'Search sponsors by name' }" />
                    </template>
                    <template #inline-filters>
                        <FilterSelect v-model="filters.tier" :field="{ placeholder: 'All tiers' }" :options="tierOptions" />
                    </template>
                </AppFilterLayout>
            </template>

            <template #name="{ data }">
                <div class="flex items-center gap-3">
                    <div class="grid h-10 w-10 shrink-0 place-items-center overflow-hidden rounded-lg border border-gray-100 bg-gray-50 dark:border-gray-800 dark:bg-gray-800">
                        <img v-if="data.logo_url" :src="data.logo_url" alt="" class="h-full w-full object-contain" />
                        <span v-else class="text-sm font-bold text-gray-300">{{ data.name?.charAt(0) }}</span>
                    </div>
                    <span class="font-medium text-gray-900 dark:text-white">{{ data.name }}</span>
                </div>
            </template>
            <template #tier="{ data }">
                <AppBadge :variant="tierVariant(data.tier)" size="sm"><span class="capitalize">{{ data.tier }}</span></AppBadge>
            </template>
            <template #website="{ data }">
                <a v-if="data.website_url" :href="data.website_url" target="_blank" rel="noopener" class="text-brand hover:underline">Visit</a>
                <span v-else class="text-gray-400">—</span>
            </template>
            <template #order="{ data }"><span class="tabular-nums">{{ data.sort_order }}</span></template>
            <template #status="{ data }">
                <AppBadge :variant="data.is_active ? 'success' : 'default'" size="sm">{{ data.is_active ? 'Active' : 'Hidden' }}</AppBadge>
            </template>

            <template #threedot="{ data }">
                <AppThreeDotOptions :actions="rowActions(data)" />
            </template>
        </AppTableLayout>
</template>
