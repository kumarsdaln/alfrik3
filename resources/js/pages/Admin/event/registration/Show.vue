<script setup lang="ts">
    import { computed } from 'vue'
    import { router } from '@inertiajs/vue3'
    import {
        ArrowLeft,
        CalendarDays,
        CheckCircle2,
        Clock,
        LogIn,
        LogOut,
        MapPin,
        QrCode,
        Ticket,
        UserRound,
    } from '@lucide/vue'

    import Date from '@/components/datadisplay/Date.vue'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Badge from '@/components/ui/badge/Badge.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    interface Registration {
        id: number
        registration_code: string
        qr_code: string | null
        status: string
        created_at: string

        event: {
            id: number
            title: string
            slug: string
            event_type: string
            start_date: string
            end_date: string | null
            location_name: string | null
            city: string | null
            state: string | null
            country: string | null
        } | null

        ticket: {
            id: number
            name: string
            price: number | string
            currency: string
        } | null

        user: {
            id: number
            name: string
            email: string
        } | null

        checkins: Checkin[]
    }

    interface Checkin {
        id: number
        checked_in_at: string
        type: 'entry' | 'exit'
        gate: string | null
        notes: string | null
        successful: boolean
        checked_in_by: {
            id: number
            name: string
        } | null
    }

    interface Props {
        registration: Registration
    }

    const props = defineProps<Props>()

    const lastSuccessfulCheckin = computed(() =>
        props.registration.checkins.find(checkin => checkin.successful),
    )

    const isCheckedIn = computed(() =>
        lastSuccessfulCheckin.value?.type === 'entry',
    )

    const canCheckIn = computed(() =>
        props.registration.status === 'confirmed' && !isCheckedIn.value,
    )

    const canCheckOut = computed(() =>
        props.registration.status === 'confirmed' && isCheckedIn.value,
    )

    const statusColor: Record<string, string> = {
        confirmed: 'success',
        pending: 'warning',
        cancelled: 'destructive',
        refunded: 'secondary',
    }

    function formatStatus(status: string): string {
        return status.charAt(0).toUpperCase() + status.slice(1)
    }

    function formatPrice(price: number | string, currency: string): string {
        return new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency,
            maximumFractionDigits: 2,
        }).format(Number(price))
    }

    function formatEventType(type: string): string {
        return type.charAt(0).toUpperCase() + type.slice(1)
    }

    function recordCheckin(type: 'entry' | 'exit') {
        router.post(
            `/admin/events/registrations/${props.registration.id}/checkin`,
            { type },
            {
                preserveScroll: true,
            },
        )
    }
</script>

