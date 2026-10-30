<script setup lang="ts">
import { computed, ref } from 'vue'
import { Head, Link, router, usePage } from '@inertiajs/vue3'

import {
    ArrowRight,
    Check,
    Star,
} from '@lucide/vue'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import Avatar from '@/components/profile/Avatar.vue'
import EventCard from '@/components/events/EventCard.vue'

import { register as registerRoute, show as eventShow } from '@/routes/events'
import { login } from '@/routes'
import { Button } from '@/components/ui/button'

interface EventCategory {
    id: number
    name: string
    slug: string
}

interface Speaker {
    id: number
    name: string
    profile_image?: string | null
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
    speakers?: Speaker[]
}

interface EventTicket {
    id: number
    name: string
    description?: string | null
    price?: number | string | null
    currency?: string | null
}

interface EventReview {
    id: number
    rating: number
    review?: string | null
    user?: {
        name?: string | null
        profile_image?: string | null
    } | null
}

interface EventDetail {
    id: number
    title: string
    slug: string
    description?: string | null
    event_type: 'online' | 'offline' | 'hybrid' | string
    start_date: string
    end_date?: string | null
    location_name?: string | null
    city?: string | null
    state?: string | null
    country?: string | null
    meeting_url?: string | null
    banner?: string | null
    cover_image?: string | null
    categories?: EventCategory[]
    sessions?: Session[]
    tickets?: EventTicket[]
    reviews?: EventReview[]
}

interface Participant {
    id: number
    name: string
    profile_image?: string | null
}

interface Registration {
    is_registered?: boolean
    registrations_count?: number
    spots_left?: number | null
}

interface RelatedEvent {
    id: number
    title: string
    slug: string
    description?: string | null
    banner?: string | null
    start_date: string
    end_date?: string | null
    location_name?: string | null
    city?: string | null
    state?: string | null
    country?: string | null
    categories?: EventCategory[]
}

interface Props {
    event: EventDetail
    speakers?: Speaker[]
    participants?: Participant[]
    registration?: Registration | null
    reviewStats?: {
        average: number
        count: number
    }
    related?: RelatedEvent[]
}

const props = withDefaults(defineProps<Props>(), {
    speakers: () => [],
    participants: () => [],
    registration: () => ({
        is_registered: false,
        registrations_count: 0,
        spots_left: null,
    }),
    reviewStats: () => ({
        average: 0,
        count: 0,
    }),
    related: () => [],
})

const page = usePage()

const authed = computed(() => Boolean(page.props.auth?.user))

/*
|--------------------------------------------------------------------------
| Registration
|--------------------------------------------------------------------------
*/

const registrationInfo = computed<Registration>(() =>
    props.registration ?? {
        is_registered: false,
        registrations_count: 0,
        spots_left: null,
    },
)

const registered = ref(
    registrationInfo.value.is_registered ?? false,
)

const regCount = ref(
    registrationInfo.value.registrations_count ?? 0,
)

const pending = ref(false)

const selectedTicketId = ref<number | null>(
    props.event.tickets?.[0]?.id ?? null,
)

const registrationError = ref('')

const hasTickets = computed(
    () => (props.event.tickets?.length ?? 0) > 0,
)

function toggleRegister() {
    if (!authed.value) {
        router.visit(login().url)
        return
    }

    if (pending.value || registered.value) {
        return
    }

    if (!hasTickets.value) {
        registrationError.value =
            'Registration is unavailable because no tickets have been configured for this event.'
        return
    }

    if (selectedTicketId.value === null) {
        registrationError.value =
            'Please select a ticket before registering.'
        return
    }

    const previousCount = regCount.value

    registrationError.value = ''
    registered.value = true
    regCount.value += 1
    pending.value = true

    router.post(
        registerRoute(props.event).url,
        {
            ticket_id: selectedTicketId.value,
        },
        {
            preserveScroll: true,

            onError: (errors) => {
                registered.value = false
                regCount.value = previousCount

                registrationError.value =
                    errors.ticket_id ??
                    Object.values(errors)[0] ??
                    'Unable to complete registration.'
            },

            onSuccess: () => {
                registrationError.value = ''
            },

            onFinish: () => {
                pending.value = false
            },
        },
    )
}

