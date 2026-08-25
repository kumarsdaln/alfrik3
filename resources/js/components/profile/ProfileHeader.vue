<script setup lang="ts">
import { ref } from 'vue'
import {
    router,
    usePage,
} from '@inertiajs/vue3'

import axios from 'axios'

import {
    Languages,
    MapPin,
} from '@lucide/vue'

import { login } from '@/routes'
import { follow as expertsFollow } from '@/routes/experts'

import Organization from '@/Icons/Organization.vue'

import AppBadge from '@/Components/Ui/AppBadge.vue'
import AppContainer from '@/Components/Ui/AppContainer.vue'
import AppHeading from '@/Components/Ui/AppHeading.vue'
import AppText from '@/Components/Ui/AppText.vue'


interface Country {
    id?: number | string
    name: string
}


interface Language {
    id: number | string
    name: string
}


interface Industry {
    id: number | string
    name: string
}


interface Expert {
    specialization?: string | null
    languages?: Language[]
    industries?: Industry[]
}


interface Profile {
    external_id: string

    name: string
    avatar?: string | null
    banner?: string | null

    is_verified?: boolean

    country?: Country | null

    expert: Expert
}


interface Props {
    profile: Profile
    isFollowing?: boolean
    followersCount?: number
}


const props = withDefaults(
    defineProps<Props>(),
    {
        isFollowing: false,
        followersCount: 0,
    },
)


const page = usePage()

const following = ref(props.isFollowing)
const followers = ref(props.followersCount)

const isToggling = ref(false)


const isOwnProfile =
    page.props.auth?.user?.external_id
    === props.profile.external_id


async function toggleFollow(): Promise<void> {
    if (!page.props.auth?.user) {
        router.get(login().url)

        return
    }

    if (isToggling.value) {
        return
    }

    isToggling.value = true

    try {
        const response = await axios.post(
            expertsFollow(
                props.profile.external_id,
            ).url,
        )

        const status = response.data.status

        if (status === 'followed') {
            following.value = true
            followers.value += 1
        }
        else if (status === 'unfollowed') {
            following.value = false

            followers.value = Math.max(
                0,
                followers.value - 1,
            )
        }
    }
    catch (error) {
        console.error(
            'Unable to update follow status.',
            error,
        )
    }
    finally {
        isToggling.value = false
    }
}


function profileInitial(
    name?: string | null,
): string {
    return name
        ?.trim()
        .charAt(0)
        .toUpperCase() || '—'
}
</script>


