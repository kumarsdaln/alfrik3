<!-- Components/Interview/InterviewSpeakerCard.vue -->
<script setup>
import { Link } from '@inertiajs/vue3';
import Avatar from '../profile/Avatar.vue';

const props = defineProps({
    name: String,
    avatar: String | null,
    role: { type: String, default: 'Speaker' },
    variant: { type: String, default: 'default' },
    href: { type: String, default: '#' }
});

const isAnswer = props.variant === 'answer';
</script>

<template>
    <Link :href="href" class="group relative flex items-center gap-5 py-2 pl-2 pr-6 transition-all duration-700">
        
        <!-- Hover Background: Not a box, but a soft "bleed" -->
        <div class="absolute inset-0 -z-10 scale-95 opacity-0 transition-all duration-500 ease-out 
                    group-hover:scale-100 group-hover:opacity-100
                    rounded-full bg-gradient-to-r from-zinc-100/50 to-transparent 
                    dark:from-white/5 dark:to-transparent"></div>

        <!-- Avatar Section -->
        <div class="relative shrink-0">
            <!-- Glow Effect for 'Answer' variant (The only "box" is a circle) -->
            <div v-if="isAnswer" 
                 class="absolute inset-0 -z-10 rounded-full bg-brand/20 blur-xl 
                        transition-transform duration-700 group-hover:scale-150"></div>
            
            <div class="relative transition-transform duration-500 group-hover:scale-110">
                <Avatar 
                    :image="avatar" 
                    :name="name" 
                    size="w-12 h-12"
                    class="ring-offset-2 ring-offset-white dark:ring-offset-zinc-950 transition-all duration-500 group-hover:ring-2 group-hover:ring-brand/30"
                >
                    {{ name?.charAt(0) || 'U' }}
                </Avatar>
                
                <!-- Status Indicator (Instead of a border-variant) -->
                <div v-if="isAnswer" 
                     class="absolute -right-1 -bottom-1 w-4 h-4 rounded-full bg-brand border-4 border-white dark:border-zinc-950">
                </div>
            </div>
        </div>

        <!-- Content Section -->
        <div class="flex flex-col min-w-0">
            <span :class="[
                'text-[9px] font-black uppercase tracking-[0.4em] transition-colors duration-500',
                isAnswer ? 'text-brand dark:text-brand' : 'text-zinc-400 dark:text-zinc-600'
            ]">
                {{ role }}
            </span>

            <div class="flex items-center gap-3">
                <h4 class="text-base sm:text-lg font-redhat text-zinc-900 dark:text-zinc-100 truncate transition-all duration-500 group-hover:text-brand dark:group-hover:text-brand">
                    {{ name || 'Unknown User' }}
                </h4>
                
                <!-- Minimal Arrow -->
                <div class="overflow-hidden w-0 opacity-0 transition-all duration-500 group-hover:w-5 group-hover:opacity-100">
                    <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                    </svg>
                </div>
            </div>
            
            <!-- Underline Accent: Only visible on hover, very thin -->
            <div class="h-px w-0 bg-gradient-to-r from-brand/50 to-transparent transition-all duration-700 group-hover:w-full mt-1"></div>
        </div>
    </Link>
</template>

<style scoped>
/* Optional: Adding a soft reveal animation for the text */
.group:hover h4 {
    letter-spacing: 0.01em;
}
</style>