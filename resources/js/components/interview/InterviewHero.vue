<script setup lang="ts">
import {
    ArrowUpRight,
    CalendarDays,
    Headphones,
    Play,
    FileText,
    Gem,
} from '@lucide/vue'

import Avatar from '@/components/profile/Avatar.vue'
import Badge from '@/components/ui/badge/Badge.vue'

import type { Interview } from '@/types'

import { formatDate } from '@/utils/dateUtils'

import { show } from '@/routes/interviews'
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'


const props = defineProps<{
    interview: Interview | null
}>()

console.log(props.interview)


/*
|--------------------------------------------------------------------------
| Format
|--------------------------------------------------------------------------
*/

const formatConfig = {
    written: {
        label: 'Written Interview',
        icon: FileText,
    },

    video: {
        label: 'Video Interview',
        icon: Play,
    },

    audio: {
        label: 'Audio Interview',
        icon: Headphones,
    },
}


/*
|--------------------------------------------------------------------------
| Media
|--------------------------------------------------------------------------
*/

const getCoverMedia = () => {
    return (
        (props.interview?.media ?? []).find(
            media =>
                media.mime_type.startsWith('image/') ||
                media.collection === 'cover' ||
                media.collection === 'thumbnail',
        ) ?? null
    )
}


/*
|--------------------------------------------------------------------------
| Participant
|--------------------------------------------------------------------------
*/

const participants = computed(() => {
    return (props.interview?.participants ?? []).filter(
        participant => participant.user?.name,
    )
})

const visibleParticipants = computed(() => {
    return participants.value.slice(0, 3)
})

const remainingParticipants = computed(() => {
    return Math.max(
        participants.value.length - 3,
        0,
    )
})

const participantNames = computed(() => {
    return participants.value
        .map(participant => participant.user?.name)
        .filter(Boolean)
        .join(', ')
})
</script>


