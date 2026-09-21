<script setup lang="ts">
    import { Link } from '@inertiajs/vue3'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import { edit } from '@/routes/admin/research'

    import type { Research } from '@/types'

    interface Props {
        research: Research
    }

    const props = defineProps<Props>()
</script>

<template>
    <div class="space-y-6 py-5">
        <!-- Header -->
        <div class="flex items-center justify-between gap-4">
            <div class="flex gap-4">
                <BackButton />

                <Heading :title="research.title" :description="research.subtitle ??
                    'Manage this research project and its related information.'
                    " />
            </div>

            <Link :href="edit(research.id)"
                class="inline-flex items-center rounded-md border px-4 py-2 text-sm font-medium">
                Edit Research
            </Link>
        </div>

        <!-- Status -->
        <div class="flex flex-wrap items-center gap-2">
            <Badge :color="research.type.color">
                {{ research.type.label }}
            </Badge>

            <Badge :color="research.status.color">
                {{ research.status.label }}
            </Badge>

            <Badge v-if="research.featured" variant="outline">
                Featured
            </Badge>
        </div>

        <!-- Overview -->
        <section class="space-y-4 border-t border-border-light pt-6 dark:border-border-dark">
            <div>
                <h2 class="text-base font-semibold">
                    Overview
                </h2>

                <p class="text-sm text-muted-foreground">
                    Basic information about this research.
                </p>
            </div>

            <div v-if="research.summary" class="rounded-md border p-4">
                <h3 class="mb-2 text-sm font-medium">
                    Summary
                </h3>

                <p class="text-sm leading-6 text-muted-foreground">
                    {{ research.summary }}
                </p>
            </div>

            <div v-if="research.description" class="rounded-md border p-4">
                <h3 class="mb-2 text-sm font-medium">
                    Description
                </h3>

                <div class="text-sm leading-6 text-muted-foreground">
                    {{ research.description }}
                </div>
            </div>
        </section>

        <!-- Workspace -->
        <section class="border-t border-border-light pt-6 dark:border-border-dark">
            <div class="mb-4">
                <h2 class="text-base font-semibold">
                    Research Workspace
                </h2>

                <p class="text-sm text-muted-foreground">
                    Manage the research methodology, evidence, findings,
                    surveys, reports, and team.
                </p>
            </div>

            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <Link :href="`/admin/research/${research.id}/methodology`"
                    class="rounded-md border p-4 transition-colors hover:bg-muted/50">
                    <div class="font-medium">
                        Methodology
                    </div>

                    <div class="mt-1 text-sm text-muted-foreground">
                        Research design and methodology.
                    </div>
                </Link>

                <Link :href="`/admin/research/${research.id}/sources`"
                    class="rounded-md border p-4 transition-colors hover:bg-muted/50">
                    <div class="font-medium">
                        Sources
                    </div>

                    <div class="mt-1 text-sm text-muted-foreground">
                        Manage research sources and references.
                    </div>
                </Link>

                <Link :href="`/admin/research/${research.id}/questions`"
                    class="rounded-md border p-4 transition-colors hover:bg-muted/50">
                    <div class="font-medium">
                        Questions
                    </div>

                    <div class="mt-1 text-sm text-muted-foreground">
                        Manage research questions and hypotheses.
                    </div>
                </Link>

                <Link :href="`/admin/research/${research.id}/findings`"
                    class="rounded-md border p-4 transition-colors hover:bg-muted/50">
                    <div class="font-medium">
                        Findings
                    </div>

                    <div class="mt-1 text-sm text-muted-foreground">
                        Manage findings and insights.
                    </div>
                </Link>

                <Link :href="`/admin/research/${research.id}/surveys`"
                    class="rounded-md border p-4 transition-colors hover:bg-muted/50">
                    <div class="font-medium">
                        Surveys
                    </div>

                    <div class="mt-1 text-sm text-muted-foreground">
                        Manage surveys connected to this research.
                    </div>
                </Link>

                <Link :href="`/admin/research/${research.id}/reports`"
                    class="rounded-md border p-4 transition-colors hover:bg-muted/50">
                    <div class="font-medium">
                        Reports
                    </div>

                    <div class="mt-1 text-sm text-muted-foreground">
                        Manage reports produced from this research.
                    </div>
                </Link>

                <Link :href="`/admin/research/${research.id}/team`"
                    class="rounded-md border p-4 transition-colors hover:bg-muted/50">
                    <div class="font-medium">
                        Team
                    </div>

                    <div class="mt-1 text-sm text-muted-foreground">
                        Manage research members and roles.
                    </div>
                </Link>

                <Link :href="`/admin/research/${research.id}/activity`"
                    class="rounded-md border p-4 transition-colors hover:bg-muted/50">
                    <div class="font-medium">
                        Activity
                    </div>

                    <div class="mt-1 text-sm text-muted-foreground">
                        View important research activity.
                    </div>
                </Link>
            </div>
        </section>
    </div>
</template>