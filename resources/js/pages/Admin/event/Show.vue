
<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import {
    CalendarDays,
    Clock,
    MapPin,
    Video,
    Users,
    Ticket,
    Pencil,
    Globe,
    LockKeyhole,
    Tag,
} from '@lucide/vue'

import type { Event } from '@/types'
import BackButton from '@/components/ui/BackButton.vue';
import Heading from '@/components/Heading.vue';
import Button from '@/components/ui/button/Button.vue';

import { edit } from '@/routes/admin/events';

const props = defineProps<{
    event: {
        data: Event
    }
}>()

const formatDate = (value: string | null | undefined) => {
    if (!value) return 'Not specified'

    const date = new Date(value)

    if (Number.isNaN(date.getTime())) return value

    return new Intl.DateTimeFormat('en-IN', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(date)
}

const locationText = () =>
    [
        props.event.data.location_name,
        props.event.data.address,
        props.event.data.city,
        props.event.data.state,
        props.event.data.country,
    ]
        .filter(Boolean)
        .join(', ')

const enumStyle = (color: string | null | undefined) => {
    if (!color) return {}

    return {
        color,
        backgroundColor: `${color}15`,
    }
}
</script>

<template>
    <div class="flex items-center justify-between gap-4 py-4">
        <div class="flex gap-4">
            <BackButton />
            <Heading :title="event.data.title" :description="`Event #${event.data.id} ${event.data.slug}`" />
        </div>
        <Button as-child class="gap-2">
            <Link :href="edit(event.data)">
                <Pencil class="size-4" />
                Edit Event
            </Link>
        </Button>
    </div>
    <div class="space-y-6 py-4 sm:py-6">
        <!-- Header -->
        <div class="flex flex-wrap items-start justify-between gap-4">
            <div class="space-y-3">
                <div>
                    <div class="mb-2 flex flex-wrap items-center gap-2">
                        <span
                            class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium"
                            :style="enumStyle(event.data.status.color)"
                        >
                            {{ event.data.status.label }}
                        </span>

                        <span
                            class="inline-flex rounded-full px-2.5 py-1 text-xs font-medium"
                            :style="enumStyle(event.data.event_type.color)"
                        >
                            {{ event.data.event_type.label }}
                        </span>

                        <span
                            class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-medium"
                            :style="enumStyle(event.data.visibility.color)"
                        >
                            <Globe
                                v-if="event.data.visibility.value === 'public'"
                                class="size-3.5"
                            />
                            <LockKeyhole v-else class="size-3.5" />
                            {{ event.data.visibility.label }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary -->
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            <div class=" border border-border bg-card p-4">
                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                    <CalendarDays class="size-4" />
                    Start date
                </div>
                <p class="mt-3 font-medium">
                    {{ formatDate(event.data.start_date) }}
                </p>
            </div>

            <div class=" border border-border bg-card p-4">
                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                    <Clock class="size-4" />
                    End date
                </div>
                <p class="mt-3 font-medium">
                    {{ formatDate(event.data.end_date) }}
                </p>
            </div>

            <div class=" border border-border bg-card p-4">
                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                    <Users class="size-4" />
                    Maximum attendees
                </div>
                <p class="mt-3 font-medium">
                    {{ event.data.max_attendees ?? 'Unlimited' }}
                </p>
            </div>

            <div class=" border border-border bg-card p-4">
                <div class="flex items-center gap-2 text-sm text-muted-foreground">
                    <Ticket class="size-4" />
                    Tickets
                </div>
                <p class="mt-3 font-medium">
                    {{ event.data.tickets?.length ?? 0 }}
                </p>
            </div>
        </div>

        <div class="grid gap-6 xl:grid-cols-[minmax(0,1.6fr)_minmax(280px,1fr)]">
            <!-- Main column -->
            <div class="min-w-0 space-y-6">
                <!-- Event details -->
                <section class=" border border-border bg-card">
                    <div class="border-b border-border px-5 py-4">
                        <h2 class="font-semibold">Event details</h2>
                    </div>

                    <div class="space-y-5 p-5">
                        <div>
                            <h3 class="mb-2 text-sm font-medium">
                                Description
                            </h3>

                            <p
                                v-if="event.data.description"
                                class="whitespace-pre-line text-sm leading-6 text-muted-foreground"
                            >
                                {{ event.data.description }}
                            </p>

                            <p v-else class="text-sm text-muted-foreground">
                                No description provided.
                            </p>
                        </div>

                        <div class="border-t border-border pt-4">
                            <h3 class="mb-2 text-sm font-medium">
                                Categories
                            </h3>

                            <div
                                v-if="event.data.categories?.length"
                                class="flex flex-wrap gap-2"
                            >
                                <span
                                    v-for="category in event.data.categories"
                                    :key="category.id"
                                    class="rounded-md border border-border px-2.5 py-1 text-sm"
                                >
                                    {{ category.name }}
                                </span>
                            </div>

                            <p v-else class="text-sm text-muted-foreground">
                                No categories assigned.
                            </p>
                        </div>

                        <div class="border-t border-border pt-4">
                            <h3 class="mb-2 flex items-center gap-2 text-sm font-medium">
                                <Tag class="size-4" />
                                Tags
                            </h3>

                            <div
                                v-if="event.data.tags?.length"
                                class="flex flex-wrap gap-2"
                            >
                                <span
                                    v-for="tag in event.data.tags"
                                    :key="tag.id"
                                    class="rounded-md bg-muted px-2.5 py-1 text-sm"
                                >
                                    {{ tag.name }}
                                </span>
                            </div>

                            <p v-else class="text-sm text-muted-foreground">
                                No tags assigned.
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Sessions -->
                <section class=" border border-border bg-card">
                    <div class="flex items-center justify-between border-b border-border px-5 py-4">
                        <h2 class="font-semibold">Sessions</h2>
                        <span class="text-sm text-muted-foreground">
                            {{ event.data.sessions?.length ?? 0 }}
                        </span>
                    </div>

                    <div
                        v-if="event.data.sessions?.length"
                        class="divide-y divide-border"
                    >
                        <div
                            v-for="session in event.data.sessions"
                            :key="session.id"
                            class="p-5"
                        >
                            <h3 class="font-medium">
                                {{ session.title }}
                            </h3>

                            <p
                                v-if="session.description"
                                class="mt-1 text-sm leading-6 text-muted-foreground"
                            >
                                {{ session.description }}
                            </p>

                            <div class="mt-3 flex flex-wrap gap-x-5 gap-y-2 text-sm text-muted-foreground">
                                <span class="inline-flex items-center gap-2">
                                    <CalendarDays class="size-4" />
                                    {{ formatDate(session.start_time) }}
                                </span>

                                <span
                                    v-if="session.end_time"
                                    class="inline-flex items-center gap-2"
                                >
                                    <Clock class="size-4" />
                                    {{ formatDate(session.end_time) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <p v-else class="p-5 text-sm text-muted-foreground">
                        No sessions have been added.
                    </p>
                </section>

                <!-- Tickets -->
                <section class=" border border-border bg-card">
                    <div class="flex items-center justify-between border-b border-border px-5 py-4">
                        <h2 class="font-semibold">Tickets</h2>
                        <span class="text-sm text-muted-foreground">
                            {{ event.data.tickets?.length ?? 0 }}
                        </span>
                    </div>

                    <div
                        v-if="event.data.tickets?.length"
                        class="divide-y divide-border"
                    >
                        <div
                            v-for="ticket in event.data.tickets"
                            :key="ticket.id"
                            class="flex flex-wrap items-start justify-between gap-3 p-5"
                        >
                            <div>
                                <h3 class="font-medium">
                                    {{ ticket.name }}
                                </h3>

                                <p
                                    v-if="ticket.description"
                                    class="mt-1 text-sm text-muted-foreground"
                                >
                                    {{ ticket.description }}
                                </p>

                                <p class="mt-2 text-xs text-muted-foreground">
                                    Capacity: {{ ticket.capacity ?? 'Unlimited' }}
                                    <span class="mx-1">·</span>
                                    {{ ticket.is_active ? 'Active' : 'Inactive' }}
                                </p>
                            </div>

                            <p class="font-semibold">
                                {{ ticket.currency }} {{ ticket.price }}
                            </p>
                        </div>
                    </div>

                    <p v-else class="p-5 text-sm text-muted-foreground">
                        No tickets have been configured.
                    </p>
                </section>
            </div>

            <!-- Sidebar -->
            <div class="min-w-0 space-y-6">
                <!-- Schedule and location -->
                <section class=" border border-border bg-card">
                    <div class="border-b border-border px-5 py-4">
                        <h2 class="font-semibold">Schedule & location</h2>
                    </div>

                    <div class="space-y-5 p-5">
                        <div>
                            <p class="mb-1 text-xs font-medium uppercase tracking-wide text-muted-foreground">
                                Starts
                            </p>
                            <p class="text-sm font-medium">
                                {{ formatDate(event.data.start_date) }}
                            </p>
                        </div>

                        <div>
                            <p class="mb-1 text-xs font-medium uppercase tracking-wide text-muted-foreground">
                                Ends
                            </p>
                            <p class="text-sm font-medium">
                                {{ formatDate(event.data.end_date) }}
                            </p>
                        </div>

                        <div class="border-t border-border pt-4">
                            <div class="mb-2 flex items-center gap-2">
                                <MapPin class="size-4 text-muted-foreground" />
                                <h3 class="text-sm font-medium">Location</h3>
                            </div>

                            <p class="text-sm leading-6 text-muted-foreground">
                                {{ locationText() || 'No location provided.' }}
                            </p>
                        </div>

                        <div
                            v-if="event.data.meeting_url"
                            class="border-t border-border pt-4"
                        >
                            <div class="mb-2 flex items-center gap-2">
                                <Video class="size-4 text-muted-foreground" />
                                <h3 class="text-sm font-medium">Meeting link</h3>
                            </div>

                            <a
                                :href="event.data.meeting_url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="break-all text-sm text-primary underline underline-offset-4"
                            >
                                {{ event.data.meeting_url }}
                            </a>
                        </div>
                    </div>
                </section>

                <!-- Record information -->
                <section class=" border border-border bg-card">
                    <div class="border-b border-border px-5 py-4">
                        <h2 class="font-semibold">Record information</h2>
                    </div>

                    <dl class="space-y-4 p-5">
                        <div>
                            <dt class="text-xs text-muted-foreground">
                                Created by
                            </dt>
                            <dd class="mt-1 text-sm font-medium">
                                {{ event.data.creator?.name ?? 'Unknown' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs text-muted-foreground">
                                Created at
                            </dt>
                            <dd class="mt-1 text-sm font-medium">
                                {{ formatDate(event.data.created_at) }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-xs text-muted-foreground">
                                Last updated
                            </dt>
                            <dd class="mt-1 text-sm font-medium">
                                {{ formatDate(event.data.updated_at) }}
                            </dd>
                        </div>
                    </dl>
                </section>

                <!-- Participants -->
                <section class=" border border-border bg-card">
                    <div class="flex items-center justify-between border-b border-border px-5 py-4">
                        <h2 class="font-semibold">Participants</h2>
                        <span class="text-sm text-muted-foreground">
                            {{ event.data.participants?.length ?? 0 }}
                        </span>
                    </div>

                    <div
                        v-if="event.data.participants?.length"
                        class="divide-y divide-border"
                    >
                        <div
                            v-for="participant in event.data.participants"
                            :key="participant.id"
                            class="flex items-center justify-between gap-3 p-4"
                        >
                            <div>
                                <p class="text-sm font-medium">
                                    {{ participant.user?.name ?? 'Unassigned user' }}
                                </p>
                                <p class="mt-1 text-xs text-muted-foreground">
                                    {{ participant.role }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <p v-else class="p-5 text-sm text-muted-foreground">
                        No participants added.
                    </p>
                </section>
            </div>
        </div>
    </div>
</template>