<template>
    <section class="w-full pb-10">

        <!-- Banner -->

        <div
            class="
                relative
                h-48
                w-full
                overflow-hidden

                bg-canvas-light

                sm:h-64
                md:h-72
                lg:h-[320px]

                dark:bg-white/5
            "
        >

            <img
                v-if="profile.banner || profile.expert?.banner"
                :src="profile.banner || profile.expert?.banner"
                :alt="`${profile.name} profile banner`"

                class="
                    h-full
                    w-full
                    object-cover
                "
            />


            <!-- Banner Fallback -->

            <div
                v-else
                class="
                    h-full
                    w-full

                    bg-canvas-light

                    dark:bg-white/5
                "
            />


            <!-- Verification -->

            <div
                v-if="profile.is_verified"
                class="
                    absolute
                    right-4
                    top-4

                    sm:right-6
                    sm:top-6

                    lg:right-8
                    lg:top-8
                "
            >
                <AppBadge
                    variant="primary"
                    class="
                        shadow-sm
                        backdrop-blur-sm
                    "
                >
                    Impact Verified
                </AppBadge>
            </div>

        </div>


        <!-- Profile Content -->

        <AppContainer
            class="
                px-4
                sm:px-6
                lg:px-8
            "
        >

            <div
                class="
                    relative
                    z-10

                    -mt-16

                    flex
                    flex-col
                    items-center

                    sm:-mt-20

                    lg:-mt-24
                    lg:flex-row
                    lg:items-start
                    lg:gap-8
                "
            >

                <!-- Avatar -->

                <div class="shrink-0">

                    <div
                        class="
                            h-32
                            w-32
                            overflow-hidden

                            rounded-full

                            border-[5px]
                            border-canvas-light

                            bg-canvas-light

                            shadow-lg

                            sm:h-40
                            sm:w-40

                            lg:h-48
                            lg:w-48

                            dark:border-canvas-dark
                            dark:bg-white/5
                        "
                    >

                        <img
                            v-if="profile.avatar || profile.expert?.avatar"
                            :src="profile.avatar || profile.expert?.avatar"
                            :alt="profile.name"

                            class="
                                h-full
                                w-full
                                object-cover
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

                                font-lora
                                text-4xl
                                font-semibold

                                text-content-lightMuted

                                lg:text-5xl

                                dark:text-content-darkMuted
                            "
                        >
                            {{ profileInitial(profile.name) }}
                        </div>

                    </div>

                </div>


                <!-- Identity & Actions -->

                <div
                    class="
                        mt-5
                        w-full

                        flex
                        flex-col

                        gap-7

                        text-center

                        lg:mt-0
                        lg:flex-row
                        lg:items-end
                        lg:justify-between
                        lg:gap-10
                        lg:pt-28
                        lg:text-left
                    "
                >

                    <!-- Identity -->

                    <div
                        class="
                            min-w-0
                            flex-1
                        "
                    >

                        <AppHeading
                            tag="h1"
                            font="prata"
                            size="3xl"
                            weight="normal"
                            tracking="tight"
                        >
                            {{ profile.name }}
                        </AppHeading>


                        <AppText
                            v-if="profile.expert?.specialization"
                            tag="p"
                            font="lora"
                            size="lg"
                            leading="relaxed"
                            color="muted"
                            class="mt-2"
                        >
                            {{ profile.expert?.specialization }}
                        </AppText>


                        <!-- Location & Languages -->

                        <div
                            v-if="
                                profile.country
                                || profile.expert?.languages?.length
                            "

                            class="
                                mt-4

                                flex
                                flex-wrap
                                items-center
                                justify-center

                                gap-x-4
                                gap-y-2

                                lg:justify-start
                            "
                        >

                            <!-- Location -->

                            <div
                                v-if="profile.country"

                                class="
                                    flex
                                    items-center
                                    gap-1.5

                                    text-content-lightMuted
                                    dark:text-content-darkMuted
                                "
                            >

                                <MapPin
                                    aria-hidden="true"
                                    class="
                                        h-4
                                        w-4
                                        shrink-0
                                    "
                                />


                                <AppText
                                    tag="span"
                                    font="redhat"
                                    size="xs"
                                    color="muted"
                                >
                                    {{ profile.country.name }}
                                </AppText>

                            </div>


                            <!-- Divider -->

                            <span
                                v-if="
                                    profile.country
                                    && profile.expert?.languages?.length
                                "

                                aria-hidden="true"

                                class="
                                    hidden
                                    h-3
                                    w-px

                                    bg-border-light

                                    sm:block

                                    dark:bg-border-dark
                                "
                            />


                            <!-- Languages -->

                            <div
                                v-if="profile.expert?.languages?.length"

                                class="
                                    flex
                                    items-center
                                    gap-1.5

                                    text-content-lightMuted
                                    dark:text-content-darkMuted
                                "
                            >

                                <Languages
                                    aria-hidden="true"

                                    class="
                                        h-4
                                        w-4
                                        shrink-0
                                    "
                                />


                                <AppText
                                    tag="span"
                                    font="redhat"
                                    size="xs"
                                    color="muted"
                                >
                                    {{
                                        profile.expert?.languages
                                            .map(language => language.name)
                                            .join(', ')
                                    }}
                                </AppText>

                            </div>

                        </div>


                        <!-- Industries -->

                        <div
                            v-if="profile.expert?.industries?.length"

                            class="
                                mt-5

                                flex
                                flex-wrap
                                items-center
                                justify-center

                                gap-2

                                lg:justify-start
                            "
                        >

                            <Organization
                                aria-hidden="true"

                                class="
                                    mr-1
                                    h-4
                                    w-4
                                    shrink-0

                                    text-content-lightMuted
                                    dark:text-content-darkMuted
                                "
                            />


                            <span
                                v-for="industry in profile.expert?.industries"
                                :key="industry.id"

                                class="
                                    rounded-full

                                    border
                                    border-border-light

                                    px-3
                                    py-1

                                    font-redhat
                                    text-[11px]
                                    font-medium

                                    text-content-lightMuted

                                    dark:border-border-dark
                                    dark:text-content-darkMuted
                                "
                            >
                                {{ industry.name }}
                            </span>

                        </div>

                    </div>


                    <!-- Follow / Authority Block -->

                    <aside
                        class="
                            mx-auto
                            w-full
                            max-w-sm

                            border-t
                            border-border-light

                            pt-5

                            text-left

                            lg:mx-0
                            lg:w-64
                            lg:border-l
                            lg:border-t-0
                            lg:pl-6
                            lg:pt-0

                            dark:border-border-dark
                        "
                    >

                        <AppText
                            tag="span"
                            font="redhat"
                            size="xs"
                            weight="bold"
                            tracking="wide"
                            uppercase
                            color="muted"
                        >
                            Community
                        </AppText>


                        <div
                            class="
                                mt-1

                                flex
                                items-baseline
                                gap-2
                            "
                        >

                            <AppHeading
                                tag="span"
                                font="redhat"
                                size="xl"
                                weight="bold"
                            >
                                {{ followers.toLocaleString() }}
                            </AppHeading>


                            <AppText
                                tag="span"
                                font="redhat"
                                size="xs"
                                color="muted"
                            >
                                Followers
                            </AppText>

                        </div>


                        <!-- Follow Button -->

                        <button
                            v-if="!isOwnProfile"
                            type="button"

                            :disabled="isToggling"
                            :aria-pressed="following"

                            class="
                                mt-4

                                flex
                                w-full
                                items-center
                                justify-center
                                gap-2

                                rounded-full

                                border

                                px-5
                                py-2.5

                                font-redhat
                                text-xs
                                font-semibold
                                tracking-wide

                                transition-all
                                duration-200

                                disabled:cursor-not-allowed
                                disabled:opacity-50

                                focus-visible:outline-none
                                focus-visible:ring-2
                                focus-visible:ring-brand/30
                                focus-visible:ring-offset-2

                                active:scale-[0.98]

                                dark:focus-visible:ring-offset-canvas-dark
                            "

                            :class="
                                following
                                    ? `
                                        border-border-light
                                        text-content-light

                                        hover:border-brand

                                        dark:border-border-dark
                                        dark:text-content-dark
                                        dark:hover:border-brand
                                    `
                                    : `
                                        border-brand
                                        bg-brand
                                        text-white

                                        hover:bg-brand-hover
                                    `
                            "

                            @click="toggleFollow"
                        >

                            <span
                                v-if="following"

                                aria-hidden="true"

                                class="
                                    h-1.5
                                    w-1.5

                                    rounded-full
                                    bg-brand
                                "
                            />

                            {{
                                isToggling
                                    ? 'Updating...'
                                    : following
                                        ? 'Following'
                                        : 'Follow'
                            }}

                        </button>

                    </aside>

                </div>

            </div>

        </AppContainer>

    </section>
</template>