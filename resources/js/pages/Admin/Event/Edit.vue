<script setup lang="ts">
import { ref } from 'vue'
import AppFormControl from '@/components/form/AppFormControl.vue'
import AppInput from '@/components/form/AppInput.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import AppTextArea from '@/components/form/AppTextArea.vue'
import AppMultiSelect from '@/components/form/AppMultiSelect.vue'
import { Button } from '@/components/ui/button'
import { Form } from '@inertiajs/vue3'
import { update } from '@/actions/App/Http/Controllers/Admin/Event/EventController'

interface Category {
    id: number
    name: string
    slug: string
}

interface Event {
    id: number
    title: string
    slug: string
    description: string | null

    event_type: 'in_person' | 'online' | 'hybrid'
    visibility: 'public' | 'private'
    status: 'draft' | 'published' | 'cancelled'

    start_date: string
    end_date: string | null

    location_name: string | null
    address: string | null
    city: string | null
    state: string | null
    country: string | null
    meeting_url: string | null

    banner: string | null
    max_attendees: number | null

    categories: Category[]
}

interface Props {
    event: Event
    categories: Category[]
}

const props = defineProps<Props>()

/*
|--------------------------------------------------------------------------
| Form State
|--------------------------------------------------------------------------
*/

const title = ref(props.event.title)

const slug = ref(props.event.slug)

const description = ref(
    props.event.description ?? ''
)

const eventType = ref(props.event.event_type)

const visibility = ref(props.event.visibility)

const status = ref(props.event.status)

const categoryIds = ref<number[]>(
    props.event.categories.map(category => category.id)
)

const startDate = ref(
    formatDateTime(props.event.start_date)
)

const endDate = ref(
    props.event.end_date
        ? formatDateTime(props.event.end_date)
        : ''
)

const locationName = ref(
    props.event.location_name ?? ''
)

const address = ref(
    props.event.address ?? ''
)

const city = ref(
    props.event.city ?? ''
)

const state = ref(
    props.event.state ?? ''
)

const country = ref(
    props.event.country ?? ''
)

const meetingUrl = ref(
    props.event.meeting_url ?? ''
)

const maxAttendees = ref(
    props.event.max_attendees !== null
        ? String(props.event.max_attendees)
        : ''
)

/*
|--------------------------------------------------------------------------
| Helpers
|--------------------------------------------------------------------------
*/

