<script setup lang="ts">
    import { computed } from 'vue'
    import { BadgeCheck } from '@lucide/vue'

    import Skills from '@/Icons/skills.vue'

    import AppHeading from '@/Components/Ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'
    import { ProfileSkill, SkillLevel } from '@/types/profile'


    interface Props {
        skills?: ProfileSkill[]
    }


    const props = withDefaults(
        defineProps < Props > (),
        {
            skills: () => [],
        },
    )


    const levelConfig: Record<
        SkillLevel,
        {
            label: string
        }
    > = {
        beginner: {
            label: 'Beginner',
        },

        intermediate: {
            label: 'Intermediate',
        },

        advanced: {
            label: 'Advanced',
        },

        expert: {
            label: 'Expert',
        },
    }


    function getLevel(level: SkillLevel) {
        return levelConfig[level] ?? levelConfig.beginner
    }


    function getScore(score?: number | null): number {
        const value = Number(score ?? 0)

        return Math.min(
            Math.max(value * 10, 0),
            100,
        )
    }


    function formatExperience(
        years?: number | null,
    ): string {
        const value = Number(years ?? 0)

        if (value === 0) {
            return 'Experience not specified'
        }

        if (value === 1) {
            return '1 year experience'
        }

        return `${value} years experience`
    }


    const hasSkills = computed(
        () => props.skills.length > 0,
    )
</script>


<template>
    <section v-if="hasSkills" class="w-full max-w-4xl">

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
                    Proficiencies
                </AppHeading>


                <AppHeading tag="h2" font="prata" size="xl" weight="normal" class="
                        tracking-tight
                        text-content-light
                        dark:text-content-dark
                    ">
                    Areas of Expertise
                </AppHeading>

            </div>


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
                <Skills class="h-4.5 w-4.5" />
            </div>

        </div>


        <!-- Skills -->

        <div class="
                grid
                grid-cols-1

                gap-x-12
                gap-y-7

                md:grid-cols-2
            ">

            <article v-for="(item, index) in props.skills" :key="item.id ?? item.skill.name ?? index" class="group">

                <!-- Skill Information -->

                <div class="
                        mb-3
                        flex
                        items-end
                        justify-between
                        gap-4
                    ">

                    <div class="min-w-0">

                        <AppText tag="p" font="redhat" size="sm" weight="medium" class="
                                truncate
                                text-content-light
                                dark:text-content-dark
                            ">
                            {{ item.skill.name }}
                        </AppText>


                        <AppText tag="span" font="redhat" size="xs" color="muted" class="mt-1 block">
                            {{
                                formatExperience(
                                    item.years_experience,
                                )
                            }}
                        </AppText>

                    </div>


                    <div class="
                            shrink-0
                            text-right
                        ">

                        <AppText tag="span" font="redhat" size="xs" weight="semibold" tracking="wide" class="
                                block
                                uppercase
                                text-content-lightMuted
                                dark:text-content-darkMuted
                            ">
                            {{ getLevel(item.level).label }}
                        </AppText>


                        <AppText tag="span" font="redhat" size="xs" weight="semibold" class="
                                mt-1
                                block
                                text-content-light
                                dark:text-content-dark
                            ">
                            {{ getScore(item.proficiency_score) }}%
                        </AppText>

                    </div>

                </div>


                <!-- Progress -->

                <div class="
                        h-1
                        w-full
                        overflow-hidden

                        rounded-full

                        bg-border-light
                        dark:bg-white/10
                    " role="progressbar" :aria-label="`${item.skill.name} proficiency`"
                    :aria-valuenow="getScore(item.proficiency_score)" aria-valuemin="0" aria-valuemax="100">
                    <div class="
                            h-full
                            rounded-full

                            bg-content-light

                            transition-[width]
                            duration-700
                            ease-out

                            group-hover:bg-brand

                            dark:bg-content-dark
                            dark:group-hover:bg-brand
                        " :style="{
                            width: `${getScore(
                                item.proficiency_score,
                            )}%`,
                        }" />
                </div>

            </article>

        </div>


        <!-- Verification Note -->

        <div class="
                mt-10
                border-t
                border-border-light
                pt-5

                dark:border-border-dark

                sm:mt-12
            ">
            <div class="
                    flex
                    items-center
                    gap-2

                    text-content-lightMuted
                    dark:text-content-darkMuted
                ">

                <BadgeCheck aria-hidden="true" class="
                        h-4
                        w-4
                        shrink-0
                    " />


                <AppText tag="span" font="redhat" size="xs" color="muted">
                    Supported by peer endorsements
                </AppText>

            </div>
        </div>

    </section>
</template>