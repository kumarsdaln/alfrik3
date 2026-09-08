<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import {
    ArrowLeft,
    CalendarDays,
    CheckCircle2,
    Clock3,
    Edit,
    ExternalLink,
    MapPin,
    Ticket,
    Users,
    Video,
} from '@lucide/vue'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import { Button } from '@/components/ui/button'

import {
    index as eventsIndex,
    edit as eventsEdit,
} from '@/routes/admin/events'

interface Category {
    id: number
    name: string
    slug: string
}

interface User {
    id: number
    name: string
    email?: string | null
    profile_image?: string | null
}

interface Participant {
    id: number
    role: string
    position: number
    user?: User | null
}

interface Speaker extends User {
    role?: string | null
    organisation?: string | null
}

interface Session {
    id: number
    title: string
    description?: string | null
    start_time?: string | null
    end_time?: string | null
    location?: string | null
    meeting_url?: string | null
    position: number
    speakers?: Speaker[]
}

interface Ticket {
    id: number
    name: string
    slug: string
    description?: string | null
    price?: number | string | null
    currency?: string | null
    capacity?: number | null
    sales_start?: string | null
    sales_end?: string | null
    is_visible: boolean
    is_active: boolean
    position: number
}

interface EventMedia {
    id: number
    type?: string | null
    title?: string | null
    caption?: string | null
    file_path: string
    file_name?: string | null
    mime_type?: string | null
    position: number
    featured: boolean
}

interface EventRegistrationStats {
    total: number
    confirmed: number
    pending: number
    cancelled: number
    checked_in: number
}

interface Event {
    id: number
    title: string
    slug: string
    description?: string | null
    event_type: 'in_person' | 'online' | 'hybrid'
    visibility: 'public' | 'private'
    start_date: string
    end_date?: string | null

    location_name?: string | null
    address?: string | null
    city?: string | null
    state?: string | null
    country?: string | null
    meeting_url?: string | null

    banner?: string | null
    max_attendees?: number | null

    status: 'draft' | 'published' | 'cancelled'

    created_at?: string | null
    updated_at?: string | null

    categories?: Category[]
    participants?: Participant[]
    sessions?: Session[]
    tickets?: Ticket[]
    media?: EventMedia[]

    registration_stats?: EventRegistrationStats
}

interface Props {
    event: Event
}

const props = defineProps<Props>()

/*
|--------------------------------------------------------------------------
| Labels
|--------------------------------------------------------------------------
*/

const eventTypeLabel = computed(() => {
    switch (props.event.event_type) {
        case 'online':
            return 'Online'

        case 'hybrid':
            return 'Hybrid'

        default:
            return 'In-person'
    }
})

const statusLabel = computed(() => {
    switch (props.event.status) {
        case 'published':
            return 'Published'

        case 'cancelled':
            return 'Cancelled'

        default:
            return 'Draft'
    }
})

const visibilityLabel = computed(() =>
    props.event.visibility === 'public'
        ? 'Public'
        : 'Private',
)

/*
|--------------------------------------------------------------------------
| Dates
|--------------------------------------------------------------------------
*/

