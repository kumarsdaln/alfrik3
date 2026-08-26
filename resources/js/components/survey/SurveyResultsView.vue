<script setup lang="ts">
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import AppBadge from '@/components/ui/AppBadge.vue'
    import type { SurveyResults } from '@/types'

    defineProps<{ results: SurveyResults }>()
</script>

<template>
    <div class="space-y-8">
        <div class="rounded-2xl bg-brand-light dark:bg-brand/10 border border-brand/20 p-6 text-center">
            <AppHeading tag="p" font="redhat" size="4xl" weight="bold" color="brand" align="center">{{ results.total_responses }}</AppHeading>
            <AppText tag="p" size="sm" color="muted" align="center" class="mt-1">total responses</AppText>
        </div>

        <div v-for="(q, qi) in results.questions" :key="q.question_id"
            class="rounded-2xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-6">
            <div class="flex flex-wrap items-center gap-2 mb-5">
                <AppHeading tag="h3" font="prata" size="lg" weight="semibold">
                    <span class="text-brand mr-1">{{ qi + 1 }}.</span>{{ q.question }}
                </AppHeading>
                <AppBadge variant="default" size="sm">{{ q.total_answers }} answers</AppBadge>
            </div>

            <!-- choice questions -->
            <div v-if="q.options" class="space-y-3">
                <div v-for="opt in q.options" :key="opt.id">
                    <div class="flex items-center justify-between mb-1">
                        <AppText tag="span" font="lora" size="sm">{{ opt.label }}</AppText>
                        <span class="flex items-center gap-1">
                            <AppText tag="span" size="xs" color="muted">{{ opt.count }} ·</AppText>
                            <AppText tag="span" size="xs" color="brand" weight="bold">{{ opt.percentage }}%</AppText>
                        </span>
                    </div>
                    <div class="h-2.5 rounded-full bg-canvas-light dark:bg-white/5 overflow-hidden">
                        <div class="h-full rounded-full bg-brand transition-all" :style="{ width: `${opt.percentage}%` }"></div>
                    </div>
                </div>
            </div>

            <!-- rating -->
            <div v-else-if="q.distribution" class="space-y-3">
                <div class="flex items-center gap-2 mb-2">
                    <AppText tag="span" size="sm" color="muted">Average:</AppText>
                    <AppHeading tag="span" font="redhat" size="lg" weight="bold" color="brand">{{ q.average }}</AppHeading>
                    <AppText tag="span" size="sm" color="muted">/ {{ q.max }}</AppText>
                </div>
                <div v-for="d in q.distribution" :key="d.value" class="flex items-center gap-3">
                    <AppText tag="span" size="sm" color="muted" class="w-6">{{ d.value }}</AppText>
                    <div class="flex-1 h-2.5 rounded-full bg-canvas-light dark:bg-white/5 overflow-hidden">
                        <div class="h-full rounded-full bg-brand"
                            :style="{ width: `${q.total_answers ? (d.count / q.total_answers) * 100 : 0}%` }"></div>
                    </div>
                    <AppText tag="span" size="sm" color="muted" align="right" class="w-8">{{ d.count }}</AppText>
                </div>
            </div>

            <!-- text -->
            <div v-else-if="q.responses" class="space-y-2 max-h-72 overflow-y-auto">
                <AppText v-for="(r, i) in q.responses" :key="i" tag="p" font="lora" size="sm"
                    class="p-3 rounded-lg bg-canvas-light dark:bg-white/5 border border-border-light dark:border-border-dark">
                    {{ r }}
                </AppText>
                <AppText v-if="!q.responses.length" tag="p" size="sm" color="muted">No text responses yet.</AppText>
            </div>
        </div>
    </div>
</template>
