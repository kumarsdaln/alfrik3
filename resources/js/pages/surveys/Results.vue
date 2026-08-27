<script setup lang="ts">
    import { Head } from '@inertiajs/vue3'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import SurveyResultsView from '@/components/survey/SurveyResultsView.vue'
    import type { Survey, SurveyResults } from '@/types'

    defineProps<{ survey: Pick<Survey, 'id' | 'title' | 'slug' | 'description'>; results: SurveyResults }>()
</script>

<template>

    <Head :title="`Results — ${survey.title}`" />
    <div class="container mx-auto px-4 py-10 max-w-2xl">
        <AppHeading tag="h2" font="prata" size="3xl" weight="semibold" class="mb-2">{{ survey.title }}</AppHeading>
        <AppText tag="p" color="muted" class="mb-8">Live results</AppText>
        <SurveyResultsView :results="results" />
    </div>
</template>