/*
|--------------------------------------------------------------------------
| Event type
|--------------------------------------------------------------------------
*/

const typeLabel = computed(() => {
    switch (props.event.event_type) {
        case 'online':
            return 'Online'

        case 'hybrid':
            return 'Hybrid'

        default:
            return 'In-person'
    }
})

/*
|--------------------------------------------------------------------------
| Dates
|--------------------------------------------------------------------------
*/

const formatDate = (value?: string | null) => {
    if (!value) {
        return ''
    }

    const date = new Date(value)

    if (Number.isNaN(date.getTime())) {
        return ''
    }

    return new Intl.DateTimeFormat('en-US', {
        weekday: 'long',
        month: 'long',
        day: 'numeric',
        year: 'numeric',
    }).format(date)
}

const formatTime = (value?: string | null) => {
    if (!value) {
        return ''
    }

    // Support database time values such as HH:mm and HH:mm:ss.
    const timeOnly = value.match(
        /^(\d{1,2}):(\d{2})(?::\d{2})?$/,
    )

    if (timeOnly) {
        const hours = Number(timeOnly[1])
        const minutes = Number(timeOnly[2])

        if (hours > 23 || minutes > 59) {
            return ''
        }

        const date = new Date(2000, 0, 1, hours, minutes)

        return new Intl.DateTimeFormat('en-US', {
            hour: 'numeric',
            minute: '2-digit',
        }).format(date)
    }

    const date = new Date(value)

    if (Number.isNaN(date.getTime())) {
        return ''
    }

    return new Intl.DateTimeFormat('en-US', {
        hour: 'numeric',
        minute: '2-digit',
    }).format(date)
}

const dateRange = computed(() => {
    const start = props.event.start_date
    const end = props.event.end_date

    if (!start) {
        return ''
    }

    if (!end) {
        return `${formatDate(start)} · ${formatTime(start)}`
    }

    const startDate = new Date(start)
    const endDate = new Date(end)

    if (
        !Number.isNaN(startDate.getTime()) &&
        !Number.isNaN(endDate.getTime()) &&
        startDate.toDateString() === endDate.toDateString()
    ) {
        return `${formatDate(start)} · ${formatTime(start)} – ${formatTime(end)}`
    }

    return `${formatDate(start)} · ${formatTime(start)} – ${formatDate(end)} · ${formatTime(end)}`
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
        props.event.state,
        props.event.country,
    ]
        .filter(Boolean)
        .join(', ')
})

/*
|--------------------------------------------------------------------------
| Banner
|--------------------------------------------------------------------------
*/

