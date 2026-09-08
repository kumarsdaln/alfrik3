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
import { Badge } from '../ui/badge'

type InterviewFormat = 'written' | 'video' | 'audio'

interface User {
    id: number
    username: string
    name: string
    avatar?: string | null
}

interface Participant {
    id: number
    role: string
    user?: User | null
}

interface Interview {
    id: number
    slug: string
    title: string
    description?: string | null
    thumbnail?: string | null
    interview_type?: string | null
    published_at?: string | null
    participants?: Participant[]
}

const props = defineProps<{
    interview: Interview | null
}>()


/*
|--------------------------------------------------------------------------
| Format
|--------------------------------------------------------------------------
*/

function getFormat(
    type?: string | null,
): InterviewFormat {
    if (!type) {
        return 'written'
    }

    const value = type.value.toLowerCase()

    if (value.includes('video')) {
        return 'video'
    }

    if (value.includes('audio')) {
        return 'audio'
    }

    return 'written'
}


/*
|--------------------------------------------------------------------------
| Format Configuration
|--------------------------------------------------------------------------
*/

const formatConfig: Record<
    InterviewFormat,
    {
        label: string
        icon: typeof FileText
    }
> = {
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
| Participants
|--------------------------------------------------------------------------
*/

function getInterviewee(
    participants?: Participant[],
): Participant | null {
    return (
        participants?.find(
            participant => participant.role === 'interviewee',
        ) ?? null
    )
}

function getIntervieweeName(
    participants?: Participant[],
): string {
    return getInterviewee(participants)?.user?.name ?? 'Featured Guest'
}

function getIntervieweeAvatar(
    participants?: Participant[],
): string | null {
    return getInterviewee(participants)?.user?.avatar ?? null
}

function getIntervieweeRole(
    participants?: Participant[],
): string {
    const participant = getInterviewee(participants)

    if (!participant) {
        return ''
    }

    return participant.role
}


/*
|--------------------------------------------------------------------------
| Date
|--------------------------------------------------------------------------
*/

function formatDate(
    date?: string | null,
): string {
    if (!date) {
        return ''
    }

    return new Intl.DateTimeFormat('en-US', {
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(new Date(date))
}
</script>


<template>
    <section
        id="interviews"
        class="border-b border-border bg-background"
    >
        <div class="px-5 sm:px-6 lg:px-0">

            <div
                class="
                    grid
                    gap-10
                    py-10
                    sm:py-14
                    lg:grid-cols-[0.9fr_1.1fr]
                    lg:gap-20
                    lg:py-16
                "
            >

                <!-- ======================================================
                     INTRO
                ======================================================= -->

                <div class="flex flex-col justify-center">

                    <p
                        class="
                            mb-5
                            text-[10px]
                            font-semibold
                            uppercase
                            tracking-[0.2em]
                            text-muted-foreground
                            sm:text-[11px]
                        "
                    >
                        Conversations · Perspectives · Ideas
                    </p>


                    <h1
                        class="
                            max-w-[650px]
                            text-[48px]
                            font-medium
                            leading-[0.94]
                            tracking-[-0.055em]
                            text-foreground
                            sm:text-[60px]
                            lg:text-[72px]
                        "
                    >
                        The people.
                        <br />

                        The ideas.
                        <br />

                        <span class="text-muted-foreground">
                            The conversation.
                        </span>
                    </h1>


                    <p
                        class="
                            mt-7
                            max-w-[520px]
                            text-[15px]
                            leading-7
                            text-muted-foreground
                            sm:text-[16px]
                        "
                    >
                        In-depth conversations with researchers,
                        leaders, creators and thinkers behind the
                        ideas, decisions and movements shaping our world.
                    </p>


                    <!-- Available Formats -->

                    <div
                        class="
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
                        "
                    >
                        <span class="flex items-center gap-1.5">
                            <FileText
                                :size="13"
                                :stroke-width="1.7"
                            />
                            Written
                        </span>

                        <span class="flex items-center gap-1.5">
                            <Play
                                :size="13"
                                :stroke-width="1.7"
                            />
                            Video
                        </span>

                        <span class="flex items-center gap-1.5">
                            <Headphones
                                :size="13"
                                :stroke-width="1.7"
                            />
                            Audio
                        </span>
                    </div>


                    <!-- CTA -->

                    <div class="mt-8">
                        <a
                            href="#interviews"
                            class="
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
                            "
                        >
                            Explore interviews

                            <ArrowUpRight
                                :size="15"
                                :stroke-width="1.8"
                                class="
                                    transition-transform
                                    duration-200
                                    group-hover:-translate-y-0.5
                                    group-hover:translate-x-0.5
                                "
                            />
                        </a>
                    </div>
                </div>


                <!-- ======================================================
                     FEATURED INTERVIEW
                ======================================================= -->

                <article
                    v-if="interview"
                    class="lg:pt-4"
                >
                    <a
                        :href="`/interviews/${interview.slug}`"
                        class="group block"
                    >

                        <!-- Image -->

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
                                    overflow-hidden
                                    sm:aspect-[16/10]
                                    lg:aspect-[4/3]
                                "
                            >
                                <img
                                    v-if="interview.thumbnail"
                                    :src="interview.thumbnail"
                                    :alt="interview.title"
                                    class="
                                        h-full
                                        w-full
                                        object-cover
                                        transition-transform
                                        duration-500
                                        group-hover:scale-[1.02]
                                    "
                                />

                                <div
                                    v-else
                                    class="
                                        flex
                                        h-full
                                        w-full
                                        items-center
                                        justify-center
                                        bg-muted
                                    "
                                >
                                    <FileText
                                        :size="36"
                                        :stroke-width="1"
                                        class="text-muted-foreground/40"
                                    />
                                </div>
                            </div>


                            <!-- Featured Badge -->
                            <div class="
                                    absolute
                                    left-3
                                    top-3
                                ">
                                <Badge variant="secondary">
                                    <component :is="Gem" :size="13" :stroke-width="1.8" />
                                    Featured
                                </Badge>
                            </div>
                        </div>


                        <!-- Content -->

                        <div class="py-5 sm:py-6">

                            <!-- Format + Date -->

                            <div
                                class="
                                    mb-4
                                    flex
                                    flex-wrap
                                    items-center
                                    justify-between
                                    gap-3
                                "
                            >
                                <span
                                    class="
                                        flex
                                        items-center
                                        gap-1.5
                                        text-[10px]
                                        font-semibold
                                        uppercase
                                        tracking-[0.15em]
                                        text-foreground
                                    "
                                >
                                    <component
                                        :is="
                                            formatConfig[
                                                getFormat(
                                                    interview.interview_type,
                                                )
                                            ].icon
                                        "
                                        :size="13"
                                        :stroke-width="1.8"
                                    />

                                    {{
                                        formatConfig[
                                            getFormat(
                                                interview.interview_type,
                                            )
                                        ].label
                                    }}
                                </span>


                                <span
                                    v-if="interview.published_at"
                                    class="
                                        flex
                                        items-center
                                        gap-1.5
                                        text-[10px]
                                        uppercase
                                        tracking-[0.12em]
                                        text-muted-foreground
                                    "
                                >
                                    <CalendarDays
                                        :size="12"
                                        :stroke-width="1.7"
                                    />

                                    {{ formatDate(interview.published_at) }}
                                </span>
                            </div>


                            <!-- Person -->

                            <div
                                v-if="getInterviewee(interview.participants)"
                                class="
                                    mb-4
                                    flex
                                    items-center
                                    gap-3
                                "
                            >
                                <Avatar
                                    :image="
                                        getIntervieweeAvatar(
                                            interview.participants,
                                        )
                                    "
                                    :name="
                                        getIntervieweeName(
                                            interview.participants,
                                        )
                                    "
                                    size="size-9"
                                />

                                <div class="min-w-0">
                                    <div
                                        class="
                                            text-[12px]
                                            font-medium
                                            text-foreground
                                        "
                                    >
                                        {{
                                            getIntervieweeName(
                                                interview.participants,
                                            )
                                        }}
                                    </div>

                                    <div
                                        class="
                                            text-[11px]
                                            text-muted-foreground
                                        "
                                    >
                                        {{
                                            getIntervieweeRole(
                                                interview.participants,
                                            )
                                        }}
                                    </div>
                                </div>
                            </div>


                            <!-- Title -->

                            <h2
                                class="
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
                                "
                            >
                                {{ interview.title }}
                            </h2>


                            <!-- Description -->

                            <p
                                v-if="interview.description"
                                class="
                                    mt-3
                                    max-w-[620px]
                                    line-clamp-2
                                    text-sm
                                    leading-6
                                    text-muted-foreground
                                "
                            >
                                {{ interview.description }}
                            </p>
                        </div>
                    </a>
                </article>

            </div>
        </div>
    </section>
</template>