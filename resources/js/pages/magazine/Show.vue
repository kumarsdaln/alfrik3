<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppButton from '@/components/ui/AppButton.vue'
    import ArticleCard from '@/components/magazine/ArticleCard.vue'
    import { ref, computed, onMounted, onBeforeUnmount, watch, nextTick } from 'vue'
    import { index as magazineIndex, view as magazineView } from '@/routes/magazine'
    import type { Magazine, MagazineSection } from '@/types'

    const props = defineProps<{
        magazine: Magazine
        related?: Magazine[]
    }>()

    const nav = ref(false)

    // Safely decode the sectioned content.
    const sections = computed<MagazineSection[]>(() => {
        if (props.magazine.sections?.length) return props.magazine.sections
        try {
            const parsed = JSON.parse(props.magazine.content ?? '{}')
            return Array.isArray(parsed?.sections) ? parsed.sections : []
        } catch {
            return []
        }
    })

    const currentSectionIndex = ref(0)
    const selectedContent = computed(() => sections.value[currentSectionIndex.value]?.content ?? '')
    const progress = computed(() =>
        sections.value.length ? ((currentSectionIndex.value + 1) / sections.value.length) * 100 : 0,
    )

    const canonical = magazineView([props.magazine.category?.slug ?? 'issue', props.magazine.slug]).url

    const publishedLabel = computed(() => {
        const d = props.magazine.published_at || props.magazine.created_at
        return d ? new Date(d).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' }) : ''
    })

    // ---- Reader controls ----
    const section = ref<HTMLElement | null>(null)
    const scrollWrapper = ref<HTMLElement | null>(null)
    const isFullscreen = ref(false)

    const toggleFullscreen = async () => {
        if (!section.value) return
        if (!isFullscreen.value) {
            await section.value.requestFullscreen?.()
        } else {
            await document.exitFullscreen?.()
        }
    }
    const handleFullscreenChange = () => { isFullscreen.value = !!document.fullscreenElement }

    const goTo = (index: number) => {
        currentSectionIndex.value = Math.max(0, Math.min(index, sections.value.length - 1))
    }
    const nextSection = () => goTo(currentSectionIndex.value + 1)
    const previousSection = () => goTo(currentSectionIndex.value - 1)
    const handleClick = (index: number) => { goTo(index); nav.value = false }

    watch(currentSectionIndex, async () => {
        await nextTick()
        if (scrollWrapper.value) scrollWrapper.value.scrollTop = 0
    })

    // ---- Bookmark (local only) ----
    const bookmarked = ref(false)
    const bookmarkKey = 'alfrik:bookmarks:magazine'
    const readBookmarks = (): string[] => {
        try { return JSON.parse(localStorage.getItem(bookmarkKey) ?? '[]') } catch { return [] }
    }
    const toggleBookmark = () => {
        const list = new Set(readBookmarks())
        bookmarked.value ? list.delete(props.magazine.slug) : list.add(props.magazine.slug)
        localStorage.setItem(bookmarkKey, JSON.stringify([...list]))
        bookmarked.value = !bookmarked.value
    }

    // ---- Share ----
    const shared = ref(false)
    const share = async () => {
        const url = window.location.href
        if (navigator.share) {
            try { await navigator.share({ title: props.magazine.title, url }) } catch { /* cancelled */ }
            return
        }
        await navigator.clipboard?.writeText(url)
        shared.value = true
        setTimeout(() => (shared.value = false), 2000)
    }

    onMounted(() => {
        document.addEventListener('fullscreenchange', handleFullscreenChange)
        bookmarked.value = readBookmarks().includes(props.magazine.slug)
    })
    onBeforeUnmount(() => document.removeEventListener('fullscreenchange', handleFullscreenChange))

    const jsonLdData = {
        '@context': 'https://schema.org',
        '@type': 'Article',
        mainEntityOfPage: { '@type': 'WebPage', '@id': canonical },
        headline: props.magazine.meta_title || props.magazine.title,
        description: props.magazine.meta_description,
        datePublished: props.magazine.created_at,
        dateModified: props.magazine.updated_at,
        author: { '@type': 'Person', name: props.magazine.author?.name ?? 'Alfrik' },
        publisher: { '@type': 'Organization', name: 'Alfrik' },
        image: props.magazine.cover_image,
    }
</script>

