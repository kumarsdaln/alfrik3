<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    href: {
        type: String,
        required: true,
    },
    image: {
        type: String,
        required: true,
    },
    name: {
        type: String,
        required: true,
    },
    designation: {
        type: String,
        required: true,
    },
    feedback: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <div class="bg-gradient-to-r from-white to-[#e0006c45] relative">
        <div class="absolute w-full h-full bg-white/40 backdrop-blur-lg">

        </div>
        <div class="group p-8 transition-all duration-500 relative overflow-hidden">
            <!-- Decorative Elements -->

            <!-- Profile Section -->
            <div class="flex flex-col md:flex-row items-center gap-6 mb-4 md:mb-8">
                <div class="relative">
                    <img :src="image" :alt="name"
                        class="w-24 h-24 rounded-full object-cover ring-4 ring-white shadow-xl group-hover:scale-105 transition-transform duration-300 relative z-10">
                </div>
                <div class="md:flex-1">
                    <h4 class="font-bold  text-center md:text-start text-primary text-xl mb-1 line-clamp-1">{{ name }}</h4>
                    <p class="text-center md:text-start text-slate-500 text-sm mb-2 line-clamp-2">
                        {{ designation }}
                    </p>
                </div>
            </div>

            <!-- Quote -->
            <div class="relative mb-4 md:mb-8">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-6 h-6 md:w-10 md:h-10 absolute -top-4 -left-2"
                    fill="#e0006c">
                    <path
                        d="M0 216C0 149.7 53.7 96 120 96l8 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-8 0c-30.9 0-56 25.1-56 56l0 8 64 0c35.3 0 64 28.7 64 64l0 64c0 35.3-28.7 64-64 64l-64 0c-35.3 0-64-28.7-64-64l0-32 0-32 0-72zm256 0c0-66.3 53.7-120 120-120l8 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-8 0c-30.9 0-56 25.1-56 56l0 8 64 0c35.3 0 64 28.7 64 64l0 64c0 35.3-28.7 64-64 64l-64 0c-35.3 0-64-28.7-64-64l0-32 0-32 0-72z" />
                </svg>
                <blockquote class="pt-4 pl-6 text-slate-600 text-sm md:text-lg italic leading-relaxed min-h-20">
                    {{ feedback }}
                </blockquote>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"
                    class="w-6 h-6 md:w-10 md:h-10 absolute -bottom-4 -right-2" fill="#e0006c">
                    <path
                        d="M448 296c0 66.3-53.7 120-120 120l-8 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l8 0c30.9 0 56-25.1 56-56l0-8-64 0c-35.3 0-64-28.7-64-64l0-64c0-35.3 28.7-64 64-64l64 0c35.3 0 64 28.7 64 64l0 32 0 32 0 72zm-256 0c0 66.3-53.7 120-120 120l-8 0c-17.7 0-32-14.3-32-32s14.3-32 32-32l8 0c30.9 0 56-25.1 56-56l0-8-64 0c-35.3 0-64-28.7-64-64l0-64c0-35.3 28.7-64 64-64l64 0c35.3 0 64 28.7 64 64l0 32 0 32 0 72z" />
                </svg>
            </div>

            <!-- Service Badge & Date -->
            <div class="flex items-center justify-between">
                <div class="inline-flex items-center gap-2 px-4 py-2">
                    <Link :href="href" class="text-sm font-semibold leading-6 text-gray-900">Read Interview
                    <span aria-hidden="true">→</span></Link>
                </div>
            </div>
        </div>
    </div>

</template>
