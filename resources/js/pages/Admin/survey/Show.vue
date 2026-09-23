<script setup lang="ts">
    import { Link } from '@inertiajs/vue3'
    import {
        BarChart3,
        ClipboardList,
        FileText,
        ListChecks,
        Pencil,
        Users,
    } from '@lucide/vue'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import type { Survey } from '@/types'

    interface Props {
        survey: Survey
    }

    defineProps<Props>()
</script>

<template>
    <div class="space-y-6 py-5">

        <!-- Header -->

        <div class="flex items-start justify-between gap-4">
            <div class="flex gap-4">
                <BackButton href="/admin/survey" />

                <Heading title="Survey"
                    description="Manage survey configuration, questions, responses, and analytics." />
            </div>

            <Button variant="outline" as-child>
                <Link :href="`/admin/survey/${survey.id}/edit`">
                    <Pencil class="mr-2 h-4 w-4" />
                    Edit Survey
                </Link>
            </Button>
        </div>

        <!-- Survey Overview -->

        <div class="rounded-lg border border-border-light bg-background p-6 dark:border-border-dark">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="space-y-2">
                    <h2 class="text-xl font-semibold">
                        {{ survey.title }}
                    </h2>

                    <p v-if="survey.description" class="max-w-3xl text-sm leading-6 text-muted-foreground">
                        {{ survey.description }}
                    </p>

                    <div class="flex flex-wrap gap-2">
                        <Badge>
                            {{ survey.status.label }}
                        </Badge>

                        <Badge v-if="survey.anonymous" variant="outline">
                            Anonymous
                        </Badge>

                        <Badge v-if="survey.multiple_responses" variant="outline">
                            Multiple Responses
                        </Badge>

                        <Badge v-if="survey.featured" variant="outline">
                            Featured
                        </Badge>
                    </div>
                </div>

                <div class="text-right">
                    <div class="text-2xl font-semibold">
                        {{ survey.response_count }}
                    </div>

                    <div class="text-sm text-muted-foreground">
                        Responses
                    </div>
                </div>
            </div>
        </div>

        <!-- Survey Information -->

        <div class="rounded-lg border border-border-light bg-background p-6 dark:border-border-dark">
            <h2 class="font-semibold">
                Survey Information
            </h2>

            <dl class="mt-5 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                <div>
                    <dt class="text-xs text-muted-foreground">
                        Slug
                    </dt>

                    <dd class="mt-1 text-sm font-medium">
                        {{ survey.slug }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs text-muted-foreground">
                        Responses
                    </dt>

                    <dd class="mt-1 text-sm font-medium">
                        {{ survey.response_count }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs text-muted-foreground">
                        Starts
                    </dt>

                    <dd class="mt-1 text-sm font-medium">
                        {{
                            survey.starts_at
                                ? new Date(
                                    survey.starts_at
                                ).toLocaleString()
                                : '—'
                        }}
                    </dd>
                </div>

                <div>
                    <dt class="text-xs text-muted-foreground">
                        Ends
                    </dt>

                    <dd class="mt-1 text-sm font-medium">
                        {{
                            survey.ends_at
                                ? new Date(
                                    survey.ends_at
                                ).toLocaleString()
                                : '—'
                        }}
                    </dd>
                </div>
            </dl>
        </div>

        <!-- Survey Workspace -->

        <div>
            <div class="mb-4">
                <h2 class="font-semibold">
                    Survey Workspace
                </h2>

                <p class="mt-1 text-sm text-muted-foreground">
                    Build the survey, collect responses, and analyze results.
                </p>
            </div>

            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">

                <!-- Sections -->

                <Link :href="`/admin/survey/${survey.id}/sections`"
                    class="group rounded-lg border border-border-light bg-background p-6 transition-colors hover:bg-muted/40 dark:border-border-dark">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-md border border-border-light dark:border-border-dark">
                        <ListChecks class="h-5 w-5" />
                    </div>

                    <h3 class="mt-4 font-semibold">
                        Sections
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-muted-foreground">
                        Organize the survey into logical sections.
                    </p>
                </Link>

                <!-- Questions -->

                <Link :href="`/admin/survey/${survey.id}/questions`"
                    class="group rounded-lg border border-border-light bg-background p-6 transition-colors hover:bg-muted/40 dark:border-border-dark">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-md border border-border-light dark:border-border-dark">
                        <ClipboardList class="h-5 w-5" />
                    </div>

                    <h3 class="mt-4 font-semibold">
                        Questions
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-muted-foreground">
                        Create and organize survey questions and answer options.
                    </p>
                </Link>

                <!-- Responses -->

                <Link :href="`/admin/survey/${survey.id}/responses`"
                    class="group rounded-lg border border-border-light bg-background p-6 transition-colors hover:bg-muted/40 dark:border-border-dark">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-md border border-border-light dark:border-border-dark">
                        <Users class="h-5 w-5" />
                    </div>

                    <h3 class="mt-4 font-semibold">
                        Responses
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-muted-foreground">
                        Review submitted survey responses.
                    </p>
                </Link>

                <!-- Analytics -->

                <Link :href="`/admin/survey/${survey.id}/analytics`"
                    class="group rounded-lg border border-border-light bg-background p-6 transition-colors hover:bg-muted/40 dark:border-border-dark">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-md border border-border-light dark:border-border-dark">
                        <BarChart3 class="h-5 w-5" />
                    </div>

                    <h3 class="mt-4 font-semibold">
                        Analytics
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-muted-foreground">
                        Analyze response data and question-level results.
                    </p>
                </Link>

                <!-- Public Survey -->

                <Link :href="`/survey/${survey.slug}`" target="_blank"
                    class="group rounded-lg border border-border-light bg-background p-6 transition-colors hover:bg-muted/40 dark:border-border-dark">
                    <div
                        class="flex h-10 w-10 items-center justify-center rounded-md border border-border-light dark:border-border-dark">
                        <FileText class="h-5 w-5" />
                    </div>

                    <h3 class="mt-4 font-semibold">
                        Public Survey
                    </h3>

                    <p class="mt-1 text-sm leading-6 text-muted-foreground">
                        Open the public survey and test the respondent experience.
                    </p>
                </Link>

            </div>
        </div>

    </div>
</template>