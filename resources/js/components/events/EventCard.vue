<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { Calendar, MapPin } from '@lucide/vue'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import { MetaInfo } from '@/components/ui/metainfo'

interface EventCategory {
    id: number
    name: string
    slug: string
}

interface EventItem {
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

const props = withDefaults(
    defineProps<{
        event: EventItem
        href: string
        compact?: boolean
    }>(),
    {
        compact: false,
    },
)

/*
|--------------------------------------------------------------------------
| Dates
|--------------------------------------------------------------------------
*/

const startDate = () => {
    if (!props.event.start_date) {
        return null
    }

    return new Date(props.event.start_date)
}

const endDate = () => {
    if (!props.event.end_date) {
        return null
    }

    return new Date(props.event.end_date)
}

const day = () => {
    const date = startDate()

    return date
        ? new Intl.DateTimeFormat('en', {
            day: '2-digit',
        }).format(date)
        : ''
}

const month = () => {
    const date = startDate()

    return date
        ? new Intl.DateTimeFormat('en', {
            month: 'short',
        }).format(date)
        : ''
}

const year = () => {
    const date = startDate()

    return date
        ? new Intl.DateTimeFormat('en', {
            year: 'numeric',
        }).format(date)
        : ''
}

const time = () => {
    const date = startDate()

    return date
        ? new Intl.DateTimeFormat('en', {
            hour: 'numeric',
            minute: '2-digit',
        }).format(date)
        : ''
}

const dateRange = () => {
    const start = startDate()
    const end = endDate()

    if (!start) {
        return ''
    }

    const startLabel = new Intl.DateTimeFormat('en', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    }).format(start)

    if (!end) {
        return startLabel
    }

    const endLabel = new Intl.DateTimeFormat('en', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    }).format(end)

    return startLabel === endLabel
        ? startLabel
        : `${startLabel} — ${endLabel}`
}

/*
|--------------------------------------------------------------------------
| Location
|--------------------------------------------------------------------------
*/

const location = () => {
    return [
        props.event.location_name,
        props.event.city,
    ]
        .filter(Boolean)
        .join(', ')
}
</script>

