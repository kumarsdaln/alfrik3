<script setup lang="ts">
    import { Head, Link, usePage } from '@inertiajs/vue3'
    import { computed, ref } from 'vue'
    import axios from 'axios'
    import {
        MapPin, Video, Globe, Users, CalendarDays, Clock, Check, Star, ArrowRight,
    } from '@lucide/vue'

    import AppContainer from '@/components/ui/AppContainer.vue'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppBadge from '@/components/ui/AppBadge.vue'
    import EventCard from '@/Components/Cards/EventCard.vue'
    import Avatar from '@/Components/profile/Avatar.vue'
    import { register as registerRoute } from '@/routes/events'
    import { login } from '@/routes'
    import type { BreadcrumbItem } from '@/types'

    const props = defineProps<{
        event: any
        speakers: any[]
        registration: { registrations_count: number; is_registered: boolean; spots_left: number | null }
        reviewStats: { average: number; count: number }
        related: any[]
        breadcrumbs?: BreadcrumbItem[]
    }>()

    const page = usePage()
    const authed = computed(() => Boolean(page.props.auth?.user))

    /* Registration (auth-gated toggle, optimistic) */
    const registered = ref(props.registration.is_registered)
    const regCount = ref(props.registration.registrations_count)
    const pending = ref(false)

    async function toggleRegister() {
        if (!authed.value) { window.location.href = login().url; return }
        if (pending.value) return
        registered.value = !registered.value
        regCount.value += registered.value ? 1 : -1
        pending.value = true
        try {
            const { data } = await axios.post(registerRoute(props.event.slug).url)
            registered.value = data.registered
            regCount.value = data.registrations_count
        } catch {
            registered.value = !registered.value
            regCount.value += registered.value ? 1 : -1
        } finally {
            pending.value = false
        }
    }

    /* Presentation helpers */
    const typeMeta = computed(() => {
        switch (props.event.event_type) {
            case 'online': return { label: 'Online', icon: Video }
            case 'hybrid': return { label: 'Hybrid', icon: Globe }
            default: return { label: 'In-person', icon: MapPin }
        }
    })

    const fmtDate = (d?: string | null) =>
        d ? new Date(d).toLocaleDateString('en-US', { weekday: 'long', month: 'long', day: 'numeric', year: 'numeric' }) : ''
    const fmtTime = (d?: string | null) =>
        d ? new Date(d).toLocaleTimeString('en-US', { hour: 'numeric', minute: '2-digit' }) : ''

    const dateRange = computed(() => {
        const s = props.event.start_date, e = props.event.end_date
        if (!s) return ''
        const sameDay = e && new Date(s).toDateString() === new Date(e).toDateString()
        if (sameDay) return `${fmtDate(s)} · ${fmtTime(s)} – ${fmtTime(e)}`
        return e ? `${fmtDate(s)} – ${fmtDate(e)}` : fmtDate(s)
    })

    const place = computed(() => {
        const e = props.event
        if (e.event_type === 'online') return 'Virtual event'
        return [e.location_name, e.city, e.country].filter(Boolean).join(', ')
    })

    const hasBanner = computed(() => Boolean((props.event.banner || '').trim()))
    const bannerUrl = computed(() => {
        const b = (props.event.banner || '').trim()
        return /^(https?:)?\/\//.test(b) || b.startsWith('/') ? b : `/${b}`
    })

    const sessionSpeakers = (s: any) => (s.speakers || []).map((u: any) => u.name).filter(Boolean).join(', ')
    const initial = (name?: string) => (name ?? '?').charAt(0).toUpperCase()

    const registerLabel = computed(() => {
        if (!authed.value) return 'Sign in to register'
        return registered.value ? 'Registered' : 'Register'
    })
</script>

