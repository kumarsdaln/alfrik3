<script setup lang="ts">
    import { computed } from 'vue'
    import { Head } from '@inertiajs/vue3'

    import AppContainer from '@/components/ui/AppContainer.vue'
    import AppBadge from '@/components/ui/AppBadge.vue'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import Avatar from '@/components/profile/Avatar.vue'

    import InterviewSpeakerCard from '@/components/interview/InterviewSpeakerCard.vue'
    import InterviewMediaPlayer from '@/components/interview/InterviewMediaPlayer.vue'
    import AppReadingProgress from '@/components/datadisplay/progress/AppReadingProgress.vue'
    import { formatDate } from '@/utils/dateUtils'
    import InterviewController from '@/actions/App/Http/Controllers/Public/Interview/InterviewController'


    // Types
    type ParticipantRole =
        | 'interviewer'
        | 'interviewee'


    interface User {
        id: number
        name: string
        avatar?: string | null
        external_id: string
    }


    interface Participant {
        id: number
        role: ParticipantRole
        user: User
    }


    interface InterviewAnswer {
        id: number
        answer: string
        answered_by: User
    }


    interface InterviewQuestion {
        id: number
        question: string
        asked_by: User
        answers: InterviewAnswer[]
    }


    interface InterviewData {
        id: number
        slug: string
        title: string
        description?: string | null
        thumbnail?: string | null
        interview_type: string
        duration: string | number
        action_label: string
        published_at: string

        participants: Participant[]
        questions: InterviewQuestion[]
    }


    interface InterviewResource {
        data: InterviewData
    }


    interface Props {
        interview: InterviewResource
    }


    const props = defineProps<Props>()


    /*
    |--------------------------------------------------------------------------
    | Interview
    |--------------------------------------------------------------------------
    */

    const interviewData = computed(
        () => props.interview.data,
    )


    /*
    |--------------------------------------------------------------------------
    | Participants
    |--------------------------------------------------------------------------
    */

    const interviewers = computed(
        () =>
            interviewData.value.participants.filter(
                participant =>
                    participant.role === 'interviewer',
            ),
    )


    const interviewees = computed(
        () =>
            interviewData.value.participants.filter(
                participant =>
                    participant.role === 'interviewee',
            ),
    )


    const interviewerNames = computed(
        () =>
            interviewers.value
                .map(participant => participant.user.name)
                .join(', '),
    )


    const intervieweeNames = computed(
        () =>
            interviewees.value
                .map(participant => participant.user.name)
                .join(', '),
    )
</script>


