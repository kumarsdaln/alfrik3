<template>
    <div class="w-full">
        <!-- Card -->
        <article
            class="bg-white dark:bg-gray-800 rounded-2xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700 transition hover:shadow-md">

            <!-- Image -->
            <div class="relative">
                <img :src="`/storage/${course.thumbnail_url}`" :alt="course.title"
                    class="w-full aspect-video object-cover" />

                <!-- Category -->
                <span
                    class="absolute top-3 left-3 bg-white dark:bg-gray-900 text-xs font-semibold text-gray-700 dark:text-gray-200 px-3 py-1 rounded-full shadow">
                    {{ course.skill_level }}
                </span>

                <!-- Rating -->
                <span
                    class="absolute top-3 right-3 flex items-center gap-1 bg-white dark:bg-gray-900 px-2 py-1 rounded-full shadow text-xs font-medium text-gray-700 dark:text-gray-200">
                    ⭐ {{ course.rating_avg }}
                </span>
            </div>

            <!-- Content -->
            <div class="p-5">
                <!-- Title -->
                <h3
                    class="font-redhat text-2xl font-semibold text-gray-900 dark:text-gray-100 leading-snug line-clamp-2">
                    {{ course.title }}
                </h3>

                <p class="font-lora mb-2 line-clamp-2 text-gray-600 dark:text-gray-300">
                    {{ course.short_description }}
                </p>

                <!-- Duration & Enrolled -->
                <div class="flex items-center gap-6 text-sm text-gray-500 dark:text-gray-400 mb-3">
                    <div class="flex items-center gap-1">
                        <Calendar class="w-4 dark:text-gray-300" />
                        <span>{{ course.duration_weeks }} Weeks</span>
                    </div>

                    <div class="flex items-center gap-1">
                        <User class="w-4 dark:text-gray-300" />
                        <span>{{ formattedEnrolled }}</span>
                    </div>
                </div>

                <!-- Price -->
                <div class="flex items-center gap-2 mb-4">
                    <span class="font-redhat text-brand font-bold text-2xl">₹{{ course.price }}</span>
                </div>

                <!-- CTA -->
                <div class="flex items-center justify-between">
                    <BaseLink variant="tertiary" :href="coursesShow(course.slug).url">Enrol Now</BaseLink>
                </div>
            </div>
        </article>
    </div>
</template>

<script setup>
    import { ref, computed } from "vue";
    import { show as coursesShow } from "@/routes/courses";
    import BaseLink from "../Links/BaseLink.vue";
    import Calendar from "@/Icons/Calendar.vue";
    import User from "@/Icons/User.vue";

    const props = defineProps({
        course: {
            type: Object,
            required: true,
        },
    });

    const formattedEnrolled = computed(() => {
        const e = Number(props.course.enrolled || 0);
        if (e >= 1000000) return (e / 1000000).toFixed(1).replace(/\.0$/, "") + "M Enrolled";
        if (e >= 1000) return (e / 1000).toFixed(1).replace(/\.0$/, "") + "k Enrolled";
        return e + " Enrolled";
    });
</script>