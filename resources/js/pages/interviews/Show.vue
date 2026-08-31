<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'

import AppContainer from '@/components/ui/AppContainer.vue'
import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'
import Avatar from '@/components/profile/Avatar.vue'

import InterviewSpeakerCard from '@/components/interview/InterviewSpeakerCard.vue'
import InterviewMediaPlayer from '@/components/interview/InterviewMediaPlayer.vue'
import AppReadingProgress from '@/components/datadisplay/progress/AppReadingProgress.vue'

import { formatDate } from '@/utils/dateUtils'

import InterviewController from '@/actions/App/Http/Controllers/Public/Interview/InterviewController'
import { show as profileShow } from '@/actions/App/Http/Controllers/Public/Profile/ProfileController'


/*
|--------------------------------------------------------------------------
| Types
|--------------------------------------------------------------------------
*/

type ParticipantRole =
    | 'interviewer'
    | 'interviewee'

interface User {
    id: number
    username: string
    name: string
    avatar?: string | null
}

interface Participant {
    id: number
    role: ParticipantRole
    user: User
}

interface InterviewMedia {
    id: number
    media_type: 'video' | 'audio'
    source_type: 'upload' | 'external'
    file_url?: string | null
    embed_url?: string | null
    duration?: number | null
    thumbnail?: string | null
}

interface InterviewAnswer {
    id: number
    answer: string
    answered_by: User
}

interface InterviewQuestion {
    id: number
    question: string
    order: number
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
    duration: string | number | null
    action_label: string
    published_at: string
    media: InterviewMedia[]
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

const interviewData = computed(() => props.interview.data)


/*
|--------------------------------------------------------------------------
| Participants
|--------------------------------------------------------------------------
*/

const interviewers = computed(() =>
    interviewData.value.participants.filter(
        participant => participant.role === 'interviewer',
    ),
)

const interviewees = computed(() =>
    interviewData.value.participants.filter(
        participant => participant.role === 'interviewee',
    ),
)

const interviewerNames = computed(() =>
    interviewers.value
        .map(participant => participant.user.name)
        .join(', '),
)

const intervieweeNames = computed(() =>
    interviewees.value
        .map(participant => participant.user.name)
        .join(', '),
)

const visibleInterviewees = computed(() =>
    interviewees.value.slice(0, 5),
)

const visibleInterviewers = computed(() =>
    interviewers.value.slice(0, 4),
)


/*
|--------------------------------------------------------------------------
| Media
|--------------------------------------------------------------------------
*/

const primaryMedia = computed(
    () => interviewData.value.media?.[0] ?? null,
)

const hasMedia = computed(
    () => Boolean(primaryMedia.value),
)


/*
|--------------------------------------------------------------------------
| Interview Type
|--------------------------------------------------------------------------
*/

const interviewTypeLabel = computed(() => {
    const type = interviewData.value.interview_type

    if (!type) {
        return 'Interview'
    }

    return type
        .replace(/[-_]/g, ' ')
        .replace(/\b\w/g, character => character.toUpperCase())
})


/*
|--------------------------------------------------------------------------
| Statistics
|--------------------------------------------------------------------------
*/

const questionCount = computed(
    () => interviewData.value.questions.length,
)

const participantCount = computed(
    () => interviewData.value.participants.length,
)


/*
|--------------------------------------------------------------------------
| Profile URL
|--------------------------------------------------------------------------
*/

function profileUrl(username: string | null | undefined): string {
    if (!username) {
        return '#'
    }

    return profileShow(username).url
}
</script>

<template>
    <Head :title="interviewData.title" />

    <div id="interview" class="pb-16 sm:pb-24 lg:pb-32" >
        <header class="mb-20 sm:mb-28 lg:mb-36">
            <!-- Title -->
            <div class="max-w-6xl">
                <AppHeading
                    tag="h1"
                    font="prata"
                    weight="normal"
                    size="5xl"
                    leading="tight"
                    tracking="tight"
                    class="text-balance sm:text-6xl lg:text-7xl xl:text-[5.5rem]"
                >
                    {{ interviewData.title }}
                </AppHeading>
            </div>


            <!-- Description -->

            <div
                v-if="interviewData.description"
                class="mt-7 max-w-3xl sm:mt-9"
            >
                <div
                    class="
                        font-lora
                        text-base
                        font-light
                        leading-relaxed
                        text-content-lightMuted
                        sm:text-xl
                        sm:leading-[1.8]
                        dark:text-content-darkMuted
                    "
                    v-html="interviewData.description"
                />
            </div>


