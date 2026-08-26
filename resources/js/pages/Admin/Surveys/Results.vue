<script setup lang="ts">
    import { Link } from '@inertiajs/vue3'
    import SurveyResultsView from '@/components/survey/SurveyResultsView.vue'
    import { edit as surveyEdit } from '@/routes/admin/surveys'
    import type { Survey, SurveyResults } from '@/types'

    const props = defineProps<{ survey: Pick<Survey, 'id' | 'title' | 'slug' | 'description' | 'show_results'>; results: SurveyResults }>()
</script>

<template>
    <div class="max-w-3xl mx-auto px-4 py-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ props.survey.title }}</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400">Survey results</p>
            </div>
            <Link :href="surveyEdit(props.survey.id).url" class="text-sm text-[#e0006c] font-semibold hover:underline">←
                Edit survey</Link>
        </div>
        <SurveyResultsView :results="props.results" />
    </div>
</template>