const formatDate = (value?: string | null) => {
    if (!value) {
        return ''
    }

    return new Intl.DateTimeFormat('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    }).format(new Date(value))
}

const formatTime = (value?: string | null) => {
    if (!value) {
        return ''
    }

    return new Intl.DateTimeFormat('en-US', {
        hour: 'numeric',
        minute: '2-digit',
    }).format(new Date(value))
}

const dateRange = computed(() => {
    if (!props.event.start_date) {
        return ''
    }

    const start = new Date(props.event.start_date)
    const end = props.event.end_date
        ? new Date(props.event.end_date)
        : null

    if (!end) {
        return `${formatDate(props.event.start_date)} · ${formatTime(props.event.start_date)}`
    }

    if (start.toDateString() === end.toDateString()) {
        return `${formatDate(props.event.start_date)} · ${formatTime(props.event.start_date)} – ${formatTime(props.event.end_date)}`
    }

    return `${formatDate(props.event.start_date)} · ${formatTime(props.event.start_date)} – ${formatDate(props.event.end_date)} · ${formatTime(props.event.end_date)}`
})

/*
|--------------------------------------------------------------------------
| Location
|--------------------------------------------------------------------------
*/

const locationLabel = computed(() => {
    if (props.event.event_type === 'online') {
        return 'Virtual event'
    }

    return [
        props.event.location_name,
        props.event.city,
        props.event.country,
    ]
        .filter(Boolean)
        .join(', ')
})

const fullAddress = computed(() =>
    [
        props.event.address,
        props.event.city,
        props.event.state,
        props.event.country,
    ]
        .filter(Boolean)
        .join(', '),
)

/*
|--------------------------------------------------------------------------
| Registration
|--------------------------------------------------------------------------
*/

const registrationStats = computed(() => ({
    total: props.event.registration_stats?.total ?? 0,
    confirmed: props.event.registration_stats?.confirmed ?? 0,
    pending: props.event.registration_stats?.pending ?? 0,
    cancelled: props.event.registration_stats?.cancelled ?? 0,
    checked_in: props.event.registration_stats?.checked_in ?? 0,
}))

const capacityPercentage = computed(() => {
    if (!props.event.max_attendees) {
        return 0
    }

    return Math.min(
        Math.round(
            (registrationStats.value.confirmed /
                props.event.max_attendees) *
                100,
        ),
        100,
    )
})

/*
|--------------------------------------------------------------------------
| Media
|--------------------------------------------------------------------------
*/

const bannerUrl = computed(() => {
    const value = props.event.banner?.trim()

    if (!value) {
        return ''
    }

    if (
        value.startsWith('/') ||
        value.startsWith('http://') ||
        value.startsWith('https://')
    ) {
        return value
    }

    return `/${value}`
})

/*
|--------------------------------------------------------------------------
| Session
|--------------------------------------------------------------------------
*/

const sessionTime = (session: Session) => {
    if (!session.start_time) {
        return ''
    }

    const start = formatTime(session.start_time)

    if (!session.end_time) {
        return start
    }

    return `${start} – ${formatTime(session.end_time)}`
}

/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
*/

const backUrl = computed(() => eventsIndex().url)

const editUrl = computed(() =>
    eventsEdit(props.event.id).url,
)
</script>

<template>
    <Head :title="`${event.title} — Events`" />

    <!--
    |--------------------------------------------------------------------------
    | Header
    |--------------------------------------------------------------------------
    -->

    <header class="border-b border-border-light dark:border-border-dark">
        <div
            class="
                container
                mx-auto
                px-4
                pb-8
                pt-8
                sm:pb-10
                sm:pt-10
                lg:pb-12
            "
        >
            <!-- Header -->
            <div
                class="
                    flex
                    flex-col
                    gap-7
                    lg:flex-row
                    lg:items-end
                    lg:justify-between
                "
            >
                <div class="min-w-0">
                    <div class="mb-4 flex flex-wrap items-center gap-3">
                        <AppText
                            tag="span"
                            font="redhat"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="primary"
                        >
                            {{ eventTypeLabel }}
                        </AppText>

                        <span
                            class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark"
                            aria-hidden="true"
                        />

                        <AppText
                            tag="span"
                            font="redhat"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            :class="{
                                'text-primary': event.status === 'published',
                                'text-muted-foreground': event.status === 'draft',
                                'text-destructive': event.status === 'cancelled',
                            }"
                        >
                            {{ statusLabel }}
                        </AppText>

                        <span
                            class="h-1 w-1 rounded-full bg-border-light dark:bg-border-dark"
                            aria-hidden="true"
                        />

                        <AppText
                            tag="span"
                            font="redhat"
                            size="xs"
                            uppercase
                            tracking="wide"
                            color="muted"
                        >
                            {{ visibilityLabel }}
                        </AppText>
                    </div>

                    <AppHeading
                        tag="h1"
                        font="prata"
                        size="5xl"
                        weight="normal"
                        leading="tight"
                        class="max-w-5xl"
                    >
                        {{ event.title }}
                    </AppHeading>

                    <AppText
                        v-if="event.description"
                        tag="p"
                        font="lora"
                        size="lg"
                        color="muted"
                        leading="relaxed"
                        class="mt-5 max-w-3xl"
                    >
                        {{ event.description }}
                    </AppText>
                </div>

                <!-- Actions -->

                <div class="flex shrink-0 items-center gap-3">
                    <Link :href="backUrl">
                        <Button
                            variant="outline"
                            type="button"
                        >
                            <ArrowLeft class="size-4" />
                            Back
                        </Button>
                    </Link>

                    <Link :href="editUrl">
                        <Button type="button">
                            <Edit class="size-4" />
                            Edit Event
                        </Button>
                    </Link>
                </div>
            </div>

            <!-- Meta -->

            <div
                class="
                    mt-8
                    grid
                    border-y
                    border-border-light
                    dark:border-border-dark
                    sm:grid-cols-3
                "
            >
                <div
                    class="
                        border-b
                        border-border-light
                        py-5
                        sm:border-b-0
                        sm:border-r
                        sm:pr-6
                        dark:border-border-dark
                    "
                >
                    <AppText
                        tag="p"
                        size="xs"
                        weight="bold"
                        uppercase
                        tracking="wide"
                        color="muted"
                    >
                        Date & time
                    </AppText>

                    <AppText
                        tag="p"
                        font="redhat"
                        size="sm"
                        class="mt-2"
                    >
                        {{ dateRange }}
                    </AppText>
                </div>

                <div
                    class="
                        border-b
                        border-border-light
                        py-5
                        sm:border-b-0
                        sm:border-r
                        sm:px-6
                        dark:border-border-dark
                    "
                >
                    <AppText
                        tag="p"
                        size="xs"
                        weight="bold"
                        uppercase
                        tracking="wide"
                        color="muted"
                    >
                        Location
                    </AppText>

                    <AppText
                        tag="p"
                        font="redhat"
                        size="sm"
                        class="mt-2"
                    >
                        {{ locationLabel || 'Not specified' }}
                    </AppText>
                </div>

                <div class="py-5 sm:pl-6">
                    <AppText
                        tag="p"
                        size="xs"
                        weight="bold"
                        uppercase
                        tracking="wide"
                        color="muted"
                    >
                        Registrations
                    </AppText>

                    <AppText
                        tag="p"
                        font="redhat"
                        size="sm"
                        class="mt-2"
                    >
                        {{ registrationStats.confirmed }}
                        <span v-if="event.max_attendees">
                            / {{ event.max_attendees }}
                        </span>
                        registered
                    </AppText>
                </div>
            </div>
        </div>
    </header>

    <!--
    |--------------------------------------------------------------------------
    | Main
    |--------------------------------------------------------------------------
    -->

    <main class="container mx-auto px-4 py-10 sm:py-14 lg:py-16">
        <div
            class="
                grid
                gap-12
                lg:grid-cols-[minmax(0,1fr)_320px]
                lg:gap-16
            "
        >
            <!-- Main content -->

            <article class="min-w-0">
                <!-- Banner -->

                <div
                    v-if="bannerUrl"
                    class="mb-10 overflow-hidden"
                >
                    <img
                        :src="bannerUrl"
                        :alt="event.title"
                        class="aspect-[16/9] h-full w-full object-cover"
                    />
                </div>

                <!-- Overview -->

                <section>
                    <AppText
                        tag="p"
                        size="xs"
                        weight="bold"
                        tracking="wide"
                        uppercase
                        color="primary"
                        class="mb-3"
                    >
                        Event Overview
                    </AppText>

                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="3xl"
                        weight="normal"
                    >
                        {{ event.title }}
                    </AppHeading>

                    <AppText
                        v-if="event.description"
                        tag="p"
                        font="lora"
                        size="lg"
                        color="muted"
                        leading="relaxed"
                        class="mt-5 max-w-3xl whitespace-pre-line"
                    >
                        {{ event.description }}
                    </AppText>
                </section>

                <!-- Schedule -->

                <section class="mt-16">
                    <div
                        class="
                            flex
                            items-end
                            justify-between
                            border-b
                            border-border-light
                            pb-5
                            dark:border-border-dark
                        "
                    >
                        <div>
                            <AppText
                                tag="p"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="primary"
                            >
                                Schedule
                            </AppText>

                            <AppHeading
                                tag="h2"
                                font="prata"
                                size="2xl"
                                weight="normal"
                                class="mt-2"
                            >
                                Event timing
                            </AppHeading>
                        </div>

                        <CalendarDays
                            :size="20"
                            :stroke-width="1.5"
                            class="text-muted"
                        />
                    </div>

                    <div
                        class="
                            divide-y
                            divide-border-light
                            dark:divide-border-dark
                        "
                    >
                        <div class="grid gap-4 py-5 sm:grid-cols-[160px_1fr]">
                            <AppText
                                tag="span"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="muted"
                            >
                                Starts
                            </AppText>

                            <AppText
                                tag="span"
                                font="redhat"
                                size="sm"
                            >
                                {{ dateRange }}
                            </AppText>
                        </div>

                        <div
                            v-if="event.end_date"
                            class="grid gap-4 py-5 sm:grid-cols-[160px_1fr]"
                        >
                            <AppText
                                tag="span"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="muted"
                            >
                                Ends
                            </AppText>

                            <AppText
                                tag="span"
                                font="redhat"
                                size="sm"
                            >
                                {{ formatDate(event.end_date) }}
                                ·
                                {{ formatTime(event.end_date) }}
                            </AppText>
                        </div>
                    </div>
                </section>

                <!-- Location -->

                <section class="mt-16">
                    <div
                        class="
                            border-b
                            border-border-light
                            pb-5
                            dark:border-border-dark
                        "
                    >
                        <AppText
                            tag="p"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="primary"
                        >
                            Location
                        </AppText>

                        <AppHeading
                            tag="h2"
                            font="prata"
                            size="2xl"
                            weight="normal"
                            class="mt-2"
                        >
                            Where it takes place
                        </AppHeading>
                    </div>

                    <div
                        class="
                            divide-y
                            divide-border-light
                            dark:divide-border-dark
                        "
                    >
                        <div
                            v-if="event.location_name"
                            class="flex gap-4 py-5"
                        >
                            <MapPin
                                :size="18"
                                :stroke-width="1.6"
                                class="mt-0.5 shrink-0 text-primary"
                            />

                            <div>
                                <AppText
                                    tag="p"
                                    font="redhat"
                                    size="sm"
                                    weight="medium"
                                >
                                    {{ event.location_name }}
                                </AppText>

                                <AppText
                                    v-if="fullAddress"
                                    tag="p"
                                    size="sm"
                                    color="muted"
                                    class="mt-1"
                                >
                                    {{ fullAddress }}
                                </AppText>
                            </div>
                        </div>

                        <div
                            v-if="event.meeting_url"
                            class="flex items-center justify-between gap-4 py-5"
                        >
                            <div class="flex items-center gap-4">
                                <Video
                                    :size="18"
                                    :stroke-width="1.6"
                                    class="shrink-0 text-primary"
                                />

                                <div>
                                    <AppText
                                        tag="p"
                                        font="redhat"
                                        size="sm"
                                        weight="medium"
                                    >
                                        Online meeting
                                    </AppText>

                                    <AppText
                                        tag="p"
                                        size="xs"
                                        color="muted"
                                        class="mt-1"
                                    >
                                        Meeting link configured
                                    </AppText>
                                </div>
                            </div>

                            <a
                                :href="event.meeting_url"
                                target="_blank"
                                rel="noopener noreferrer"
                                class="
                                    inline-flex
                                    items-center
                                    gap-2
                                    text-xs
                                    font-semibold
                                    text-primary
                                    hover:underline
                                "
                            >
                                Open
                                <ExternalLink :size="14" />
                            </a>
                        </div>

                        <div
                            v-if="
                                !event.location_name &&
                                !fullAddress &&
                                !event.meeting_url
                            "
                            class="py-6"
                        >
                            <AppText
                                tag="p"
                                size="sm"
                                color="muted"
                            >
                                No location information has been added.
                            </AppText>
                        </div>
                    </div>
                </section>

                <!-- Sessions -->

                <section class="mt-16">
                    <div
                        class="
                            flex
                            items-end
                            justify-between
                            border-b
                            border-border-light
                            pb-5
                            dark:border-border-dark
                        "
                    >
                        <div>
                            <AppText
                                tag="p"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="primary"
                            >
                                Programme
                            </AppText>

                            <AppHeading
                                tag="h2"
                                font="prata"
                                size="2xl"
                                weight="normal"
                                class="mt-2"
                            >
                                Sessions
                            </AppHeading>
                        </div>

                        <AppText
                            tag="span"
                            size="sm"
                            color="muted"
                        >
                            {{ event.sessions?.length ?? 0 }}
                        </AppText>
                    </div>

                    <div
                        v-if="event.sessions?.length"
                        class="
                            divide-y
                            divide-border-light
                            dark:divide-border-dark
                        "
                    >
                        <article
                            v-for="session in event.sessions"
                            :key="session.id"
                            class="
                                grid
                                gap-5
                                py-7
                                sm:grid-cols-[130px_1fr]
                            "
                        >
                            <div>
                                <AppText
                                    tag="p"
                                    font="redhat"
                                    size="xs"
                                    weight="bold"
                                    color="primary"
                                >
                                    {{ sessionTime(session) }}
                                </AppText>
                            </div>

                            <div>
                                <AppHeading
                                    tag="h3"
                                    font="prata"
                                    size="lg"
                                    weight="normal"
                                    leading="tight"
                                >
                                    {{ session.title }}
                                </AppHeading>

                                <AppText
                                    v-if="session.description"
                                    tag="p"
                                    font="lora"
                                    size="sm"
                                    color="muted"
                                    leading="relaxed"
                                    class="mt-2 max-w-2xl"
                                >
                                    {{ session.description }}
                                </AppText>

                                <div
                                    v-if="
                                        session.speakers?.length ||
                                        session.location
                                    "
                                    class="
                                        mt-4
                                        flex
                                        flex-wrap
                                        gap-x-5
                                        gap-y-2
                                    "
                                >
                                    <AppText
                                        v-if="session.speakers?.length"
                                        tag="span"
                                        size="xs"
                                        color="muted"
                                    >
                                        {{
                                            session.speakers
                                                .map(speaker => speaker.name)
                                                .join(', ')
                                        }}
                                    </AppText>

                                    <AppText
                                        v-if="session.location"
                                        tag="span"
                                        size="xs"
                                        color="muted"
                                    >
                                        {{ session.location }}
                                    </AppText>
                                </div>
                            </div>
                        </article>
                    </div>

                    <div
                        v-else
                        class="
                            border-b
                            border-border-light
                            py-8
                            dark:border-border-dark
                        "
                    >
                        <AppText
                            tag="p"
                            size="sm"
                            color="muted"
                        >
                            No sessions have been added yet.
                        </AppText>
                    </div>
                </section>

                <!-- Tickets -->

                <section class="mt-16">
                    <div
                        class="
                            flex
                            items-end
                            justify-between
                            border-b
                            border-border-light
                            pb-5
                            dark:border-border-dark
                        "
                    >
                        <div>
                            <AppText
                                tag="p"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="primary"
                            >
                                Admission
                            </AppText>

                            <AppHeading
                                tag="h2"
                                font="prata"
                                size="2xl"
                                weight="normal"
                                class="mt-2"
                            >
                                Tickets
                            </AppHeading>
                        </div>

                        <Ticket
                            :size="20"
                            :stroke-width="1.5"
                            class="text-muted"
                        />
                    </div>

                    <div
                        v-if="event.tickets?.length"
                        class="
                            divide-y
                            divide-border-light
                            dark:divide-border-dark
                        "
                    >
                        <div
                            v-for="ticket in event.tickets"
                            :key="ticket.id"
                            class="
                                flex
                                flex-col
                                gap-4
                                py-6
                                sm:flex-row
                                sm:items-center
                                sm:justify-between
                            "
                        >
                            <div>
                                <AppHeading
                                    tag="h3"
                                    font="prata"
                                    size="lg"
                                    weight="normal"
                                >
                                    {{ ticket.name }}
                                </AppHeading>

                                <AppText
                                    v-if="ticket.description"
                                    tag="p"
                                    size="sm"
                                    color="muted"
                                    class="mt-1"
                                >
                                    {{ ticket.description }}
                                </AppText>
                            </div>

                            <div class="flex items-center gap-5">
                                <AppText
                                    v-if="ticket.capacity"
                                    tag="span"
                                    size="xs"
                                    color="muted"
                                >
                                    {{ ticket.capacity }} places
                                </AppText>

                                <AppText
                                    tag="span"
                                    font="redhat"
                                    size="sm"
                                    weight="bold"
                                >
                                    {{
                                        ticket.price === null ||
                                        ticket.price === undefined
                                            ? 'Free'
                                            : `${ticket.currency ?? ''} ${ticket.price}`
                                    }}
                                </AppText>
                            </div>
                        </div>
                    </div>

                    <div
                        v-else
                        class="
                            border-b
                            border-border-light
                            py-8
                            dark:border-border-dark
                        "
                    >
                        <AppText
                            tag="p"
                            size="sm"
                            color="muted"
                        >
                            No tickets have been added yet.
                        </AppText>
                    </div>
                </section>

                <!-- Participants -->

                <section class="mt-16">
                    <div
                        class="
                            flex
                            items-end
                            justify-between
                            border-b
                            border-border-light
                            pb-5
                            dark:border-border-dark
                        "
                    >
                        <div>
                            <AppText
                                tag="p"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="primary"
                            >
                                People
                            </AppText>

                            <AppHeading
                                tag="h2"
                                font="prata"
                                size="2xl"
                                weight="normal"
                                class="mt-2"
                            >
                                Participants
                            </AppHeading>
                        </div>

                        <Users
                            :size="20"
                            :stroke-width="1.5"
                            class="text-muted"
                        />
                    </div>

                    <div
                        v-if="event.participants?.length"
                        class="
                            divide-y
                            divide-border-light
                            dark:divide-border-dark
                        "
                    >
                        <div
                            v-for="participant in event.participants"
                            :key="participant.id"
                            class="
                                flex
                                items-center
                                justify-between
                                gap-4
                                py-5
                            "
                        >
                            <div>
                                <AppText
                                    tag="p"
                                    font="redhat"
                                    size="sm"
                                    weight="medium"
                                >
                                    {{ participant.user?.name }}
                                </AppText>

                                <AppText
                                    v-if="participant.user?.email"
                                    tag="p"
                                    size="xs"
                                    color="muted"
                                    class="mt-1"
                                >
                                    {{ participant.user.email }}
                                </AppText>
                            </div>

                            <AppText
                                tag="span"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="muted"
                            >
                                {{ participant.role }}
                            </AppText>
                        </div>
                    </div>

                    <div
                        v-else
                        class="
                            border-b
                            border-border-light
                            py-8
                            dark:border-border-dark
                        "
                    >
                        <AppText
                            tag="p"
                            size="sm"
                            color="muted"
                        >
                            No participants have been added yet.
                        </AppText>
                    </div>
                </section>
            </article>

            <!-- Sidebar -->

            <aside>
                <div
                    class="
                        sticky
                        top-24
                        border
                        border-border-light
                        bg-surface-light
                        dark:border-border-dark
                        dark:bg-surface-dark
                    "
                >
                    <!-- Registration -->

                    <div
                        class="
                            border-b
                            border-border-light
                            p-6
                            dark:border-border-dark
                        "
                    >
                        <AppText
                            tag="p"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="primary"
                        >
                            Registration
                        </AppText>

                        <AppHeading
                            tag="h2"
                            font="prata"
                            size="xl"
                            weight="normal"
                            class="mt-2"
                        >
                            Event capacity
                        </AppHeading>
                    </div>

                    <div class="p-6">
                        <div
                            class="
                                grid
                                grid-cols-2
                                divide-x
                                divide-border-light
                                border-y
                                border-border-light
                                dark:divide-border-dark
                                dark:border-border-dark
                            "
                        >
                            <div class="py-4 pr-4">
                                <AppText
                                    tag="p"
                                    size="xs"
                                    color="muted"
                                >
                                    Confirmed
                                </AppText>

                                <AppText
                                    tag="p"
                                    font="redhat"
                                    size="2xl"
                                    weight="medium"
                                    class="mt-1"
                                >
                                    {{ registrationStats.confirmed }}
                                </AppText>
                            </div>

                            <div class="py-4 pl-4">
                                <AppText
                                    tag="p"
                                    size="xs"
                                    color="muted"
                                >
                                    Checked in
                                </AppText>

                                <AppText
                                    tag="p"
                                    font="redhat"
                                    size="2xl"
                                    weight="medium"
                                    class="mt-1"
                                >
                                    {{ registrationStats.checked_in }}
                                </AppText>
                            </div>
                        </div>

                        <div
                            v-if="event.max_attendees"
                            class="mt-6"
                        >
                            <div class="flex items-center justify-between">
                                <AppText
                                    tag="span"
                                    size="xs"
                                    color="muted"
                                >
                                    Capacity
                                </AppText>

                                <AppText
                                    tag="span"
                                    size="xs"
                                    weight="bold"
                                >
                                    {{ capacityPercentage }}%
                                </AppText>
                            </div>

                            <div class="mt-2 h-1.5 bg-muted">
                                <div
                                    class="h-full bg-primary"
                                    :style="{
                                        width: `${capacityPercentage}%`,
                                    }"
                                />
                            </div>

                            <AppText
                                tag="p"
                                size="xs"
                                color="muted"
                                class="mt-2"
                            >
                                {{ registrationStats.confirmed }}
                                of {{ event.max_attendees }} places filled
                            </AppText>
                        </div>

                        <div class="mt-6 space-y-3">
                            <div class="flex items-center justify-between">
                                <AppText
                                    tag="span"
                                    size="xs"
                                    color="muted"
                                >
                                    Total
                                </AppText>

                                <AppText
                                    tag="span"
                                    size="xs"
                                    weight="medium"
                                >
                                    {{ registrationStats.total }}
                                </AppText>
                            </div>

                            <div class="flex items-center justify-between">
                                <AppText
                                    tag="span"
                                    size="xs"
                                    color="muted"
                                >
                                    Pending
                                </AppText>

                                <AppText
                                    tag="span"
                                    size="xs"
                                    weight="medium"
                                >
                                    {{ registrationStats.pending }}
                                </AppText>
                            </div>

                            <div class="flex items-center justify-between">
                                <AppText
                                    tag="span"
                                    size="xs"
                                    color="muted"
                                >
                                    Cancelled
                                </AppText>

                                <AppText
                                    tag="span"
                                    size="xs"
                                    weight="medium"
                                >
                                    {{ registrationStats.cancelled }}
                                </AppText>
                            </div>
                        </div>
                    </div>

                    <!-- Details -->

                    <div
                        class="
                            border-t
                            border-border-light
                            p-6
                            dark:border-border-dark
                        "
                    >
                        <AppText
                            tag="p"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="primary"
                            class="mb-4"
                        >
                            Event details
                        </AppText>

                        <dl
                            class="
                                divide-y
                                divide-border-light
                                border-y
                                border-border-light
                                dark:divide-border-dark
                                dark:border-border-dark
                            "
                        >
                            <div class="flex items-center justify-between gap-4 py-3">
                                <AppText
                                    tag="dt"
                                    size="xs"
                                    color="muted"
                                >
                                    Type
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="xs"
                                    weight="medium"
                                >
                                    {{ eventTypeLabel }}
                                </AppText>
                            </div>

                            <div class="flex items-center justify-between gap-4 py-3">
                                <AppText
                                    tag="dt"
                                    size="xs"
                                    color="muted"
                                >
                                    Visibility
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="xs"
                                    weight="medium"
                                >
                                    {{ visibilityLabel }}
                                </AppText>
                            </div>

                            <div class="flex items-center justify-between gap-4 py-3">
                                <AppText
                                    tag="dt"
                                    size="xs"
                                    color="muted"
                                >
                                    Sessions
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="xs"
                                    weight="medium"
                                >
                                    {{ event.sessions?.length ?? 0 }}
                                </AppText>
                            </div>

                            <div class="flex items-center justify-between gap-4 py-3">
                                <AppText
                                    tag="dt"
                                    size="xs"
                                    color="muted"
                                >
                                    Tickets
                                </AppText>

                                <AppText
                                    tag="dd"
                                    size="xs"
                                    weight="medium"
                                >
                                    {{ event.tickets?.length ?? 0 }}
                                </AppText>
                            </div>
                        </dl>
                    </div>

                    <!-- Categories -->

                    <div
                        v-if="event.categories?.length"
                        class="
                            border-t
                            border-border-light
                            p-6
                            dark:border-border-dark
                        "
                    >
                        <AppText
                            tag="p"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="primary"
                            class="mb-4"
                        >
                            Categories
                        </AppText>

                        <div
                            class="
                                divide-y
                                divide-border-light
                                border-y
                                border-border-light
                                dark:divide-border-dark
                                dark:border-border-dark
                            "
                        >
                            <AppText
                                v-for="category in event.categories"
                                :key="category.id"
                                tag="p"
                                size="sm"
                                class="py-3"
                            >
                                {{ category.name }}
                            </AppText>
                        </div>
                    </div>

                    <!-- Status -->

                    <div
                        class="
                            border-t
                            border-border-light
                            p-6
                            dark:border-border-dark
                        "
                    >
                        <div class="flex items-center gap-2">
                            <CheckCircle2
                                :size="16"
                                :stroke-width="1.7"
                                class="text-primary"
                            />

                            <AppText
                                tag="span"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="primary"
                            >
                                {{ statusLabel }}
                            </AppText>
                        </div>

                        <AppText
                            tag="p"
                            size="xs"
                            color="muted"
                            leading="relaxed"
                            class="mt-3"
                        >
                            Event ID: {{ event.id }}
                        </AppText>
                    </div>
                </div>
            </aside>
        </div>
    </main>
</template>