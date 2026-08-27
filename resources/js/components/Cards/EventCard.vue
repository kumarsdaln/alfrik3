<script setup lang="ts">
    import { computed, ref } from 'vue'
    import { Link } from '@inertiajs/vue3'
    import { MapPin, Video, Globe, Users } from '@lucide/vue'
    import { show as eventShow } from '@/routes/events'
    import AppHeading from '@/Components/Ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'

    const props = defineProps<{
        event: {
            slug: string
            title: string
            event_type?: string | null
            start_date?: string | null
            end_date?: string | null
            location_name?: string | null
            city?: string | null
            country?: string | null
            banner?: string | null
            max_attendees?: number | null
            registrations_count?: number
            categories?: { id: number; name: string }[]
        }
    }>()

    const FALLBACK = '/frontend/images/placeholder.jpg'
    const failed = ref(false)

    const banner = computed(() => {
        const b = (props.event.banner || '').trim()
        if (!b || failed.value) return FALLBACK
        return /^(https?:)?\/\//.test(b) || b.startsWith('/') ? b : `/${b}`
    })

    const typeMeta = computed(() => {
        switch (props.event.event_type) {
            case 'online': return { label: 'Online', icon: Video }
            case 'hybrid': return { label: 'Hybrid', icon: Globe }
            default: return { label: 'In-person', icon: MapPin }
        }
    })

    const start = computed(() => (props.event.start_date ? new Date(props.event.start_date) : null))

    const dateParts = computed(() => {
        if (!start.value) return null
        return {
            month: start.value.toLocaleDateString('en-US', { month: 'short' }).toUpperCase(),
            day: start.value.toLocaleDateString('en-US', { day: 'numeric' }),
        }
    })

    const timeLabel = computed(() =>
        start.value
            ? start.value.toLocaleDateString('en-US', { weekday: 'short', month: 'long', day: 'numeric', year: 'numeric' })
            : '',
    )

    const place = computed(() => {
        if (props.event.event_type === 'online') return 'Virtual event'
        return [props.event.city, props.event.country].filter(Boolean).join(', ') || props.event.location_name || ''
    })
</script>

<template>
    <Link :href="eventShow(event.slug).url"
        class="group flex flex-col overflow-hidden rounded-2xl border border-border-light bg-surface-light shadow-editorial transition-all duration-300 hover:-translate-y-1 hover:border-brand/40 dark:border-border-dark dark:bg-surface-dark dark:shadow-editorial-dark">
        <!-- Banner -->
        <div class="relative aspect-[16/9] overflow-hidden bg-canvas-light dark:bg-white/5">
            <img :src="banner" :alt="event.title" loading="lazy" @error="failed = true"
                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105" />

            <!-- Date chip -->
            <div v-if="dateParts"
                class="absolute left-4 top-4 flex flex-col items-center rounded-xl bg-surface-light px-3 py-1.5 text-center shadow-editorial dark:bg-surface-dark dark:shadow-editorial-dark">
                <span class="font-redhat text-[10px] font-black uppercase tracking-widest text-brand">{{ dateParts.month }}</span>
                <span class="font-prata text-lg leading-none text-content-light dark:text-content-dark">{{ dateParts.day }}</span>
            </div>

            <!-- Type badge -->
            <span
                class="absolute right-4 top-4 inline-flex items-center gap-1.5 rounded-full bg-surface-light/95 px-3 py-1 font-redhat text-[10px] font-bold uppercase tracking-wide text-content-light backdrop-blur dark:bg-surface-dark/90 dark:text-content-dark">
                <component :is="typeMeta.icon" class="h-3 w-3 text-brand" />
                {{ typeMeta.label }}
            </span>
        </div>

        <!-- Body -->
        <div class="flex flex-1 flex-col p-5">
            <AppText v-if="timeLabel" tag="span" font="redhat" size="xs" weight="bold" uppercase color="brand"
                class="!tracking-[0.14em]">
                {{ timeLabel }}
            </AppText>

            <AppHeading tag="h3" font="prata" weight="normal" size="lg" leading="tight" :clamp="2" hover-brand
                class="mt-2">
                {{ event.title }}
            </AppHeading>

            <div class="mt-auto flex items-center justify-between gap-3 pt-4">
                <AppText v-if="place" tag="span" font="redhat" size="xs" color="muted" truncate
                    class="inline-flex items-center gap-1.5">
                    <MapPin class="h-3.5 w-3.5 shrink-0" />
                    {{ place }}
                </AppText>
                <AppText v-if="event.registrations_count" tag="span" font="redhat" size="xs" color="muted"
                    class="inline-flex shrink-0 items-center gap-1.5">
                    <Users class="h-3.5 w-3.5" />
                    {{ event.registrations_count }}
                </AppText>
            </div>
        </div>
    </Link>
</template>
