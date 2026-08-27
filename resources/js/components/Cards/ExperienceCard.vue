<script setup lang="ts">
    import Calendar from '@/Icons/Calendar.vue'

    import AppHeading from '@/Components/Ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'

    import {
        formatPeriod,
        getDuration,
    } from '@/utils/dateUtils'


    interface ExperienceItem {
        id?: number | string

        role: string
        company_name: string

        type?: string | null

        start_date?: string | null
        end_date?: string | null

        description?: string | null
    }


    interface Props {
        item: ExperienceItem
    }


    defineProps < Props > ()
</script>


<template>
    <div class="
            group/card
            w-full
            min-w-0
        ">

        <!-- Role & Duration -->

        <div class="
                mb-2

                flex
                flex-col
                gap-1

                sm:flex-row
                sm:items-baseline
                sm:justify-between
                sm:gap-5
            ">

            <AppHeading tag="h3" font="lora" size="lg" weight="semibold" class="
                    min-w-0

                    text-zinc-900

                    transition-colors
                    duration-300

                    group-hover/card:text-brand

                    dark:text-white
                    dark:group-hover/card:text-brand
                ">
                {{ item.role }}
            </AppHeading>


            <AppText tag="span" font="redhat" size="xs" color="muted" class="
                    shrink-0
                    whitespace-nowrap
                ">
                {{
                    getDuration(
                        item.start_date,
                        item.end_date,
                    )
                }}
            </AppText>

        </div>


        <!-- Company & Employment Type -->

        <div class="
                mb-3

                flex
                flex-wrap
                items-center

                gap-x-2.5
                gap-y-1.5
            ">

            <AppText tag="span" font="redhat" size="sm" weight="medium" class="
                    text-zinc-600
                    dark:text-zinc-400
                ">
                {{ item.company_name }}
            </AppText>


            <template v-if="item.type">

                <span aria-hidden="true" class="
                        h-1
                        w-1
                        shrink-0

                        rounded-full

                        bg-zinc-300
                        dark:bg-zinc-700
                    " />


                <AppText tag="span" font="redhat" size="xs" weight="medium" class="
                        text-zinc-400
                        dark:text-zinc-500
                    ">
                    {{ item.type }}
                </AppText>

            </template>

        </div>


        <!-- Period -->

        <div class="
                mb-4

                flex
                items-center
                gap-2

                text-zinc-400
                dark:text-zinc-500
            ">

            <Calendar aria-hidden="true" class="
                    h-3.5
                    w-3.5
                    shrink-0
                " />


            <AppText tag="span" font="redhat" size="xs" color="muted">
                {{
                    formatPeriod(
                        item.start_date,
                        item.end_date,
                    )
                }}
            </AppText>

        </div>


        <!-- Description -->

        <AppText v-if="item.description" tag="p" font="lora" size="md" leading="relaxed" color="muted" class="
                whitespace-pre-line
            ">
            {{ item.description }}
        </AppText>

    </div>
</template>