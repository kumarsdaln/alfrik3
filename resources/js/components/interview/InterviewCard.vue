<script setup lang="ts">
    import { computed, ref } from 'vue'
    import { Link } from '@inertiajs/vue3'
    import { ArrowRight } from '@lucide/vue'

    import { formatDate } from '@/utils/dateUtils'

    import AppBadge from '@/Components/Ui/AppBadge.vue'
    import AppHeading from '@/Components/Ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'
    import Avatar from '@/components/profile/Avatar.vue'

    import {
        show,
    } from '@/actions/App/Domains/Interview/Http/Controllers/InterviewController'


    /*
    |--------------------------------------------------------------------------
    | Types
    |--------------------------------------------------------------------------
    */

    interface User {
        id: number
        name: string
        avatar?: string | null
        external_id?: string | null
    }

    interface Participant {
        id: number
        user?: User | null
    }

    interface Interview {
        id: number
        slug: string
        title: string
        description?: string | null
        thumbnail?: string | null
        interview_type: string
        duration: string | number
        action_label: string
        published_at: string
        participants?: Participant[]
    }

    interface Props {
        interview: Interview
    }

    const props = defineProps<Props>()


    /*
    |--------------------------------------------------------------------------
    | Thumbnail
    |--------------------------------------------------------------------------
    */

    // A thumbnail path can be present but point at a file that no longer exists,
    // so a v-if on the value alone still leaves a broken image. Track load
    // failures and fall back to the placeholder.
    const thumbFailed = ref(false)

    const hasThumbnail = computed(
        () => Boolean(props.interview.thumbnail) && !thumbFailed.value,
    )


    /*
    |--------------------------------------------------------------------------
    | Participants
    |--------------------------------------------------------------------------
    */

    const visibleParticipants = computed(() =>
        props.interview.participants?.slice(0, 3) ?? [],
    )

    const participantNames = computed(() =>
        props.interview.participants
            ?.map(participant => participant.user?.name)
            .filter(Boolean)
            .join(', ') ?? '',
    )
</script>


<template>
    <article class="
            group relative flex h-full flex-col
            overflow-hidden
        ">
        <!-- Hero -->

        <div class="
                relative aspect-[16/10]
                overflow-hidden
            ">
            <!-- Thumbnail -->

            <img v-if="hasThumbnail" :src="interview.thumbnail" :alt="interview.title"
                @error="thumbFailed = true" class="
                    h-full w-full
                    object-cover

                    transition-transform
                    duration-500

                    group-hover:scale-[1.03]
                ">


            <!-- Thumbnail Placeholder -->

            <img v-else src="/frontend/images/placeholder.jpg" :alt="interview.title" class="
                    h-full w-full
                    object-cover
                ">


            <!-- Image Overlay -->

            <div class="
                    pointer-events-none
                    absolute inset-0

                    bg-gradient-to-t
                    from-black/60
                    via-black/10
                    to-transparent
                " />


            <!-- Type -->

            <div class="absolute left-4 top-4 sm:left-5 sm:top-5">
                <AppBadge>
                    {{ interview.interview_type }}
                </AppBadge>
            </div>


            <!-- Duration -->

            <div class="absolute bottom-4 right-4 sm:bottom-5 sm:right-5">
                <div class="
                        rounded-full
                        bg-black/60

                        px-3 py-1.5

                        backdrop-blur-md
                    ">
                    <AppText size="xs" weight="medium" class="text-white">
                        {{ interview.duration }}
                        {{ interview.action_label }}
                    </AppText>
                </div>
            </div>
        </div>


        <!-- Content -->
        <div class="flex flex-1 flex-col py-5 sm:py-6">

            <!-- Meta -->
            <div class="mb-3 flex items-center gap-2 sm:mb-4">
                <AppText size="xs" weight="medium" color="muted">
                    {{ formatDate(interview.published_at) }}
                </AppText>
            </div>


            <!-- Main Interview Link -->

            <Link :href="show(interview.slug)" class="
                    after:absolute
                    after:inset-0
                    after:z-0

                    focus:outline-none
                ">
                <AppHeading tag="h3" font="prata" weight="normal" size="lg" leading="tight" :clamp="2" hover-brand>
                    {{ interview.title }}
                </AppHeading>
            </Link>


            <!-- Spacer -->
            <div class="flex-1" />


            <!-- Footer -->
            <div class="
                    relative z-10
                    mt-6
                    flex items-center
                    justify-between
                    gap-4
                    border-t
                    border-zinc-100
                    pt-5
                    dark:border-white/10
                ">
                <!-- Participants -->
                <div class="flex min-w-0 items-center gap-3">

                    <!-- Avatars -->
                    <div v-if="visibleParticipants.length" class="flex shrink-0 -space-x-2">
                        <template v-for="participant in visibleParticipants" :key="participant.id">
                            <div class="relative rounded-full border-2
                                    border-white
                                    dark:border-[#080808]
                                ">
                                <Avatar 
                                    :name="participant.user?.name" 
                                    :image="participant.user?.avatar"
                                    size="h-9 w-9" />
                            </div>
                        </template>
                    </div>


                    <!-- Expert Information -->
                    <div v-if="participantNames" class="hidden min-w-0 max-w-[180px] flex-col gap-1 sm:flex">
                        <AppText size="xs" color="muted">
                            Featured Experts
                        </AppText>
                        <AppText size="sm" weight="medium" truncate>
                            {{ participantNames }}
                        </AppText>
                    </div>
                </div>


                <!-- CTA -->
                <div class="flex shrink-0 items-center gap-2 text-brand">
                    <AppText size="sm" weight="semibold" class="text-brand">
                        View
                    </AppText>
                    <ArrowRight class="h-4 w-4 transition-transform duration-200 group-hover:translate-x-1" />
                </div>
            </div>
        </div>
    </article>
</template>