            <!-- People -->

            <div
                v-if="interviewees.length || interviewers.length"
                class="
                    mt-10
                    flex
                    flex-col
                    gap-7
                    border-y
                    border-border
                    py-6
                    sm:mt-12
                    sm:flex-row
                    sm:flex-wrap
                    sm:items-center
                    sm:gap-10
                "
            >

                <!-- Interviewees -->

                <div
                    v-if="interviewees.length"
                    class="flex min-w-0 items-center gap-4"
                >
                    <div class="flex shrink-0 -space-x-3">
                        <Link
                            v-for="participant in visibleInterviewees"
                            :key="participant.id"
                            :href="profileUrl(participant.user.username)"
                            class="
                                rounded-full
                                border-2
                                border-background
                                transition-transform
                                hover:z-10
                                hover:-translate-y-1
                            "
                        >
                            <Avatar
                                :name="participant.user.name"
                                :image="participant.user.avatar"
                                size="size-11"
                            />
                        </Link>

                        <div
                            v-if="interviewees.length > visibleInterviewees.length"
                            class="
                                flex
                                size-11
                                items-center
                                justify-center
                                rounded-full
                                border-2
                                border-background
                                bg-muted
                                text-xs
                                font-semibold
                                text-muted-foreground
                            "
                        >
                            +{{ interviewees.length - visibleInterviewees.length }}
                        </div>
                    </div>

                    <div class="min-w-0">
                        <AppText
                            tag="span"
                            size="xs"
                            weight="semibold"
                            color="muted"
                            class="uppercase tracking-[0.16em]"
                        >
                            Featuring
                        </AppText>

                        <AppText
                            tag="div"
                            size="sm"
                            weight="semibold"
                            class="mt-1 max-w-[280px]"
                            truncate
                            :title="intervieweeNames"
                        >
                            {{ intervieweeNames }}
                        </AppText>
                    </div>
                </div>


                <!-- Divider -->

                <span
                    v-if="interviewees.length && interviewers.length"
                    class="
                        hidden
                        h-10
                        w-px
                        bg-border
                        sm:block
                    "
                    aria-hidden="true"
                />


                <!-- Interviewers -->

                <div
                    v-if="interviewers.length"
                    class="flex min-w-0 items-center gap-4"
                >
                    <div class="flex shrink-0 -space-x-2">
                        <Link
                            v-for="participant in visibleInterviewers"
                            :key="participant.id"
                            :href="profileUrl(participant.user.username)"
                            class="
                                rounded-full
                                border-2
                                border-background
                                transition-transform
                                hover:z-10
                                hover:-translate-y-1
                            "
                        >
                            <Avatar
                                :name="participant.user.name"
                                :image="participant.user.avatar"
                                size="size-10"
                            />
                        </Link>
                    </div>

                    <div class="min-w-0">
                        <AppText
                            tag="span"
                            size="xs"
                            weight="semibold"
                            color="muted"
                            class="uppercase tracking-[0.16em]"
                        >
                            Interviewed by
                        </AppText>

                        <AppText
                            tag="div"
                            size="sm"
                            weight="medium"
                            class="mt-1 max-w-[260px]"
                            truncate
                            :title="interviewerNames"
                        >
                            {{ interviewerNames }}
                        </AppText>
                    </div>
                </div>


                <!-- Meta -->

                <div class="flex items-center gap-3 sm:ml-auto">
                    <AppText
                        tag="span"
                        size="sm"
                        color="muted"
                    >
                        {{ formatDate(interviewData.published_at) }}
                    </AppText>

                    <span
                        class="size-1 rounded-full bg-muted-foreground/40"
                        aria-hidden="true"
                    />

                    <AppText
                        tag="span"
                        size="sm"
                        color="muted"
                    >
                        {{ interviewData.duration || '—' }}

                        <span v-if="interviewData.action_label">
                            {{ interviewData.action_label }}
                        </span>
                    </AppText>
                </div>
            </div>


            <!-- Media -->

            <div
                v-if="hasMedia || interviewData.thumbnail"
                class="relative mt-10 overflow-hidden bg-muted sm:mt-14"
            >
                <InterviewMediaPlayer
                    v-if="hasMedia"
                    :media="primaryMedia"
                />

                <img
                    v-else
                    :src="interviewData.thumbnail!"
                    :alt="interviewData.title"
                    class="aspect-[16/9] w-full object-cover"
                />
            </div>
        </header>


        <!-- ================================================================
             CONTENT INTRO
        ================================================================= -->