<template>

    <Head :title="magazine.meta_title || magazine.title">
        <meta name="description" :content="magazine.meta_description ?? ''">
        <meta name="keywords" :content="magazine.meta_keywords ?? ''">
        <link rel="canonical" :href="canonical">
            <component :is="'script'" type="application/ld+json">{{ JSON.stringify(jsonLdData) }}</component>
    </Head>

    <div class="bg-surface-light dark:bg-surface-dark lg:h-screen" ref="section">
        <!-- Reader top bar -->
        <nav class="sticky top-0 z-[51] bg-surface-light/95 dark:bg-surface-dark/95 backdrop-blur-sm shadow-sm">
            <div class="h-1 bg-border-light dark:bg-border-dark">
                <div class="h-full bg-brand transition-all duration-300" :style="{ width: `${progress}%` }"></div>
            </div>
            <div class="container mx-auto px-4 py-3">
                <div class="flex items-center justify-between gap-4">
                    <div class="flex items-center gap-3 min-w-0">
                        <button @click="nav = !nav"
                            class="lg:hidden text-content-lightMuted dark:text-content-darkMuted shrink-0"
                            aria-label="Toggle contents">
                            <svg v-if="!nav" class="w-6 h-6" fill="currentColor" viewBox="0 0 448 512">
                                <path
                                    d="M0 96C0 78.3 14.3 64 32 64l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 128C14.3 128 0 113.7 0 96zM0 256c0-17.7 14.3-32 32-32l384 0c17.7 0 32 14.3 32 32s-14.3 32-32 32L32 288c-17.7 0-32-14.3-32-32zM448 416c0 17.7-14.3 32-32 32L32 448c-17.7 0-32-14.3-32-32s14.3-32 32-32l384 0c17.7 0 32 14.3 32 32z" />
                            </svg>
                            <svg v-else class="w-6 h-6" fill="currentColor" viewBox="0 0 384 512">
                                <path
                                    d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z" />
                            </svg>
                        </button>
                        <Link :href="magazineIndex().url" class="shrink-0">
                            <AppText tag="span" font="prata" size="lg" color="default" weight="normal">ALFRIK</AppText>
                        </Link>
                        <AppText tag="span" font="redhat" size="sm" color="muted" truncate class="hidden md:block">
                            — {{ magazine.title }}
                            <template v-if="magazine.author"> · By {{ magazine.author.name }}</template>
                        </AppText>
                    </div>
                    <div class="flex items-center gap-1.5 shrink-0">
                        <AppText v-if="magazine.reading_minutes" tag="span" font="redhat" size="xs" color="muted"
                            class="hidden sm:inline mr-2">
                            {{ magazine.reading_minutes }} min read
                        </AppText>
                        <button @click="toggleBookmark" :title="bookmarked ? 'Bookmarked' : 'Bookmark'"
                            class="p-2 rounded-full hover:bg-border-light dark:hover:bg-border-dark transition-colors"
                            :class="bookmarked ? 'text-brand' : 'text-content-lightMuted dark:text-content-darkMuted'">
                            <svg class="w-5 h-5" :fill="bookmarked ? 'currentColor' : 'none'" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z" />
                            </svg>
                        </button>
                        <button @click="share" :title="shared ? 'Link copied!' : 'Share'"
                            class="p-2 rounded-full hover:bg-border-light dark:hover:bg-border-dark transition-colors text-content-lightMuted dark:text-content-darkMuted">
                            <svg v-if="!shared" class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M8.7 10.7a4 4 0 100 2.6m6.6-6.6a4 4 0 100-2.6m0 15.8a4 4 0 100-2.6M8.7 13.3l6.6 3.8m0-10.2L8.7 10.7" />
                            </svg>
                            <svg v-else class="w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </button>
                        <button @click="toggleFullscreen" :title="isFullscreen ? 'Exit fullscreen' : 'Fullscreen'"
                            class="p-2 rounded-full hover:bg-border-light dark:hover:bg-border-dark transition-colors text-content-lightMuted dark:text-content-darkMuted">
                            <svg v-if="!isFullscreen" class="w-5 h-5" fill="currentColor" viewBox="0 0 512 512">
                                <path
                                    d="M344 0L488 0c13.3 0 24 10.7 24 24l0 144c0 9.7-5.8 18.5-14.8 22.2s-19.3 1.7-26.2-5.2l-39-39-87 87c-9.4 9.4-24.6 9.4-33.9 0l-32-32c-9.4-9.4-9.4-24.6 0-33.9l87-87L327 41c-6.9-6.9-8.9-17.2-5.2-26.2S334.3 0 344 0zM168 512L24 512c-13.3 0-24-10.7-24-24L0 344c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2l39 39 87-87c9.4-9.4 24.6-9.4 33.9 0l32 32c9.4 9.4 9.4 24.6 0 33.9l-87 87 39 39c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8z" />
                            </svg>
                            <svg v-else class="w-5 h-5" fill="currentColor" viewBox="0 0 512 512">
                                <path
                                    d="M439 7c9.4-9.4 24.6-9.4 33.9 0l32 32c9.4 9.4 9.4 24.6 0 33.9l-87 87 39 39c6.9 6.9 8.9 17.2 5.2 26.2s-12.5 14.8-22.2 14.8l-144 0c-13.3 0-24-10.7-24-24l0-144c0-9.7 5.8-18.5 14.8-22.2s19.3-1.7 26.2 5.2l39 39L439 7zM72 272l144 0c13.3 0 24 10.7 24 24l0 144c0 9.7-5.8 18.5-14.8 22.2s-19.3 1.7-26.2-5.2l-39-39L73 505c-9.4 9.4-24.6 9.4-33.9 0L7 473c-9.4-9.4-9.4-24.6 0-33.9l87-87L55 313c-6.9-6.9-8.9-17.2-5.2-26.2s12.5-14.8 22.2-14.8z" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </nav>

        <div class="lg:flex h-[calc(100%-68px)] relative">
            <!-- Contents sidebar -->
            <aside
                :class="`absolute lg:static inset-0 bg-surface-light dark:bg-surface-dark transform ${nav ? 'translate-x-0' : '-translate-x-full'} lg:translate-x-0 transition-transform duration-300 w-72 border-r border-border-light dark:border-border-dark p-6 overflow-y-auto z-50`">
                <div v-if="magazine.author || publishedLabel"
                    class="mb-6 pb-6 border-b border-border-light dark:border-border-dark">
                    <AppText v-if="magazine.author" tag="p" font="redhat" size="sm" weight="semibold" color="default">
                        By {{ magazine.author.name }}
                    </AppText>
                    <AppText tag="p" font="redhat" size="sm" color="muted" class="mt-1">
                        <span v-if="publishedLabel">{{ publishedLabel }}</span>
                        <span v-if="magazine.reading_minutes"> · {{ magazine.reading_minutes }} min read</span>
                    </AppText>
                </div>

                <AppHeading tag="h2" font="prata" size="xl" weight="normal" class="mb-6">Contents</AppHeading>
                <nav class="space-y-2">
                    <button v-for="(topic, index1) in sections" :key="index1" @click="handleClick(index1)"
                        :class="['w-full text-left flex items-start gap-2 rounded-lg px-3 py-2 font-redhat text-sm transition-colors',
                            index1 === currentSectionIndex ? 'bg-brand/10 text-brand font-semibold' : 'text-content-lightMuted dark:text-content-darkMuted hover:bg-canvas-light dark:hover:bg-canvas-dark']">
                        <span class="text-xs opacity-60 mt-0.5">{{ index1 + 1 }}</span>
                        <span>{{ topic.section || `Section ${index1 + 1}` }}</span>
                    </button>
                </nav>
            </aside>

            <!-- Content area -->
            <div class="relative w-full h-full">
                <div class="h-[calc(100%-70px)] overflow-y-auto" ref="scrollWrapper">
                    <article
                        class="max-w-3xl mx-auto px-4 lg:px-8 py-12 font-lora prose prose-lg dark:prose-invert max-w-none">
                        <transition name="page-flip" mode="out-in">
                            <div :key="currentSectionIndex" v-html="selectedContent"></div>
                        </transition>
                        <AppText v-if="!sections.length" tag="p" font="lora" color="muted" align="center"
                            class="italic py-20">
                            This issue has no readable content yet.
                        </AppText>
                    </article>
                </div>

                <!-- Pager -->
                <div
                    class="absolute bottom-0 left-0 right-0 border-t border-border-light dark:border-border-dark bg-surface-light/90 dark:bg-surface-dark/90 backdrop-blur-sm z-40">
                    <div class="max-w-5xl mx-auto px-4 py-3 flex justify-between items-center">
                        <AppButton variant="outline" @click="previousSection" :disabled="currentSectionIndex === 0">
                            <template #icon-left>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
                                </svg>
                            </template>
                            Previous
                        </AppButton>
                        <AppText tag="span" font="redhat" size="sm" color="muted" class="hidden sm:inline">
                            Section {{ currentSectionIndex + 1 }} of {{ sections.length }}
                        </AppText>
                        <AppButton variant="primary" @click="nextSection"
                            :disabled="currentSectionIndex >= sections.length - 1">
                            Next
                            <template #icon-right>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                                </svg>
                            </template>
                        </AppButton>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Related issues -->
    <section v-if="related && related.length" class="container mx-auto px-4 py-16">
        <AppHeading tag="h2" font="prata" size="2xl" weight="bold" class="mb-8">More to read</AppHeading>
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
            <ArticleCard v-for="item in related" :key="item.id" :item="item" compact
                :href="magazineView([item.category?.slug ?? 'issue', item.slug]).url" />
        </div>
    </section>
</template>

<style scoped>

    .page-flip-enter-active,
    .page-flip-leave-active {
        transition: transform 0.5s ease, opacity 0.5s ease;
        transform-style: preserve-3d;
        perspective: 1000px;
    }

    .page-flip-enter-from {
        transform: rotateY(90deg);
        opacity: 0;
    }

    .page-flip-leave-to {
        transform: rotateY(-90deg);
        opacity: 0;
    }
</style>