<template>

    <Head :title="interviewData.title" />
    <AppContainer id="interview">

        <!-- Interview Header -->
        <header class="
                    relative
                    mb-16

                    lg:mb-28
                ">

            <!-- Cover -->

            <div class="
                        relative
                        overflow-hidden

                        bg-zinc-100

                        dark:bg-zinc-900
                    ">
                <!-- Media player (video / audio interviews) -->
                <InterviewMediaPlayer v-if="interviewData.media && interviewData.media.length"
                    :media="interviewData.media[0]" />

                <img v-else-if="interviewData.thumbnail" :src="interviewData.thumbnail" :alt="interviewData.title"
                    class="
                            aspect-video
                            w-full

                            object-cover
                        ">


                <!-- No Thumbnail -->

                <div v-else class="
                            flex aspect-video
                            items-center
                            justify-center

                            bg-zinc-100

                            dark:bg-zinc-900
                        ">
                    <AppText size="sm" color="muted">
                        No interview cover image
                    </AppText>
                </div>


                <!-- Badge -->

                <div class="
                            absolute
                            left-4 top-4

                            sm:left-6
                            sm:top-6
                        ">
                    <AppBadge>
                        {{ interviewData.interview_type }}
                    </AppBadge>
                </div>

            </div>


            <!-- Header Information -->

            <div class="
                        relative z-10

                        pt-8

                        lg:-mt-16
                        lg:px-8
                        lg:pt-0
                    ">

                <!-- Title -->

                <div class="
                            lg:max-w-5xl
                            lg:bg-[#FAFAFA]
                            lg:p-8
                            lg:pb-6

                            dark:lg:bg-[#050505]
                        ">
                    <AppHeading tag="h1" font="prata" weight="normal" size="5xl" leading="tight" tracking="tight">
                        {{ interviewData.title }}
                    </AppHeading>
                </div>


                <!-- Interview Meta -->

                <div class="
                            flex flex-col
                            gap-6

                            border-t
                            border-zinc-200

                            pt-6

                            sm:flex-row
                            sm:flex-wrap
                            sm:items-center

                            lg:gap-10

                            dark:border-white/10
                        ">

                    <!-- Featured Guests -->

                    <div v-if="interviewees.length" class="
                                flex
                                items-center
                                gap-3
                            ">

                        <div class="flex -space-x-3">

                            <div v-for="guest in interviewees.slice(0, 3)" :key="guest.id" class="
                                        rounded-full

                                        border-2
                                        border-[#FAFAFA]

                                        dark:border-[#050505]
                                    ">
                                <Avatar :name="guest.user.name" :image="guest.user.avatar" size="h-11 w-11" />
                            </div>

                        </div>


                        <div class="min-w-0">

                            <AppText size="xs" color="muted" weight="medium" class="
                                        uppercase
                                        tracking-widest
                                    ">
                                Featuring
                            </AppText>


                            <AppText size="sm" weight="semibold" truncate class="
                                        mt-1
                                        max-w-[240px]
                                    ">
                                {{ intervieweeNames }}
                            </AppText>

                        </div>

                    </div>


                    <!-- Hosts -->

                    <div v-if="interviewers.length" class="
                                flex
                                items-center
                                gap-3

                                sm:border-l
                                sm:border-zinc-200
                                sm:pl-8

                                dark:sm:border-white/10
                            ">

                        <div class="flex -space-x-2">

                            <div v-for="host in interviewers.slice(0, 2)" :key="host.id" class="
                                        rounded-full

                                        border-2
                                        border-[#FAFAFA]

                                        dark:border-[#050505]
                                    ">
                                <Avatar :name="host.user.name" :image="host.user.avatar" size="h-10 w-10" />
                            </div>

                        </div>


                        <div class="min-w-0">

                            <AppText size="xs" color="muted" weight="medium" class="
                                        uppercase
                                        tracking-widest
                                    ">
                                Hosted By
                            </AppText>


                            <AppText size="sm" weight="medium" truncate class="
                                        mt-1
                                        max-w-[220px]
                                    ">
                                {{ interviewerNames }}
                            </AppText>

                        </div>

                    </div>


                    <!-- Date and Duration -->

                    <div class="
                                flex
                                items-center
                                gap-3

                                sm:ml-auto
                            ">
                        <AppText size="sm" color="muted" weight="medium">
                            {{
                                formatDate(
                                    interviewData.published_at,
                                )
                            }}
                        </AppText>


                        <span class="
                                    h-1 w-1
                                    rounded-full

                                    bg-zinc-300

                                    dark:bg-zinc-700
                                " />


                        <AppText size="sm" color="muted" weight="medium">
                            {{ interviewData.duration }}
                            {{ interviewData.action_label }}
                        </AppText>

                    </div>

                </div>

            </div>

        </header>


        <!--
            |--------------------------------------------------------------------------
            | Interview Introduction
            |--------------------------------------------------------------------------
            -->

        <section v-if="interviewData.description" class="
                    mb-24
                    max-w-3xl

                    lg:mb-36
                ">
            <div class="
                        prose
                        prose-lg
                        prose-zinc

                        max-w-none

                        font-lora

                        prose-p:text-xl
                        prose-p:font-light
                        prose-p:leading-[1.8]
                        prose-p:text-zinc-700

                        dark:prose-invert
                        dark:prose-p:text-slate-300

                        sm:prose-p:text-2xl
                    " v-html="interviewData.description" />
        </section>


        <!--
            |--------------------------------------------------------------------------
            | Questions and Answers
            |--------------------------------------------------------------------------
            -->

        <main class="pb-28">

            <div class="
                        relative
                        space-y-24

                        lg:space-y-44
                    ">

                <!-- Vertical Timeline -->

                <div class="
                            absolute
                            bottom-0
                            left-5/12
                            top-0

                            hidden
                            w-px

                            -translate-x-1/2

                            bg-zinc-200

                            lg:block

                            dark:bg-white/5
                        " />


                <!-- Question -->

                <article v-for="(question, index) in interviewData.questions" :key="question.id" class="
                            group relative
                            grid grid-cols-1
                            gap-10
                            lg:grid-cols-12
                            lg:gap-20
                        ">

                    <!-- Question Side -->
                    <div class="relative lg:col-span-5">
                        <div class="lg:sticky lg:top-28">

                            <!-- Decorative Number -->
                            <div aria-hidden="true" class="
                                        pointer-events-none

                                        absolute
                                        -left-4
                                        -top-16

                                        select-none

                                        font-prata
                                        text-[7rem]
                                        leading-none

                                        text-zinc-100

                                        transition-colors
                                        duration-500

                                        group-hover:text-brand/10

                                        dark:text-white/[0.025]
                                        dark:group-hover:text-brand/[0.05]

                                        sm:-left-8
                                        sm:text-[9rem]
                                    ">
                                {{
                                    String(index + 1)
                                        .padStart(2, '0')
                                }}
                            </div>


                            <!-- Question Content -->
                            <div class="relative z-10">
                                <InterviewSpeakerCard :href="expertsProfile(
                                    question.asked_by.external_id,
                                ).url" :name="question.asked_by.name" :avatar="question.asked_by.avatar" />


                                <AppHeading tag="h2" font="redhat" size="3xl" weight="normal" leading="relaxed"
                                    class="mt-5 sm:text-4xl">
                                    “{{ question.question }}”
                                </AppHeading>

                            </div>

                        </div>
                    </div>


                    <!-- Answers -->

                    <div class="
                                space-y-14
                                lg:col-span-7
                                lg:pt-14
                                lg:space-y-20
                            ">

                        <article v-for="answer in question.answers" :key="answer.id" class="relative">
                            <InterviewSpeakerCard :href="expertsProfile(
                                answer.answered_by.external_id,
                            ).url" :name="answer.answered_by.name" :avatar="answer.answered_by.avatar"
                                role="Guest" />

                            <div class="
                                        mt-5
                                        max-w-prose
                                        whitespace-pre-wrap
                                        font-lora
                                        text-xl
                                        font-light
                                        leading-[1.8]
                                        text-zinc-800
                                        sm:text-2xl
                                        dark:text-slate-300" v-html="answer.answer" />

                        </article>


                        <!-- No Answers -->

                        <div v-if="!question.answers.length" class="
                                    border-l-2
                                    border-zinc-200

                                    py-2 pl-5

                                    dark:border-zinc-800
                                ">
                            <AppText size="sm" color="muted" leading="relaxed">
                                No response has been published for this question yet.
                            </AppText>
                        </div>

                    </div>

                </article>

            </div>

        </main>

    </AppContainer>


    <!-- Reading Progress -->
    <AppReadingProgress :title="interviewData.title" :href="InterviewController.show(interviewData.slug).url"
        target="#interview" />
</template>