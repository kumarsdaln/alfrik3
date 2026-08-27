<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import { show as surveyShow } from '@/routes/surveys'
    import type { Survey } from '@/types'

    defineProps<{ surveys: (Survey & { questions_count?: number; responses_count?: number })[] }>()

    const closesLabel = (d?: string | null) =>
        d ? `Closes ${new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })}` : ''
</script>

<template>

    <Head title="Surveys — Alfrik">
        <meta name="description" content="Share your voice — take part in Alfrik community surveys." />
    </Head>
    <div class="container mx-auto px-4 pt-10 pb-4 text-center">
        <AppText tag="p" font="redhat" size="xs" weight="bold" tracking="wide" uppercase color="brand" align="center"
            class="mb-4">
            Your Voice
        </AppText>
        <AppHeading tag="h1" font="prata" size="5xl" weight="bold" align="center" class="mb-4">Surveys</AppHeading>
        <AppText tag="p" font="lora" color="muted" align="center" class="max-w-2xl mx-auto">
            Help shape the community — take part in our open surveys.
        </AppText>
    </div>

    <section class="container mx-auto px-4 py-10 pb-20 max-w-3xl">
        <div v-if="surveys.length" class="space-y-4">
            <Link v-for="s in surveys" :key="s.id" :href="surveyShow(s.slug).url"
                class="group flex items-center justify-between gap-4 rounded-2xl border border-border-light dark:border-border-dark bg-surface-light dark:bg-surface-dark p-6 hover:border-brand transition-colors">
                <div class="min-w-0">
                    <AppHeading tag="h2" font="prata" size="lg" weight="semibold" hover-brand>{{ s.title }}</AppHeading>
                    <AppText v-if="s.description" tag="p" font="lora" size="sm" color="muted" :clamp="2" class="mt-1">{{
                        s.description }}</AppText>
                    <div class="mt-2 flex items-center gap-3">
                        <AppText tag="span" size="xs" color="muted">{{ s.questions_count ?? 0 }} questions</AppText>
                        <AppText tag="span" size="xs" color="muted">· {{ s.responses_count ?? 0 }} responses</AppText>
                        <AppText v-if="s.closes_at" tag="span" size="xs" color="muted">· {{ closesLabel(s.closes_at) }}
                        </AppText>
                    </div>
                </div>
                <span
                    class="shrink-0 inline-flex items-center rounded-full bg-brand px-5 py-2.5 transition-colors group-hover:bg-brand-hover">
                    <AppText tag="span" font="redhat" size="sm" weight="bold" class="text-white">Take survey</AppText>
                </span>
            </Link>
        </div>
        <div v-else
            class="py-28 text-center border border-dashed border-border-light dark:border-border-dark rounded-3xl">
            <AppText tag="p" font="lora" size="xl" color="muted" align="center" class="italic mb-3">No open surveys
                right now.</AppText>
            <AppText tag="p" size="sm" color="muted" align="center">Check back soon.</AppText>
        </div>
    </section>
</template>