        <section
            v-if="interviewData.description"
            class="mb-20 sm:mb-28 lg:hidden"
        >
            <div class="border-l-2 border-brand pl-5 sm:pl-7">
                <AppText
                    tag="p"
                    font="lora"
                    size="lg"
                    leading="relaxed"
                    color="muted"
                    class="italic sm:text-xl"
                >
                    A conversation exploring ideas, experiences,
                    challenges, and the work behind the person.
                </AppText>
            </div>
        </section>


        <!-- ================================================================
             INTERVIEW CONTENT
        ================================================================= -->

        <main>

            <!-- Section Heading -->

            <div
                class="
                    mb-14
                    flex
                    items-end
                    justify-between
                    gap-6
                    sm:mb-20
                "
            >
                <div>
                    <AppText
                        tag="div"
                        size="xs"
                        weight="semibold"
                        color="brand"
                        class="mb-3 uppercase tracking-[0.18em]"
                    >
                        The Conversation
                    </AppText>

                    <AppHeading
                        tag="h2"
                        font="prata"
                        weight="normal"
                        size="3xl"
                        leading="tight"
                        class="sm:text-4xl"
                    >
                        Questions &amp; Answers
                    </AppHeading>
                </div>

                <AppText
                    tag="span"
                    size="sm"
                    color="muted"
                    class="shrink-0"
                >
                    {{ questionCount }}
                    {{ questionCount === 1 ? 'Question' : 'Questions' }}
                </AppText>
            </div>


            <!-- Questions -->

            <div class="space-y-20 sm:space-y-28 lg:space-y-36">
                <article
                    v-for="(question, index) in interviewData.questions"
                    :key="question.id"
                    class="
                        relative
                        grid
                        grid-cols-1
                        gap-8
                        lg:grid-cols-12
                        lg:gap-16
                    "
                >

                    <!-- Question -->

                    <div class="lg:col-span-5">
                        <div class="lg:sticky lg:top-28">

                            <!-- Number -->

                            <div
                                class="
                                    mb-5
                                    font-prata
                                    text-5xl
                                    leading-none
                                    text-muted-foreground/20
                                    sm:text-7xl
                                "
                                aria-hidden="true"
                            >
                                {{ String(index + 1).padStart(2, '0') }}
                            </div>


                            <!-- Speaker -->

                            <InterviewSpeakerCard
                                :href="profileUrl(question.asked_by.username)"
                                :name="question.asked_by.name"
                                :avatar="question.asked_by.avatar"
                                role="Interviewer"
                            />


                            <!-- Question -->

                            <AppHeading
                                tag="h2"
                                font="prata"
                                weight="normal"
                                size="2xl"
                                leading="relaxed"
                                class="mt-6 text-balance sm:text-3xl lg:text-4xl"
                            >
                                {{ question.question }}
                            </AppHeading>

                        </div>
                    </div>


                    <!-- Answers -->

                    <div
                        class="
                            space-y-12
                            lg:col-span-7
                            lg:pt-16
                            lg:space-y-20
                        "
                    >

                        <article
                            v-for="answer in question.answers"
                            :key="answer.id"
                            class="
                                relative
                                border-l
                                border-border
                                pl-5
                                sm:pl-8
                            "
                        >

                            <!-- Answer Speaker -->

                            <InterviewSpeakerCard
                                :href="profileUrl(answer.answered_by.username)"
                                :name="answer.answered_by.name"
                                :avatar="answer.answered_by.avatar"
                                role="Interviewee"
                                variant="answer"
                            />


                            <!-- Answer -->

                            <div
                                class="
                                    mt-6
                                    max-w-prose
                                    font-lora
                                    text-lg
                                    font-light
                                    leading-[1.85]
                                    text-content-light
                                    sm:text-xl
                                    lg:text-2xl
                                    dark:text-content-dark
                                "
                                v-html="answer.answer"
                            />

                        </article>


                        <!-- No Answer -->

                        <div
                            v-if="!question.answers.length"
                            class="
                                border-l-2
                                border-border
                                py-2
                                pl-5
                            "
                        >
                            <AppText
                                size="sm"
                                color="muted"
                                leading="relaxed"
                            >
                                No response has been published for this
                                question yet.
                            </AppText>
                        </div>

                    </div>
                </article>
            </div>

        </main>
    </div>


    <!-- Reading Progress -->

    <AppReadingProgress
        :title="interviewData.title"
        :href="InterviewController.show(interviewData.slug).url"
        target="#interview"
    />
</template>