<script setup lang="ts">
import { Link } from '@inertiajs/vue3'

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

const props = defineProps<{
    event: EventItem
    eventUrl: string
}>()

/*
|--------------------------------------------------------------------------
| Date
|--------------------------------------------------------------------------
*/

const startDate = () => {
    return props.event.start_date
        ? new Date(props.event.start_date)
        : null
}

const endDate = () => {
    return props.event.end_date
        ? new Date(props.event.end_date)
        : null
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
    | Event Hero
    |--------------------------------------------------------------------------
    -->

    <section
        class="
            border-b
            border-border-light
            dark:border-border-dark
        "
    >
        <div class="py-10 sm:py-14 lg:py-20">
            <div
                class="
                    grid
                    gap-10
                    lg:grid-cols-[minmax(0,1.35fr)_minmax(360px,0.65fr)]
                    lg:gap-16
                    xl:gap-20
                "
            >
                <!--
                |--------------------------------------------------------------------------
                | Image
                |--------------------------------------------------------------------------
                -->

                <Link
                    :href="eventUrl"
                    class="
                        group
                        relative
                        block
                        overflow-hidden
                        bg-muted
                    "
                >
                    <div
                        class="
                            aspect-[16/10]
                            overflow-hidden
                            lg:aspect-[16/11]
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

                    <!-- Date Badge -->

                    <div
                        class="
                            absolute
                            bottom-0
                            left-0
                            flex
                            min-w-28
                            flex-col
                            bg-surface-light
                            px-5
                            py-4
                            dark:bg-surface-dark
                        "
                    >
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

                        <span
                            class="
                                mt-1
                                font-prata
                                text-4xl
                                leading-none
                                text-content-light
                                dark:text-content-dark
                            "
                        >
                            {{ day() }}
                        </span>

                        <AppText
                            tag="span"
                            size="xs"
                            color="muted"
                            class="mt-1"
                        >
                            {{ year() }}
                        </AppText>
                    </div>
                </Link>

                <!--
                |--------------------------------------------------------------------------
                | Content
                |--------------------------------------------------------------------------
                -->

                <div
                    class="
                        flex
                        flex-col
                        justify-center
                    "
                >
                    <!-- Category -->

                    <div
                        v-if="event.categories?.length"
                        class="
                            flex
                            items-center
                            gap-2
                        "
                    >
                        <span
                            class="h-px w-8 bg-primary"
                            aria-hidden="true"
                        />

                        <AppText
                            tag="span"
                            font="redhat"
                            size="xs"
                            weight="bold"
                            tracking="wide"
                            uppercase
                            color="primary"
                        >
                            {{ event.categories[0].name }}
                        </AppText>
                    </div>

                    <!-- Event Label -->

                    <AppText
                        tag="p"
                        size="xs"
                        weight="medium"
                        tracking="wide"
                        uppercase
                        color="muted"
                        class="mt-5"
                    >
                        Upcoming event
                    </AppText>

                    <!-- Title -->

                    <Link
                        :href="eventUrl"
                        class="group/title mt-2"
                    >
                        <AppHeading
                            tag="h1"
                            font="prata"
                            size="4xl"
                            weight="normal"
                            leading="tight"
                            hover-primary
                        >
                            {{ event.title }}
                        </AppHeading>
                    </Link>

                    <!-- Description -->

                    <AppText
                        v-if="event.description"
                        tag="p"
                        font="lora"
                        size="base"
                        color="muted"
                        leading="relaxed"
                        :clamp="4"
                        class="mt-5 max-w-2xl"
                    >
                        {{ event.description }}
                    </AppText>

                    <!-- Event Information -->

                    <div
                        class="
                            mt-7
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
                                    }]
                                    : []),

                                ...(time()
                                    ? [{
                                        value: time(),
                                    }]
                                    : []),

                                ...(location()
                                    ? [{
                                        value: location(),
                                    }]
                                    : []),
                            ]"
                        />
                    </div>

                    <!-- Action -->

                    <div class="mt-8">
                        <Link
                            :href="eventUrl"
                            class="
                                inline-flex
                                items-center
                                gap-3
                                font-redhat
                                text-sm
                                font-semibold
                                text-content-light
                                transition-all
                                duration-300
                                hover:gap-4
                                hover:text-primary
                                dark:text-content-dark
                            "
                        >
                            View event

                            <span
                                aria-hidden="true"
                                class="text-base"
                            >
                                →
                            </span>
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </section>
</template>