<template>
    <!--
    |--------------------------------------------------------------------------
    | Compact
    |--------------------------------------------------------------------------
    -->

    <Link
        v-if="compact"
        :href="href"
        class="
            group
            flex
            h-full
            flex-col
            border
            border-border-light
            bg-surface-light
            p-5
            transition-colors
            duration-300
            hover:border-primary
            dark:border-border-dark
            dark:bg-surface-dark
        "
    >
        <!-- Date -->

        <div
            class="
                flex
                items-start
                justify-between
                border-b
                border-border-light
                pb-5
                dark:border-border-dark
            "
        >
            <div class="flex items-baseline gap-2">
                <AppText
                    tag="span"
                    font="redhat"
                    size="2xl"
                    weight="medium"
                    leading="none"
                >
                    {{ day() }}
                </AppText>

                <AppText
                    tag="span"
                    size="xs"
                    weight="bold"
                    tracking="wide"
                    uppercase
                    color="primary"
                >
                    {{ month() }}
                </AppText>
            </div>

            <AppText
                tag="span"
                size="xs"
                color="muted"
            >
                {{ year() }}
            </AppText>
        </div>

        <!-- Category -->

        <AppText
            v-if="event.categories?.length"
            tag="p"
            size="xs"
            weight="bold"
            tracking="wide"
            uppercase
            color="muted"
            class="mt-5"
        >
            {{ event.categories[0].name }}
        </AppText>

        <!-- Title -->

        <AppHeading
            tag="h3"
            font="prata"
            size="lg"
            weight="normal"
            leading="tight"
            hover-primary
            :clamp="3"
            class="mt-2"
        >
            {{ event.title }}
        </AppHeading>

        <!-- Description -->

        <AppText
            v-if="event.description"
            tag="p"
            font="lora"
            size="xs"
            color="muted"
            leading="relaxed"
            :clamp="2"
            class="mt-3"
        >
            {{ event.description }}
        </AppText>

        <!-- Location -->

        <div class="mt-auto pt-5">
            <MetaInfo
                :items="[
                    ...(location()
                        ? [{
                            value: location(),
                            icon: MapPin,
                        }]
                        : []),

                    ...(time()
                        ? [{
                            value: time(),
                            icon: Calendar,
                        }]
                        : []),
                ]"
            />
        </div>
    </Link>

    <!--
    |--------------------------------------------------------------------------
    | Full Event Card
    |--------------------------------------------------------------------------
    -->

    <Link
        v-else
        :href="href"
        class="
            group
            grid
            overflow-hidden
            border-y
            border-border-light
            bg-surface-light
            transition-colors
            duration-300
            hover:border-primary
            dark:border-border-dark
            dark:bg-surface-dark
            md:grid-cols-[180px_260px_1fr]
        "
    >
        <!--
        |--------------------------------------------------------------------------
        | Date Block
        |--------------------------------------------------------------------------
        -->

        <div
            class="
                flex
                flex-col
                justify-between
                border-b
                border-border-light
                p-6
                dark:border-border-dark
                md:border-b-0
                md:border-r
                dark:md:border-border-dark
            "
        >
            <div>
                <AppText
                    tag="span"
                    size="xs"
                    weight="bold"
                    tracking="wide"
                    uppercase
                    color="primary"
                >
                    {{ month() }}
                </AppText>

                <div
                    class="
                        mt-1
                        font-prata
                        text-5xl
                        font-normal
                        leading-none
                        text-content-light
                        dark:text-content-dark
                    "
                >
                    {{ day() }}
                </div>

                <AppText
                    tag="span"
                    size="xs"
                    color="muted"
                    class="mt-2 block"
                >
                    {{ year() }}
                </AppText>
            </div>

            <div class="mt-8">
                <AppText
                    v-if="time()"
                    tag="span"
                    size="xs"
                    color="muted"
                >
                    {{ time() }}
                </AppText>
            </div>
        </div>

        <!--
        |--------------------------------------------------------------------------
        | Image
        |--------------------------------------------------------------------------
        -->

        <div
            class="
                relative
                overflow-hidden
                bg-muted
            "
        >
            <div
                class="
                    aspect-[4/3]
                    h-full
                    md:aspect-auto
                "
            >
                <img
                    v-if="event.banner"
                    :src="event.banner"
                    :alt="event.title"
                    class="
                        h-full
                        w-full
                        object-cover
                        transition-transform
                        duration-700
                        ease-out
                        group-hover:scale-[1.025]
                    "
                />

                <div
                    v-else
                    class="
                        flex
                        h-full
                        min-h-52
                        items-center
                        justify-center
                        bg-surface-light
                        dark:bg-surface-dark
                    "
                >
                    <AppText
                        size="xs"
                        weight="bold"
                        tracking="wide"
                        uppercase
                        color="muted"
                    >
                        Event
                    </AppText>
                </div>
            </div>
        </div>

        <!--
        |--------------------------------------------------------------------------
        | Content
        |--------------------------------------------------------------------------
        -->

        <div
            class="
                flex
                min-w-0
                flex-col
                px-6
                py-7
                sm:px-8
                sm:py-8
            "
        >
            <!-- Category -->

            <AppText
                v-if="event.categories?.length"
                tag="p"
                size="xs"
                weight="bold"
                tracking="wide"
                uppercase
                color="primary"
            >
                {{ event.categories[0].name }}
            </AppText>

            <!-- Title -->

            <AppHeading
                tag="h3"
                font="prata"
                size="2xl"
                weight="normal"
                leading="tight"
                hover-primary
                :clamp="3"
                class="mt-2"
            >
                {{ event.title }}
            </AppHeading>

            <!-- Description -->

            <AppText
                v-if="event.description"
                tag="p"
                font="lora"
                size="sm"
                color="muted"
                leading="relaxed"
                :clamp="3"
                class="mt-4"
            >
                {{ event.description }}
            </AppText>

            <!-- Meta -->

            <div
                class="
                    mt-auto
                    flex
                    flex-wrap
                    items-end
                    justify-between
                    gap-5
                    border-t
                    border-border-light
                    pt-5
                    dark:border-border-dark
                "
            >
                <MetaInfo
                    :items="[
                        ...(dateRange()
                            ? [{
                                value: dateRange(),
                                icon: Calendar,
                            }]
                            : []),

                        ...(location()
                            ? [{
                                value: location(),
                                icon: MapPin,
                            }]
                            : []),
                    ]"
                />

                <span
                    class="
                        shrink-0
                        font-redhat
                        text-xs
                        font-semibold
                        tracking-wide
                        text-primary
                        transition-transform
                        duration-300
                        group-hover:translate-x-1
                    "
                >
                    View event →
                </span>
            </div>
        </div>
    </Link>
</template>