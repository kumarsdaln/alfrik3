
<script setup lang="ts">
import { computed } from 'vue'
import { Link } from '@inertiajs/vue3'
import { ArrowRight, Gem, Users } from '@lucide/vue'

import type { Research } from '@/types'

import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import Avatar from '@/components/profile/Avatar.vue'

import Section from '../ui/Section.vue'
import { Grid, GridItem } from '../ui/grid'
import Badge from '../ui/badge/Badge.vue'
import { MetaInfo } from '../ui/metainfo'

const props = defineProps<{
    research: Research
    researchUrl: string
}>()

/*
|--------------------------------------------------------------------------
| Media
|--------------------------------------------------------------------------
*/

const coverImage = computed(() =>
    props.research.media?.find(media =>
        media.mime_type?.startsWith('image/')
    )?.original_url ?? null
)

/*
|--------------------------------------------------------------------------
| Research Information
|--------------------------------------------------------------------------
*/

const summary = computed(() =>
    props.research.summary || props.research.description
)

const categories = computed(() =>
    props.research.categories ?? []
)

const tags = computed(() =>
    props.research.tags ?? []
)

/*
|--------------------------------------------------------------------------
| Participants
|--------------------------------------------------------------------------
*/

const participants = computed(() =>
    (props.research.participants ?? [])
        .filter(participant => participant.user?.name)
)

const visibleParticipants = computed(() =>
    participants.value.slice(0, 4)
)

const remainingParticipants = computed(() =>
    Math.max(participants.value.length - 4, 0)
)

const participantNames = computed(() =>
    participants.value
        .map(participant => participant.user?.name)
        .filter(Boolean)
        .join(', ')
)

/*
|--------------------------------------------------------------------------
| Publication Metadata
|--------------------------------------------------------------------------
*/

const metaItems = computed(() => [
    ...(props.research.published_at
        ? [{
            label: 'Published',
            value: new Intl.DateTimeFormat('en-IN', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
            }).format(new Date(props.research.published_at)),
        }]
        : []),

    ...(props.research.methodology?.geography
        ? [{
            label: 'Geography',
            value: props.research.methodology.geography,
        }]
        : []),

    ...(props.research.methodology?.sample_size
        ? [{
            label: 'Sample size',
            value: props.research.methodology.sample_size.toLocaleString('en-IN'),
        }]
        : []),
])
</script>

<template>
    <Section background="paper" spacing="lg">
        <Grid
            :cols="1"
            :responsive="{ lg: 2 }"
            gap="2xl"
            align="center"
        >
            <!-- Research Image -->
            <GridItem>
                <div class="relative overflow-hidden bg-muted">
                    <img
                        v-if="coverImage"
                        :src="coverImage"
                        :alt="research.title"
                        class="aspect-[4/3] w-full object-cover"
                        fetchpriority="high"
                    />

                    <div
                        v-else
                        class="flex aspect-[4/3] w-full items-center justify-center"
                    >
                        <AppText size="sm" color="muted">
                            Research publication
                        </AppText>
                    </div>

                    <!-- Featured Badge -->
                    <div
                        v-if="research.featured"
                        class="absolute left-4 top-4"
                    >
                        <Badge variant="secondary">
                            <Gem :size="12" />
                            Featured research
                        </Badge>
                    </div>
                </div>
            </GridItem>

            <!-- Research Content -->
            <GridItem>
                <div class="flex flex-col items-start">

                    <!-- Research Type -->
                    <AppText
                        tag="p"
                        size="xs"
                        weight="semibold"
                        uppercase
                        tracking="wide"
                        color="brand"
                    >
                        {{ research.type.label }}
                    </AppText>

                    <!-- Categories -->
                    <div
                        v-if="categories.length"
                        class="mt-3 flex flex-wrap items-center gap-x-2 gap-y-1"
                    >
                        <template
                            v-for="(category, index) in categories"
                            :key="category.id"
                        >
                            <AppText
                                tag="span"
                                size="xs"
                                color="muted"
                                weight="medium"
                            >
                                {{ category.name }}
                            </AppText>

                            <span
                                v-if="index < categories.length - 1"
                                class="text-border-light dark:text-border-dark"
                            >
                                /
                            </span>
                        </template>
                    </div>

                    <!-- Title -->
                    <AppHeading
                        tag="h1"
                        font="prata"
                        size="3xl"
                        weight="normal"
                        leading="tight"
                        hover-brand
                        class="mt-4"
                    >
                        {{ research.title }}
                    </AppHeading>

                    <!-- Subtitle -->
                    <AppText
                        v-if="research.subtitle"
                        tag="p"
                        font="lora"
                        size="lg"
                        color="muted"
                        leading="relaxed"
                        class="mt-4"
                    >
                        {{ research.subtitle }}
                    </AppText>

                    <!-- Summary -->
                    <AppText
                        v-if="summary"
                        tag="p"
                        font="lora"
                        size="sm"
                        color="muted"
                        leading="relaxed"
                        :clamp="4"
                        class="mt-4"
                    >
                        {{ summary }}
                    </AppText>

                    <!-- Research Topics -->
                    <div
                        v-if="tags.length"
                        class="mt-5 flex flex-wrap items-center gap-x-3 gap-y-2"
                    >
                        <AppText
                            v-for="tag in tags"
                            :key="tag.id"
                            tag="span"
                            size="xs"
                            color="muted"
                        >
                            #{{ tag.name }}
                        </AppText>
                    </div>

                    <!-- Research Participants -->
                    <div
                        v-if="participants.length"
                        class="
                            mt-7
                            flex
                            w-full
                            items-center
                            gap-3
                            border-t
                            border-border-light
                            pt-5
                            dark:border-border-dark
                        "
                    >
                        <div class="flex shrink-0 items-center -space-x-3">
                            <Avatar
                                v-for="participant in visibleParticipants"
                                :key="participant.id"
                                :name="participant.user?.name"
                                :image="participant.user?.avatar"
                                size="size-9"
                                rounded="full"
                                class="ring-2 ring-background"
                            />

                            <div
                                v-if="remainingParticipants"
                                class="
                                    flex
                                    size-9
                                    items-center
                                    justify-center
                                    rounded-full
                                    bg-muted
                                    text-[10px]
                                    font-semibold
                                    text-muted-foreground
                                    ring-2
                                    ring-background
                                "
                            >
                                +{{ remainingParticipants }}
                            </div>
                        </div>

                        <div class="min-w-0">
                            <AppText
                                tag="p"
                                size="xs"
                                weight="semibold"
                                class="truncate"
                            >
                                {{ participantNames }}
                            </AppText>

                            <AppText
                                tag="p"
                                size="xs"
                                color="muted"
                                class="mt-0.5"
                            >
                                {{ participants.length }}
                                {{ participants.length === 1 ? 'Research contributor' : 'Research contributors' }}
                            </AppText>
                        </div>
                    </div>

                    <!-- Publication Metadata -->
                    <div
                        v-if="metaItems.length"
                        class="mt-5 w-full"
                    >
                        <MetaInfo :items="metaItems" />
                    </div>

                    <!-- CTA -->
                    <Link
                        :href="researchUrl"
                        class="
                            group
                            mt-7
                            inline-flex
                            items-center
                            gap-2
                            text-sm
                            font-semibold
                            text-primary
                            transition-colors
                            hover:text-primary/75
                        "
                    >
                        Explore full research

                        <ArrowRight
                            class="
                                h-4
                                w-4
                                transition-transform
                                duration-200
                                group-hover:translate-x-1
                            "
                        />
                    </Link>
                </div>
            </GridItem>
        </Grid>
    </Section>
</template>
