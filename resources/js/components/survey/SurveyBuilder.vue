<script setup lang="ts">
    import { reactive, ref } from 'vue'
    import { useForm } from '@inertiajs/vue3'
    import AppButton from '@/components/ui/AppButton.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppToggle from '@/components/ui/AppToggle.vue'
    
    import type { Survey, SurveyFormQuestion, SurveyQuestionType } from '@/types'
    import { Trash2 } from '@lucide/vue'

    const props = defineProps<{ survey?: Survey; submitUrl: string }>()

    const s = props.survey

    const meta = reactive({
        title: s?.title ?? '',
        description: s?.description ?? '',
        status: !!s?.status,
        allow_anonymous: s?.allow_anonymous ?? true,
        one_response_per_user: s?.one_response_per_user ?? true,
        show_results: s?.show_results ?? false,
        published_at: s?.published_at ? s.published_at.slice(0, 16) : '',
        closes_at: s?.closes_at ? s.closes_at.slice(0, 16) : '',
    })

    const questions = reactive<SurveyFormQuestion[]>(
        (s?.questions ?? []).map(q => ({
            id: q.id,
            question: q.question,
            type: q.type,
            required: !!q.required,
            settings: { max: q.settings?.max ?? 5 },
            options: (q.options ?? []).map(o => ({ id: o.id, label: o.label })),
        })),
    )

    const TYPES: { value: SurveyQuestionType; label: string }[] = [
        { value: 'single_choice', label: 'Single choice' },
        { value: 'multiple_choice', label: 'Multiple choice' },
        { value: 'rating', label: 'Rating' },
        { value: 'text', label: 'Text' },
    ]
    const isChoice = (t: SurveyQuestionType) => t === 'single_choice' || t === 'multiple_choice'

    function addQuestion() {
        questions.push({ question: '', type: 'single_choice', required: false, settings: { max: 5 }, options: [{ label: '' }, { label: '' }] })
    }
    function removeQuestion(i: number) { questions.splice(i, 1) }
    function move(i: number, dir: -1 | 1) {
        const j = i + dir
        if (j < 0 || j >= questions.length) return
        const [q] = questions.splice(i, 1)
        questions.splice(j, 0, q)
    }
    function addOption(q: SurveyFormQuestion) { q.options.push({ label: '' }) }
    function removeOption(q: SurveyFormQuestion, i: number) { q.options.splice(i, 1) }

    const form = useForm({})
    const errorMsg = ref('')

    function save() {
        errorMsg.value = ''
        if (!meta.title.trim()) { errorMsg.value = 'Title is required.'; return }
        for (const q of questions) {
            if (!q.question.trim()) { errorMsg.value = 'Every question needs text.'; return }
            if (isChoice(q.type) && q.options.filter(o => o.label.trim()).length < 2) {
                errorMsg.value = 'Choice questions need at least two options.'; return
            }
        }

        form.transform(() => ({
            ...meta,
            questions: questions.map(q => ({
                id: q.id,
                question: q.question,
                type: q.type,
                required: q.required,
                settings: q.type === 'rating' ? { max: Number(q.settings.max) || 5 } : null,
                options: isChoice(q.type) ? q.options.filter(o => o.label.trim()).map(o => ({ id: o.id, label: o.label })) : [],
            })),
        })).post(props.submitUrl, { preserveScroll: true })
    }
</script>

