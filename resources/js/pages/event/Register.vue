<script setup lang="ts">
import { computed, watch } from 'vue'
import { Head, Link, useForm, usePage } from '@inertiajs/vue3'
import { CalendarDays, Check, MapPin, Ticket } from '@lucide/vue'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import { Button } from '@/components/ui/button'
import { register as registerRoute, show as eventShow } from '@/routes/events'
import { login } from '@/routes'

interface EventCategory {
    id: number
    name: string
    slug: string
}

interface EventTicket {
    id: number
    name: string
    slug: string
    description?: string | null
    price: number | string | null
    currency?: string | null
    sales_start?: string | null
    sales_end?: string | null
    is_visible?: boolean
    is_active?: boolean
}

interface EventItem {
    id: number
    title: string
    slug: string
    description?: string | null
    banner?: string | null
    cover_image?: string | null
    start_date: string
    end_date?: string | null
    event_type?: 'online' | 'offline' | 'hybrid' | string
    location_name?: string | null
    city?: string | null
    state?: string | null
    country?: string | null
    categories?: EventCategory[]
}

interface Props {
    event: EventItem
    tickets: EventTicket[]
}

const props = defineProps<Props>()
const page = usePage()
const authenticated = computed(() => Boolean(page.props.auth?.user))

const availableTickets = computed(() => {
    const now = Date.now()

    return (props.tickets ?? []).filter((ticket) => {
        if (ticket.is_visible === false || ticket.is_active === false) return false
        if (ticket.sales_start && new Date(ticket.sales_start).getTime() > now) return false
        if (ticket.sales_end && new Date(ticket.sales_end).getTime() < now) return false
        return true
    })
})

const form = useForm<{ ticket_id: number | null }>({
    ticket_id: availableTickets.value[0]?.id ?? null,
})

watch(availableTickets, (tickets) => {
    if (!tickets.some((ticket) => ticket.id === form.ticket_id)) {
        form.ticket_id = tickets[0]?.id ?? null
    }
})

const selectedTicket = computed(() =>
    availableTickets.value.find((ticket) => ticket.id === form.ticket_id) ?? null,
)
const selectedPrice = computed(() => Number(selectedTicket.value?.price ?? 0))
const isFree = computed(() => selectedTicket.value !== null && selectedPrice.value <= 0)

const bannerUrl = computed(() => {
    const value = (props.event.banner || props.event.cover_image || '').trim()
    if (!value) return ''
    if (value.startsWith('/') || value.startsWith('http://') || value.startsWith('https://')) return value
    return `/${value}`
})

const locationLabel = computed(() => {
    if (props.event.event_type === 'online') return 'Online event'

    return [
        props.event.location_name,
        props.event.city,
        props.event.state,
        props.event.country,
    ]
        .filter((value, index, values): value is string => Boolean(value) && values.indexOf(value) === index)
        .join(', ') || 'Location to be announced'
})

function formatDate(value?: string | null): string {
    if (!value) return ''
    const date = new Date(value)
    if (Number.isNaN(date.getTime())) return ''

    return new Intl.DateTimeFormat('en-IN', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(date)
}

function formatPrice(ticket: EventTicket): string {
    const price = Number(ticket.price ?? 0)
    if (!Number.isFinite(price) || price <= 0) return 'Free'

    const currency = ticket.currency || 'INR'
    try {
        return new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency,
            maximumFractionDigits: 2,
        }).format(price)
    } catch {
        return `${currency} ${price.toLocaleString('en-IN')}`
    }
}

function register(): void {
    if (!authenticated.value) {
        window.location.href = login().url
        return
    }

    if (!form.ticket_id || form.processing) return

    form.post(registerRoute(props.event.slug).url, {
        preserveScroll: true,
    })
}
</script>

