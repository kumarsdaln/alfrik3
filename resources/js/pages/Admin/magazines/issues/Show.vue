<script setup lang="ts">
    import { Link } from '@inertiajs/vue3'

    import Date from '@/components/datadisplay/Date.vue'
    import AppText from '@/components/ui/AppText.vue'
    import Badge from '@/components/ui/badge/Badge.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import {
        edit,
        index,
    } from '@/routes/admin/magazines/issues'

    import type {
        Magazine,
        MagazineIssue,
    } from '@/types'

    interface Props {
        magazine: Magazine
        issue: {
            data: MagazineIssue
        }
    }

    defineProps<Props>()
</script>

<template>
    <TableLayout>
        <template #header>
            <div class="flex items-center justify-between gap-4 py-5">
                <div class="flex items-center gap-4">
                    <BackButton />

                    <Heading
                        :title="issue.data.title"
                        :description="`Magazine issue of ${magazine.title}.`"
                    />
                </div>

                <div class="flex items-center gap-2">
                    <Button
                        variant="outline"
                        as-child
                    >
                        <Link :href="index(magazine.id).url">
                            Back to Issues
                        </Link>
                    </Button>

                    <Button as-child>
                        <Link :href="edit({magazine:magazine.id, issue:issue.data.id}).url">
                            Edit Issue
                        </Link>
                    </Button>
                </div>
            </div>
        </template>

        <div class="py-6">
            <div class="max-w-6xl space-y-8">
                <!-- Overview -->
                <section class="rounded-lg border bg-card">
                    <div class="border-b px-6 py-4">
                        <AppText class="font-semibold">
                            Issue Overview
                        </AppText>
                    </div>

                    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-2 lg:grid-cols-3">
                        <div>
                            <AppText class="text-sm text-muted-foreground">
                                Title
                            </AppText>

                            <AppText class="mt-1 font-medium">
                                {{ issue.data.title }}
                            </AppText>
                        </div>

                        <div>
                            <AppText class="text-sm text-muted-foreground">
                                Slug
                            </AppText>

                            <AppText class="mt-1">
                                {{ issue.data.slug || '—' }}
                            </AppText>
                        </div>

                        <div>
                            <AppText class="text-sm text-muted-foreground">
                                Status
                            </AppText>

                            <div class="mt-2">
                                <Badge :color="issue.data.status.color">
                                    {{ issue.data.status.label }}
                                </Badge>
                            </div>
                        </div>

                        <div>
                            <AppText class="text-sm text-muted-foreground">
                                Volume
                            </AppText>

                            <AppText class="mt-1">
                                {{ issue.data.volume ?? '—' }}
                            </AppText>
                        </div>

                        <div>
                            <AppText class="text-sm text-muted-foreground">
                                Issue Number
                            </AppText>

                            <AppText class="mt-1">
                                {{ issue.data.issue_number ?? '—' }}
                            </AppText>
                        </div>

                        <div>
                            <AppText class="text-sm text-muted-foreground">
                                Editor
                            </AppText>

                            <AppText class="mt-1">
                                {{ issue.data.editor || '—' }}
                            </AppText>
                        </div>

                        <div>
                            <AppText class="text-sm text-muted-foreground">
                                Cover Date
                            </AppText>

                            <div class="mt-1">
                                <Date
                                    v-if="issue.data.cover_date"
                                    :value="issue.data.cover_date"
                                />

                                <span v-else>—</span>
                            </div>
                        </div>

                        <div>
                            <AppText class="text-sm text-muted-foreground">
                                Published At
                            </AppText>

                            <div class="mt-1">
                                <Date
                                    v-if="issue.data.published_at"
                                    :value="issue.data.published_at"
                                />

                                <span v-else>—</span>
                            </div>
                        </div>

                        <div>
                            <AppText class="text-sm text-muted-foreground">
                                Downloads
                            </AppText>

                            <AppText class="mt-1 font-medium">
                                {{ issue.data.download_count ?? 0 }}
                            </AppText>
                        </div>
                    </div>
                </section>

                <!-- Subtitle & Description -->
                <section
                    v-if="issue.data.subtitle || issue.data.description"
                    class="rounded-lg border bg-card"
                >
                    <div class="border-b px-6 py-4">
                        <AppText class="font-semibold">
                            About This Issue
                        </AppText>
                    </div>

                    <div class="space-y-6 p-6">
                        <div v-if="issue.data.subtitle">
                            <AppText class="text-sm text-muted-foreground">
                                Subtitle
                            </AppText>

                            <AppText class="mt-1">
                                {{ issue.data.subtitle }}
                            </AppText>
                        </div>

                        <div v-if="issue.data.description">
                            <AppText class="text-sm text-muted-foreground">
                                Description
                            </AppText>

                            <div
                                class="mt-2 whitespace-pre-line text-sm leading-6"
                            >
                                {{ issue.data.description }}
                            </div>
                        </div>
                    </div>
                </section>

                <!-- File -->
                <section class="rounded-lg border bg-card">
                    <div class="border-b px-6 py-4">
                        <AppText class="font-semibold">
                            Issue File
                        </AppText>
                    </div>

                    <div class="grid grid-cols-1 gap-6 p-6 md:grid-cols-3">
                        <div>
                            <AppText class="text-sm text-muted-foreground">
                                File
                            </AppText>

                            <AppText class="mt-1">
                                {{ issue.data.file_path || 'No file uploaded' }}
                            </AppText>
                        </div>

                        <div>
                            <AppText class="text-sm text-muted-foreground">
                                File Type
                            </AppText>

                            <AppText class="mt-1">
                                {{ issue.data.file_type || '—' }}
                            </AppText>
                        </div>

                        <div>
                            <AppText class="text-sm text-muted-foreground">
                                File Size
                            </AppText>

                            <AppText class="mt-1">
                                {{ issue.data.file_size || '—' }}
                            </AppText>
                        </div>
                    </div>
                </section>

                <!-- Articles -->
                <section class="rounded-lg border bg-card">
                    <div class="flex items-center justify-between border-b px-6 py-4">
                        <div>
                            <AppText class="font-semibold">
                                Articles
                            </AppText>

                            <AppText class="mt-1 text-sm text-muted-foreground">
                                Articles included in this issue.data.
                            </AppText>
                        </div>

                        <Button
                            variant="outline"
                            as-child
                        >
                            <Link
                                :href="`/admin/magazines/${magazine.id}/issues/${issue.data.id}/articles`"
                            >
                                Manage Articles
                            </Link>
                        </Button>
                    </div>

                    <div
                        v-if="issue.data.articles?.length"
                        class="divide-y"
                    >
                        <div
                            v-for="article in issue.data.articles"
                            :key="article.id"
                            class="flex items-center justify-between gap-4 px-6 py-4"
                        >
                            <div class="min-w-0">
                                <AppText class="font-medium truncate">
                                    {{ article.title }}
                                </AppText>

                                <AppText class="text-sm text-muted-foreground">
                                    {{ article.type ?? 'Article' }}
                                </AppText>
                            </div>

                            <Badge
                                v-if="article.status"
                                :color="article.status.color"
                            >
                                {{ article.status.label }}
                            </Badge>
                        </div>
                    </div>

                    <div
                        v-else
                        class="px-6 py-8 text-center"
                    >
                        <AppText class="text-sm text-muted-foreground">
                            No articles have been added to this issue yet.
                        </AppText>
                    </div>
                </section>
            </div>
        </div>
    </TableLayout>
</template>