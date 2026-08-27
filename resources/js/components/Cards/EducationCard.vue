<script setup lang="ts">
import Building from '@/Icons/Building.vue'

import AppHeading from '@/Components/Ui/AppHeading.vue'
import AppText from '@/Components/Ui/AppText.vue'

import {
    formatDate,
    getDuration,
} from '@/utils/dateUtils'


interface EducationItem {
    id?: number | string

    degree: string
    institution: string

    field_of_study?: string | null

    start_date?: string | null
    end_date?: string | null

    institution_verified?: boolean
}


interface Props {
    item: EducationItem
}


defineProps<Props>()


function formatEducationDate(
    date?: string | null,
): string {
    if (!date) {
        return 'Not specified'
    }

    return formatDate(
        date,
        {
            year: 'numeric',
            month: 'short',
        },
    )
}
</script>


<template>
    <div
        class="
            group/edu
            w-full
            min-w-0
        "
    >

        <!-- Degree & Duration -->

        <div
            class="
                mb-2

                flex
                flex-col
                gap-1

                sm:flex-row
                sm:items-baseline
                sm:justify-between
                sm:gap-5
            "
        >

            <AppHeading
                tag="h3"
                font="lora"
                size="lg"
                weight="semibold"
                class="
                    min-w-0

                    text-zinc-900

                    transition-colors
                    duration-300

                    group-hover/edu:text-brand

                    dark:text-white
                    dark:group-hover/edu:text-brand
                "
            >
                {{ item.degree }}
            </AppHeading>


            <AppText
                tag="span"
                font="redhat"
                size="xs"
                color="muted"
                class="
                    shrink-0
                    whitespace-nowrap
                "
            >
                {{
                    getDuration(
                        item.start_date,
                        item.end_date,
                    )
                }}
            </AppText>

        </div>


        <!-- Institution -->

        <div
            class="
                mb-4

                flex
                items-center
                gap-2

                text-zinc-500
                dark:text-zinc-400
            "
        >

            <Building
                aria-hidden="true"
                class="
                    h-3.5
                    w-3.5
                    shrink-0

                    text-zinc-400
                    dark:text-zinc-500
                "
            />


            <AppText
                tag="span"
                font="redhat"
                size="sm"
                weight="medium"
                class="
                    truncate

                    text-zinc-600
                    dark:text-zinc-400
                "
            >
                {{ item.institution }}
            </AppText>

        </div>


        <!-- Field of Study -->

        <div
            v-if="item.field_of_study"
            class="mb-4"
        >

            <AppText
                tag="span"
                font="redhat"
                size="xs"
                weight="semibold"
                tracking="wide"
                class="
                    mb-1
                    block

                    uppercase

                    text-zinc-400
                    dark:text-zinc-500
                "
            >
                Field of Study
            </AppText>


            <AppText
                tag="p"
                font="lora"
                size="md"
                class="
                    text-zinc-700
                    dark:text-zinc-300
                "
            >
                {{ item.field_of_study }}
            </AppText>

        </div>


        <!-- Academic Period -->

        <div
            class="
                flex
                flex-wrap
                items-center
                gap-2

                text-zinc-400
                dark:text-zinc-500
            "
        >

            <AppText
                tag="span"
                font="redhat"
                size="xs"
                color="muted"
            >
                {{
                    formatEducationDate(
                        item.start_date,
                    )
                }}
            </AppText>


            <span
                aria-hidden="true"
                class="
                    h-px
                    w-4

                    bg-zinc-300
                    dark:bg-zinc-700
                "
            />


            <AppText
                tag="span"
                font="redhat"
                size="xs"
                :class="
                    !item.end_date
                        ? 'font-medium text-brand'
                        : 'text-zinc-500 dark:text-zinc-400'
                "
            >
                {{
                    item.end_date
                        ? formatEducationDate(
                            item.end_date,
                        )
                        : 'Present'
                }}
            </AppText>

        </div>

    </div>
</template>