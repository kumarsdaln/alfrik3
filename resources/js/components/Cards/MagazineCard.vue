<script setup>
    import { Link } from '@inertiajs/vue3';
    import { computed, ref } from 'vue';

    const FALLBACK_IMAGE = '/frontend/images/placeholder.jpg';

    const props = defineProps({
        href: {
            type: String,
            required: true,
        },
        // Issues without a cover render the placeholder rather than a broken image.
        image: {
            type: String,
            default: '',
        },
        title: {
            type: String,
            required: true,
        },
    });

    const failed = ref(false);

    const imageSrc = computed(() => {
        const src = (props.image || '').trim();

        if (!src || failed.value) {
            return FALLBACK_IMAGE;
        }

        return /^(https?:)?\/\//.test(src) || src.startsWith('/') ? src : `/${src}`;
    });
</script>

<template>
    <div class="aspect-[707/1000] text-center relative sm:w-full bg-canvas-light dark:bg-white/5">
        <img :src="imageSrc" :alt="title" loading="lazy" class="w-full h-full object-cover"
            @error="failed = true">
        <div class="absolute w-full h-full top-0 left-0">
            <Link class="block w-full h-full" :title="title" :href="href">
            </Link>
        </div>
    </div>
</template>
