<script setup>
    import { computed, ref } from 'vue'
    import { Link } from '@inertiajs/vue3'
    import { ArrowUpRight, ArrowRight } from '@lucide/vue'

    const props = defineProps({
        href: String,
        image: String,
        title: String,
        brief: String,
        imageAlt: String,
    })

    // Icon uploads can be missing — fall back to the tool's initial letter.
    const iconFailed = ref(false)
    const initial = computed(() => ((props.title || 'C').replace(/<[^>]*>/g, '').trim().charAt(0) || 'C'))
</script>

<template>
    <Link
        :href="href"
        class="group relative flex h-full flex-col rounded-3xl border border-black/5 bg-white p-6 transition-all duration-300 hover:-translate-y-1 hover:border-brand/30 hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.12)] dark:border-white/10 dark:bg-zinc-900"
    >
        <!-- ICON + LINK -->
        <div class="flex items-start justify-between">
            <div class="grid h-16 w-16 place-items-center overflow-hidden rounded-2xl bg-brand/10 text-brand">
                <img
                    v-if="!iconFailed && image"
                    :src="image"
                    :alt="imageAlt"
                    loading="lazy"
                    class="h-9 w-9 object-contain"
                    @error="iconFailed = true"
                />
                <span v-else class="font-prata text-2xl">{{ initial }}</span>
            </div>
            <span class="grid h-9 w-9 place-items-center rounded-full border border-black/5 text-zinc-400 transition group-hover:border-brand group-hover:bg-brand group-hover:text-white dark:border-white/10">
                <ArrowUpRight class="h-4 w-4" />
            </span>
        </div>

        <!-- BODY -->
        <h3 v-html="title" class="mt-5 font-prata text-xl leading-snug text-zinc-900 dark:text-white"></h3>
        <p class="mt-2 flex-1 font-lora text-sm leading-relaxed text-zinc-600 line-clamp-3 dark:text-zinc-300">
            {{ brief }}
        </p>

        <!-- FOOTER -->
        <div class="mt-6 flex items-center gap-1.5 border-t border-black/5 pt-4 text-sm font-semibold text-brand dark:border-white/10">
            Open calculator
            <ArrowRight class="h-4 w-4 transition group-hover:translate-x-0.5" />
        </div>
    </Link>
</template>
