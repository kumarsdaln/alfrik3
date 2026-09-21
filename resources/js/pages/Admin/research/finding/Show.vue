<script setup lang="ts">
    import { Link } from '@inertiajs/vue3'
    import { Pencil, ArrowLeft, Plus } from '@lucide/vue'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import type {
        Research,
        ResearchFinding,
        ResearchQuestion,
        ResearchEvidence,
    } from '@/types'

    interface Props {
        research: Research
        finding: ResearchFinding
        questions: ResearchQuestion[]
        evidence: ResearchEvidence[]
    }

    defineProps<Props>()
</script>

<template>
    <div class="space-y-6 py-5">

        <!-- Header -->
        <div class="flex items-start justify-between gap-4">
            <div class="flex gap-4">
                <BackButton :href="`/admin/research/${research.id}/findings`" />

                <Heading title="Research Finding"
                    description="View the finding, related research questions, and supporting evidence." />
            </div>

            <Button as-child>
                <Link :href="`/admin/research/${research.id}/findings/${finding.id}/edit`">
                    <Pencil class="mr-2 h-4 w-4" />
                    Edit
                </Link>
            </Button>
        </div>

        <!-- Finding -->
        <div class="rounded-lg border border-border-light bg-background p-6 dark:border-border-dark">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="space-y-2">
                    <h2 class="text-xl font-semibold">
                        {{ finding.title }}
                    </h2>

                    <div class="flex flex-wrap gap-2">
                        <Badge>
                            {{ finding.type.label }}
                        </Badge>

                        <Badge v-if="finding.confidence !== null" variant="outline">
                            Confidence: {{ finding.confidence }}%
                        </Badge>
                    </div>
                </div>

                <div class="text-sm text-muted-foreground">
                    Position {{ finding.position + 1 }}
                </div>
            </div>

            <!-- Summary -->
            <div v-if="finding.summary" class="mt-6">
                <h3 class="mb-2 text-sm font-semibold">
                    Summary
                </h3>

                <p class="text-sm leading-6 text-muted-foreground">
                    {{ finding.summary }}
                </p>
            </div>

            <!-- Description -->
            <div v-if="finding.description" class="mt-6">
                <h3 class="mb-2 text-sm font-semibold">
                    Description
                </h3>

                <div class="whitespace-pre-line text-sm leading-6 text-muted-foreground">
                    {{ finding.description }}
                </div>
            </div>
        </div>

        <!-- Related Questions -->
        <div class="rounded-lg border border-border-light bg-background dark:border-border-dark">
            <div
                class="flex items-center justify-between border-b border-border-light px-6 py-4 dark:border-border-dark">
                <div>
                    <h2 class="font-semibold">
                        Related Questions
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Research questions connected to this finding.
                    </p>
                </div>

                <Button variant="outline" size="sm" as-child>
                    <Link :href="`/admin/research/${research.id}/findings/${finding.id}/questions`">
                        <Plus class="mr-2 h-4 w-4" />
                        Manage
                    </Link>
                </Button>
            </div>

            <div v-if="questions.length" class="divide-y divide-border-light dark:divide-border-dark">
                <div v-for="question in questions" :key="question.id" class="px-6 py-4">
                    <div class="flex items-start gap-3">
                        <span class="text-sm font-medium text-muted-foreground">
                            {{ question.position + 1 }}.
                        </span>

                        <div class="min-w-0">
                            <p class="text-sm font-medium">
                                {{ question.question }}
                            </p>

                            <p v-if="question.description" class="mt-1 text-sm text-muted-foreground">
                                {{ question.description }}
                            </p>

                            <Badge variant="outline" class="mt-2">
                                {{ question.type.label }}
                            </Badge>
                        </div>
                    </div>
                </div>
            </div>

            <div v-else class="px-6 py-10 text-center text-sm text-muted-foreground">
                No research questions are linked to this finding.
            </div>
        </div>

        <!-- Evidence -->
        <div class="rounded-lg border border-border-light bg-background dark:border-border-dark">
            <div
                class="flex items-center justify-between border-b border-border-light px-6 py-4 dark:border-border-dark">
                <div>
                    <h2 class="font-semibold">
                        Supporting Evidence
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Evidence supporting this research finding.
                    </p>
                </div>

                <Button variant="outline" size="sm" as-child>
                    <Link :href="`/admin/research/${research.id}/findings/${finding.id}/evidence`">
                        <Plus class="mr-2 h-4 w-4" />
                        Manage
                    </Link>
                </Button>
            </div>

            <div v-if="evidence.length" class="divide-y divide-border-light dark:divide-border-dark">
                <div v-for="item in evidence" :key="item.id" class="px-6 py-4">
                    <div class="space-y-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <Badge>
                                {{ item.type.label }}
                            </Badge>

                            <span v-if="item.title" class="text-sm font-medium">
                                {{ item.title }}
                            </span>
                        </div>

                        <p v-if="item.description" class="text-sm leading-6 text-muted-foreground">
                            {{ item.description }}
                        </p>

                        <p v-if="item.citation" class="text-sm text-muted-foreground">
                            {{ item.citation }}
                        </p>
                    </div>
                </div>
            </div>

            <div v-else class="px-6 py-10 text-center text-sm text-muted-foreground">
                No supporting evidence has been added.
            </div>
        </div>

        <!-- Metadata -->
        <div class="rounded-lg border border-border-light bg-background p-6 dark:border-border-dark">
            <h2 class="font-semibold">
                Finding Information
            </h2>

            <dl class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <dt class="text-xs text-muted-foreground">
                        Research
                    </dt>

                    <dd class="mt-1 text-sm font-medium">
                        {{ research.title }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs text-muted-foreground">
                        Finding Type
                    </dt>

                    <dd class="mt-1 text-sm font-medium">
                        {{ finding.type.label }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs text-muted-foreground">
                        Confidence
                    </dt>

                    <dd class="mt-1 text-sm font-medium">
                        {{ finding.confidence !== null
                            ? `${finding.confidence}%`
                            : '—'
                        }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs text-muted-foreground">
                        Position
                    </dt>

                    <dd class="mt-1 text-sm font-medium">
                        {{ finding.position + 1 }}
                    </dd>
                </div>
            </dl>
        </div>

    </div>
</template>