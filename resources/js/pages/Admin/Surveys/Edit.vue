<script setup lang="ts">
    import { Link } from '@inertiajs/vue3'
    import SurveyBuilder from '@/components/survey/SurveyBuilder.vue'
    import { update as surveyUpdate, results as surveyResults } from '@/routes/admin/surveys'
    import type { Survey } from '@/types'

    const props = defineProps<{ survey: Survey }>()
</script>

<template>
        <div class="py-4">
            <div class="max-w-3xl mx-auto px-4 flex items-center justify-between mb-2">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Survey</h1>
                <Link :href="surveyResults(props.survey.id).url" class="text-sm text-[#e0006c] font-semibold hover:underline">View results →</Link>
            </div>
            <SurveyBuilder :survey="props.survey" :submit-url="surveyUpdate(props.survey.id).url" />
        </div>
</template>
