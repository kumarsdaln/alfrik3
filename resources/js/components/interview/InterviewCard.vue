
<script setup lang="ts">
import { computed, ref, watch } from 'vue'
import { Link } from '@inertiajs/vue3'
import {
    ArrowUpRight,
    CalendarDays,
    FileText,
    Headphones,
    Play,
} from '@lucide/vue'

import Avatar from '@/components/profile/Avatar.vue'
import Badge from '@/components/ui/badge/Badge.vue'

import type { Interview } from '@/types'
import { formatDate } from '@/utils/dateUtils'
import { show } from '@/routes/interviews'

const props = defineProps<{
    interview: Interview
}>()

const FALLBACK_IMAGE = '/frontend/images/placeholder.jpg'
const MAX_VISIBLE_PARTICIPANTS = 3

const imageFailed = ref(false)

/*
|--------------------------------------------------------------------------
| Media
|--------------------------------------------------------------------------
*/

const imageMedia = computed(() => {
    const media = props.interview.media ?? []

    return (
        media.find(
            item =>
                item.collection === 'cover' &&
                item.mime_type?.startsWith('image/'),
        ) ??
        media.find(
            item =>
                item.collection === 'thumbnail' &&
                item.mime_type?.startsWith('image/'),
        ) ??
        media.find(
            item => item.mime_type?.startsWith('image/'),
        ) ??
        null
    )
})

const imageSrc = computed(() => {
    if (imageFailed.value) {
        return FALLBACK_IMAGE
    }

    return imageMedia.value?.url || FALLBACK_IMAGE
})

watch(
    () => [props.interview.slug, imageMedia.value?.url],
    () => {
        imageFailed.value = false
    },
)

const handleImageError = (event: Event) => {
    const image = event.target as HTMLImageElement

    if (image.src.endsWith(FALLBACK_IMAGE)) {
        return
    }

    imageFailed.value = true
}

/*
|--------------------------------------------------------------------------
| Format
|--------------------------------------------------------------------------
*/

const formatConfig = computed(() => {
    switch (props.interview.interview_type?.value) {
        case 'video':
            return {
                label: 'Video interview',
                action: 'Watch interview',
                icon: Play,
            }

        case 'audio':
            return {
                label: 'Audio interview',
                action: 'Listen to interview',
                icon: Headphones,
            }

        default:
            return {
                label: 'Written interview',
                action: 'Read interview',
                icon: FileText,
            }
    }
})

const isMediaInterview = computed(() =>
    ['video', 'audio'].includes(
        props.interview.interview_type?.value ?? '',
    ),
)

/*
|--------------------------------------------------------------------------
| Participants
|--------------------------------------------------------------------------
*/

const participants = computed(() =>
    (props.interview.participants ?? []).filter(
        participant => participant.user?.name,
    ),
)

const visibleParticipants = computed(() =>
    participants.value.slice(0, MAX_VISIBLE_PARTICIPANTS),
)

const remainingParticipants = computed(() =>
    Math.max(
        participants.value.length - MAX_VISIBLE_PARTICIPANTS,
        0,
    ),
)

const participantNames = computed(() =>
    participants.value
        .map(participant => participant.user?.name)
        .filter(Boolean)
        .join(', '),
)

/*
|--------------------------------------------------------------------------
| Navigation
|--------------------------------------------------------------------------
*/

const interviewUrl = computed(() =>
    show(props.interview.slug).url,
)
</script>

<template>
    <article
        class="group min-w-0"
        data-slot="interview-card"
    >
        <Link
            :href="interviewUrl"
            :aria-label="`${formatConfig.action}: ${interview.title}`"
            class="block rounded-sm outline-none focus-visible:ring-2 focus-visible:ring-primary focus-visible:ring-offset-4 focus-visible:ring-offset-background"
        >
            <!-- Cover -->

            <div
                class="relative aspect-[16/10] overflow-hidden bg-muted"
            >
                <img
                    :src="imageSrc"
                    :alt="imageMedia?.alt || interview.title"
                    loading="lazy"
                    decoding="async"
                    class="size-full object-cover transition-transform duration-700 ease-out group-hover:scale-[1.035] motion-reduce:transform-none"
                    @error="handleImageError"
                />

                <!-- Format Badge -->

                <div class="absolute left-3 top-3 sm:left-4 sm:top-4">
                    <Badge variant="secondary">
                        <component
                            :is="formatConfig.icon"
                            :size="12"
                            :stroke-width="1.8"
                        />
                        {{ formatConfig.label }}
                    </Badge>
                </div>

                <!-- Media Action -->

                <div
                    v-if="isMediaInterview"
                    class="absolute bottom-4 right-4 flex size-11 items-center justify-center rounded-full border border-border/60 bg-card text-foreground shadow-sm transition-transform duration-300 group-hover:scale-110 motion-reduce:transform-none"
                    aria-hidden="true"
                >
                    <Play
                        v-if="interview.interview_type?.value === 'video'"
                        :size="18"
                        :stroke-width="1.8"
                        class="ml-0.5 fill-current"
                    />

                    <Headphones
                        v-else
                        :size="19"
                        :stroke-width="1.7"
                    />
                </div>
            </div>

            <!-- Editorial Content -->

            <div class="pt-5">
                <!-- Participants -->

                <div
                    v-if="participants.length"
                    class="flex min-w-0 items-center gap-3"
                >
                    <div class="flex shrink-0 -space-x-2">
                        <Avatar
                            v-for="participant in visibleParticipants"
                            :key="participant.id"
                            :name="participant.user?.name"
                            :image="participant.user?.avatar"
                            size="size-7"
                            text-size="text-[10px]"
                            rounded="full"
                            class="ring-2 ring-background"
                        />

                        <span
                            v-if="remainingParticipants"
                            class="flex size-7 shrink-0 items-center justify-center rounded-full bg-muted text-[9px] font-semibold text-muted-foreground ring-2 ring-background"
                        >
                            +{{ remainingParticipants }}
                        </span>
                    </div>

                    <p
                        class="min-w-0 truncate text-xs text-muted-foreground"
                        :title="participantNames"
                    >
                        {{ participantNames }}
                    </p>
                </div>

                <!-- Interview Title -->

                <h3
                    class="mt-4 line-clamp-3 font-serif text-[23px] font-medium leading-[1.22] tracking-[-0.025em] text-foreground transition-colors duration-200 group-hover:text-brand sm:text-[25px]"
                >
                    {{ interview.title }}
                </h3>

                <!-- Description -->

                <p
                    v-if="interview.description"
                    class="mt-3 line-clamp-2 text-[13px] leading-6 text-muted-foreground"
                >
                    {{ interview.description }}
                </p>

                <!-- Footer -->

                <div
                    class="mt-5 flex flex-wrap items-center justify-between gap-x-4 gap-y-3 border-t border-border/70 pt-4"
                >
                    <span
                        class="inline-flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.13em] text-foreground transition-colors group-hover:text-brand"
                    >
                        {{ formatConfig.action }}

                        <ArrowUpRight
                            :size="15"
                            :stroke-width="1.7"
                            class="transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 motion-reduce:transform-none"
                        />
                    </span>

                    <time
                        v-if="interview.published_at"
                        class="inline-flex items-center gap-1.5 text-[10px] uppercase tracking-[0.08em] text-muted-foreground"
                    >
                        <CalendarDays
                            :size="12"
                            :stroke-width="1.6"
                        />
                        {{ formatDate(interview.published_at) }}
                    </time>
                </div>
            </div>
        </Link>
    </article>
</template>
