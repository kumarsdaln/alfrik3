<script setup lang="ts">
    import { computed } from 'vue'
    import {
        ArrowUpRight,
        CalendarDays,
        FileText,
        Gem,
        Headphones,
        Play,
    } from '@lucide/vue'
    import { Link } from '@inertiajs/vue3'

    import Avatar from '@/components/profile/Avatar.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import type { Interview } from '@/types'

    import { formatDate } from '@/utils/dateUtils'
    import { show } from '@/routes/interviews'
    import Section from '../ui/Section.vue'
    import { Grid, GridItem } from '../ui/grid'

    const props = defineProps<{
        interview: Interview | null
    }>()

    /*
    |--------------------------------------------------------------------------
    | Interview Formats
    |--------------------------------------------------------------------------
    */

    const formatConfig = {
        written: {
            label: 'Written',
            description: 'Read the conversation',
            icon: FileText,
        },
        video: {
            label: 'Video',
            description: 'Watch the conversation',
            icon: Play,
        },
        audio: {
            label: 'Audio',
            description: 'Listen to the conversation',
            icon: Headphones,
        },
    } as const

    const interviewFormat = computed(() => {
        const type = props.interview?.interview_type?.value

        return (
            formatConfig[type as keyof typeof formatConfig] ??
            formatConfig.written
        )
    })

    /*
    |--------------------------------------------------------------------------
    | Cover Media
    |--------------------------------------------------------------------------
    */

    const coverMedia = computed(() => {
        return (
            props.interview?.media?.find(
                media =>
                    media.collection === 'cover' ||
                    media.collection === 'thumbnail' ||
                    media.mime_type?.startsWith('image/'),
            ) ?? null
        )
    })

    /*
    |--------------------------------------------------------------------------
    | Participants
    |--------------------------------------------------------------------------
    */

    const participants = computed(() => {
        return (props.interview?.participants ?? []).filter(
            participant => participant.user?.name,
        )
    })

    const visibleParticipants = computed(() =>
        participants.value.slice(0, 3),
    )

    const remainingParticipants = computed(() =>
        Math.max(participants.value.length - 3, 0),
    )

    const participantNames = computed(() =>
        participants.value
            .map(participant => participant.user?.name)
            .filter(Boolean)
            .join(', '),
    )
</script>


