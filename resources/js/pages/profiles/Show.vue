<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3'

    import Heading from '@/components/Heading.vue'
    import Avatar from '@/Components/Profile/Avatar.vue'

    import { Badge } from '@/components/ui/badge'
    import { Button } from '@/components/ui/button'

    interface Position {
        id: number
        name: string
    }

    interface Country {
        id: number
        name: string
        code: string
    }

    interface Language {
        id: number
        name: string
        native: string
        code: string
    }

    interface Industry {
        id: number
        name: string
    }

    interface Profile {
        id: number
        name: string
        username: string
        avatar: string | null
        headline: string | null
        position?: Position | null
        country?: Country | null
        languages?: Language[]
        industries?: Industry[]
    }

    interface Props {
        user: Profile
    }

    const props = defineProps<Props>()
    console.log(props.user)
</script>

<template>

    <Head :title="`${props.user.data.name} - Profile`" />

    <h1 class="sr-only">
        {{ user.data.name }} profile
    </h1>

    <div class="mx-auto max-w-5xl">

        <!-- Back -->

        <div class="mb-6">
            <Button as-child variant="ghost" size="sm" class="-ml-2">
                <Link href="/profiles">
                    <svg class="mr-2 h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M19 12H5" />
                        <path d="m12 19-7-7 7-7" />
                    </svg>

                    Profiles
                </Link>
            </Button>
        </div>


        <!-- Profile Header -->

        <section class="
                rounded-2xl
                border
                border-border
                bg-background
                px-6
                py-8
                sm:px-10
                sm:py-10
            ">
            <div class="
                    flex
                    flex-col
                    items-center
                    text-center
                    sm:flex-row
                    sm:items-start
                    sm:text-left
                ">

                <!-- Avatar -->
                <Avatar :image="user.data.avatar" :name="user.data.name" size="size-28 sm:size-32" text-size="text-2xl"
                    rounded="full" />



                <!-- Identity -->

                <div class="
                        mt-6
                        min-w-0
                        sm:ml-7
                        sm:mt-1
                    ">
                    <h2 class="
                            text-2xl
                            font-semibold
                            tracking-tight
                            text-foreground
                            sm:text-3xl
                        ">
                        {{ user.data.name }}
                    </h2>

                    <p class="
                            mt-1
                            text-sm
                            text-muted-foreground
                        ">
                        @{{ user.data.username }}
                    </p>


                    <!-- Headline -->

                    <p v-if="user.data.headline" class="
                            mt-5
                            max-w-2xl
                            text-base
                            leading-7
                            text-foreground
                        ">
                        {{ user.data.headline }}
                    </p>


                    <!-- Primary Information -->

                    <div class="
                            mt-5
                            flex
                            flex-wrap
                            justify-center
                            gap-x-5
                            gap-y-2
                            text-sm
                            sm:justify-start
                        ">

                        <div v-if="user.data.position" class="
                                flex
                                items-center
                                gap-2
                                text-muted-foreground
                            ">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <rect width="20" height="14" x="2" y="7" rx="2" />
                                <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16" />
                            </svg>

                            <span>
                                {{ user.data.position.name }}
                            </span>
                        </div>


                        <div v-if="user.data.country" class="
                                flex
                                items-center
                                gap-2
                                text-muted-foreground
                            ">
                            <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 21s7-6.1 7-12a7 7 0 1 0-14 0c0 5.9 7 12 7 12Z" />
                                <circle cx="12" cy="9" r="2.5" />
                            </svg>

                            <span>
                                {{ user.data.country.name }}
                            </span>
                        </div>

                    </div>
                </div>

            </div>
        </section>


        <!-- Details -->

        <div class="
                mt-6
                grid
                gap-6
                lg:grid-cols-[1fr_320px]
            ">

            <!-- Main -->

            <main class="
                    rounded-2xl
                    border
                    border-border
                    bg-background
                    p-6
                    sm:p-8
                ">

                <!-- Languages -->

                <section v-if="user.data.languages?.length">
                    <Heading variant="small" title="Languages" description="Languages spoken or used professionally" />

                    <div class="mt-5 flex flex-wrap gap-2">
                        <Badge v-for="language in props.user.data.languages" :key="language.id" variant="secondary">
                            {{ language.name }}
                        </Badge>
                    </div>
                </section>


                <!-- Industries -->

                <section v-if="user.data.industries?.length" class="mt-10">
                    <Heading variant="small" title="Industries"
                        description="Areas of professional interest and experience" />

                    <div class="mt-5 flex flex-wrap gap-2">
                        <Badge v-for="industry in props.user.data.industries" :key="industry.id" variant="secondary">
                            {{ industry.name }}
                        </Badge>
                    </div>
                </section>

            </main>


            <!-- Sidebar -->

            <aside class="
                    h-fit
                    rounded-2xl
                    border
                    border-border
                    bg-background
                    p-6
                ">

                <h2 class="
                        text-sm
                        font-semibold
                        text-foreground
                    ">
                    Profile information
                </h2>


                <!-- Position -->

                <div v-if="user.data.position" class="mt-5">
                    <p class="
                            text-xs
                            font-medium
                            uppercase
                            tracking-wide
                            text-muted-foreground
                        ">
                        Position
                    </p>

                    <p class="
                            mt-1
                            text-sm
                            font-medium
                            text-foreground
                        ">
                        {{ user.data.position.name }}
                    </p>
                </div>


                <!-- Country -->

                <div v-if="user.data.country" class="mt-5">
                    <p class="
                            text-xs
                            font-medium
                            uppercase
                            tracking-wide
                            text-muted-foreground
                        ">
                        Country
                    </p>

                    <p class="
                            mt-1
                            text-sm
                            font-medium
                            text-foreground
                        ">
                        {{ user.data.country.name }}
                    </p>
                </div>


                <!-- Languages count -->

                <div v-if="user.data.languages?.length" class="mt-5">
                    <p class="
                            text-xs
                            font-medium
                            uppercase
                            tracking-wide
                            text-muted-foreground
                        ">
                        Languages
                    </p>

                    <p class="
                            mt-1
                            text-sm
                            font-medium
                            text-foreground
                        ">
                        {{ user.data.languages.length }}
                    </p>
                </div>


                <!-- Industries count -->

                <div v-if="user.data.industries?.length" class="mt-5">
                    <p class="
                            text-xs
                            font-medium
                            uppercase
                            tracking-wide
                            text-muted-foreground
                        ">
                        Industries
                    </p>

                    <p class="
                            mt-1
                            text-sm
                            font-medium
                            text-foreground
                        ">
                        {{ user.data.industries.length }}
                    </p>
                </div>

            </aside>

        </div>

    </div>
</template>