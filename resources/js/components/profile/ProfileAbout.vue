<script setup lang="ts">
import {
    nextTick,
    onBeforeUnmount,
    onMounted,
    ref,
    watch,
} from 'vue'

import { ChevronDown } from '@lucide/vue'

import AppHeading from '@/Components/Ui/AppHeading.vue'
import AppText from '@/Components/Ui/AppText.vue'


interface Props {
    bio?: string | null
    title?: string
    emptyText?: string
    size?: 'sm' | 'md' | 'lg'
}


const props = withDefaults(
    defineProps<Props>(),
    {
        bio: '',
        title: 'The Narrative',
        emptyText: 'No narrative provided.',
        size: 'lg',
    },
)


const expanded = ref(false)
const isLong = ref(false)

const bioContainerRef = ref<HTMLElement | null>(null)


async function checkOverflow(): Promise<void> {
    await nextTick()

    const element = bioContainerRef.value

    if (!element) {
        return
    }

    const textElement = element.firstElementChild as HTMLElement | null

    if (!textElement) {
        return
    }

    isLong.value =
        textElement.scrollHeight > textElement.clientHeight + 1
}


function toggle(): void {
    expanded.value = !expanded.value
}


watch(
    () => props.bio,
    async () => {
        expanded.value = false

        await nextTick()
        await checkOverflow()
    },
)


let resizeObserver: ResizeObserver | null = null


onMounted(async () => {
    await checkOverflow()

    if (bioContainerRef.value) {
        resizeObserver = new ResizeObserver(async () => {
            if (!expanded.value) {
                await checkOverflow()
            }
        })

        resizeObserver.observe(bioContainerRef.value)
    }
})


onBeforeUnmount(() => {
    resizeObserver?.disconnect()
})
</script>


<template>
    <section
        class="
            w-full
            max-w-3xl
        "
    >

        <!-- Section Label -->

        <AppHeading
            tag="h2"
            font="redhat"
            size="xs"
            weight="bold"
            tracking="wide"
            uppercase
            color="brand"
            class="mb-5"
        >
            {{ title }}
        </AppHeading>


        <!-- Narrative -->

        <div class="relative">

            <div
                ref="bioContainerRef"
                class="relative"
            >

                <AppText
                    tag="p"
                    font="lora"
                    :size="size"
                    leading="relaxed"
                    color="muted"
                    :clamp="expanded ? undefined : 5"
                    class="
                        whitespace-pre-line
                        transition-all
                        duration-300
                    "
                >
                    {{ bio || emptyText }}
                </AppText>

            </div>


            <!-- Expand / Collapse -->

            <button
                v-if="isLong"
                type="button"
                :aria-expanded="expanded"
                class="
                    group
                    mt-6

                    inline-flex
                    items-center
                    gap-2.5

                    text-content-light
                    dark:text-content-dark

                    transition-colors
                    duration-300

                    hover:text-brand

                    focus-visible:outline-none
                    focus-visible:ring-2
                    focus-visible:ring-brand/30
                    focus-visible:ring-offset-4

                    dark:hover:text-brand
                    dark:focus-visible:ring-offset-canvas-dark
                "
                @click="toggle"
            >

                <AppText
                    tag="span"
                    font="redhat"
                    size="xs"
                    weight="semibold"
                    tracking="wide"
                    class="
                        border-b
                        border-current/20
                        pb-0.5

                        uppercase

                        transition-colors
                        duration-300

                        group-hover:border-brand/40
                    "
                >
                    {{
                        expanded
                            ? 'Show Less'
                            : 'Read Full Narrative'
                    }}
                </AppText>


                <ChevronDown
                    aria-hidden="true"
                    class="
                        h-4
                        w-4

                        transition-transform
                        duration-300
                        ease-out
                    "
                    :class="{
                        'rotate-180': expanded,
                    }"
                />

            </button>

        </div>

    </section>
</template>