<template>
    <div class="max-w-3xl mx-auto px-4 py-6 space-y-6">
        <div v-if="errorMsg" class="rounded-lg bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-900/40 px-4 py-3">
            <AppText tag="p" size="sm" color="danger">{{ errorMsg }}</AppText>
        </div>

        <!-- Survey meta -->
        <div class="rounded-2xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-6 space-y-4">
            <div>
                <AppText tag="label" size="xs" weight="bold" tracking="wide" uppercase color="muted" class="block mb-1">Title</AppText>
                <input v-model="meta.title" type="text" placeholder="Survey title"
                    class="w-full rounded-lg border-border-light dark:border-border-dark bg-canvas-light dark:bg-white/5 text-content-light dark:text-content-dark focus:border-brand focus:ring focus:ring-brand/20" />
            </div>
            <div>
                <AppText tag="label" size="xs" weight="bold" tracking="wide" uppercase color="muted" class="block mb-1">Description</AppText>
                <textarea v-model="meta.description" rows="2"
                    class="w-full rounded-lg border-border-light dark:border-border-dark bg-canvas-light dark:bg-white/5 text-content-light dark:text-content-dark focus:border-brand focus:ring focus:ring-brand/20"></textarea>
            </div>
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <AppText tag="label" size="xs" weight="bold" tracking="wide" uppercase color="muted" class="block mb-1">Publish date (optional)</AppText>
                    <input v-model="meta.published_at" type="datetime-local"
                        class="w-full rounded-lg border-border-light dark:border-border-dark bg-canvas-light dark:bg-white/5 text-content-light dark:text-content-dark focus:border-brand focus:ring focus:ring-brand/20" />
                </div>
                <div>
                    <AppText tag="label" size="xs" weight="bold" tracking="wide" uppercase color="muted" class="block mb-1">Closes at (optional)</AppText>
                    <input v-model="meta.closes_at" type="datetime-local"
                        class="w-full rounded-lg border-border-light dark:border-border-dark bg-canvas-light dark:bg-white/5 text-content-light dark:text-content-dark focus:border-brand focus:ring focus:ring-brand/20" />
                </div>
            </div>
            <div class="flex flex-wrap gap-x-8 gap-y-3 pt-2">
                <AppToggle v-model="meta.allow_anonymous" true-label="Guests can respond" false-label="Members only" />
                <AppToggle v-model="meta.one_response_per_user" true-label="One response per person" false-label="Unlimited responses" />
                <AppToggle v-model="meta.show_results" true-label="Public results" false-label="Private results" />
            </div>
        </div>

        <!-- Questions -->
        <div v-for="(q, qi) in questions" :key="qi" class="rounded-2xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-6 space-y-4">
            <div class="flex items-start justify-between gap-3">
                <AppText tag="span" size="sm" weight="bold" color="muted" class="mt-2.5">Q{{ qi + 1 }}</AppText>
                <input v-model="q.question" type="text" placeholder="Question text"
                    class="flex-1 rounded-lg border-border-light dark:border-border-dark bg-canvas-light dark:bg-white/5 text-content-light dark:text-content-dark focus:border-brand focus:ring focus:ring-brand/20" />
                <div class="flex items-center gap-1">
                    <button type="button" @click="move(qi, -1)" class="p-1.5 text-content-lightMuted dark:text-content-darkMuted hover:text-content-light dark:hover:text-content-dark" title="Move up">↑</button>
                    <button type="button" @click="move(qi, 1)" class="p-1.5 text-content-lightMuted dark:text-content-darkMuted hover:text-content-light dark:hover:text-content-dark" title="Move down">↓</button>
                    <AppButton type="button" variant="delete" size="sm" icon-only @click="removeQuestion(qi)">
                        <template #icon-left><Trash2 class="w-4 h-4" /></template>
                    </AppButton>
                </div>
            </div>

            <div class="flex flex-wrap items-center gap-4">
                <select v-model="q.type"
                    class="rounded-lg border-border-light dark:border-border-dark bg-canvas-light dark:bg-white/5 text-content-light dark:text-content-dark text-sm focus:border-brand focus:ring focus:ring-brand/20">
                    <option v-for="t in TYPES" :key="t.value" :value="t.value">{{ t.label }}</option>
                </select>
                <AppToggle v-model="q.required" true-label="Required" false-label="Optional" />
                <div v-if="q.type === 'rating'" class="flex items-center gap-2">
                    <AppText tag="span" size="sm" color="muted">Max</AppText>
                    <input v-model.number="q.settings.max" type="number" min="2" max="10"
                        class="w-16 rounded-lg border-border-light dark:border-border-dark bg-canvas-light dark:bg-white/5 text-content-light dark:text-content-dark focus:border-brand focus:ring focus:ring-brand/20" />
                </div>
            </div>

            <!-- Options for choice questions -->
            <div v-if="isChoice(q.type)" class="space-y-2 pl-6">
                <div v-for="(opt, oi) in q.options" :key="oi" class="flex items-center gap-2">
                    <AppText tag="span" size="sm" color="muted">{{ oi + 1 }}.</AppText>
                    <input v-model="opt.label" type="text" placeholder="Option label"
                        class="flex-1 rounded-lg border-border-light dark:border-border-dark bg-canvas-light dark:bg-white/5 text-content-light dark:text-content-dark text-sm focus:border-brand focus:ring focus:ring-brand/20" />
                    <AppButton type="button" variant="delete" size="xs" @click="removeOption(q, oi)">✕</AppButton>
                </div>
                <AppButton type="button" variant="add" size="sm" @click="addOption(q)">Add option</AppButton>
            </div>
        </div>

        <AppButton type="button" variant="add" size="lg" full-width @click="addQuestion">Add Question</AppButton>

        <!-- Save bar -->
        <div class="flex items-center justify-end gap-3 sticky bottom-0 bg-surface-light/90 dark:bg-surface-dark/90 backdrop-blur py-4 -mx-4 px-4 border-t border-border-light dark:border-border-dark">
            <div class="mr-auto">
                <AppToggle v-model="meta.status" true-label="Live" false-label="Draft" />
            </div>
            <AppButton variant="primary" type="button" :loading="form.processing" :disabled="form.processing" @click="save">
                Save Survey
            </AppButton>
        </div>
    </div>
</template>
