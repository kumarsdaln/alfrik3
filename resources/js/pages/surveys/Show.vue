<script setup lang="ts">
    import { Head, Link, useForm } from '@inertiajs/vue3'
    import { reactive } from 'vue'
    import AppLayout from '@/Layouts/AppLayout.vue'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppButton from '@/components/ui/AppButton.vue'
    import { submit as surveySubmit, results as surveyResults } from '@/routes/surveys'
    import type { Survey, SurveyQuestion } from '@/types'

    const props = defineProps<{
        survey: Survey
        isOpen: boolean
        hasResponded: boolean
    }>()

    // Answer model keyed by question id.
    const answers = reactive<Record<number, unknown>>({})
    for (const q of props.survey.questions ?? []) {
        if (q.id != null) answers[q.id] = q.type === 'multiple_choice' ? [] : ''
    }

    const form = useForm({ answers })

    function ratingMax(q: SurveyQuestion) { return q.settings?.max ?? 5 }

    function toggleMulti(qid: number, optId: number) {
        const arr = answers[qid] as number[]
        const i = arr.indexOf(optId)
        i === -1 ? arr.push(optId) : arr.splice(i, 1)
    }

    function submit() {
        form.transform(() => ({ answers })).post(surveySubmit(props.survey.slug).url, { preserveScroll: true })
    }
</script>

<template>

    <Head :title="survey.title">
        <meta name="description" :content="survey.description ?? ''" />
    </Head>
    <div class="container mx-auto px-4 py-10 max-w-2xl">
        <AppHeading tag="h2" font="prata" size="3xl" weight="semibold" class="mb-3">{{ survey.title }}</AppHeading>
        <AppText v-if="survey.description" tag="p" font="lora" color="muted" class="mb-8">{{ survey.description }}
        </AppText>

        <!-- States -->
        <div v-if="hasResponded"
            class="rounded-2xl border border-green-200 dark:border-green-900/40 bg-green-50 dark:bg-green-900/10 p-8 text-center">
            <AppText tag="p" size="lg" weight="semibold" color="success" align="center" class="mb-2">You've already
                responded 🎉</AppText>
            <AppText tag="p" size="sm" color="success" align="center" class="mb-4">Thanks for taking part.</AppText>
            <AppButton v-if="survey.show_results" variant="outline" size="sm" :href="surveyResults(survey.slug).url">
                View results</AppButton>
        </div>

        <div v-else-if="!isOpen"
            class="rounded-2xl border border-border-light dark:border-border-dark bg-canvas-light dark:bg-white/5 p-8 text-center">
            <AppText tag="p" size="lg" weight="semibold" align="center">This survey is closed.</AppText>
        </div>

        <!-- Form -->
        <form v-else @submit.prevent="submit" class="space-y-8">
            <div v-for="(q, qi) in survey.questions" :key="q.id"
                class="rounded-2xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-6">
                <AppText tag="label" font="lora" weight="semibold" color="default" class="block mb-4">
                    <span class="text-brand mr-1">{{ qi + 1 }}.</span>{{ q.question }}
                    <span v-if="q.required" class="text-red-500">*</span>
                </AppText>

                <!-- single choice -->
                <div v-if="q.type === 'single_choice'" class="space-y-2">
                    <label v-for="opt in q.options" :key="opt.id"
                        class="flex items-center gap-3 p-3 rounded-lg border border-border-light dark:border-border-dark cursor-pointer hover:border-brand transition-colors">
                        <input type="radio" :name="`q${q.id}`" :value="opt.id" v-model="answers[q.id!]"
                            class="text-brand focus:ring-brand" />
                        <AppText tag="span" font="lora" size="sm">{{ opt.label }}</AppText>
                    </label>
                </div>

                <!-- multiple choice -->
                <div v-else-if="q.type === 'multiple_choice'" class="space-y-2">
                    <label v-for="opt in q.options" :key="opt.id"
                        class="flex items-center gap-3 p-3 rounded-lg border border-border-light dark:border-border-dark cursor-pointer hover:border-brand transition-colors">
                        <input type="checkbox" :checked="(answers[q.id!] as number[]).includes(opt.id!)"
                            @change="toggleMulti(q.id!, opt.id!)" class="rounded text-brand focus:ring-brand" />
                        <AppText tag="span" font="lora" size="sm">{{ opt.label }}</AppText>
                    </label>
                </div>

                <!-- rating -->
                <div v-else-if="q.type === 'rating'" class="flex gap-2">
                    <button v-for="n in ratingMax(q)" :key="n" type="button" @click="answers[q.id!] = n"
                        :class="['w-11 h-11 rounded-full border font-redhat font-bold transition-colors', Number(answers[q.id!]) >= n ? 'bg-brand border-brand text-white' : 'border-border-light dark:border-border-dark text-content-lightMuted dark:text-content-darkMuted hover:border-brand']">
                        {{ n }}
                    </button>
                </div>

                <!-- text -->
                <textarea v-else v-model="answers[q.id!]" rows="3" placeholder="Your answer…"
                    class="w-full rounded-lg border-border-light dark:border-border-dark bg-canvas-light dark:bg-white/5 text-content-light dark:text-content-dark focus:border-brand focus:ring focus:ring-brand/20"></textarea>

                <AppText v-if="form.errors[`answers.${q.id}` as keyof typeof form.errors]" tag="p" size="sm"
                    color="danger" class="mt-2">
                    {{ form.errors[`answers.${q.id}` as keyof typeof form.errors] }}
                </AppText>
            </div>

            <AppButton type="submit" variant="primary" size="lg" full-width :loading="form.processing"
                :disabled="form.processing">
                {{ form.processing ? 'Submitting…' : 'Submit Response' }}
            </AppButton>
        </form>
    </div>
</template>
