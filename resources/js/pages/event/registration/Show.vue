<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import QRCode from 'qrcode'
import { Head, Link } from '@inertiajs/vue3'
import { CalendarDays, CheckCircle2, MapPin, Ticket } from '@lucide/vue'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import { Button } from '@/components/ui/button'
import { show as eventShow } from '@/routes/events'

interface EventItem {
    id: number
    title: string
    slug: string
    start_date: string
    end_date?: string | null
    event_type?: string
    location_name?: string | null
    city?: string | null
    state?: string | null
    country?: string | null
}

interface TicketItem {
    id: number
    name: string
    price?: number | string | null
    currency?: string | null
}

interface RegistrationItem {
    id: number
    registration_code: string
    status: string
    qr_code?: string | null
    created_at?: string | null
    checked_in_at?: string | null
}

interface Props {
    event: EventItem
    registration: RegistrationItem
    ticket?: TicketItem | null
}

const props = defineProps<Props>()

const isConfirmed = computed(() => props.registration.status?.toLowerCase() === 'confirmed')

const statusLabel = computed(() => {
    switch (props.registration.status?.toLowerCase()) {
        case 'confirmed': return 'Confirmed'
        case 'pending': return 'Pending payment or confirmation'
        case 'cancelled': return 'Cancelled'
        case 'refunded': return 'Refunded'
        default: return props.registration.status || 'Unknown'
    }
})

const locationLabel = computed(() => {
    if (props.event.event_type === 'online') return 'Online event'

    return [props.event.location_name, props.event.city, props.event.state, props.event.country]
        .filter((value, index, values): value is string => Boolean(value) && values.indexOf(value) === index)
        .join(', ') || 'Location to be announced'
})

const qrCodeDataUrl = ref('')
const qrCodeError = ref(false)

watch(
    () => [props.registration.qr_code, props.registration.status] as const,
    async ([token, status]) => {
        qrCodeDataUrl.value = ''
        qrCodeError.value = false

        if (!token || status?.toLowerCase() !== 'confirmed') return

        try {
            qrCodeDataUrl.value = await QRCode.toDataURL(token, {
                errorCorrectionLevel: 'M',
                margin: 2,
                width: 320,
                color: { dark: '#111111', light: '#FFFFFF' },
            })
        } catch {
            qrCodeError.value = true
        }
    },
    { immediate: true },
)

function formatDate(value?: string | null): string {
    if (!value) return ''
    const date = new Date(value)
    if (Number.isNaN(date.getTime())) return ''

    return new Intl.DateTimeFormat('en-IN', {
        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric',
    }).format(date)
}

function formatPrice(ticket?: TicketItem | null): string {
    if (!ticket) return '—'
    const price = Number(ticket.price ?? 0)
    if (!Number.isFinite(price) || price <= 0) return 'Free'

    const currency = ticket.currency || 'INR'
    try {
        return new Intl.NumberFormat('en-IN', {
            style: 'currency', currency, maximumFractionDigits: 2,
        }).format(price)
    } catch {
        return `${currency} ${price.toLocaleString('en-IN')}`
    }
}
</script>

