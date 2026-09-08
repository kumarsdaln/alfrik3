<script setup lang="ts">
    import { Bookmark, Share2 } from '@lucide/vue';
    import AppShareModal from '@/Components/Ui/AppShareModal.vue';
    import {
        computed,
        onMounted,
        onUnmounted,
        ref,
    } from 'vue'

    interface Props {
        title: string
        href: string
        /**
         * Element selector to track.
         * Example:
         * "#article-content"
         */
        target?: string
        /**
         * Hide until user scrolls.
         */
        hideUntilScroll?: number
    }

    const props = withDefaults(
        defineProps<Props>(),
        {
            target: 'body',
            hideUntilScroll: 120,
        }
    )

    const emit = defineEmits<{
        (e: 'bookmark'): void
    }>()

    const progress = ref(0)

    const visible = ref(false)

    const progressOffset = computed(
        () => 100 - progress.value
    )

    const updateProgress = () => {
        const element = document.querySelector(props.target,) as HTMLElement | null
        if (!element) {
            return
        }
        const rect = element.getBoundingClientRect()
        const scrollTop = window.scrollY
        const start = scrollTop + rect.top
        const height = element.offsetHeight
        const viewport = window.innerHeight
        const end = start + height - viewport
        if (scrollTop <= start) {
            progress.value = 0
        } else if (
            scrollTop >= end
        ) {
            progress.value = 100
        } else {
            progress.value = Math.min(
                100,
                Math.max(0, ((scrollTop - start) / (end - start)) * 100,),
            )
        }
        visible.value = scrollTop > props.hideUntilScroll
    }

    onMounted(() => {
        updateProgress()
        window.addEventListener(
            'scroll',
            updateProgress,
            {
                passive: true,
            }
        )
        window.addEventListener(
            'resize',
            updateProgress,
        )
    })

    onUnmounted(() => {
        window.removeEventListener(
            'scroll',
            updateProgress,
        )
        window.removeEventListener(
            'resize',
            updateProgress,
        )
    })

    const openShareModal = async () => {
        // Native share first
        if (navigator.share) {
            try {
                await navigator.share({
                    title: props.title,
                    url: props.href,
                })

                return
            } catch {
                // User cancelled
                return
            }
        }
    }
</script>

<template>
    <Transition enter-active-class="duration-300 ease-out" enter-from-class="translate-y-6 opacity-0"
        enter-to-class="translate-y-0 opacity-100" leave-active-class="duration-200 ease-in"
        leave-from-class="translate-y-0 opacity-100" leave-to-class="translate-y-6 opacity-0">
        <div class="fixed bottom-6 left-1/2 z-50 w-[92%] max-w-2xl -translate-x-1/2">

            <div
                class="flex items-center justify-between rounded-2xl border border-border-light bg-white/90 px-4 py-3 shadow-2xl backdrop-blur-xl transition-colors dark:border-border-dark dark:bg-[#080808]/80">

                <!-- Progress -->
                <div class="flex min-w-0 flex-1 items-center gap-4">

                    <div class="relative flex h-11 w-11 shrink-0 items-center justify-center">

                        <svg class="absolute inset-0 h-full w-full -rotate-90" viewBox="0 0 36 36">

                            <circle cx="18" cy="18" r="16" fill="none" class="stroke-zinc-200 dark:stroke-white/10"
                                stroke-width="2" />

                            <circle cx="18" cy="18" r="16" fill="none" stroke="currentColor" stroke-width="2"
                                class="text-brand transition-all duration-300" stroke-dasharray="100"
                                :stroke-dashoffset="100 - progress" />

                        </svg>

                        <span class="text-[10px] font-bold text-content-light dark:text-content-dark">

                            {{ Math.round(progress) }}%

                        </span>

                    </div>

                    <div class="min-w-0">

                        <p
                            class="mb-1 text-[10px] font-semibold uppercase tracking-[0.25em] text-content-lightMuted dark:text-content-darkMuted">

                            Reading Progress

                        </p>

                        <h4
                            class="truncate font-redhat text-sm font-semibold text-content-light dark:text-content-dark">

                            {{ title }}

                        </h4>

                    </div>

                </div>

                <!-- Actions -->
                <div class="ml-4 flex items-center gap-2 border-l border-border-light pl-4 dark:border-border-dark">

                    <button @click="openShareModal"
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-transparent bg-surface-light text-content-lightMuted transition-all hover:border-border-light hover:bg-zinc-100 hover:text-content-light dark:bg-surface-dark dark:text-content-darkMuted dark:hover:border-border-dark dark:hover:bg-white/5 dark:hover:text-content-dark">
                        <slot name="copy-icon">
                            <Share2 class="h-4 w-4" />
                        </slot>
                    </button>

                    <button @click="emit('bookmark')"
                        class="flex h-10 w-10 items-center justify-center rounded-xl border border-transparent bg-surface-light text-content-lightMuted transition-all hover:border-brand/20 hover:bg-brand/10 hover:text-brand dark:bg-surface-dark dark:text-content-darkMuted dark:hover:bg-brand/10 dark:hover:text-brand">

                        <slot name="bookmark-icon">
                            <Bookmark class="h-4 w-4" />
                        </slot>
                    </button>
                </div>
            </div>
        </div>
    </Transition>
</template>