<template>

    <Head :title="`${event.title} - Events`">
        <meta name="description" :content="event.description ?? ''" />
    </Head>


    <!-- Hero banner. With no cover image the band stays a solid near-black
             editorial block so the white title always reads. -->
    <div class="relative">
        <div class="relative h-80 w-full overflow-hidden bg-content-light sm:h-96">
            <img v-if="hasBanner" :src="bannerUrl" :alt="event.title" class="h-full w-full object-cover" />
            <div v-if="hasBanner" class="absolute inset-0 bg-black/45" />
            <AppContainer class="absolute inset-x-0 bottom-0 pb-8">
                <div class="flex flex-wrap items-center gap-2">
                    <span
                        class="inline-flex items-center gap-1.5 rounded-full bg-white/15 px-3 py-1 font-redhat text-[11px] font-bold uppercase tracking-wide text-white backdrop-blur">
                        <component :is="typeMeta.icon" class="h-3.5 w-3.5" />
                        {{ typeMeta.label }}
                    </span>
                    <AppBadge v-for="c in (event.categories || [])" :key="c.id" variant="primary" size="sm">
                        {{ c.name }}
                    </AppBadge>
                </div>
                <AppHeading tag="h1" font="prata" weight="normal" size="4xl" leading="tight" class="mt-4 !text-white">
                    {{ event.title }}
                </AppHeading>
            </AppContainer>
        </div>
    </div>

    <AppContainer class="pb-24 pt-10">
        <div class="lg:grid lg:grid-cols-12 lg:gap-12">
            <!-- Main -->
            <div class="lg:col-span-8">
                <!-- About -->
                <section v-if="event.description">
                    <AppText tag="p" font="redhat" size="xs" weight="black" uppercase color="brand"
                        class="mb-3 !tracking-[0.28em]">About this event</AppText>
                    <AppText tag="p" font="lora" size="lg" color="muted" leading="relaxed">
                        {{ event.description }}
                    </AppText>
                </section>

                <!-- Agenda -->
                <section v-if="event.sessions?.length" class="mt-14">
                    <AppHeading tag="h2" font="prata" weight="normal" size="2xl" class="mb-6">Agenda</AppHeading>
                    <ol class="relative border-l border-border-light dark:border-border-dark">
                        <li v-for="s in event.sessions" :key="s.id" class="relative ml-6 pb-8 last:pb-0">
                            <span
                                class="absolute -left-[1.9rem] top-1 grid h-4 w-4 place-items-center rounded-full border-2 border-brand bg-canvas-light dark:bg-canvas-dark">
                                <span class="h-1.5 w-1.5 rounded-full bg-brand" />
                            </span>
                            <AppText tag="span" font="redhat" size="xs" weight="bold" uppercase color="brand"
                                class="inline-flex items-center gap-1.5 !tracking-wide">
                                <Clock class="h-3.5 w-3.5" /> {{ fmtTime(s.start_time) }} – {{ fmtTime(s.end_time) }}
                            </AppText>
                            <AppHeading tag="h3" font="prata" weight="normal" size="lg" leading="tight" class="mt-1">
                                {{ s.title }}
                            </AppHeading>
                            <AppText v-if="s.description" tag="p" font="lora" size="sm" color="muted" class="mt-1">
                                {{ s.description }}
                            </AppText>
                            <div v-if="s.speakers?.length" class="mt-3 flex items-center gap-2">
                                <div class="flex -space-x-2">
                                    <Avatar v-for="sp in s.speakers.slice(0, 4)" :key="sp.id" :name="sp.name"
                                        :image="sp.profile_image" size="w-7 h-7"
                                        class="ring-2 ring-canvas-light dark:ring-canvas-dark" />
                                </div>
                                <AppText tag="span" font="redhat" size="xs" color="muted" truncate>
                                    {{ sessionSpeakers(s) }}
                                </AppText>
                            </div>
                            <AppText v-if="s.location" tag="p" font="redhat" size="xs" color="muted"
                                class="mt-2 inline-flex items-center gap-1.5">
                                <MapPin class="h-3.5 w-3.5" /> {{ s.location }}
                            </AppText>
                        </li>
                    </ol>
                </section>

                <!-- Speakers -->
                <section v-if="speakers.length" class="mt-14">
                    <AppHeading tag="h2" font="prata" weight="normal" size="2xl" class="mb-6">Speakers</AppHeading>
                    <div class="grid grid-cols-2 gap-4 sm:grid-cols-3">
                        <div v-for="sp in speakers" :key="sp.id"
                            class="flex flex-col items-center rounded-2xl border border-border-light bg-surface-light p-5 text-center shadow-editorial dark:border-border-dark dark:bg-surface-dark dark:shadow-editorial-dark">
                            <Avatar :name="sp.name" :image="sp.profile_image" size="w-16 h-16" />
                            <AppText tag="p" font="redhat" size="sm" weight="semibold" class="mt-3" truncate>
                                {{ sp.name }}
                            </AppText>
                        </div>
                    </div>
                </section>

                <!-- Reviews -->
                <section v-if="event.reviews?.length" class="mt-14">
                    <div class="mb-6 flex items-center gap-3">
                        <AppHeading tag="h2" font="prata" weight="normal" size="2xl">Reviews</AppHeading>
                        <span class="inline-flex items-center gap-1 font-redhat text-sm font-bold text-brand">
                            <Star class="h-4 w-4 fill-brand" /> {{ reviewStats.average }}
                            <span class="font-normal text-content-lightMuted dark:text-content-darkMuted">
                                ({{ reviewStats.count }})
                            </span>
                        </span>
                    </div>
                    <ul class="space-y-6">
                        <li v-for="r in event.reviews" :key="r.id" class="flex gap-4">
                            <Avatar :name="r.user?.name" :image="r.user?.profile_image" size="w-10 h-10" />
                            <div class="flex-1">
                                <div class="flex items-center gap-2">
                                    <AppText tag="span" font="redhat" size="sm" weight="semibold">
                                        {{ r.user?.name ?? 'Attendee' }}
                                    </AppText>
                                    <span class="inline-flex items-center gap-0.5">
                                        <Star v-for="n in (r.rating || 0)" :key="n"
                                            class="h-3 w-3 fill-brand text-brand" />
                                    </span>
                                </div>
                                <AppText tag="p" font="lora" size="sm" color="muted" class="mt-1">{{ r.review }}
                                </AppText>
                            </div>
                        </li>
                    </ul>
                </section>
            </div>

            <!-- Sidebar: details + register -->
            <aside class="mt-12 lg:col-span-4 lg:mt-0">
                <div
                    class="lg:sticky lg:top-24 rounded-3xl border border-border-light bg-surface-light p-6 shadow-editorial dark:border-border-dark dark:bg-surface-dark dark:shadow-editorial-dark">
                    <dl class="space-y-5">
                        <div class="flex gap-3">
                            <CalendarDays class="mt-0.5 h-5 w-5 shrink-0 text-brand" />
                            <div>
                                <AppText tag="dt" font="redhat" size="xs" weight="bold" uppercase color="muted"
                                    class="!tracking-wide">Date &amp; time</AppText>
                                <AppText tag="dd" font="redhat" size="sm" class="mt-0.5">{{ dateRange }}</AppText>
                            </div>
                        </div>
                        <div v-if="place" class="flex gap-3">
                            <MapPin class="mt-0.5 h-5 w-5 shrink-0 text-brand" />
                            <div>
                                <AppText tag="dt" font="redhat" size="xs" weight="bold" uppercase color="muted"
                                    class="!tracking-wide">Location</AppText>
                                <AppText tag="dd" font="redhat" size="sm" class="mt-0.5">{{ place }}</AppText>
                            </div>
                        </div>
                        <div class="flex gap-3">
                            <Users class="mt-0.5 h-5 w-5 shrink-0 text-brand" />
                            <div>
                                <AppText tag="dt" font="redhat" size="xs" weight="bold" uppercase color="muted"
                                    class="!tracking-wide">Attendance</AppText>
                                <AppText tag="dd" font="redhat" size="sm" class="mt-0.5">
                                    {{ regCount }} registered<template v-if="event.max_attendees"> · {{
                                        registration.spots_left }} spots left</template>
                                </AppText>
                            </div>
                        </div>
                    </dl>

                    <button type="button" @click="toggleRegister" :disabled="pending" :class="[
                        'mt-6 flex w-full items-center justify-center gap-2 rounded-full px-5 py-3 font-redhat text-sm font-bold uppercase tracking-wide transition-colors disabled:opacity-60',
                        registered
                            ? 'border border-brand text-brand hover:bg-brand-light dark:hover:bg-brand/10'
                            : 'bg-brand text-white hover:bg-brand-hover',
                    ]">
                        <Check v-if="registered" class="h-4 w-4" />
                        {{ registerLabel }}
                    </button>
                    <AppText v-if="registered" tag="p" font="redhat" size="xs" color="muted" align="center"
                        class="mt-2">You're on the list
                        — tap to cancel.</AppText>
                </div>
            </aside>
        </div>

        <!-- Related -->
        <section v-if="related.length" class="mt-20">
            <div class="mb-6 flex items-end justify-between">
                <AppHeading tag="h2" font="prata" weight="normal" size="2xl">More events</AppHeading>
                <Link href="/events"
                    class="inline-flex items-center gap-1 font-redhat text-sm font-semibold text-brand hover:gap-2 transition-all">
                    All events
                    <ArrowRight class="h-4 w-4" />
                </Link>
            </div>
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                <EventCard v-for="e in related" :key="e.id" :event="e" />
            </div>
        </section>
    </AppContainer>
</template>
