<script setup lang="ts">
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import Empty from '@/components/datadisplay/empty/Empty.vue'

    import SurveyCard from '@/components/survey/SurveyCard.vue'
    import { Survey } from '@/types'

    interface Props {
        surveys: {
            data: Survey[]
        }
    }


    const props = defineProps<Props>()
</script>

<template>
    <section class="border-b border-border-light dark:border-border-dark">
        <div class="pb-12 pt-12 sm:pb-16 sm:pt-16 lg:pb-20 lg:pt-20">
            <div class="max-w-4xl">
                <AppText tag="p" font="redhat" size="xs" weight="bold" tracking="wide" uppercase class="mb-5">
                    Your Voice
                </AppText>

                <AppHeading tag="h1" font="prata" size="5xl" weight="normal" leading="tight">
                    Surveys
                </AppHeading>

                <AppText tag="p" font="lora" size="lg" color="muted" leading="relaxed" class="mt-5 max-w-2xl">
                    Share your perspective and contribute to the research,
                    conversations, and ideas shaping the industries and
                    communities around us.
                </AppText>
            </div>
        </div>
    </section>

    <section class="py-10 sm:py-14 lg:py-16">
        <div v-if="surveys.data.length" class="border-t border-border-light dark:border-border-dark">
            <SurveyCard v-for="(survey, index) in surveys.data" :key="survey.id" :survey="survey" :index="index" />
        </div>


        <Empty v-else title="No open surveys"
            description="There are no active surveys at the moment. Check back soon for new opportunities to share your perspective." />

    </section>
</template>