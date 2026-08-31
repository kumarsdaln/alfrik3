<script setup>
    import { Head, useForm } from '@inertiajs/vue3'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppButton from '@/components/ui/AppButton.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'

    const props = defineProps({
        event: {
            type: Object,
            required: true,
        },
        types: {
            type: Array,
            default: () => [],
        },
        visibilities: {
            type: Array,
            default: () => [],
        },
        statuses: {
            type: Array,
            default: () => [],
        },
    })

    function toOptions(values) {
        return (values ?? []).map((value) => ({ label: value, value }))
    }

    // Format an ISO / date string into the "YYYY-MM-DDTHH:mm" shape
    // expected by <input type="datetime-local">.
    function toDateTimeLocal(value) {
        if (!value) {
            return ''
        }

        const date = new Date(value)

        if (Number.isNaN(date.getTime())) {
            return ''
        }

        const pad = (n) => String(n).padStart(2, '0')

        return (
            `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}` +
            `T${pad(date.getHours())}:${pad(date.getMinutes())}`
        )
    }

    const form = useForm({
        title: props.event.title ?? '',
        description: props.event.description ?? '',
        event_type: props.event.event_type ?? props.types?.[0] ?? '',
        visibility: props.event.visibility ?? 'public',
        status: props.event.status ?? 'draft',
        start_date: toDateTimeLocal(props.event.start_date),
        end_date: toDateTimeLocal(props.event.end_date),
        location_name: props.event.location_name ?? '',
        address: props.event.address ?? '',
        city: props.event.city ?? '',
        state: props.event.state ?? '',
        country: props.event.country ?? '',
        meeting_url: props.event.meeting_url ?? '',
        max_attendees: props.event.max_attendees ?? null,
    })

    function submit() {
        form.post(route('admin.events.update', props.event.id))
    }
</script>

<template>

    <Head title="Edit event" />

    <form class="mx-auto max-w-4xl space-y-6 p-4 sm:p-6" @submit.prevent="submit">
        <!-- HEADER -->
        <div>
            <AppHeading font="redhat" weight="bold" size="lg">
                Edit event
            </AppHeading>
            <AppText color="muted" size="sm" class="mt-1">
                Update the event schedule, location and settings.
            </AppText>
        </div>

        <!-- DETAILS -->
        <div
            class="space-y-5 rounded-2xl border border-border-light bg-surface-light p-6 shadow-sm dark:border-border-dark dark:bg-surface-dark">
            <AppInput v-model="form.title" name="title" label="Title" placeholder="Event title" required
                :error="form.errors.title" />

            <AppTextarea v-model="form.description" name="description" label="Description"
                placeholder="What this event is about…" :error="form.errors.description" />

            <div class="grid gap-4 sm:grid-cols-2">
                <AppSelect v-model="form.event_type" name="event_type" label="Event type" :options="toOptions(types)"
                    :error="form.errors.event_type" />
                <AppSelect v-model="form.visibility" name="visibility" label="Visibility"
                    :options="toOptions(visibilities)" :error="form.errors.visibility" />
                <AppSelect v-model="form.status" name="status" label="Status" :options="toOptions(statuses)"
                    :error="form.errors.status" />
                <AppInput v-model="form.max_attendees" name="max_attendees" type="number" label="Max attendees"
                    placeholder="Leave empty for unlimited" :error="form.errors.max_attendees" />
                <AppInput v-model="form.start_date" name="start_date" type="datetime-local" label="Start date"
                    :error="form.errors.start_date" />
                <AppInput v-model="form.end_date" name="end_date" type="datetime-local" label="End date"
                    :error="form.errors.end_date" />
            </div>
        </div>

        <!-- LOCATION -->
        <div
            class="space-y-5 rounded-2xl border border-border-light bg-surface-light p-6 shadow-sm dark:border-border-dark dark:bg-surface-dark">
            <AppHeading font="redhat" weight="bold" size="sm" tag="h3">
                Location
            </AppHeading>

            <AppInput v-model="form.location_name" name="location_name" label="Location name" placeholder="Venue name"
                :error="form.errors.location_name" />
            <AppInput v-model="form.address" name="address" label="Address" placeholder="Street address"
                :error="form.errors.address" />

            <div class="grid gap-4 sm:grid-cols-3">
                <AppInput v-model="form.city" name="city" label="City" :error="form.errors.city" />
                <AppInput v-model="form.state" name="state" label="State" :error="form.errors.state" />
                <AppInput v-model="form.country" name="country" label="Country" :error="form.errors.country" />
            </div>

            <AppInput v-model="form.meeting_url" name="meeting_url" type="url" label="Meeting URL"
                placeholder="https://…" :error="form.errors.meeting_url" />
        </div>

        <!-- FOOTER -->
        <div class="flex justify-end gap-3">
            <AppButton variant="cancel" :href="route('admin.events.index')">Cancel</AppButton>
            <AppButton type="submit" variant="submit" :loading="form.processing" :disabled="form.processing">
                Save changes
            </AppButton>
        </div>
    </form>
</template>