<template>
    <Head :title="`Register — ${event.title}`">
        <meta name="description" :content="`Choose a ticket and register for ${event.title}.`" />
    </Head>

    <section class="border-b border-border-light dark:border-border-dark">
        <div class="mx-auto grid max-w-7xl gap-8 px-5 py-10 sm:px-8 sm:py-14 lg:grid-cols-[minmax(0,1fr)_minmax(320px,0.8fr)] lg:items-center lg:gap-16 lg:py-16">
            <div>
                <div class="mb-5 flex flex-wrap items-center gap-3">
                    <span class="h-px w-8 bg-brand" />
                    <AppText tag="span" font="redhat" size="xs" weight="bold" uppercase tracking="wide" color="brand">
                        Event registration
                    </AppText>
                </div>

                <AppHeading tag="h1" font="prata" size="4xl" weight="normal" leading="tight">
                    Reserve your place
                </AppHeading>
                <AppText tag="p" font="lora" size="sm" color="muted" leading="relaxed" class="mt-5 max-w-2xl">
                    Choose the ticket that suits you and complete your registration for this event.
                </AppText>

                <div class="mt-8 flex flex-wrap gap-x-6 gap-y-3 border-y border-border-light py-5 dark:border-border-dark">
                    <div class="flex items-start gap-3">
                        <CalendarDays class="mt-0.5 h-4 w-4 shrink-0 text-brand" />
                        <div>
                            <AppText tag="p" size="xs" weight="bold" uppercase tracking="wide" color="muted">Date</AppText>
                            <AppText tag="p" font="redhat" size="sm" class="mt-1">{{ formatDate(event.start_date) || 'Date to be announced' }}</AppText>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <MapPin class="mt-0.5 h-4 w-4 shrink-0 text-brand" />
                        <div>
                            <AppText tag="p" size="xs" weight="bold" uppercase tracking="wide" color="muted">Location</AppText>
                            <AppText tag="p" font="redhat" size="sm" class="mt-1">{{ locationLabel }}</AppText>
                        </div>
                    </div>
                </div>
            </div>

            <div class="relative overflow-hidden border border-border-light bg-surface-light dark:border-border-dark dark:bg-surface-dark">
                <img v-if="bannerUrl" :src="bannerUrl" :alt="event.title" class="aspect-[16/10] w-full object-cover" loading="lazy" decoding="async" />
                <div v-else class="flex aspect-[16/10] items-center justify-center bg-surface-light dark:bg-surface-dark">
                    <AppText size="xs" weight="bold" uppercase tracking="wide" color="muted">Event</AppText>
                </div>
                <div class="border-t border-border-light p-5 dark:border-border-dark sm:p-6">
                    <div v-if="event.categories?.length" class="mb-3 flex flex-wrap gap-2">
                        <AppText v-for="category in event.categories" :key="category.id" tag="span" size="xs" weight="bold" uppercase tracking="wide" color="brand">
                            {{ category.name }}
                        </AppText>
                    </div>
                    <AppHeading tag="h2" font="prata" size="xl" weight="normal" leading="tight">{{ event.title }}</AppHeading>
                    <AppText v-if="event.description" tag="p" font="lora" size="sm" color="muted" leading="relaxed" class="mt-3">
                        {{ event.description }}
                    </AppText>
                    <Link :href="eventShow(event.slug).url" class="mt-5 inline-flex items-center font-redhat text-sm font-semibold text-brand hover:underline">
                        View event details
                    </Link>
                </div>
            </div>
        </div>
    </section>

    <div class="mx-auto grid max-w-7xl gap-12 px-5 py-12 sm:px-8 lg:grid-cols-[minmax(0,1fr)_320px] lg:gap-16 lg:py-16">
        <main>
            <div class="border-b border-border-light pb-5 dark:border-border-dark">
                <AppText tag="p" font="redhat" size="xs" weight="bold" uppercase tracking="wide" color="brand">Admission</AppText>
                <AppHeading tag="h2" font="prata" size="2xl" weight="normal" class="mt-2">Select a ticket</AppHeading>
                <AppText tag="p" size="sm" color="muted" class="mt-2">Select one ticket type for your registration.</AppText>
            </div>

            <div v-if="availableTickets.length" class="divide-y divide-border-light dark:divide-border-dark">
                <label v-for="ticket in availableTickets" :key="ticket.id" class="flex cursor-pointer items-start gap-4 py-6">
                    <input v-model="form.ticket_id" type="radio" name="ticket_id" :value="ticket.id" class="mt-1 h-4 w-4 shrink-0 accent-current" :aria-label="`Select ${ticket.name}`" />
                    <span class="min-w-0 flex-1">
                        <span class="flex flex-wrap items-start justify-between gap-3">
                            <AppHeading tag="span" font="prata" size="lg" weight="normal">{{ ticket.name }}</AppHeading>
                            <AppText tag="span" font="redhat" size="sm" weight="bold">{{ formatPrice(ticket) }}</AppText>
                        </span>
                        <AppText v-if="ticket.description" tag="span" size="sm" color="muted" leading="relaxed" class="mt-2 block">
                            {{ ticket.description }}
                        </AppText>
                    </span>
                </label>
            </div>
            <div v-else class="border-b border-border-light py-8 dark:border-border-dark">
                <AppText tag="p" font="lora" size="sm" color="muted" leading="relaxed">
                    Registration is not currently available for this event. Please check back later.
                </AppText>
            </div>

            <p v-if="form.errors.ticket_id" class="mt-3 text-sm text-red-600">{{ form.errors.ticket_id }}</p>
            <p v-if="form.errors.event" class="mt-3 text-sm text-red-600">{{ form.errors.event }}</p>
        </main>

        <aside>
            <div class="border border-border-light bg-surface-light dark:border-border-dark dark:bg-surface-dark lg:sticky lg:top-24">
                <div class="border-b border-border-light p-6 dark:border-border-dark">
                    <AppText tag="p" font="redhat" size="xs" weight="bold" uppercase tracking="wide" color="brand">Your registration</AppText>
                    <AppHeading tag="h2" font="prata" size="xl" weight="normal" class="mt-2">Booking summary</AppHeading>
                </div>
                <div class="p-6">
                    <div class="flex items-start gap-3">
                        <Ticket class="mt-0.5 h-5 w-5 shrink-0 text-brand" />
                        <div class="min-w-0 flex-1">
                            <AppText tag="p" size="xs" weight="bold" uppercase tracking="wide" color="muted">Selected ticket</AppText>
                            <AppText tag="p" font="redhat" size="sm" weight="semibold" class="mt-2">{{ selectedTicket?.name ?? 'No ticket selected' }}</AppText>
                            <AppText v-if="selectedTicket?.description" tag="p" size="xs" color="muted" class="mt-1">{{ selectedTicket.description }}</AppText>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-between gap-4 border-t border-border-light pt-5 dark:border-border-dark">
                        <AppText tag="span" size="sm" color="muted">Total</AppText>
                        <AppText tag="span" font="redhat" size="lg" weight="bold">{{ selectedTicket ? formatPrice(selectedTicket) : '—' }}</AppText>
                    </div>

                    <Button variant="primary" type="button" class="mt-6 w-full" :disabled="!selectedTicket || form.processing || availableTickets.length === 0" @click="register">
                        <Check v-if="isFree" class="h-4 w-4" />
                        {{ form.processing ? 'Submitting…' : (authenticated ? (isFree ? 'Complete registration' : 'Continue to payment') : 'Sign in to register') }}
                    </Button>

                    <AppText v-if="!authenticated" tag="p" size="xs" color="muted" leading="relaxed" class="mt-3 text-center">
                        Sign in is required to register for this event.
                    </AppText>
                    <AppText v-else tag="p" size="xs" color="muted" leading="relaxed" class="mt-3 text-center">
                        Paid tickets require successful payment before confirmation.
                    </AppText>
                    <Link :href="eventShow(event.slug).url" class="mt-5 block text-center font-redhat text-sm text-muted hover:text-brand">
                        Back to event details
                    </Link>
                </div>
            </div>
        </aside>
    </div>
</template>
