<script setup lang="ts">
import { Eye } from '@lucide/vue'

import Calendar from '@/Icons/Calendar.vue'

import AppHeading from '@/Components/Ui/AppHeading.vue'
import AppText from '@/Components/Ui/AppText.vue'

import { formatDate } from '@/utils/dateUtils'


interface Certificate {
    id?: number | string

    title: string
    organization?: string | null
    level?: string | null

    issue_date?: string | null
    attachment?: string | null
}


interface Props {
    certificate: Certificate
}


const props = defineProps<Props>()


const emit = defineEmits<{
    preview: [attachment: string]
}>()


function openPreview(): void {
    if (!props.certificate.attachment) {
        return
    }

    emit(
        'preview',
        props.certificate.attachment,
    )
}


function initials(
    name?: string | null,
): string {
    if (!name?.trim()) {
        return 'CR'
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


function formatIssueDate(
    date?: string | null,
): string | null {
    if (!date) {
        return null
    }

    return formatDate(
        date,
        {
            month: 'short',
            year: 'numeric',
        },
    )
}
</script>


<template>
    <article
        class="
            group/card
            relative

            h-full
            w-full

            rounded-2xl

            border
            border-zinc-200/70

            p-5

            transition-all
            duration-300

            hover:border-zinc-300
            hover:bg-zinc-50/50

            dark:border-zinc-800/80
            dark:hover:border-zinc-700
            dark:hover:bg-zinc-900/30
        "
    >

        <div
            class="
                flex
                items-start
                gap-4

                sm:gap-5
            "
        >

            <!-- Attachment Preview -->

            <button
                v-if="certificate.attachment"
                type="button"
                :aria-label="`Preview ${certificate.title} credential`"

                class="
                    group/preview
                    relative

                    h-14
                    w-14
                    shrink-0

                    overflow-hidden
                    rounded-xl

                    border
                    border-zinc-200/80

                    bg-zinc-100

                    outline-none

                    transition-all
                    duration-300

                    hover:border-zinc-300

                    focus-visible:ring-2
                    focus-visible:ring-brand/30
                    focus-visible:ring-offset-2

                    dark:border-zinc-800
                    dark:bg-zinc-900
                    dark:hover:border-zinc-700
                    dark:focus-visible:ring-offset-zinc-950
                "

                @click="openPreview"
            >

                <img
                    :src="certificate.attachment"
                    :alt="`${certificate.title} credential preview`"

                    class="
                        h-full
                        w-full

                        object-cover

                        grayscale

                        transition-all
                        duration-500

                        group-hover/preview:scale-105
                        group-hover/preview:grayscale-0

                        group-focus-visible/preview:scale-105
                        group-focus-visible/preview:grayscale-0
                    "
                />


                <!-- Preview Overlay -->

                <span
                    class="
                        absolute
                        inset-0

                        flex
                        items-center
                        justify-center

                        bg-zinc-950/40

                        opacity-0

                        transition-opacity
                        duration-300

                        group-hover/preview:opacity-100
                        group-focus-visible/preview:opacity-100
                    "
                >
                    <Eye
                        aria-hidden="true"
                        class="h-4 w-4 text-white"
                    />
                </span>

            </button>


            <!-- Initials Fallback -->

            <div
                v-else
                aria-hidden="true"

                class="
                    flex
                    h-14
                    w-14
                    shrink-0
                    items-center
                    justify-center

                    rounded-xl

                    border
                    border-zinc-200/80

                    bg-zinc-50

                    dark:border-zinc-800
                    dark:bg-zinc-900
                "
            >

                <AppText
                    tag="span"
                    font="redhat"
                    size="xs"
                    weight="semibold"
                    tracking="wide"

                    class="
                        text-zinc-400
                        dark:text-zinc-500
                    "
                >
                    {{ initials(certificate.organization) }}
                </AppText>

            </div>


            <!-- Content -->

            <div
                class="
                    min-w-0
                    flex-1
                    pt-0.5
                "
            >

                <!-- Title -->

                <AppHeading
                    tag="h3"
                    font="lora"
                    size="md"
                    weight="semibold"

                    class="
                        text-zinc-900

                        transition-colors
                        duration-300

                        group-hover/card:text-brand

                        dark:text-white
                        dark:group-hover/card:text-brand
                    "
                >
                    {{ certificate.title }}
                </AppHeading>


                <!-- Organization -->

                <AppText
                    v-if="certificate.organization"
                    tag="p"
                    font="redhat"
                    size="xs"
                    weight="medium"
                    color="muted"

                    class="
                        mt-1.5
                        truncate
                    "
                >
                    {{ certificate.organization }}
                </AppText>


                <!-- Metadata -->

                <div
                    v-if="
                        certificate.level
                        || certificate.issue_date
                    "

                    class="
                        mt-5

                        flex
                        flex-wrap
                        items-center

                        gap-x-3
                        gap-y-2

                        border-t
                        border-zinc-200/60

                        pt-3

                        dark:border-zinc-800/80
                    "
                >

                    <!-- Level -->

                    <AppText
                        v-if="certificate.level"
                        tag="span"
                        font="redhat"
                        size="xs"
                        weight="semibold"
                        tracking="wide"

                        class="
                            uppercase
                            text-zinc-500

                            dark:text-zinc-400
                        "
                    >
                        {{ certificate.level }}
                    </AppText>


                    <!-- Divider -->

                    <span
                        v-if="
                            certificate.level
                            && certificate.issue_date
                        "

                        aria-hidden="true"

                        class="
                            h-1
                            w-1
                            shrink-0

                            rounded-full

                            bg-zinc-300
                            dark:bg-zinc-700
                        "
                    />


                    <!-- Issue Date -->

                    <div
                        v-if="certificate.issue_date"

                        class="
                            flex
                            items-center
                            gap-1.5

                            text-zinc-400
                            dark:text-zinc-500
                        "
                    >

                        <Calendar
                            aria-hidden="true"

                            class="
                                h-3.5
                                w-3.5
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
                                formatIssueDate(
                                    certificate.issue_date,
                                )
                            }}
                        </AppText>

                    </div>

                </div>


                <!-- Preview Action -->

                <button
                    v-if="certificate.attachment"
                    type="button"

                    class="
                        mt-4

                        font-redhat
                        text-[11px]
                        font-semibold
                        uppercase
                        tracking-wide

                        text-zinc-400

                        transition-colors
                        duration-300

                        hover:text-brand

                        focus-visible:outline-none
                        focus-visible:text-brand

                        dark:text-zinc-500
                        dark:hover:text-brand
                    "

                    @click="openPreview"
                >
                    View credential
                </button>

            </div>

        </div>

    </article>
</template>