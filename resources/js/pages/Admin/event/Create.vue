<script setup lang="ts">
    import {
        AppFormControl,
        AppInput,
        AppTextarea,
        AppSelect,
    } from '@/components/form'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'

    import { index, store } from '@/routes/admin/events'

    import type { FormOption } from '@/types'

    import { Form, Link } from '@inertiajs/vue3'

    interface Props {
        typeOptions: FormOption[]
        visibilityOptions: FormOption[]
        statusOptions: FormOption[]
    }

    defineProps<Props>()
</script>

<template>
    <div class="space-y-6 py-5">
        <div class="flex gap-4">
            <BackButton />

            <Heading title="Create Event"
                description="Create an event with basic information. Sessions, participants, tickets, and other details can be managed after creation." />
        </div>

        <Form v-slot="{ errors, processing }" v-bind="store.form()" class="space-y-4">
            <AppFormControl label="Title" required :error="errors.title">
                <AppInput name="title" placeholder="Enter event title" />
            </AppFormControl>

            <AppFormControl label="Slug" required :error="errors.slug">
                <AppInput name="slug" placeholder="Enter event slug" />
            </AppFormControl>

            <AppFormControl label="Description" :error="errors.description">
                <AppTextarea name="description" placeholder="Enter event description" />
            </AppFormControl>

            <AppFormControl label="Event Type" required :error="errors.event_type">
                <AppSelect name="event_type" placeholder="Select event type" :options="typeOptions" />
            </AppFormControl>

            <AppFormControl label="Visibility" required :error="errors.visibility">
                <AppSelect name="visibility" placeholder="Select event visibility" :options="visibilityOptions" />
            </AppFormControl>

            <AppFormControl label="Start Date" required :error="errors.start_date">
                <AppInput name="start_date" type="datetime-local" />
            </AppFormControl>

            <AppFormControl label="End Date" :error="errors.end_date">
                <AppInput name="end_date" type="datetime-local" />
            </AppFormControl>

            <AppFormControl label="Location Name" :error="errors.location_name">
                <AppInput name="location_name" placeholder="Enter venue or platform name" />
            </AppFormControl>

            <AppFormControl label="Address" :error="errors.address">
                <AppInput name="address" placeholder="Enter event address" />
            </AppFormControl>

            <div class="grid gap-4 sm:grid-cols-3">
                <AppFormControl label="City" :error="errors.city">
                    <AppInput name="city" placeholder="Enter city" />
                </AppFormControl>

                <AppFormControl label="State" :error="errors.state">
                    <AppInput name="state" placeholder="Enter state" />
                </AppFormControl>

                <AppFormControl label="Country" :error="errors.country">
                    <AppInput name="country" placeholder="Enter country" />
                </AppFormControl>
            </div>

            <AppFormControl label="Meeting URL" :error="errors.meeting_url">
                <AppInput name="meeting_url" type="url" placeholder="Enter online meeting URL" />
            </AppFormControl>

            <AppFormControl label="Maximum Attendees" :error="errors.max_attendees">
                <AppInput name="max_attendees" type="number" min="1"
                    placeholder="Leave empty for unlimited attendees" />
            </AppFormControl>

            <AppFormControl label="Event Status" required :error="errors.status">
                <AppSelect name="status" placeholder="Select event status" :options="statusOptions" />
            </AppFormControl>

            <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
                <Button type="button" variant="outline" as-child>
                    <Link :href="index.url()">
                        Cancel
                    </Link>
                </Button>

                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Processing...' : 'Save Event' }}
                </Button>
            </div>
        </Form>
    </div>
</template>
