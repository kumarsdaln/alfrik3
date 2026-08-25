<script setup>
    import PageHeader from '@/Components/Admin/PageHeader.vue';
    import AdminLayout from '@/Layouts/AdminLayout.vue';
    import MediaManager from '@/Pages/Admin/Interviews/Partials/MediaManager.vue';
    import { formatDate } from '@/utils/dateUtils';
    import { Link } from '@inertiajs/vue3';
    import { show as adminUsersShow } from '@/routes/admin/users';

    const props = defineProps({
        interview: {
            type: Object,
            required: true
        }
    });
</script>

<template>
    <AdminLayout>
        <PageHeader title="Interview Details" />
        <div class="min-h-screen bg-[#f8f8fc] dark:bg-[#0b0b0c] text-gray-900 dark:text-white">

            <div class="max-w-7xl mx-auto px-5 py-10">
                <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-10">

                    <!-- MAIN -->
                    <div>

                        <div class="rounded-2xl overflow-hidden h-64 sm:h-80 relative mb-8">

                            <!-- Thumbnail Image -->
                            <img v-if="interview.thumbnail" :src="interview.thumbnail"
                                class="absolute inset-0 w-full h-full object-cover" alt="thumbnail" />

                            <!-- Gradient Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/70 to-purple-600/70"></div>

                            <!-- Content -->
                            <div class="absolute bottom-0 left-0 right-0 p-6 sm:p-8">
                                <h1 class="text-3xl sm:text-4xl font-bold text-white">
                                    {{ interview.title }}
                                </h1>
                            </div>

                        </div>

                        <!-- META -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">

                            <div
                                class="bg-white dark:bg-[#121214] border border-[#e8e8f0] dark:border-gray-800 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Duration</p>
                                <p class="text-sm font-semibold">{{ interview.duration }}</p>
                            </div>

                            <div
                                class="bg-white dark:bg-[#121214] border border-[#e8e8f0] dark:border-gray-800 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Views</p>
                                <p class="text-sm font-semibold">{{ interview.views_count }}</p>
                            </div>

                            <div
                                class="bg-white dark:bg-[#121214] border border-[#e8e8f0] dark:border-gray-800 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Questions</p>
                                <p class="text-sm font-semibold">{{ interview.questions.length }}</p>
                            </div>

                            <div
                                class="bg-white dark:bg-[#121214] border border-[#e8e8f0] dark:border-gray-800 rounded-xl p-4">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Published</p>
                                <p class="text-sm font-semibold">{{ formatDate(interview.published_at) }}</p>
                            </div>

                        </div>

                        <!-- PARTICIPANTS -->
                        <div class="mb-10">
                            <h2 class="text-xl font-semibold mb-5">Participants</h2>

                            <div class="grid sm:grid-cols-2 md:grid-cols-3 gap-4">

                                <div v-for="p in interview.participants" :key="p.id" class="flex items-center gap-4 p-4 rounded-2xl border
            bg-white dark:bg-[#121214]
            border-[#e8e8f0] dark:border-gray-800
            hover:shadow-md transition">

                                    <!-- Avatar -->
                                    <div
                                        class="w-12 h-12 rounded-full overflow-hidden bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-sm font-bold">
                                        <img v-if="p.user.profile_image" :src="p.user.profile_image"
                                            class="w-full h-full object-cover" />
                                        <span v-else>
                                            {{ p.user.name.charAt(0) }}
                                        </span>
                                    </div>

                                    <!-- Info -->
                                    <div>
                                        <p class="text-sm font-semibold">
                                            {{ p.user.name }}
                                        </p>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ p.role }}
                                        </p>
                                    </div>

                                </div>

                            </div>
                        </div>

                        <!-- MEDIA -->
                        <div class="mb-8">
                            <MediaManager :interview="interview" />
                        </div>

                        <!-- DESCRIPTION -->
                        <div class="mb-8">
                            <div v-html="interview.description"></div>
                        </div>
                        
                        <!-- Q&A -->
                        <div class="mb-14">
                            <h2 class="text-xl font-semibold mb-6">Q&A</h2>

                            <div class="space-y-8">

                                <div v-for="q in interview.questions" :key="q.id">

                                    <!-- QUESTION (Interviewer) -->
                                    <div class="flex items-start gap-4">

                                        <!-- Avatar -->
                                        <div
                                            class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/40 flex items-center justify-center text-sm font-bold">
                                            <Link :href="adminUsersShow(q.asked_by).url" class="w-full h-full flex items-center justify-center">
                                                <img v-if="q.interviewer?.profile_image" :src="q.interviewer.profile_image"
                                                    class="w-full h-full object-cover rounded-full" />
                                                <span v-else>
                                                    {{ q.interviewer?.name?.charAt(0) }}
                                                </span>
                                            </Link>
                                        </div>

                                        <!-- Bubble -->
                                        <div class="flex-1">
                                            <div class="inline-block px-4 py-3 rounded-2xl
                        bg-indigo-50 dark:bg-indigo-900/30
                        text-sm font-medium">
                                                {{ q.question }}
                                            </div>
                                        </div>

                                    </div>

                                    <!-- ANSWERS -->
                                    <div class="mt-4 space-y-4">

                                        <div v-for="a in q.answers" :key="a.id"
                                            class="flex items-start gap-4 justify-end">

                                            <!-- Bubble -->
                                            <div class="flex-1 text-right">
                                                <div class="inline-block px-4 py-3 rounded-2xl
                            bg-gray-100 dark:bg-gray-800 text-sm">

                                                    <p class="text-xs text-gray-500 mb-1">
                                                        {{ a.answered_by?.name }}
                                                    </p>

                                                    {{ a.answer }}
                                                </div>
                                            </div>

                                            <!-- Avatar -->
                                            <div
                                                class="w-10 h-10 rounded-full overflow-hidden bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-sm font-bold">
                                                <img v-if="a.answered_by?.profile_image" :src="a.answered_by.profile_image"
                                                    class="w-full h-full object-cover" />
                                                <span v-else>
                                                    {{ a.answered_by?.name?.charAt(0) }}
                                                </span>
                                            </div>

                                        </div>

                                        <!-- Empty -->
                                        <div v-if="!q.answers?.length" class="text-sm text-gray-400 italic ml-14">
                                            No responses yet
                                        </div>

                                    </div>

                                </div>

                            </div>
                        </div>

                    </div>

                    <!-- SIDEBAR -->
                    <div class="hidden lg:block">

                        <div
                            class="bg-white dark:bg-[#121214] border border-[#e8e8f0] dark:border-gray-800 rounded-2xl p-5 mb-4">
                            <p class="text-xs text-gray-500 mb-3">Created by</p>
                            <p class="font-semibold">Admin</p>
                        </div>

                        <div
                            class="bg-white dark:bg-[#121214] border border-[#e8e8f0] dark:border-gray-800 rounded-2xl p-5">
                            <p class="text-xs text-gray-500 mb-3">Share</p>

                            <button class="w-full px-3 py-2 rounded-lg text-sm
                    bg-gray-100 dark:bg-gray-800">
                                Copy Link
                            </button>

                        </div>

                    </div>

                </div>
            </div>

        </div>
    </AdminLayout>
</template>