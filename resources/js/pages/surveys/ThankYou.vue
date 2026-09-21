<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import {
    ArrowLeft,
    CheckCircle2,
    ClipboardList,
} from '@lucide/vue'

import AppButton from '@/components/ui/AppButton.vue'
import AppHeading from '@/components/ui/AppHeading.vue'
import AppText from '@/components/ui/AppText.vue'

import { index as surveysIndex } from '@/routes/surveys'

defineProps<{
    survey: {
        title: string
        slug: string
    }
}>()
</script>

<template>
    <Head :title="`Thank You — ${survey.title}`">
        <meta
            name="description"
            content="Thank you for participating in this Alfrik survey."
        />
    </Head>

    <main
        class="
            container
            mx-auto
            flex
            min-h-[calc(100vh-64px)]
            max-w-4xl
            items-center
            justify-center
            px-4
            py-16
            sm:px-6
            lg:px-8
        "
    >
        <section
            class="
                w-full
                border
                border-border-light
                px-6
                py-16
                text-center
                dark:border-border-dark
                sm:px-12
                sm:py-20
            "
        >
            <!-- Icon -->

            <div
                class="
                    mx-auto
                    mb-7
                    flex
                    h-16
                    w-16
                    items-center
                    justify-center
                    border
                    border-primary/20
                    bg-primary/5
                    text-primary
                "
            >
                <CheckCircle2
                    :size="30"
                    :stroke-width="1.5"
                />
            </div>

            <!-- Eyebrow -->

            <AppText
                tag="p"
                size="xs"
                weight="bold"
                tracking="wide"
                uppercase
                color="primary"
            >
                Response Received
            </AppText>

            <!-- Heading -->

            <AppHeading
                tag="h1"
                font="prata"
                size="4xl"
                weight="normal"
                leading="tight"
                align="center"
                class="mt-4"
            >
                Thank you for participating.
            </AppHeading>

            <!-- Message -->

            <AppText
                tag="p"
                font="lora"
                size="lg"
                color="muted"
                leading="relaxed"
                align="center"
                class="mx-auto mt-5 max-w-xl"
            >
                Your response to
                <span class="text-content-light dark:text-content-dark">
                    {{ survey.title }}
                </span>
                has been successfully recorded.
            </AppText>

            <!-- Confirmation -->

            <div
                class="
                    mx-auto
                    mt-8
                    flex
                    max-w-md
                    items-center
                    justify-center
                    gap-3
                    border-y
                    border-border-light
                    py-4
                    dark:border-border-dark
                "
            >
                <ClipboardList
                    :size="16"
                    :stroke-width="1.7"
                    class="shrink-0 text-primary"
                />

                <AppText
                    tag="span"
                    size="xs"
                    color="muted"
                >
                    Your contribution has been added to the survey responses.
                </AppText>
            </div>

            <!-- Actions -->

            <div
                class="
                    mt-9
                    flex
                    flex-col
                    items-center
                    justify-center
                    gap-3
                    sm:flex-row
                "
            >
                <AppButton
                    :href="surveysIndex.url()"
                    variant="outline"
                >
                    <span class="flex items-center gap-2">
                        <ArrowLeft
                            :size="15"
                            :stroke-width="1.8"
                        />

                        Explore more surveys
                    </span>
                </AppButton>
            </div>
        </section>
    </main>
</template>