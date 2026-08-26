<script setup lang="ts">
    import SurveyBuilder from '@/components/survey/SurveyBuilder.vue'
    import { store as surveyStore } from '@/routes/admin/surveys'
</script>

<template>
    <div class="py-4">
        <h1 class="max-w-3xl mx-auto px-4 text-2xl font-bold text-gray-900 dark:text-white mb-2">New Survey</h1>
        <SurveyBuilder :submit-url="surveyStore().url" />
    </div>
</template>