<template>
    <section id="interviews" class="border-b border-border bg-background">
        <div class="px-5 sm:px-6 lg:px-0">
            <div class="
                    grid
                    gap-10
                    py-10
                    sm:py-14
                    lg:grid-cols-[0.9fr_1.1fr]
                    lg:gap-20
                    lg:py-16
                ">

                <!-- Intro -->

                <div class="flex flex-col justify-center">
                    <p class="
                            mb-5
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[0.2em]
                            text-muted-foreground
                            sm:text-[11px]
                        ">
                        Conversations · Perspectives · Ideas
                    </p>

                    <h1 class="
                            max-w-[650px]
                            text-[48px]
                            font-medium
                            leading-[0.94]
                            tracking-[-0.055em]
                            text-foreground
                            sm:text-[60px]
                            lg:text-[72px]
                        ">
                        The people.
                        <br />

                        The ideas.
                        <br />

                        <span class="text-muted-foreground">
                            The conversation.
                        </span>
                    </h1>

                    <p class="
                            mt-7
                            max-w-[520px]
                            text-[15px]
                            leading-7
                            text-muted-foreground
                            sm:text-[16px]
                        ">
                        In-depth conversations with researchers,
                        leaders, creators and thinkers behind the
                        ideas, decisions and movements shaping our world.
                    </p>

                    <!-- Available Formats -->

                    <div class="
                            mt-6
                            flex
                            flex-wrap
                            items-center
                            gap-x-5
                            gap-y-2
                            text-[11px]
                            uppercase
                            tracking-[0.12em]
                            text-muted-foreground
                        ">
                        <span class="flex items-center gap-1.5">
                            <FileText :size="13" :stroke-width="1.7" />
                            Written
                        </span>

                        <span class="flex items-center gap-1.5">
                            <Play :size="13" :stroke-width="1.7" />
                            Video
                        </span>

                        <span class="flex items-center gap-1.5">
                            <Headphones :size="13" :stroke-width="1.7" />
                            Audio
                        </span>
                    </div>

                    <!-- CTA -->

                    <div class="mt-8">
                        <a href="#interviews" class="
                                group
                                inline-flex
                                items-center
                                gap-3
                                border-b
                                border-foreground
                                pb-1.5
                                text-[13px]
                                font-medium
                                text-foreground
                            ">
                            Explore interviews

                            <ArrowUpRight :size="15" :stroke-width="1.8" class="
                                    transition-transform
                                    duration-200
                                    group-hover:-translate-y-0.5
                                    group-hover:translate-x-0.5
                                " />
                        </a>
                    </div>
                </div>


                <!-- Featured Interview -->

                <article v-if="interview" class="lg:pt-4">
                    <Link :href="show(interview.slug).url" :aria-label="interview.title" class="block">

                    <!-- Image -->
                    <div class="
                                relative
                                overflow-hidden
                                bg-muted
                            ">
                        <div class="
                                    aspect-[4/3]
                                    overflow-hidden
                                    sm:aspect-[16/10]
                                    lg:aspect-[4/3]
                                ">
                            <img v-if="getCoverMedia()?.url" :src="getCoverMedia()?.url" :alt="getCoverMedia()?.alt ??
                                interview.title
                                " class="
                                        h-full
                                        w-full
                                        object-cover
                                        transition-transform
                                        duration-500
                                        group-hover:scale-[1.02]
                                    " />

                            <div v-else class="
                                        flex
                                        h-full
                                        w-full
                                        items-center
                                        justify-center
                                        bg-muted
                                    ">
                                <FileText :size="36" :stroke-width="1" class="text-muted-foreground/40" />
                            </div>
                        </div>

                        <!-- Featured Badge -->

                        <div class="
                                    absolute
                                    left-3
                                    top-3
                                ">
                            <Badge variant="secondary">
                                <Gem :size="13" :stroke-width="1.8" />

                                Featured
                            </Badge>
                        </div>
                    </div>


                    <!-- Content -->

                    <div class="py-5 sm:py-6">

                        <!-- Format + Date -->

                        <div class="
                                    mb-4
                                    flex
                                    flex-wrap
                                    items-center
                                    justify-between
                                    gap-3
                                ">
                            <span class="
                                        flex
                                        items-center
                                        gap-1.5
                                        text-[10px]
                                        font-semibold
                                        uppercase
                                        tracking-[0.15em]
                                        text-foreground
                                    ">
                                <component :is="formatConfig[
                                    interview.interview_type.value
                                ]?.icon ?? FileText
                                    " :size="13" :stroke-width="1.8" />

                                {{
                                    formatConfig[
                                        interview.interview_type.value
                                    ]?.label ?? 'Written Interview'
                                }}
                            </span>


                            <span v-if="interview.published_at" class="
                                        flex
                                        items-center
                                        gap-1.5
                                        text-[10px]
                                        uppercase
                                        tracking-[0.12em]
                                        text-muted-foreground
                                    ">
                                <CalendarDays :size="12" :stroke-width="1.7" />

                                {{
                                    formatDate(
                                        interview.published_at,
                                    )
                                }}
                            </span>
                        </div>


                        <!-- Participants -->

                        <!-- Participants -->

                        <div v-if="participants.length" class="
        mb-4
        flex
        min-w-0
        items-center
        gap-3
    ">
                            <!-- Avatar Stack -->

                            <div class="flex shrink-0 -space-x-2">
                                <Avatar v-for="participant in visibleParticipants" :key="participant.id"
                                    :name="participant.user?.name" :image="participant.user?.avatar" size="size-8"
                                    text-size="text-[10px]" rounded="full" class="ring-2 ring-white" />

                                <div v-if="remainingParticipants" class="
                flex
                size-8
                shrink-0
                items-center
                justify-center
                rounded-full
                bg-neutral-100
                text-[9px]
                font-medium
                text-neutral-600
                ring-2
                ring-white
            ">
                                    +{{ remainingParticipants }}
                                </div>
                            </div>

                            <!-- Names -->

                            <p class="
            min-w-0
            truncate
            text-[11px]
            leading-5
            text-muted-foreground
        " :title="participantNames">
                                {{ participantNames }}
                            </p>
                        </div>


                        <!-- Title -->

                        <h2 class="
                                    max-w-[650px]
                                    text-[22px]
                                    font-medium
                                    leading-[1.2]
                                    tracking-[-0.025em]
                                    text-foreground
                                    transition-colors
                                    duration-200
                                    group-hover:text-muted-foreground
                                    sm:text-[25px]
                                ">
                            {{ interview.title }}
                        </h2>


                        <!-- Description -->

                        <p v-if="interview.description" class="
                                    mt-3
                                    max-w-[620px]
                                    line-clamp-2
                                    text-sm
                                    leading-6
                                    text-muted-foreground
                                ">
                            {{ interview.description }}
                        </p>

                    </div>
                    </Link>
                </article>

            </div>
        </div>
    </section>
</template>
