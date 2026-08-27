<script setup>
    import Avatar from '@/Components/Profile/Avatar.vue'
    import VerifiedBadge from '@/Components/Profile/VerifiedBadge.vue'
    import { Link } from '@inertiajs/vue3'
    import { MapPin } from '@lucide/vue'

    const props = defineProps({
        href: String,
        image: String,
        name: String,
        tagline: String,
        category: String,
        industries: Array,
        followers: Number,
        posts: Number,
        location: String,
        profile_views: Number,
        type: String,
        is_verified: Boolean
    })
</script>

<template>
    <Link :href="href" class="group relative flex flex-col h-full rounded-[2rem] bg-white dark:bg-[#0a0a0a] 
               border border-zinc-200 dark:border-white/5 overflow-hidden hover:border-brand/40 
               dark:hover:border-brand/30 transition-all duration-500 hover:-translate-y-2 
               shadow-sm hover:shadow-2xl dark:hover:shadow-[0_20px_40px_-15px_rgba(0,0,0,0.8)] 
               focus:outline-none focus:ring-2 focus:ring-brand focus:ring-offset-2 
               dark:focus:ring-offset-[#050505]">

        <div class="p-6 sm:p-8 flex-1 flex flex-col relative z-10">
            <div class="flex items-start justify-between mb-6">
                <div class="relative">
                    <div class="absolute inset-0 bg-brand/20 blur-md rounded-full scale-0 
                        group-hover:scale-100 transition-transform duration-500">
                    </div>
                    <Avatar :image="image" :name="name" size="w-20 h-20" 
                        class="relative z-10 border-2 
                        border-white dark:border-[#0a0a0a] shadow-sm group-hover:border-brand/50 
                        transition-colors" />
                </div>
                <VerifiedBadge v-if="is_verified" showLabel class="scale-90 origin-top-right drop-shadow-sm" />
            </div>

            <h3 class="font-redhat text-xl lg:text-2xl font-bold text-zinc-900 dark:text-white 
                leading-tight mb-3 dark:group-hover:text-brand transition-colors line-clamp-1">
                {{ name }}
            </h3>

            <p class="font-lora text-sm text-zinc-600 dark:text-slate-400 font-light leading-relaxed line-clamp-2 mb-6 flex-1">
                {{ tagline }}
            </p>

            <div class="mb-3 flex items-center gap-2 flex-wrap">
                <span v-if="location"
                    class="flex items-center gap-1 font-lora text-sm text-zinc-500 dark:text-slate-400">
                    <MapPin class="w-3.5 h-3.5 text-zinc-400" />
                    {{ location }}
                </span>
            </div>

            <div class="pt-5 border-t border-zinc-100 dark:border-white/5 flex items-center justify-between mt-auto">

                <div class="flex items-center gap-1.5 text-zinc-500 dark:text-slate-500 
                    group-hover:text-zinc-900 dark:group-hover:text-white transition-colors">
                    <span class="font-redhat text-sm">
                        {{ profile_views }} Views
                    </span>
                </div>

                <div class="w-8 h-8 rounded-full border border-zinc-200 dark:border-white/10 
                    flex items-center justify-center text-zinc-400 dark:text-slate-500 
                    group-hover:bg-black group-hover:border-white/10 group-hover:text-white 
                    transition-all transform group-hover:translate-x-1 shadow-sm">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                    </svg>
                </div>

            </div>
        </div>
    </Link>
</template>