<template>
    <Head :title="`Registration ${registration.registration_code} — ${event.title}`">
        <meta name="description" :content="`Registration details for ${event.title}.`" />
    </Head>

    <div class="mx-auto max-w-4xl px-5 py-12 sm:px-8 sm:py-16">
        <div class="border border-border-light bg-surface-light dark:border-border-dark dark:bg-surface-dark">
            <div class="border-b border-border-light p-6 dark:border-border-dark sm:p-8">
                <div class="flex items-start gap-4">
                    <div class="flex h-12 w-12 shrink-0 items-center justify-center border border-border-light dark:border-border-dark">
                        <CheckCircle2 class="h-6 w-6" :class="isConfirmed ? 'text-green-600' : 'text-brand'" />
                    </div>
                    <div class="min-w-0">
                        <AppText tag="p" font="redhat" size="xs" weight="bold" uppercase tracking="wide" color="brand">Registration details</AppText>
                        <AppHeading tag="h1" font="prata" size="3xl" weight="normal" leading="tight" class="mt-2">
                            {{ isConfirmed ? 'You’re registered' : 'Your registration status' }}
                        </AppHeading>
                        <AppText tag="p" size="sm" color="muted" leading="relaxed" class="mt-3">
                            {{ isConfirmed ? 'Keep this confirmation and your booking reference for the event.' : 'Check the status below before attending. A ticket is valid only when its status is confirmed.' }}
                        </AppText>
                    </div>
                </div>
            </div>

            <div class="grid gap-8 p-6 sm:p-8 md:grid-cols-[minmax(0,1fr)_220px]">
                <div>
                    <AppText tag="p" font="redhat" size="xs" weight="bold" uppercase tracking="wide" color="brand">Event</AppText>
                    <AppHeading tag="h2" font="prata" size="2xl" weight="normal" leading="tight" class="mt-2">{{ event.title }}</AppHeading>

                    <div class="mt-6 space-y-4">
                        <div class="flex items-start gap-3">
                            <CalendarDays class="mt-0.5 h-4 w-4 shrink-0 text-brand" />
                            <div>
                                <AppText tag="p" size="xs" weight="bold" uppercase tracking="wide" color="muted">Date</AppText>
                                <AppText tag="p" font="redhat" size="sm" class="mt-1">{{ formatDate(event.start_date) || 'Date to be announced' }}</AppText>
                                <AppText v-if="event.end_date && formatDate(event.end_date) !== formatDate(event.start_date)" tag="p" size="sm" color="muted" class="mt-1">Ends {{ formatDate(event.end_date) }}</AppText>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <MapPin class="mt-0.5 h-4 w-4 shrink-0 text-brand" />
                            <div>
                                <AppText tag="p" size="xs" weight="bold" uppercase tracking="wide" color="muted">Location</AppText>
                                <AppText tag="p" font="redhat" size="sm" class="mt-1">{{ locationLabel }}</AppText>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <Ticket class="mt-0.5 h-4 w-4 shrink-0 text-brand" />
                            <div>
                                <AppText tag="p" size="xs" weight="bold" uppercase tracking="wide" color="muted">Ticket</AppText>
                                <AppText tag="p" font="redhat" size="sm" class="mt-1">{{ ticket?.name ?? 'Event registration' }}</AppText>
                                <AppText tag="p" size="sm" color="muted" class="mt-1">{{ formatPrice(ticket) }}</AppText>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 border-y border-border-light py-5 dark:border-border-dark">
                        <AppText tag="p" size="xs" weight="bold" uppercase tracking="wide" color="muted">Booking reference</AppText>
                        <AppText tag="p" font="redhat" size="lg" weight="bold" class="mt-2 break-all">{{ registration.registration_code }}</AppText>
                        <div class="mt-4 flex flex-wrap items-center gap-3">
                            <AppText tag="span" size="xs" color="muted">Status</AppText>
                            <span class="inline-flex items-center border px-3 py-1 text-xs font-semibold" :class="isConfirmed ? 'border-green-700/30 text-green-700 dark:text-green-400' : 'border-border-light text-content-light dark:border-border-dark dark:text-content-dark'">
                                {{ statusLabel }}
                            </span>
                        </div>
                    </div>
                </div>

                <aside class="flex flex-col items-center justify-start border-t border-border-light pt-6 dark:border-border-dark md:border-l md:border-t-0 md:pl-6 md:pt-0">
                    <AppText tag="p" size="xs" weight="bold" uppercase tracking="wide" color="muted" class="text-center">Entry QR code</AppText>
                    <div class="mt-4 flex aspect-square w-full max-w-[200px] items-center justify-center border border-border-light bg-white p-3 dark:border-border-dark">
                        <img v-if="isConfirmed && qrCodeDataUrl" :src="qrCodeDataUrl" :alt="`QR code for registration ${registration.registration_code}`" class="h-full w-full object-contain" />
                        <div v-else class="px-3 text-center">
                            <Ticket class="mx-auto h-8 w-8 text-muted" />
                            <AppText tag="p" size="xs" color="muted" class="mt-3">{{ qrCodeError ? 'Could not generate the QR code. Please refresh the page.' : isConfirmed ? 'QR code is not available for this registration.' : 'A QR code is available after registration is confirmed.' }}</AppText>
                        </div>
                    </div>
                    <AppText tag="p" size="xs" color="muted" leading="relaxed" class="mt-4 text-center">Present this code at check-in if the event supports QR entry.</AppText>
                </aside>
            </div>

            <div class="flex flex-col gap-3 border-t border-border-light p-6 dark:border-border-dark sm:flex-row sm:items-center sm:justify-between sm:p-8">
                <AppText tag="p" size="xs" color="muted" leading="relaxed">Keep your booking reference accessible on the day of the event.</AppText>
                <Link :href="eventShow(event.slug).url">
                    <Button variant="primary" type="button" class="w-full sm:w-auto">Back to event</Button>
                </Link>
            </div>
        </div>
    </div>
</template>