<template>
    <TableLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4 py-5">
                <div class="flex items-center gap-4">
                    <BackButton />

                    <Heading title="Registration Details"
                        description="View attendee information and manage event check-in." />
                </div>
            </div>
        </template>

        <div class="space-y-6 py-6">
            <!-- Registration header -->
            <section class="rounded-lg border border-border bg-card p-5">
                <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-start">
                    <div class="flex items-start gap-3">
                        <div class="flex size-12 shrink-0 items-center justify-center rounded-lg border border-border">
                            <Ticket class="size-6 text-muted-foreground" />
                        </div>

                        <div class="min-w-0">
                            <h2 class="text-lg font-semibold">
                                {{ registration.registration_code }}
                            </h2>

                            <p class="mt-1 text-sm text-muted-foreground">
                                Registration #{{ registration.id }}
                            </p>

                            <p class="mt-1 text-sm text-muted-foreground">
                                Registered on
                                <Date :value="registration.created_at" />
                            </p>
                        </div>
                    </div>

                    <Badge :color="statusColor[registration.status] ?? 'secondary'">
                        {{ formatStatus(registration.status) }}
                    </Badge>
                </div>
            </section>

            <!-- Check-in actions -->
            <section class="rounded-lg border border-border bg-card p-5">
                <div class="mb-4">
                    <h2 class="font-semibold">Attendance</h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Record the attendee's entry or exit.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button type="button" :disabled="!canCheckIn"
                        class="inline-flex items-center gap-2 rounded-md bg-primary px-4 py-2 text-sm font-medium text-primary-foreground disabled:cursor-not-allowed disabled:opacity-50"
                        @click="recordCheckin('entry')">
                        <LogIn class="size-4" />
                        Check in
                    </button>

                    <button type="button" :disabled="!canCheckOut"
                        class="inline-flex items-center gap-2 rounded-md border border-border px-4 py-2 text-sm font-medium disabled:cursor-not-allowed disabled:opacity-50"
                        @click="recordCheckin('exit')">
                        <LogOut class="size-4" />
                        Check out
                    </button>
                </div>

                <p v-if="registration.status !== 'confirmed'" class="mt-3 text-sm text-muted-foreground">
                    Only confirmed registrations can check in.
                </p>

                <p v-else-if="isCheckedIn" class="mt-3 flex items-center gap-2 text-sm text-muted-foreground">
                    <CheckCircle2 class="size-4" />
                    Attendee is currently checked in.
                </p>

                <p v-else class="mt-3 text-sm text-muted-foreground">
                    Attendee is not currently checked in.
                </p>
            </section>

            <!-- Main information -->
            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Attendee -->
                <section class="rounded-lg border border-border bg-card p-5">
                    <div class="mb-4 flex items-center gap-2">
                        <UserRound class="size-5 text-muted-foreground" />
                        <h2 class="font-semibold">Attendee</h2>
                    </div>

                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm text-muted-foreground">Name</dt>
                            <dd class="mt-1 font-medium">
                                {{ registration.user?.name ?? 'Guest attendee' }}
                            </dd>
                        </div>

                        <div>
                            <dt class="text-sm text-muted-foreground">Email</dt>
                            <dd class="mt-1 break-all font-medium">
                                {{ registration.user?.email ?? '—' }}
                            </dd>
                        </div>
                    </dl>
                </section>

                <!-- Event -->
                <section class="rounded-lg border border-border bg-card p-5">
                    <div class="mb-4 flex items-center gap-2">
                        <CalendarDays class="size-5 text-muted-foreground" />
                        <h2 class="font-semibold">Event</h2>
                    </div>

                    <template v-if="registration.event">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm text-muted-foreground">Event title</dt>
                                <dd class="mt-1 font-medium">
                                    {{ registration.event.title }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm text-muted-foreground">Event type</dt>
                                <dd class="mt-1 font-medium">
                                    {{ formatEventType(registration.event.event_type) }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm text-muted-foreground">Start date</dt>
                                <dd class="mt-1 font-medium">
                                    <Date :value="registration.event.start_date" />
                                </dd>
                            </div>

                            <div v-if="registration.event.end_date">
                                <dt class="text-sm text-muted-foreground">End date</dt>
                                <dd class="mt-1 font-medium">
                                    <Date :value="registration.event.end_date" />
                                </dd>
                            </div>

                            <div v-if="
                                registration.event.location_name ||
                                registration.event.city ||
                                registration.event.state ||
                                registration.event.country
                            ">
                                <dt class="text-sm text-muted-foreground">Location</dt>
                                <dd class="mt-1 flex items-start gap-2 font-medium">
                                    <MapPin class="mt-0.5 size-4 shrink-0 text-muted-foreground" />
                                    <span>
                                        {{
                                            [
                                                registration.event.location_name,
                                                registration.event.city,
                                                registration.event.state,
                                                registration.event.country,
                                            ].filter(Boolean).join(', ')
                                        }}
                                    </span>
                                </dd>
                            </div>
                        </dl>
                    </template>

                    <p v-else class="text-sm text-muted-foreground">
                        Event information is unavailable.
                    </p>
                </section>

                <!-- Ticket -->
                <section class="rounded-lg border border-border bg-card p-5">
                    <div class="mb-4 flex items-center gap-2">
                        <Ticket class="size-5 text-muted-foreground" />
                        <h2 class="font-semibold">Ticket</h2>
                    </div>

                    <template v-if="registration.ticket">
                        <dl class="space-y-4">
                            <div>
                                <dt class="text-sm text-muted-foreground">Ticket name</dt>
                                <dd class="mt-1 font-medium">
                                    {{ registration.ticket.name }}
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm text-muted-foreground">Price</dt>
                                <dd class="mt-1 font-medium">
                                    {{
                                        formatPrice(
                                            registration.ticket.price,
                                            registration.ticket.currency,
                                        )
                                    }}
                                </dd>
                            </div>
                        </dl>
                    </template>

                    <p v-else class="text-sm text-muted-foreground">
                        Ticket information is unavailable.
                    </p>
                </section>

                <!-- QR code -->
                <section class="rounded-lg border border-border bg-card p-5">
                    <div class="mb-4 flex items-center gap-2">
                        <QrCode class="size-5 text-muted-foreground" />
                        <h2 class="font-semibold">QR Registration Code</h2>
                    </div>

                    <p class="break-all rounded-md border border-border bg-muted/30 p-3 font-mono text-sm">
                        {{ registration.qr_code ?? 'No QR code assigned' }}
                    </p>

                    <p class="mt-3 text-sm text-muted-foreground">
                        Use this code to locate the registration in the scanner.
                    </p>
                </section>
            </div>

            <!-- Check-in history -->
            <section class="rounded-lg border border-border bg-card p-5">
                <div class="mb-5">
                    <h2 class="font-semibold">Check-in History</h2>
                    <p class="mt-1 text-sm text-muted-foreground">
                        A record of attendance activity for this registration.
                    </p>
                </div>

                <div v-if="registration.checkins.length" class="divide-y divide-border">
                    <div v-for="checkin in registration.checkins" :key="checkin.id"
                        class="flex flex-col gap-3 py-4 first:pt-0 last:pb-0 sm:flex-row sm:items-start sm:justify-between">
                        <div class="flex items-start gap-3">
                            <div
                                class="flex size-10 shrink-0 items-center justify-center rounded-md border border-border">
                                <LogIn v-if="checkin.type === 'entry'" class="size-5 text-muted-foreground" />
                                <LogOut v-else class="size-5 text-muted-foreground" />
                            </div>

                            <div>
                                <div class="font-medium">
                                    {{ checkin.type === 'entry' ? 'Entry recorded' : 'Exit recorded' }}
                                </div>

                                <div class="mt-1 text-sm text-muted-foreground">
                                    <Date :value="checkin.checked_in_at" />
                                </div>

                                <div v-if="checkin.checked_in_by" class="mt-1 text-sm text-muted-foreground">
                                    Recorded by {{ checkin.checked_in_by.name }}
                                </div>

                                <div v-if="checkin.gate" class="mt-1 text-sm text-muted-foreground">
                                    Gate: {{ checkin.gate }}
                                </div>

                                <div v-if="checkin.notes" class="mt-1 text-sm text-muted-foreground">
                                    {{ checkin.notes }}
                                </div>
                            </div>
                        </div>

                        <Badge :color="checkin.successful ? 'success' : 'destructive'">
                            {{ checkin.successful ? 'Successful' : 'Unsuccessful' }}
                        </Badge>
                    </div>
                </div>

                <div v-else
                    class="flex flex-col items-center justify-center rounded-md border border-dashed border-border px-6 py-10 text-center">
                    <Clock class="mb-3 size-8 text-muted-foreground" />
                    <p class="font-medium">No attendance activity yet</p>
                    <p class="mt-1 text-sm text-muted-foreground">
                        Check-in and check-out records will appear here.
                    </p>
                </div>
            </section>
        </div>
    </TableLayout>
</template>