function formatDateTime(value: string): string {
    const date = new Date(value)

    const pad = (number: number) =>
        String(number).padStart(2, '0')

    return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}T${pad(date.getHours())}:${pad(date.getMinutes())}`
}
</script>

<template>
    <div class="mx-auto max-w-6xl px-6 py-10 lg:px-8">

        <!-- ========================================================= -->
        <!-- HEADER -->
        <!-- ========================================================= -->

        <div class="mb-10">
            <p
                class="mb-2 text-xs font-semibold uppercase tracking-[0.18em] text-primary"
            >
                Events
            </p>

            <h1 class="font-redhat text-3xl font-semibold tracking-tight">
                Edit Event
            </h1>

            <p
                class="mt-2 max-w-2xl text-sm text-content-light/60 dark:text-content-dark/60"
            >
                Update the core information about this event. Sessions,
                participants, tickets and media can be configured separately.
            </p>
        </div>


        <!-- ========================================================= -->
        <!-- FORM -->
        <!-- ========================================================= -->

        <Form
            v-bind="update.form(event.id)"
            method="put"
            enctype="multipart/form-data"
            class="space-y-10"
            v-slot="{ errors, processing }"
        >

            <!-- ===================================================== -->
            <!-- BASIC INFORMATION -->
            <!-- ===================================================== -->

            <section class="space-y-6">

                <div
                    class="border-b border-border-light pb-4 dark:border-border-dark"
                >
                    <h2 class="font-redhat text-lg font-semibold">
                        Basic Information
                    </h2>

                    <p
                        class="mt-1 text-sm text-content-light/60 dark:text-content-dark/60"
                    >
                        The essential information about the event.
                    </p>
                </div>


                <!-- Title -->

                <AppFormControl
                    label="Title"
                    required
                    :error="errors.title"
                >
                    <AppInput
                        v-model="title"
                        name="title"
                        placeholder="Enter event title"
                    />
                </AppFormControl>


                <!-- Slug -->

                <AppFormControl
                    label="Slug"
                    :error="errors.slug"
                >
                    <AppInput
                        v-model="slug"
                        name="slug"
                        placeholder="event-slug"
                    />
                </AppFormControl>


                <!-- Description -->

                <AppFormControl
                    label="Description"
                    :error="errors.description"
                >
                    <AppTextArea
                        v-model="description"
                        name="description"
                        :rows="7"
                        placeholder="Describe the event..."
                    />
                </AppFormControl>


                <!-- Event Type / Visibility -->

                <div class="grid gap-6 md:grid-cols-2">

                    <AppFormControl
                        label="Event Type"
                        required
                        :error="errors.event_type"
                    >
                        <AppSelect
                            v-model="eventType"
                            name="event_type"
                            :options="[
                                {
                                    label: 'In Person',
                                    value: 'in_person',
                                },
                                {
                                    label: 'Online',
                                    value: 'online',
                                },
                                {
                                    label: 'Hybrid',
                                    value: 'hybrid',
                                },
                            ]"
                        />
                    </AppFormControl>


                    <AppFormControl
                        label="Visibility"
                        required
                        :error="errors.visibility"
                    >
                        <AppSelect
                            v-model="visibility"
                            name="visibility"
                            :options="[
                                {
                                    label: 'Public',
                                    value: 'public',
                                },
                                {
                                    label: 'Private',
                                    value: 'private',
                                },
                            ]"
                        />
                    </AppFormControl>

                </div>

            </section>


            <!-- ===================================================== -->
            <!-- CATEGORIES -->
            <!-- ===================================================== -->

            <section class="space-y-6">

                <div
                    class="border-b border-border-light pb-4 dark:border-border-dark"
                >
                    <h2 class="font-redhat text-lg font-semibold">
                        Categories
                    </h2>

                    <p
                        class="mt-1 text-sm text-content-light/60 dark:text-content-dark/60"
                    >
                        Associate the event with one or more categories.
                    </p>
                </div>


                <AppMultiSelect
                    v-model="categoryIds"
                    name="category_ids"
                    :options="
                        categories.map(category => ({
                            value: category.id,
                            label: category.name,
                        }))
                    "
                    placeholder="Select categories"
                />


                <p
                    v-if="errors.category_ids"
                    class="text-sm text-red-600"
                >
                    {{ errors.category_ids }}
                </p>

            </section>


            <!-- ===================================================== -->
            <!-- SCHEDULE -->
            <!-- ===================================================== -->

            <section class="space-y-6">

                <div
                    class="border-b border-border-light pb-4 dark:border-border-dark"
                >
                    <h2 class="font-redhat text-lg font-semibold">
                        Schedule
                    </h2>

                    <p
                        class="mt-1 text-sm text-content-light/60 dark:text-content-dark/60"
                    >
                        Set when the event starts and ends.
                    </p>
                </div>


                <div class="grid gap-6 md:grid-cols-2">

                    <!-- Start -->

                    <AppFormControl
                        label="Start Date"
                        required
                        :error="errors.start_date"
                    >
                        <AppInput
                            v-model="startDate"
                            name="start_date"
                            type="datetime-local"
                        />
                    </AppFormControl>


                    <!-- End -->

                    <AppFormControl
                        label="End Date"
                        :error="errors.end_date"
                    >
                        <AppInput
                            v-model="endDate"
                            name="end_date"
                            type="datetime-local"
                        />
                    </AppFormControl>

                </div>

            </section>


            <!-- ===================================================== -->
            <!-- LOCATION -->
            <!-- ===================================================== -->

            <section class="space-y-6">

                <div
                    class="border-b border-border-light pb-4 dark:border-border-dark"
                >
                    <h2 class="font-redhat text-lg font-semibold">
                        Location
                    </h2>

                    <p
                        class="mt-1 text-sm text-content-light/60 dark:text-content-dark/60"
                    >
                        Provide the physical or online location details.
                    </p>
                </div>


                <div class="grid gap-6 md:grid-cols-2">

                    <!-- Location Name -->

                    <AppFormControl
                        label="Location Name"
                        :error="errors.location_name"
                    >
                        <AppInput
                            v-model="locationName"
                            name="location_name"
                            placeholder="Conference venue"
                        />
                    </AppFormControl>


                    <!-- Address -->

                    <AppFormControl
                        label="Address"
                        :error="errors.address"
                    >
                        <AppInput
                            v-model="address"
                            name="address"
                            placeholder="Full address"
                        />
                    </AppFormControl>


                    <!-- City -->

                    <AppFormControl
                        label="City"
                        :error="errors.city"
                    >
                        <AppInput
                            v-model="city"
                            name="city"
                            placeholder="City"
                        />
                    </AppFormControl>


                    <!-- State -->

                    <AppFormControl
                        label="State"
                        :error="errors.state"
                    >
                        <AppInput
                            v-model="state"
                            name="state"
                            placeholder="State"
                        />
                    </AppFormControl>


                    <!-- Country -->

                    <AppFormControl
                        label="Country"
                        :error="errors.country"
                    >
                        <AppInput
                            v-model="country"
                            name="country"
                            placeholder="Country"
                        />
                    </AppFormControl>


                    <!-- Meeting URL -->

                    <AppFormControl
                        label="Meeting URL"
                        :error="errors.meeting_url"
                    >
                        <AppInput
                            v-model="meetingUrl"
                            name="meeting_url"
                            type="url"
                            placeholder="https://..."
                        />
                    </AppFormControl>

                </div>

            </section>


            <!-- ===================================================== -->
            <!-- CAPACITY -->
            <!-- ===================================================== -->

            <section class="space-y-6">

                <div
                    class="border-b border-border-light pb-4 dark:border-border-dark"
                >
                    <h2 class="font-redhat text-lg font-semibold">
                        Capacity
                    </h2>

                    <p
                        class="mt-1 text-sm text-content-light/60 dark:text-content-dark/60"
                    >
                        Control how many attendees can register.
                    </p>
                </div>


                <AppFormControl
                    label="Maximum Attendees"
                    :error="errors.max_attendees"
                >
                    <AppInput
                        v-model="maxAttendees"
                        name="max_attendees"
                        type="number"
                        min="1"
                        placeholder="Leave empty for unlimited"
                    />
                </AppFormControl>

            </section>


            <!-- ===================================================== -->
            <!-- MEDIA -->
            <!-- ===================================================== -->

            <section class="space-y-6">

                <div
                    class="border-b border-border-light pb-4 dark:border-border-dark"
                >
                    <h2 class="font-redhat text-lg font-semibold">
                        Media
                    </h2>

                    <p
                        class="mt-1 text-sm text-content-light/60 dark:text-content-dark/60"
                    >
                        Replace the current event banner if needed.
                    </p>
                </div>


                <!-- Current Banner -->

                <div
                    v-if="event.banner"
                    class="overflow-hidden border border-border-light dark:border-border-dark"
                >
                    <img
                        :src="`/storage/${event.banner}`"
                        alt="Current event banner"
                        class="h-64 w-full object-cover"
                    />
                </div>


                <!-- New Banner -->

                <AppFormControl
                    label="Replace Banner"
                    :error="errors.banner"
                >
                    <AppInput
                        name="banner"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                    />
                </AppFormControl>


                <p
                    class="text-xs text-content-light/50 dark:text-content-dark/50"
                >
                    Leave this empty to keep the current banner.
                </p>

            </section>


            <!-- ===================================================== -->
            <!-- PUBLISHING -->
            <!-- ===================================================== -->

            <section class="space-y-6">

                <div
                    class="border-b border-border-light pb-4 dark:border-border-dark"
                >
                    <h2 class="font-redhat text-lg font-semibold">
                        Publishing
                    </h2>

                    <p
                        class="mt-1 text-sm text-content-light/60 dark:text-content-dark/60"
                    >
                        Control the current publishing state of the event.
                    </p>
                </div>


                <AppFormControl
                    label="Status"
                    required
                    :error="errors.status"
                >
                    <AppSelect
                        v-model="status"
                        name="status"
                        :options="[
                            {
                                label: 'Draft',
                                value: 'draft',
                            },
                            {
                                label: 'Published',
                                value: 'published',
                            },
                            {
                                label: 'Cancelled',
                                value: 'cancelled',
                            },
                        ]"
                    />
                </AppFormControl>

            </section>


            <!-- ===================================================== -->
            <!-- ACTIONS -->
            <!-- ===================================================== -->

            <div
                class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark"
            >

                <Button
                    type="button"
                    variant="outline"
                    as-child
                >
                    <a href="/admin/events">
                        Cancel
                    </a>
                </Button>


                <Button
                    type="submit"
                    :disabled="processing"
                >
                    {{ processing ? 'Updating...' : 'Update Event' }}
                </Button>

            </div>

        </Form>

    </div>
</template>