<script setup lang="ts">
    import { computed, ref } from 'vue'
    import { Head, Link, usePage } from '@inertiajs/vue3'
    import axios from 'axios'

    import {
        ArrowRight,
        CalendarDays,
        Check,
        Clock,
        MapPin,
        Star,
        Users,
        Video,
    } from '@lucide/vue'

    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import Avatar from '@/components/profile/Avatar.vue'
    import EventCard from '@/components/events/EventCard.vue'

    import { register as registerRoute } from '@/routes/events'
    import { login } from '@/routes'

    import { Button } from '@/components/ui/button'

    interface Category {
        id: number
        name: string
        slug: string
    }

    interface User {
        id: number
        name: string
        profile_image?: string | null
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
        speakers?: Speaker[]
    }

    interface Ticket {
        id: number
        name: string
        description?: string | null
        price?: number | string | null
        currency?: string | null
    }

    interface Review {
        id: number
        rating: number
        review?: string | null
        user?: User | null
    }

    interface EventMedia {
        id: number
        type?: string | null
        url?: string | null
        title?: string | null
    }

    interface EventItem {
        id: number
        title: string
        slug: string

        description?: string | null

        banner?: string | null
        cover_image?: string | null

        event_type?: 'online' | 'hybrid' | 'in_person' | string | null

        start_date: string
        end_date?: string | null

        location_name?: string | null
        city?: string | null
        country?: string | null
        venue?: string | null

        max_attendees?: number | null

        categories?: Category[]
        sessions?: Session[]
        tickets?: Ticket[]
        media?: EventMedia[]
        reviews?: Review[]
    }

    interface Registration {
        registrations_count: number
        is_registered: boolean
        spots_left: number | null
    }

    interface Props {
        event: EventItem
        speakers: Speaker[]
        participants: User[]
        registration: Registration
        reviewStats: {
            average: number
            count: number
        }
        related: EventItem[]
    }

    const props = defineProps<Props>()

    const page = usePage()

    const authed = computed(() => Boolean(page.props.auth?.user))

    /*
    |--------------------------------------------------------------------------
    | Registration
    |--------------------------------------------------------------------------
    */

    const registered = ref(
        props.registration?.is_registered ?? false,
    )

    const regCount = ref(
        props.registration?.registrations_count ?? 0,
    )

    const pending = ref(false)

    async function toggleRegister() {
        if (!authed.value) {
            window.location.href = login().url
            return
        }

        if (pending.value) {
            return
        }

        const previousRegistered = registered.value
        const previousCount = regCount.value

        registered.value = !registered.value

        regCount.value += registered.value ? 1 : -1

        pending.value = true

        try {
            const { data } = await axios.post(
                registerRoute(props.event.slug).url,
            )

            registered.value = Boolean(data.registered)

            if (typeof data.registrations_count === 'number') {
                regCount.value = data.registrations_count
            }
        } catch {
            registered.value = previousRegistered
            regCount.value = previousCount
        } finally {
            pending.value = false
        }
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

    const TypeIcon = computed(() => {
        switch (props.event.event_type) {
            case 'online':
                return Video

            default:
                return MapPin
        }
    })

    /*
    |--------------------------------------------------------------------------
    | Dates
    |--------------------------------------------------------------------------
    */

    const formatDate = (
        value?: string | null,
    ) => {
        if (!value) {
            return ''
        }

        return new Intl.DateTimeFormat(
            'en-US',
            {
                weekday: 'long',
                month: 'long',
                day: 'numeric',
                year: 'numeric',
            },
        ).format(new Date(value))
    }

    const formatTime = (
        value?: string | null,
    ) => {
        if (!value) {
            return ''
        }

        return new Intl.DateTimeFormat(
            'en-US',
            {
                hour: 'numeric',
                minute: '2-digit',
            },
        ).format(new Date(value))
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
            startDate.toDateString() ===
            endDate.toDateString()
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

    const sessionTime = (
        session: Session,
    ) => {
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
        if (props.speakers?.length) {
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

        return registered.value
            ? 'Registered'
            : 'Register for this event'
    })
</script>

<template>

    <Head :title="`${event.title} — Events`">
        <meta name="description" :content="event.description ?? ''" />
    </Head>

    <!--
    |--------------------------------------------------------------------------
    | Hero
    |--------------------------------------------------------------------------
    -->

    <section class="
            border-b
            border-border-light
            dark:border-border-dark
        ">
        <div class="
                    grid
                    gap-8
                    py-10
                    sm:py-14
                    lg:grid-cols-[1.15fr_0.85fr]
                    lg:items-end
                    lg:gap-16
                    lg:py-16
                ">
            <!-- Image -->

            <div class="
                        relative
                        overflow-hidden
                        bg-surface-light
                        dark:bg-surface-dark
                    ">
                <img v-if="bannerUrl" :src="bannerUrl" :alt="event.title" class="
                            aspect-[16/10]
                            h-full
                            w-full
                            object-cover
                        " />

                <div v-else class="
                            flex
                            aspect-[16/10]
                            items-center
                            justify-center
                            border
                            border-border-light
                            dark:border-border-dark
                        ">
                    <AppText size="xs" weight="bold" uppercase tracking="wide" color="muted">
                        Event
                    </AppText>
                </div>
            </div>

            <!-- Information -->

            <div>
                <div class="
                            mb-5
                            flex
                            flex-wrap
                            items-center
                            gap-3
                        ">
                    <span class="
                                h-px
                                w-8
                                bg-brand
                            " />

                    <AppText tag="span" font="redhat" size="xs" weight="bold" uppercase tracking="wide" color="brand">
                        {{ typeLabel }}
                    </AppText>

                    <template v-for="category in event.categories" :key="category.id">
                        <span class="
                                    text-content-lightMuted
                                    dark:text-content-darkMuted
                                ">
                            /
                        </span>

                        <AppText tag="span" font="redhat" size="xs" uppercase tracking="wide" color="muted">
                            {{ category.name }}
                        </AppText>
                    </template>
                </div>

                <AppHeading tag="h1" font="prata" size="4xl" weight="normal" leading="tight" hover-brand>
                    {{ event.title }}
                </AppHeading>

                <AppText v-if="event.description" tag="p" font="lora" size="lg" color="muted" leading="relaxed"
                    class="mt-6 max-w-2xl">
                    {{ event.description }}
                </AppText>

                <!-- Event facts -->

                <div class="
                            mt-8
                            grid
                            border-y
                            border-border-light
                            dark:border-border-dark
                            sm:grid-cols-2
                        ">
                    <div class="
                                border-b
                                border-border-light
                                py-5
                                sm:border-r
                                sm:pr-6
                                dark:border-border-dark
                            ">
                        <AppText tag="p" size="xs" uppercase tracking="wide" weight="bold" color="muted">
                            Date & time
                        </AppText>

                        <AppText tag="p" font="redhat" size="sm" class="mt-2">
                            {{ dateRange }}
                        </AppText>
                    </div>

                    <div class="py-5 sm:pl-6">
                        <AppText tag="p" size="xs" uppercase tracking="wide" weight="bold" color="muted">
                            Location
                        </AppText>

                        <AppText tag="p" font="redhat" size="sm" class="mt-2">
                            {{ locationLabel }}
                        </AppText>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!--
    |--------------------------------------------------------------------------
    | Main
    |--------------------------------------------------------------------------
    -->

    <div class="py-12 lg:py-16">
        <div class="
                grid
                gap-14
                lg:grid-cols-[minmax(0,1fr)_320px]
                lg:gap-16
            ">
            <!--
            |--------------------------------------------------------------------------
            | Main content
            |--------------------------------------------------------------------------
            -->

            <main>
                <!-- About -->

                <section v-if="event.description">
                    <AppText tag="p" font="redhat" size="xs" weight="bold" uppercase tracking="wide" color="brand">
                        About the event
                    </AppText>

                    <AppHeading tag="h2" font="prata" size="2xl" weight="normal" leading="tight" class="mt-3">
                        {{ event.title }}
                    </AppHeading>

                    <AppText tag="p" font="lora" size="base" color="muted" leading="relaxed" class="mt-5 max-w-3xl">
                        {{ event.description }}
                    </AppText>
                </section>

                <!--
                |--------------------------------------------------------------------------
                | Speakers
                |--------------------------------------------------------------------------
                -->

                <section v-if="allSpeakers.length" class="mt-16">
                    <div class="
                            flex
                            items-end
                            justify-between
                            border-b
                            border-border-light
                            pb-5
                            dark:border-border-dark
                        ">
                        <div>
                            <AppText tag="p" font="redhat" size="xs" weight="bold" uppercase tracking="wide"
                                color="brand">
                                People
                            </AppText>

                            <AppHeading tag="h2" font="prata" size="2xl" weight="normal" class="mt-2">
                                Speakers
                            </AppHeading>
                        </div>

                        <AppText tag="span" size="sm" color="muted">
                            {{ allSpeakers.length }}
                            {{ allSpeakers.length === 1 ? 'speaker' : 'speakers' }}
                        </AppText>
                    </div>

                    <div class="
                            grid
                            divide-y
                            divide-border-light
                            dark:divide-border-dark
                            sm:grid-cols-2
                            sm:divide-y-0
                            sm:divide-x
                            sm:border-b
                            dark:sm:divide-border-dark
                        ">
                        <div v-for="speaker in allSpeakers" :key="speaker.id" class="
                                flex
                                items-center
                                gap-4
                                py-6
                                sm:px-5
                                lg:px-6
                            ">
                            <Avatar :name="speaker.name" :image="speaker.profile_image" size="w-14 h-14"
                                class="shrink-0" />

                            <div class="min-w-0">
                                <AppText tag="p" font="redhat" size="sm" weight="semibold">
                                    {{ speaker.name }}
                                </AppText>

                                <AppText v-if="speaker.role" tag="p" size="xs" color="muted" class="mt-1">
                                    {{ speaker.role }}
                                </AppText>

                                <AppText v-if="speaker.organisation" tag="p" size="xs" color="muted">
                                    {{ speaker.organisation }}
                                </AppText>
                            </div>
                        </div>
                    </div>
                </section>

                <!--
                |--------------------------------------------------------------------------
                | Participants
                |--------------------------------------------------------------------------
                -->

                <section v-if="participants.length || registration.registrations_count" class="mt-16">
                    <div class="
                            border-b
                            border-border-light
                            pb-5
                            dark:border-border-dark
                        ">
                        <AppText tag="p" font="redhat" size="xs" weight="bold" uppercase tracking="wide" color="brand">
                            Community
                        </AppText>

                        <div class="
                                mt-2
                                flex
                                flex-wrap
                                items-baseline
                                gap-3
                            ">
                            <AppHeading tag="h2" font="prata" size="2xl" weight="normal">
                                Participants
                            </AppHeading>

                            <AppText tag="span" size="sm" color="muted">
                                {{ registration.registrations_count }}
                                registered
                            </AppText>
                        </div>
                    </div>

                    <div v-if="participants.length" class="
                            mt-6
                            flex
                            flex-wrap
                            gap-x-6
                            gap-y-5
                        ">
                        <div v-for="participant in participants" :key="participant.id" class="
                                flex
                                items-center
                                gap-3
                            ">
                            <Avatar :name="participant.name" :image="participant.profile_image" size="w-9 h-9" />

                            <AppText tag="span" font="redhat" size="sm">
                                {{ participant.name }}
                            </AppText>
                        </div>
                    </div>

                    <div v-else class="mt-6">
                        <AppText tag="p" font="lora" size="sm" color="muted">
                            {{ registration.registrations_count }}
                            people have registered for this event.
                        </AppText>
                    </div>
                </section>

                <!--
                |--------------------------------------------------------------------------
                | Agenda
                |--------------------------------------------------------------------------
                -->

                <section v-if="event.sessions?.length" class="mt-16">
                    <div class="
                            border-b
                            border-border-light
                            pb-5
                            dark:border-border-dark
                        ">
                        <AppText tag="p" font="redhat" size="xs" weight="bold" uppercase tracking="wide" color="brand">
                            Programme
                        </AppText>

                        <AppHeading tag="h2" font="prata" size="2xl" weight="normal" class="mt-2">
                            Agenda
                        </AppHeading>
                    </div>

                    <div>
                        <article v-for="session in event.sessions" :key="session.id" class="
                                grid
                                gap-4
                                border-b
                                border-border-light
                                py-7
                                dark:border-border-dark
                                sm:grid-cols-[130px_1fr]
                            ">
                            <div>
                                <AppText tag="p" font="redhat" size="xs" weight="bold" color="brand">
                                    {{ sessionTime(session) }}
                                </AppText>
                            </div>

                            <div>
                                <AppHeading tag="h3" font="prata" size="lg" weight="normal" leading="tight">
                                    {{ session.title }}
                                </AppHeading>

                                <AppText v-if="session.description" tag="p" font="lora" size="sm" color="muted"
                                    leading="relaxed" class="mt-2 max-w-2xl">
                                    {{ session.description }}
                                </AppText>

                                <div v-if="session.speakers?.length || session.location" class="
                                        mt-4
                                        flex
                                        flex-wrap
                                        items-center
                                        gap-x-5
                                        gap-y-2
                                    ">
                                    <div v-if="session.speakers?.length" class="
                                            flex
                                            items-center
                                            gap-2
                                        ">
                                        <div class="flex -space-x-2">
                                            <Avatar v-for="speaker in session.speakers.slice(0, 3)" :key="speaker.id"
                                                :name="speaker.name" :image="speaker.profile_image" size="w-7 h-7"
                                                class="
                                                    ring-2
                                                    ring-surface-light
                                                    dark:ring-surface-dark
                                                " />
                                        </div>

                                        <AppText tag="span" size="xs" color="muted">
                                            {{session.speakers.map(s => s.name).join(', ')}}
                                        </AppText>
                                    </div>

                                    <AppText v-if="session.location" tag="span" size="xs" color="muted">
                                        {{ session.location }}
                                    </AppText>
                                </div>
                            </div>
                        </article>
                    </div>
                </section>

                <!--
                |--------------------------------------------------------------------------
                | Tickets
                |--------------------------------------------------------------------------
                -->

                <section v-if="event.tickets?.length" class="mt-16">
                    <div class="
                            border-b
                            border-border-light
                            pb-5
                            dark:border-border-dark
                        ">
                        <AppText tag="p" font="redhat" size="xs" weight="bold" uppercase tracking="wide" color="brand">
                            Admission
                        </AppText>

                        <AppHeading tag="h2" font="prata" size="2xl" weight="normal" class="mt-2">
                            Tickets
                        </AppHeading>
                    </div>

                    <div>
                        <div v-for="ticket in event.tickets" :key="ticket.id" class="
                                flex
                                flex-col
                                gap-4
                                border-b
                                border-border-light
                                py-6
                                sm:flex-row
                                sm:items-center
                                sm:justify-between
                                dark:border-border-dark
                            ">
                            <div>
                                <AppHeading tag="h3" font="prata" size="lg" weight="normal">
                                    {{ ticket.name }}
                                </AppHeading>

                                <AppText v-if="ticket.description" tag="p" size="sm" color="muted" class="mt-1">
                                    {{ ticket.description }}
                                </AppText>
                            </div>

                            <AppText tag="p" font="redhat" size="sm" weight="bold">
                                {{
                                    ticket.price === null ||
                                        ticket.price === undefined
                                        ? 'Free'
                                        : `${ticket.currency ?? ''} ${ticket.price}`
                                }}
                            </AppText>
                        </div>
                    </div>
                </section>

                <!--
                |--------------------------------------------------------------------------
                | Reviews
                |--------------------------------------------------------------------------
                -->

                <section v-if="event.reviews?.length" class="mt-16">
                    <div class="
                            flex
                            flex-wrap
                            items-end
                            justify-between
                            gap-4
                            border-b
                            border-border-light
                            pb-5
                            dark:border-border-dark
                        ">
                        <div>
                            <AppText tag="p" font="redhat" size="xs" weight="bold" uppercase tracking="wide"
                                color="brand">
                                Attendee feedback
                            </AppText>

                            <AppHeading tag="h2" font="prata" size="2xl" weight="normal" class="mt-2">
                                Reviews
                            </AppHeading>
                        </div>

                        <div v-if="reviewStats.count" class="
                                flex
                                items-center
                                gap-2
                                font-redhat
                                text-sm
                            ">
                            <Star class="
                                    h-4
                                    w-4
                                    fill-current
                                    text-brand
                                " />

                            {{ reviewStats.average }}

                            <span class="text-content-lightMuted dark:text-content-darkMuted">
                                ({{ reviewStats.count }})
                            </span>
                        </div>
                    </div>

                    <div>
                        <article v-for="review in event.reviews" :key="review.id" class="
                                flex
                                gap-4
                                border-b
                                border-border-light
                                py-6
                                dark:border-border-dark
                            ">
                            <Avatar :name="review.user?.name" :image="review.user?.profile_image" size="w-10 h-10"
                                class="shrink-0" />

                            <div>
                                <AppText tag="p" font="redhat" size="sm" weight="semibold">
                                    {{ review.user?.name ?? 'Attendee' }}
                                </AppText>

                                <div class="
                                        mt-1
                                        flex
                                        gap-0.5
                                    ">
                                    <Star v-for="n in review.rating" :key="n" class="
                                            h-3
                                            w-3
                                            fill-current
                                            text-brand
                                        " />
                                </div>

                                <AppText v-if="review.review" tag="p" font="lora" size="sm" color="muted"
                                    leading="relaxed" class="mt-2">
                                    {{ review.review }}
                                </AppText>
                            </div>
                        </article>
                    </div>
                </section>
            </main>

            <!--
            |--------------------------------------------------------------------------
            | Registration Sidebar
            |--------------------------------------------------------------------------
            -->

            <aside>
                <div class="
                        lg:sticky
                        lg:top-24
                        border
                        border-border-light
                        bg-surface-light
                        dark:border-border-dark
                        dark:bg-surface-dark
                    ">
                    <div class="border-b border-border-light p-6 dark:border-border-dark">
                        <AppText tag="p" font="redhat" size="xs" weight="bold" uppercase tracking="wide" color="brand">
                            Attend this event
                        </AppText>

                        <AppHeading tag="h2" font="prata" size="xl" weight="normal" class="mt-2">
                            Join the conversation
                        </AppHeading>
                    </div>

                    <div class="p-6">
                        <!-- Date -->

                        <div class="border-b border-border-light pb-5 dark:border-border-dark">
                            <AppText tag="p" size="xs" uppercase tracking="wide" weight="bold" color="muted">
                                Date
                            </AppText>

                            <AppText tag="p" font="redhat" size="sm" class="mt-2">
                                {{ dateRange }}
                            </AppText>
                        </div>

                        <!-- Location -->

                        <div class="border-b border-border-light py-5 dark:border-border-dark">
                            <AppText tag="p" size="xs" uppercase tracking="wide" weight="bold" color="muted">
                                Location
                            </AppText>

                            <AppText tag="p" font="redhat" size="sm" class="mt-2">
                                {{ locationLabel }}
                            </AppText>
                        </div>

                        <!-- Participants -->

                        <div class="border-b border-border-light py-5 dark:border-border-dark">
                            <AppText tag="p" size="xs" uppercase tracking="wide" weight="bold" color="muted">
                                Participation
                            </AppText>

                            <div class="mt-2 flex items-center justify-between gap-4">
                                <AppText tag="p" font="redhat" size="sm">
                                    {{ regCount }} registered
                                </AppText>

                                <AppText v-if="registration.spots_left !== null" tag="p" size="xs" color="muted">
                                    {{ registration.spots_left }}
                                    spots left
                                </AppText>
                            </div>
                        </div>

                        <!-- CTA -->

                        <Button variant="primary" class="mt-4 w-full" type="button" :disabled="pending"
                            @click="toggleRegister">
                            <Check v-if="registered" class="h-4 w-4" />

                            {{ registerLabel }}
                        </Button>

                        <AppText v-if="registered" tag="p" size="xs" color="muted" align="center" class="mt-3">
                            You're registered for this event.
                        </AppText>
                    </div>
                </div>
            </aside>
        </div>

        <!--
        |--------------------------------------------------------------------------
        | Related Events
        |--------------------------------------------------------------------------
        -->

        <section v-if="related.length" class="mt-20 border-t border-border-light pt-12 dark:border-border-dark">
            <div class="
                    flex
                    items-end
                    justify-between
                    gap-6
                ">
                <div>
                    <AppText tag="p" font="redhat" size="xs" weight="bold" uppercase tracking="wide" color="brand">
                        Continue exploring
                    </AppText>

                    <AppHeading tag="h2" font="prata" size="2xl" weight="normal" class="mt-2">
                        More events
                    </AppHeading>
                </div>

                <Link href="/events" class="
                        hidden
                        items-center
                        gap-2
                        font-redhat
                        text-sm
                        font-semibold
                        transition-all
                        hover:gap-3
                        hover:text-brand
                        sm:flex
                    ">
                    All events

                    <ArrowRight class="h-4 w-4" />
                </Link>
            </div>

            <div class="
                    mt-7
                    grid
                    gap-6
                    sm:grid-cols-2
                    lg:grid-cols-3
                ">
                <EventCard v-for="item in related" :key="item.id" :event="item" />
            </div>
        </section>
    </div>
</template>