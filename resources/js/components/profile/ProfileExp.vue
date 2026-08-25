<script setup lang="ts">
    import { BadgeCheck } from '@lucide/vue'

    import Briefcase from '@/Icons/Briefcase.vue'

    import AppHeading from '@/Components/Ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'

    import ExperienceCard from '../Cards/ExperienceCard.vue'


    interface Experience {
        id?: number | string

        company_name: string
        position?: string | null

        start_date?: string | null
        end_date?: string | null

        is_verified?: boolean
    }


    interface Props {
        experience?: Experience[]
    }


    const props = withDefaults(
        defineProps < Props > (),
        {
            experience: () => [],
        },
    )


    function initials(name?: string | null): string {
        if (!name?.trim()) {
            return '—'
        }

        return name
            .trim()
            .split(/\s+/)
            .filter(Boolean)
            .map(word => word.charAt(0))
            .join('')
            .slice(0, 2)
            .toUpperCase()
    }
</script>


<template>
    <section v-if="props.experience.length" class="w-full">

        <!-- Header -->

        <div class="
                mb-8
                flex
                items-start
                justify-between
                gap-6

                sm:mb-10
            ">

            <div>

                <AppHeading tag="span" font="redhat" size="xs" weight="bold" tracking="wide" uppercase color="brand" class="
                        mb-2
                        block
                    ">
                    Institutional Record
                </AppHeading>


                <AppHeading tag="h2" font="prata" size="xl" weight="normal" class="
                        tracking-tight
                        text-content-light
                        dark:text-content-dark
                    ">
                    Professional Timeline
                </AppHeading>

            </div>


            <!-- Icon -->

            <div aria-hidden="true" class="
                    flex
                    h-11
                    w-11
                    shrink-0
                    items-center
                    justify-center

                    rounded-full

                    border
                    border-border-light

                    text-content-lightMuted

                    dark:border-border-dark
                    dark:text-content-darkMuted
                ">
                <Briefcase class="h-4.5 w-4.5" />
            </div>

        </div>


        <!-- Timeline -->

        <div class="relative">

            <!-- Vertical Line -->

            <div aria-hidden="true" class="
                    absolute

                    bottom-5
                    left-[19px]
                    top-5

                    w-px

                    bg-border-light
                    dark:bg-border-dark
                " />


            <!-- Experience Items -->

            <div class="space-y-10">

                <article v-for="(item, index) in props.experience" :key="item.id
                    ?? `${item.company_name}-${item.start_date}-${index}`
                    " class="
                        group
                        relative

                        flex
                        items-start
                        gap-5

                        sm:gap-6
                    ">

                    <!-- Timeline Node -->

                    <div class="
                            relative
                            z-10

                            flex
                            h-10
                            w-10
                            shrink-0
                            items-center
                            justify-center

                            rounded-full

                            border

                            bg-canvas-light

                            font-redhat
                            text-[10px]
                            font-semibold
                            tracking-wide

                            transition-colors
                            duration-300

                            dark:bg-canvas-dark
                        " :class="!item.end_date
                                ? `
                                    border-brand/40
                                    text-brand
                                    dark:border-brand/50
                                    dark:text-brand
                                `
                                : `
                                    border-border-light
                                    text-content-lightMuted
                                    dark:border-border-dark
                                    dark:text-content-darkMuted
                                `
                            ">
                        {{ initials(item.company_name) }}
                    </div>


                    <!-- Content -->

                    <div class="
                            min-w-0
                            flex-1
                            pt-0.5
                        ">

                        <ExperienceCard :item="item" />


                        <!-- Metadata -->

                        <div v-if="item.is_verified || !item.end_date" class="
                                mt-3
                                flex
                                flex-wrap
                                items-center
                                gap-x-4
                                gap-y-2
                            ">

                            <!-- Verified -->

                            <div v-if="item.is_verified" class="
                                    flex
                                    items-center
                                    gap-1.5

                                    text-content-lightMuted
                                    dark:text-content-darkMuted
                                ">
                                <BadgeCheck aria-hidden="true" class="h-3.5 w-3.5" />

                                <AppText tag="span" font="redhat" size="xs" color="muted">
                                    Verified tenure
                                </AppText>
                            </div>


                            <!-- Current Position -->

                            <div v-if="!item.end_date" class="
                                    flex
                                    items-center
                                    gap-2
                                ">
                                <span aria-hidden="true" class="
                                        h-1.5
                                        w-1.5
                                        rounded-full
                                        bg-brand
                                    " />

                                <AppText tag="span" font="redhat" size="xs" weight="medium" class="text-brand">
                                    Current position
                                </AppText>
                            </div>

                        </div>

                    </div>

                </article>

            </div>

        </div>

    </section>
</template>