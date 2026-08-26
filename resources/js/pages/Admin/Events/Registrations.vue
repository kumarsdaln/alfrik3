<script setup>
    import { Head } from '@inertiajs/vue3'

    import AppTableLayout from '@/layouts/dashboard/AppTableLayout.vue'
    import AppButton from '@/components/ui/AppButton.vue'
    import AppBadge from '@/components/ui/AppBadge.vue'
    import Avatar from '@/components/profile/Avatar.vue'

    const props = defineProps({
        event: { type: Object, required: true },
        registrations: { type: Array, default: () => [] },
    })

    const headers = [
        { key: 'attendee', label: 'Attendee' },
        { key: 'role', label: 'Role' },
    ]

    const columns = [
        { key: 'attendee', slot: 'attendee' },
        { key: 'role', slot: 'role' },
    ]
</script>

<template>

    <Head title="Registrations" />

    <AppTableLayout :title="`${event.title} — Registrations`" :headers="headers" :columns="columns"
        :data="registrations" :sticky-actions="false" primary-key="id">
        <template #header>
            <div class="flex items-center gap-3">
                <span
                    class="inline-flex items-center gap-1.5 rounded-full border border-gray-200 bg-white px-3 py-1 text-xs text-gray-500 dark:border-white/10 dark:bg-white/[0.03] dark:text-gray-400">
                    Registered
                    <span class="font-semibold text-gray-900 dark:text-white">{{ registrations.length }}/{{
                        event.max_attendees || '∞' }}</span>
                </span>
                <AppButton variant="back" size="sm" :href="route('admin.events.index')">Back to events</AppButton>
            </div>
        </template>

        <template #attendee="{ data }">
            <div class="flex items-center gap-3">
                <Avatar :image="data.profile_image" :name="data.name" size="h-9 w-9" />
                <div class="min-w-0">
                    <div class="truncate font-medium text-gray-900 dark:text-white">{{ data.name }}</div>
                    <div class="truncate text-xs text-gray-500 dark:text-gray-400">{{ data.email }}</div>
                </div>
            </div>
        </template>
        <template #role="{ data }">
            <AppBadge variant="info" size="sm"><span class="capitalize">{{ data.role }}</span></AppBadge>
        </template>
    </AppTableLayout>
</template>
