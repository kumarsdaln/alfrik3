<script setup lang="ts">
    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppTextArea from '@/components/form/AppTextArea.vue'
    import { Button } from '@/components/ui/button'
    import { Form } from '@inertiajs/vue3'
    import { store } from '@/actions/App/Http/Controllers/Admin/Event/EventController'
    import AppMultiSelect from '@/components/form/AppMultiSelect.vue'
    import { EventCategory, EventStatus, EventType, EventVisibility } from '@/types/event'
    import Heading from '@/components/Heading.vue'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'

    interface Props {
        categories: EventCategory[]
        statusOptions: EventStatus[]
        typeOptions: EventType[]
        visibilityOptions: EventVisibility[]
    }

    defineProps<Props>()
</script>

<template>
    <div class="mx-auto max-w-6xl px-6 py-10 lg:px-8">

        <!-- Header -->
        <div class="mb-10">
            <p class="mb-2 text-xs font-semibold uppercase tracking-[0.18em] text-primary">
                Events
            </p>
            
            <AppHeading tag="h1">Create Event</AppHeading>
            <AppText>Create the core event information. Sessions, participants,
                tickets and media can be configured after the event is created.</AppText>
        </div>

        <Form v-bind="store.form()" enctype="multipart/form-data" class="space-y-10" v-slot="{ errors, processing }">

            <!-- ===================================================== -->
            <!-- BASIC INFORMATION -->
            <!-- ===================================================== -->
            <section class="space-y-6">

                <Heading title="Basic Information" description="The essential information about the event." />

                <AppFormControl label="Title" required :error="errors.title">
                    <AppInput name="title" placeholder="Enter event title" />
                </AppFormControl>

                <AppFormControl label="Slug" :error="errors.slug">
                    <AppInput name="slug" placeholder="event-slug" />
                </AppFormControl>

                <AppFormControl label="Description" :error="errors.description">
                    <AppTextArea name="description" :rows="7" placeholder="Describe the event..." />
                </AppFormControl>

                <div class="grid gap-6 md:grid-cols-2">
                    <AppFormControl label="Event Type" required :error="errors.event_type">
                        <AppSelect name="event_type" :options="typeOptions" />
                    </AppFormControl>

                    <AppFormControl label="Visibility" required :error="errors.visibility">
                        <AppSelect name="visibility" :options="visibilityOptions" />
                    </AppFormControl>
                </div>

            </section>


            <!-- ===================================================== -->
            <!-- CATEGORIES -->
            <!-- ===================================================== -->

            <section class="space-y-6">
                <Heading title="Categories" description="Associate the event with one or more categories." />

                <AppMultiSelect name="category_ids" :options="categories.map(category => ({
                    value: category.id,
                    label: category.name,
                }))
                    " placeholder="Select categories" />
            </section>


            <!-- ===================================================== -->
            <!-- SCHEDULE -->
            <!-- ===================================================== -->

            <section class="space-y-6">
                <Heading title="Schedule" />
                <div class="grid gap-6 md:grid-cols-2">
                    <AppFormControl label="Start Date" required :error="errors.start_date">
                        <AppInput name="start_date" type="datetime-local" />
                    </AppFormControl>

                    <AppFormControl label="End Date" :error="errors.end_date">
                        <AppInput name="end_date" type="datetime-local" />
                    </AppFormControl>
                </div>
            </section>


            <!-- ===================================================== -->
            <!-- LOCATION -->
            <!-- ===================================================== -->

            <section class="space-y-6">
                <Heading title="Location" />

                <div class="grid gap-6 md:grid-cols-2">

                    <AppFormControl label="Location Name" :error="errors.location_name">
                        <AppInput name="location_name" placeholder="Conference venue" />
                    </AppFormControl>

                    <AppFormControl label="Address" :error="errors.address">
                        <AppInput name="address" placeholder="Full address" />
                    </AppFormControl>

                    <AppFormControl label="City" :error="errors.city">
                        <AppInput name="city" />
                    </AppFormControl>

                    <AppFormControl label="State" :error="errors.state">
                        <AppInput name="state" />
                    </AppFormControl>

                    <AppFormControl label="Country" :error="errors.country">
                        <AppInput name="country" />
                    </AppFormControl>

                    <AppFormControl label="Meeting URL" :error="errors.meeting_url">
                        <AppInput name="meeting_url" type="url" placeholder="https://..." />
                    </AppFormControl>
                </div>
            </section>


            <!-- ===================================================== -->
            <!-- CAPACITY -->
            <!-- ===================================================== -->

            <section class="space-y-6">
                <Heading title="Capacity" />
                <AppFormControl label="Maximum Attendees" :error="errors.max_attendees">
                    <AppInput name="max_attendees" type="number" min="1" placeholder="Leave empty for unlimited" />
                </AppFormControl>
            </section>


            <!-- ===================================================== -->
            <!-- MEDIA -->
            <!-- ===================================================== -->

            <section class="space-y-6">
                <Heading title="Media" />

                <AppFormControl label="Banner" :error="errors.banner">
                    <AppInput name="banner" type="file" accept="image/jpeg,image/png,image/webp" />
                </AppFormControl>

            </section>


            <!-- ===================================================== -->
            <!-- PUBLISHING -->
            <!-- ===================================================== -->

            <section class="space-y-6">
                <Heading title="Publishing" />

                <AppFormControl label="Status" required :error="errors.status">
                    <AppSelect name="status" :options="statusOptions" />
                </AppFormControl>

            </section>


            <!-- ===================================================== -->
            <!-- ACTIONS -->
            <!-- ===================================================== -->

            <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
                <Button type="button" variant="outline" as-child>
                    <a href="/admin/events">
                        Cancel
                    </a>
                </Button>

                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Creating...' : 'Create Event' }}
                </Button>
            </div>

        </Form>
    </div>
</template>