const bannerUrl = computed(() => {
    const value = (
        props.event.banner ||
        props.event.cover_image ||
        ''
    ).trim()

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
| Session time
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
| Speakers
|--------------------------------------------------------------------------
*/

const allSpeakers = computed(() => {
    if (props.speakers.length) {
        return props.speakers
    }

    const map = new Map<number, Speaker>()

    for (const session of props.event.sessions ?? []) {
        for (const speaker of session.speakers ?? []) {
            if (!map.has(speaker.id)) {
                map.set(speaker.id, speaker)
            }
        }
    }

    return Array.from(map.values())
})

/*
|--------------------------------------------------------------------------
| Register label
|--------------------------------------------------------------------------
*/

const registerLabel = computed(() => {
    if (!authed.value) {
        return 'Sign in to register'
    }

    if (registered.value) {
        return 'Registered'
    }

    if (!hasTickets.value) {
        return 'Registration unavailable'
    }

    if (pending.value) {
        return 'Registering…'
    }

    return 'Register for this event'
})
</script>

<template>
    <Head :title="`${event.title} — Events`">
        <meta
            name="description"
            :content="event.description ?? ''"
        />
    </Head>

    <!-- Hero -->
    <section class="border-b border-border-light dark:border-border-dark">
        <div
            class="grid gap-8 py-10 sm:py-14 lg:grid-cols-[1.15fr_0.85fr] lg:items-end lg:gap-16 lg:py-16"
        >
            <!-- Event image -->
            <div class="relative overflow-hidden bg-surface-light dark:bg-surface-dark">
                <img
                    v-if="bannerUrl"
                    :src="bannerUrl"
                    :alt="event.title"
                    class="aspect-[16/10] h-full w-full object-cover"
                />

                <div
                    v-else
                    class="flex aspect-[16/10] items-center justify-center border border-border-light dark:border-border-dark"
                >
                    <AppText
                        size="xs"
                        weight="bold"
                        uppercase
                        tracking="wide"
                        color="muted"
                    >
                        Event
                    </AppText>
                </div>
            </div>

            <!-- Event information -->
            <div>
                <div class="mb-5 flex flex-wrap items-center gap-3">
                    <span class="h-px w-8 bg-brand" />

                    <AppText
                        tag="span"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        uppercase
                        tracking="wide"
                        color="brand"
                    >
                        {{ typeLabel }}
                    </AppText>

                    <template
                        v-for="category in event.categories"
                        :key="category.id"
                    >
                        <span class="text-content-lightMuted dark:text-content-darkMuted">
                            /
                        </span>

                        <AppText
                            tag="span"
                            font="redhat"
                            size="xs"
                            uppercase
                            tracking="wide"
                            color="muted"
                        >
                            {{ category.name }}
                        </AppText>
                    </template>
                </div>

                <AppHeading
                    tag="h1"
                    font="prata"
                    size="4xl"
                    weight="normal"
                    leading="tight"
                    hover-brand
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
                    class="mt-6 max-w-2xl"
                >
                    {{ event.description }}
                </AppText>

                <!-- Event facts -->
                <div
                    class="mt-8 grid border-y border-border-light dark:border-border-dark sm:grid-cols-2"
                >
                    <div
                        class="border-b border-border-light py-5 sm:border-r sm:pr-6 dark:border-border-dark"
                    >
                        <AppText
                            tag="p"
                            size="xs"
                            uppercase
                            tracking="wide"
                            weight="bold"
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

                    <div class="py-5 sm:pl-6">
                        <AppText
                            tag="p"
                            size="xs"
                            uppercase
                            tracking="wide"
                            weight="bold"
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
                            {{ locationLabel }}
                        </AppText>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Main content -->
    <div class="py-12 lg:py-16">
        <div class="grid gap-14 lg:grid-cols-[minmax(0,1fr)_320px] lg:gap-16">
            <main>
                <!-- About -->
                <section v-if="event.description">
                    <AppText
                        tag="p"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        uppercase
                        tracking="wide"
                        color="brand"
                    >
                        About the event
                    </AppText>

                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="2xl"
                        weight="normal"
                        leading="tight"
                        class="mt-3"
                    >
                        {{ event.title }}
                    </AppHeading>

                    <AppText
                        tag="p"
                        font="lora"
                        size="sm"
                        color="muted"
                        leading="relaxed"
                        class="mt-5 max-w-3xl"
                    >
                        {{ event.description }}
                    </AppText>
                </section>

                <!-- Speakers -->
                <section v-if="allSpeakers.length" class="mt-16">
                    <div
                        class="flex items-end justify-between border-b border-border-light pb-5 dark:border-border-dark"
                    >
                        <div>
                            <AppText
                                tag="p"
                                font="redhat"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="brand"
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
                                Speakers
                            </AppHeading>
                        </div>

                        <AppText tag="span" size="sm" color="muted">
                            {{ allSpeakers.length }}
                            {{ allSpeakers.length === 1 ? 'speaker' : 'speakers' }}
                        </AppText>
                    </div>

                    <div
                        class="grid divide-y divide-border-light dark:divide-border-dark sm:grid-cols-2 sm:divide-y-0 sm:divide-x sm:border-b dark:sm:divide-border-dark"
                    >
                        <div
                            v-for="speaker in allSpeakers"
                            :key="speaker.id"
                            class="flex items-center gap-4 py-6 sm:px-5 lg:px-6"
                        >
                            <Avatar
                                :name="speaker.name"
                                :image="speaker.profile_image"
                                size="w-14 h-14"
                                class="shrink-0"
                            />

                            <div class="min-w-0">
                                <AppText
                                    tag="p"
                                    font="redhat"
                                    size="sm"
                                    weight="semibold"
                                >
                                    {{ speaker.name }}
                                </AppText>

                                <AppText
                                    v-if="speaker.role"
                                    tag="p"
                                    size="xs"
                                    color="muted"
                                    class="mt-1"
                                >
                                    {{ speaker.role }}
                                </AppText>

                                <AppText
                                    v-if="speaker.organisation"
                                    tag="p"
                                    size="xs"
                                    color="muted"
                                >
                                    {{ speaker.organisation }}
                                </AppText>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Participants -->
                <section
                    v-if="participants.length || registrationInfo.registrations_count"
                    class="mt-16"
                >
                    <div
                        class="border-b border-border-light pb-5 dark:border-border-dark"
                    >
                        <AppText
                            tag="p"
                            font="redhat"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="brand"
                        >
                            Community
                        </AppText>

                        <div class="mt-2 flex flex-wrap items-baseline gap-3">
                            <AppHeading
                                tag="h2"
                                font="prata"
                                size="2xl"
                                weight="normal"
                            >
                                Participants
                            </AppHeading>

                            <AppText tag="span" size="sm" color="muted">
                                {{ registrationInfo.registrations_count }}
                                registered
                            </AppText>
                        </div>
                    </div>

                    <div
                        v-if="participants.length"
                        class="mt-6 flex flex-wrap gap-x-6 gap-y-5"
                    >
                        <div
                            v-for="participant in participants"
                            :key="participant.id"
                            class="flex items-center gap-3"
                        >
                            <Avatar
                                :name="participant.name"
                                :image="participant.profile_image"
                                size="w-9 h-9"
                            />

                            <AppText tag="span" font="redhat" size="sm">
                                {{ participant.name }}
                            </AppText>
                        </div>
                    </div>

                    <div v-else class="mt-6">
                        <AppText
                            tag="p"
                            font="lora"
                            size="sm"
                            color="muted"
                        >
                            {{ registrationInfo.registrations_count }}
                            people have registered for this event.
                        </AppText>
                    </div>
                </section>

                <!-- Agenda -->
                <section v-if="event.sessions?.length" class="mt-16">
                    <div
                        class="border-b border-border-light pb-5 dark:border-border-dark"
                    >
                        <AppText
                            tag="p"
                            font="redhat"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="brand"
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
                            Agenda
                        </AppHeading>
                    </div>

                    <div>
                        <article
                            v-for="session in event.sessions"
                            :key="session.id"
                            class="grid gap-4 border-b border-border-light py-7 dark:border-border-dark sm:grid-cols-[130px_1fr]"
                        >
                            <div>
                                <AppText
                                    tag="p"
                                    font="redhat"
                                    size="xs"
                                    weight="bold"
                                    color="brand"
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
                                    v-if="session.speakers?.length || session.location"
                                    class="mt-4 flex flex-wrap items-center gap-x-5 gap-y-2"
                                >
                                    <div
                                        v-if="session.speakers?.length"
                                        class="flex items-center gap-2"
                                    >
                                        <div class="flex -space-x-2">
                                            <Avatar
                                                v-for="speaker in session.speakers.slice(0, 3)"
                                                :key="speaker.id"
                                                :name="speaker.name"
                                                :image="speaker.profile_image"
                                                size="w-7 h-7"
                                                class="ring-2 ring-surface-light dark:ring-surface-dark"
                                            />
                                        </div>

                                        <AppText tag="span" size="xs" color="muted">
                                            {{ session.speakers.map(s => s.name).join(', ') }}
                                        </AppText>
                                    </div>

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
                </section>

                <!-- Tickets -->
                <section class="mt-16">
                    <div
                        class="border-b border-border-light pb-5 dark:border-border-dark"
                    >
                        <AppText
                            tag="p"
                            font="redhat"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="brand"
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

                    <div v-if="hasTickets">
                        <label
                            v-for="ticket in event.tickets"
                            :key="ticket.id"
                            class="flex cursor-pointer items-start gap-4 border-b border-border-light py-6 dark:border-border-dark"
                        >
                            <input
                                v-model="selectedTicketId"
                                type="radio"
                                name="ticket_id"
                                :value="ticket.id"
                                :disabled="pending || registered"
                                class="mt-1 accent-current"
                                @change="registrationError = ''"
                            />

                            <div class="flex-1">
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

                            <AppText
                                tag="p"
                                font="redhat"
                                size="sm"
                                weight="bold"
                            >
                                {{
                                    ticket.price === null ||
                                    ticket.price === undefined ||
                                    Number(ticket.price) === 0
                                        ? 'Free'
                                        : `${ticket.currency ?? ''} ${ticket.price}`
                                }}
                            </AppText>
                        </label>
                    </div>

                    <div v-else class="border-b border-border-light py-6 dark:border-border-dark">
                        <AppText tag="p" size="sm" color="muted">
                            Registration is not available yet because this event has no tickets. Please check back later.
                        </AppText>
                    </div>
                </section>

                <!-- Reviews -->
                <section v-if="event.reviews?.length" class="mt-16">
                    <div
                        class="flex flex-wrap items-end justify-between gap-4 border-b border-border-light pb-5 dark:border-border-dark"
                    >
                        <div>
                            <AppText
                                tag="p"
                                font="redhat"
                                size="xs"
                                weight="bold"
                                uppercase
                                tracking="wide"
                                color="brand"
                            >
                                Attendee feedback
                            </AppText>

                            <AppHeading
                                tag="h2"
                                font="prata"
                                size="2xl"
                                weight="normal"
                                class="mt-2"
                            >
                                Reviews
                            </AppHeading>
                        </div>

                        <div
                            v-if="reviewStats.count"
                            class="flex items-center gap-2 font-redhat text-sm"
                        >
                            <Star class="h-4 w-4 fill-current text-brand" />

                            {{ reviewStats.average }}

                            <span class="text-content-lightMuted dark:text-content-darkMuted">
                                ({{ reviewStats.count }})
                            </span>
                        </div>
                    </div>

                    <div>
                        <article
                            v-for="review in event.reviews"
                            :key="review.id"
                            class="flex gap-4 border-b border-border-light py-6 dark:border-border-dark"
                        >
                            <Avatar
                                :name="review.user?.name"
                                :image="review.user?.profile_image"
                                size="w-10 h-10"
                                class="shrink-0"
                            />

                            <div>
                                <AppText
                                    tag="p"
                                    font="redhat"
                                    size="sm"
                                    weight="semibold"
                                >
                                    {{ review.user?.name ?? 'Attendee' }}
                                </AppText>

                                <div class="mt-1 flex gap-0.5">
                                    <Star
                                        v-for="n in review.rating"
                                        :key="n"
                                        class="h-3 w-3 fill-current text-brand"
                                    />
                                </div>

                                <AppText
                                    v-if="review.review"
                                    tag="p"
                                    font="lora"
                                    size="sm"
                                    color="muted"
                                    leading="relaxed"
                                    class="mt-2"
                                >
                                    {{ review.review }}
                                </AppText>
                            </div>
                        </article>
                    </div>
                </section>
            </main>

            <!-- Registration sidebar -->
            <aside>
                <div
                    class="border border-border-light bg-surface-light dark:border-border-dark dark:bg-surface-dark lg:sticky lg:top-24"
                >
                    <div
                        class="border-b border-border-light p-6 dark:border-border-dark"
                    >
                        <AppText
                            tag="p"
                            font="redhat"
                            size="xs"
                            weight="bold"
                            uppercase
                            tracking="wide"
                            color="brand"
                        >
                            Attend this event
                        </AppText>

                        <AppHeading
                            tag="h2"
                            font="prata"
                            size="xl"
                            weight="normal"
                            class="mt-2"
                        >
                            Join the conversation
                        </AppHeading>
                    </div>

                    <div class="p-6">
                        <!-- Date -->
                        <div
                            class="border-b border-border-light pb-5 dark:border-border-dark"
                        >
                            <AppText
                                tag="p"
                                size="xs"
                                uppercase
                                tracking="wide"
                                weight="bold"
                                color="muted"
                            >
                                Date
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

                        <!-- Location -->
                        <div
                            class="border-b border-border-light py-5 dark:border-border-dark"
                        >
                            <AppText
                                tag="p"
                                size="xs"
                                uppercase
                                tracking="wide"
                                weight="bold"
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
                                {{ locationLabel }}
                            </AppText>
                        </div>

                        <!-- Participants -->
                        <div
                            class="border-b border-border-light py-5 dark:border-border-dark"
                        >
                            <AppText
                                tag="p"
                                size="xs"
                                uppercase
                                tracking="wide"
                                weight="bold"
                                color="muted"
                            >
                                Participation
                            </AppText>

                            <div class="mt-2 flex items-center justify-between gap-4">
                                <AppText tag="p" font="redhat" size="sm">
                                    {{ regCount }} registered
                                </AppText>

                                <AppText
                                    v-if="registrationInfo.spots_left !== null"
                                    tag="p"
                                    size="xs"
                                    color="muted"
                                >
                                    {{ registrationInfo.spots_left }}
                                    spots left
                                </AppText>
                            </div>
                        </div>

                        <!-- Registration button -->
                        <Button
                            variant="primary"
                            class="mt-4 w-full"
                            type="button"
                            :disabled="
                                pending ||
                                registered ||
                                !hasTickets ||
                                selectedTicketId === null
                            "
                            @click="toggleRegister"
                        >
                            <Check v-if="registered" class="h-4 w-4" />
                            {{ registerLabel }}
                        </Button>

                        <AppText
                            v-if="registrationError"
                            tag="p"
                            size="xs"
                            color="danger"
                            class="mt-3"
                            role="alert"
                        >
                            {{ registrationError }}
                        </AppText>

                        <AppText
                            v-if="registered"
                            tag="p"
                            size="xs"
                            color="muted"
                            class="mt-3 text-center"
                        >
                            You're registered for this event.
                        </AppText>

                        <AppText
                            v-else-if="!hasTickets"
                            tag="p"
                            size="xs"
                            color="muted"
                            class="mt-3"
                        >
                            The event organizer needs to configure at least one ticket before registration can open.
                        </AppText>
                    </div>
                </div>
            </aside>
        </div>

        <!-- Related events -->
        <section
            v-if="related.length"
            class="mt-20 border-t border-border-light pt-12 dark:border-border-dark"
        >
            <div class="flex items-end justify-between gap-6">
                <div>
                    <AppText
                        tag="p"
                        font="redhat"
                        size="xs"
                        weight="bold"
                        uppercase
                        tracking="wide"
                        color="brand"
                    >
                        Continue exploring
                    </AppText>

                    <AppHeading
                        tag="h2"
                        font="prata"
                        size="2xl"
                        weight="normal"
                        class="mt-2"
                    >
                        More events
                    </AppHeading>
                </div>

                <Link
                    href="/events"
                    class="hidden items-center gap-2 font-redhat text-sm font-semibold transition-all hover:gap-3 hover:text-brand sm:flex"
                >
                    All events

                    <ArrowRight class="h-4 w-4" />
                </Link>
            </div>

            <div class="mt-7 grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <EventCard
                    v-for="item in related"
                    :key="item.id"
                    :event="item"
                    :href="eventShow(item.slug).url"
                />
            </div>
        </section>
    </div>
</template>