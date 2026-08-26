<script setup>
    import { Head, router } from '@inertiajs/vue3'
    import { Pencil, Users, Trash2 } from '@lucide/vue'

    import AppTableLayout from '@/layouts/dashboard/AppTableLayout.vue'
    import AppFilterLayout from '@/components/filters/layout/AppFilterLayout.vue'
    import FilterInput from '@/components/filters/fields/FilterInput.vue'
    import FilterSelect from '@/components/filters/fields/FilterSelect.vue'
    import AppButton from '@/components/ui/AppButton.vue'
    import AppBadge from '@/components/ui/AppBadge.vue'
    import AppThreeDotOptions from '@/components/ui/AppThreeDotOptions.vue'
    import PlusIcon from '@/Icons/PlusIcon.vue'
    import useFilterSync from '@/composables/useFilterSync'

    const props = defineProps({
        events: { type: Object, default: () => ({ data: [] }) },
        filters: { type: Object, default: () => ({ search: '', status: '' }) },
        statuses: { type: Array, default: () => [] },
    })

    const headers = [
        { key: 'title', label: 'Title' },
        { key: 'status', label: 'Status' },
        { key: 'start', label: 'Start' },
        { key: 'city', label: 'City' },
        { key: 'attendees', label: 'Attendees' },
    ]

    const columns = [
        { key: 'title', slot: 'title' },
        { key: 'status', slot: 'status' },
        { key: 'start', slot: 'start' },
        { key: 'city', slot: 'city' },
        { key: 'attendees', slot: 'attendees' },
    ]

    const { filters, applyFilters, resetFilters } = useFilterSync({
        initialFilters: {
            search: props.filters?.search ?? '',
            status: props.filters?.status ?? '',
        },
    })

    const statusOptions = props.statuses.map((s) => ({ value: s, label: s }))

    const statusVariant = (value) =>
        ({ published: 'success', draft: 'default', cancelled: 'danger' }[value] ?? 'default')

    const formatDate = (value) =>
        value ? new Date(value).toLocaleDateString('en-IN', { day: '2-digit', month: 'short', year: 'numeric' }) : '—'

    const destroy = (event) => {
        if (!window.confirm(`Delete event "${event.title}"? This cannot be undone.`)) return
        router.delete(route('admin.events.destroy', event.id), { preserveScroll: true })
    }

    const rowActions = (data) => [
        { label: 'Edit event', icon: Pencil, href: route('admin.events.edit', data.id) },
        { label: 'Registrations', icon: Users, href: route('admin.events.registrations', data.id) },
        { label: 'Delete', icon: Trash2, danger: true, action: () => destroy(data) },
    ]
</script>

<template>
    <Head title="Events" />

    <AdminLayout>
        <AppTableLayout
            title="Events"
            :headers="headers"
            :columns="columns"
            :data="events.data"
            :total="events.total"
            :per-page="events.per_page"
            :current-page="events.current_page"
            :last-page="events.last_page"
            :prev-page-url="events.prev_page_url"
            :next-page-url="events.next_page_url"
            primary-key="id"
        >
            <template #header>
                <AppButton variant="add" size="sm" :href="route('admin.events.create')">
                    <template #icon-left>
                        <PlusIcon class="w-3" />
                    </template>
                    New event
                </AppButton>
            </template>

            <template #filter>
                <AppFilterLayout :filters="filters" @apply="applyFilters" @reset="resetFilters">
                    <template #search>
                        <FilterInput v-model="filters.search" :field="{ placeholder: 'Search events by title' }" />
                    </template>
                    <template #inline-filters>
                        <FilterSelect v-model="filters.status" :field="{ placeholder: 'All statuses' }" :options="statusOptions" />
                    </template>
                </AppFilterLayout>
            </template>

            <template #title="{ data }">
                <div class="font-medium text-gray-900 dark:text-white">{{ data.title }}</div>
                <div class="text-xs capitalize text-gray-500 dark:text-gray-400">{{ data.event_type }}</div>
            </template>
            <template #status="{ data }">
                <AppBadge :variant="statusVariant(data.status)" size="sm"><span class="capitalize">{{ data.status }}</span></AppBadge>
            </template>
            <template #start="{ data }">{{ formatDate(data.start_date) }}</template>
            <template #city="{ data }">{{ data.city || '—' }}</template>
            <template #attendees="{ data }">
                <span class="tabular-nums">{{ data.participants_count ?? 0 }}/{{ data.max_attendees || '∞' }}</span>
            </template>

            <template #threedot="{ data }">
                <AppThreeDotOptions :actions="rowActions(data)" />
            </template>
        </AppTableLayout>
    </AdminLayout>
</template>