<template>
    <Section id="interviews">
        <Grid :cols="1"
        :responsive="{
            lg: 2,
        }"
        gap="xl"
        align="center"
        class="py-14 sm:py-16 lg:py-24">
            <!-- Editorial Introduction -->

            <GridItem>
                <div class="flex flex-col justify-center">
                    <div class="mb-7 flex items-center gap-3">
                        <span class="h-px w-8 bg-brand" />

                        <span class="text-[10px] font-semibold uppercase tracking-[0.22em] text-muted-foreground">
                            Conversations & Perspectives
                        </span>
                    </div>

                    <h1
                        class="max-w-[580px] font-serif text-[46px] font-medium leading-[1.04] tracking-[-0.045em] text-foreground sm:text-[58px] lg:text-[64px] xl:text-[76px]">
                        The people.
                        <br />
                        The ideas.
                        <br />
                        <span class="font-editorial font-normal italic text-brand">
                            The conversation.
                        </span>
                    </h1>

                    <p class="mt-7 max-w-[440px] text-sm leading-7 text-muted-foreground sm:text-[15px]">
                        Thoughtful conversations with researchers,
                        leaders, creators and thinkers shaping the
                        ideas, decisions and movements of our time.
                    </p>

                    <!-- Formats -->

                    <div class="mt-8 flex flex-wrap items-center gap-x-6 gap-y-3">
                        <span v-for="format in [
                            { label: 'Written', icon: FileText },
                            { label: 'Video', icon: Play },
                            { label: 'Audio', icon: Headphones },
                        ]" :key="format.label"
                            class="flex items-center gap-2 text-[10px] font-medium uppercase tracking-[0.13em] text-muted-foreground">
                            <component :is="format.icon" :size="15" :stroke-width="1.6" class="text-brand" />
                            {{ format.label }}
                        </span>
                    </div>

                    <div class="mt-10">
                        <a href="#interview-list"
                            class="group inline-flex items-center gap-3 border-b border-brand pb-2 text-xs font-semibold uppercase tracking-[0.12em] text-foreground transition-colors hover:text-brand">
                            Explore all interviews

                            <ArrowUpRight :size="16"
                                class="transition-transform duration-200 group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                        </a>
                    </div>
                </div>
            </GridItem>

            <!-- Featured Interview -->
            <GridItem>
                <article v-if="interview" class="min-w-0">
                    <Link :href="show(interview.slug).url" :aria-label="`Read ${interview.title}`" class="group block">
                        <!-- Image -->

                        <div class="relative overflow-hidden bg-muted">
                            <div class="aspect-[4/3] overflow-hidden sm:aspect-[16/10]">
                                <img v-if="coverMedia?.url" :src="coverMedia.url"
                                    :alt="coverMedia.alt ?? interview.title"
                                    class="h-full w-full object-cover transition-transform duration-700 group-hover:scale-[1.035]" />

                                <div v-else class="flex h-full w-full items-center justify-center bg-muted">
                                    <FileText :size="42" :stroke-width="1" class="text-muted-foreground/40" />
                                </div>
                            </div>

                            <div class="absolute left-4 top-4 sm:left-5 sm:top-5">
                                <Badge variant="secondary">
                                    <Gem :size="12" />
                                    Featured conversation
                                </Badge>
                            </div>
                        </div>

                        <!-- Content -->

                        <div class="pt-6 sm:pt-7">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <span
                                    class="flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.14em] text-brand">
                                    <component :is="interviewFormat.icon" :size="14" />
                                    {{ interviewFormat.label }} interview
                                </span>

                                <span v-if="interview.published_at"
                                    class="flex items-center gap-2 text-[10px] uppercase tracking-[0.1em] text-muted-foreground">
                                    <CalendarDays :size="13" />
                                    {{ formatDate(interview.published_at) }}
                                </span>
                            </div>

                            <!-- Participants -->

                            <div v-if="participants.length" class="mt-5 flex min-w-0 items-center gap-3">
                                <div class="flex shrink-0 -space-x-2">
                                    <Avatar v-for="participant in visibleParticipants" :key="participant.id"
                                        :name="participant.user?.name" :image="participant.user?.avatar" size="size-9"
                                        text-size="text-[10px]" rounded="full" class="ring-2 ring-background" />

                                    <div v-if="remainingParticipants"
                                        class="flex size-9 items-center justify-center rounded-full bg-muted text-[10px] font-semibold text-muted-foreground ring-2 ring-background">
                                        +{{ remainingParticipants }}
                                    </div>
                                </div>

                                <p class="min-w-0 truncate text-[11px] leading-5 text-muted-foreground"
                                    :title="participantNames">
                                    {{ participantNames }}
                                </p>
                            </div>

                            <h2
                                class="mt-5 max-w-[650px] font-serif text-[25px] font-medium leading-[1.18] tracking-[-0.025em] text-foreground transition-colors group-hover:text-brand sm:text-[30px] lg:text-[32px]">
                                {{ interview.title }}
                            </h2>

                            <p v-if="interview.description"
                                class="mt-3 line-clamp-2 max-w-[620px] text-[13px] leading-6 text-muted-foreground sm:text-sm">
                                {{ interview.description }}
                            </p>

                            <div
                                class="mt-5 flex items-center gap-2 text-[10px] font-semibold uppercase tracking-[0.14em] text-foreground transition-colors group-hover:text-brand">
                                Read the conversation

                                <ArrowUpRight :size="14"
                                    class="transition-transform group-hover:translate-x-0.5 group-hover:-translate-y-0.5" />
                            </div>
                        </div>
                    </Link>
                </article>

                <!-- Empty Featured State -->

                <div v-else
                    class="flex aspect-[4/3] flex-col items-center justify-center border border-border bg-card px-6 text-center">
                    <FileText :size="32" class="text-muted-foreground/50" />

                    <h2 class="mt-5 font-serif text-2xl text-foreground">
                        Conversations worth sharing.
                    </h2>

                    <p class="mt-2 max-w-xs text-sm leading-6 text-muted-foreground">
                        Our featured interviews will appear here.
                        Check back soon.
                    </p>
                </div>
            </GridItem>
        </Grid>
    </Section>
</template>
