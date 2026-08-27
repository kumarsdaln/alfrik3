<script setup>
    import { Link } from '@inertiajs/vue3';
    import { formatDate } from '@/utils/dateUtils';
    import { computed, ref } from 'vue';
    import AppHeading from '../Ui/AppHeading.vue';
    import AppText from '../Ui/AppText.vue';
    import AppBadge from '../Ui/AppBadge.vue';

    const FALLBACK_IMAGE = '/frontend/images/placeholder.jpg';

    const props = defineProps({
        href: {
            type: String,
            required: true,
        },
        title: {
            type: String,
            required: true,
        },
        // Everything below is optional: this card is shared across content types
        // (magazine issues, interviews, articles) and not all of them carry a
        // category, tag list or cover image.
        image: {
            type: String,
            default: '',
        },
        subtitle: {
            type: String,
            default: '',
        },
        category: {
            type: Object,
            default: null,
        },
        tags: {
            type: Array,
            default: () => [],
        },
        created_at: {
            type: String,
            default: '',
        },
        dateLabel: {
            type: String,
            default: 'Published on',
        },
    });

    const failed = ref(false);

    /**
     * Stored image paths are inconsistent: some are absolute ("/storage/..."),
     * some are bare relative paths, some are remote URLs. Normalise them, and
     * fall back to the placeholder when the path is empty or the file 404s.
     */
    const imageSrc = computed(() => {
        const src = (props.image || '').trim();

        if (!src || failed.value) {
            return FALLBACK_IMAGE;
        }

        if (/^(https?:)?\/\//.test(src) || src.startsWith('/')) {
            return src;
        }

        return `/${src}`;
    });

    // Subtitles are sometimes stored as HTML fragments; strip tags and collapse
    // the whitespace the removed tags leave behind so the excerpt reads cleanly.
    const plainSubtitle = computed(() =>
        props.subtitle
            ? props.subtitle.replace(/<[^>]*>?/gm, '').replace(/\s+/g, ' ').trim()
            : ''
    );

    // Cap the visible tags so the row never shows a truncated final label.
    const MAX_TAGS = 3;
    const visibleTags = computed(() => props.tags.slice(0, MAX_TAGS));
</script>

<template>
    <article class="w-full">
        <div class="aspect-video relative w-full overflow-hidden bg-canvas-light dark:bg-white/5">
            <img :src="imageSrc" :alt="title" loading="lazy" class="w-full h-full object-cover"
                @error="failed = true">

            <Link class="absolute inset-0 block" :title="title" :href="href" />

            <AppBadge v-if="category" class="absolute bottom-2 left-2">
                {{ category.name }}
            </AppBadge>
        </div>

        <div class="flex flex-col gap-1 w-full pl-2 sm:pl-0 py-0 sm:py-4">
            <div v-if="visibleTags.length" class="mb-1 flex items-center gap-1 text-left sm:mb-3">
                <span class="flex min-w-0 items-center" v-for="(tag, index) in visibleTags"
                    :key="tag.id ?? tag.name">
                    <AppText tag="span" font="redhat" size="sm" color="brand" truncate class="mr-1">
                        {{ tag.name }}
                    </AppText>

                    <AppText v-if="index !== visibleTags.length - 1" tag="span" font="redhat" size="sm" color="muted"
                        class="mr-1">
                        |
                    </AppText>
                </span>
            </div>

            <AppHeading tag="h3" font="prata" weight="normal" size="lg" leading="tight" :clamp="2" hover-brand>
                <Link :href="href" rel="bookmark" class="text-inherit">
                    {{ title }}
                </Link>
            </AppHeading>

            <AppText v-if="plainSubtitle" tag="div" font="lora" size="sm" weight="normal" leading="tight"
                color="muted" :clamp="2" class="mb-1.5 sm:mb-3">
                {{ plainSubtitle }}
            </AppText>

            <!-- Extra per-type detail (e.g. interview participants) -->
            <slot name="meta" />

            <AppText v-if="created_at" tag="div" font="redhat" size="sm" color="muted">
                <span v-if="dateLabel">{{ dateLabel }}&nbsp;</span>
                <time :datetime="created_at">{{ formatDate(created_at) }}</time>
            </AppText>
        </div>
    </article>
</template>

<style scoped>
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }

    .scrollbar-hide {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>
