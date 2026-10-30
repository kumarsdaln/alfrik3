<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3'
    import { Pencil } from '@lucide/vue'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import {
        edit,
        index,
    } from '@/routes/admin/report/sections/blocks'

    interface Props {
        report: {
            id: number
            title: string
        }

        section: {
            id: number
            title: string
        }

        block: {
            id: number
            type: {
                value: string
                label: string
            }
            title: string | null
            description: string | null
            content: Record<string, unknown> | null
            position: number
        }
    }

    const props = defineProps<Props>()

    const formatContent = (content: Record<string, unknown> | null) => {
        if (!content) {
            return '{}'
        }

        return JSON.stringify(content, null, 2)
    }
</script>

<template>

    <Head :title="`${block.title || block.type.label} - Content Block`" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between gap-4 py-5">
            <div class="flex items-center gap-4">
                <BackButton />

                <Heading title="Content Block" :description="`${report.title} / ${section.title}`" />
            </div>

            <Button as-child class="gap-2">
                <Link :href="edit({
                    report: report.id,
                    section: section.id,
                    block: block.id,
                })">
                    <Pencil class="size-4" />
                    Edit Block
                </Link>
            </Button>
        </div>

        <!-- Block Information -->
        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Main -->
            <div class="space-y-6 lg:col-span-2">
                <div class="rounded-lg border bg-background">
                    <div class="border-b px-6 py-5">
                        <div class="flex items-start justify-between gap-4">
                            <div class="space-y-1">
                                <h2 class="text-lg font-semibold">
                                    {{ block.title || 'Untitled Block' }}
                                </h2>

                                <p v-if="block.description" class="text-sm text-muted-foreground">
                                    {{ block.description }}
                                </p>
                            </div>

                            <Badge variant="secondary">
                                {{ block.type.label }}
                            </Badge>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6">
                        <div class="mb-3">
                            <h3 class="text-sm font-medium">
                                Content
                            </h3>

                            <p class="text-sm text-muted-foreground">
                                Structured data stored for this content block.
                            </p>
                        </div>

                        <pre
                            class="overflow-x-auto rounded-lg border bg-muted/30 p-5 text-sm leading-6"><code>{{ formatContent(block.content) }}</code></pre>
                    </div>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="space-y-6">
                <div class="rounded-lg border bg-background">
                    <div class="border-b px-6 py-5">
                        <h2 class="font-semibold">
                            Block Information
                        </h2>
                    </div>

                    <div class="divide-y">
                        <div class="px-6 py-4">
                            <p class="text-sm text-muted-foreground">
                                Type
                            </p>

                            <div class="mt-1">
                                <Badge variant="secondary">
                                    {{ block.type.label }}
                                </Badge>
                            </div>
                        </div>

                        <div class="px-6 py-4">
                            <p class="text-sm text-muted-foreground">
                                Position
                            </p>

                            <p class="mt-1 font-medium">
                                {{ block.position }}
                            </p>
                        </div>

                        <div class="px-6 py-4">
                            <p class="text-sm text-muted-foreground">
                                Section
                            </p>

                            <p class="mt-1 font-medium">
                                {{ section.title }}
                            </p>
                        </div>

                        <div class="px-6 py-4">
                            <p class="text-sm text-muted-foreground">
                                Report
                            </p>

                            <p class="mt-1 font-medium">
                                {{ report.title }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <div class="rounded-lg border bg-background p-6">
                    <Heading title="Navigation" description="Return to the content blocks for this section." />

                    <Button variant="outline" as-child class="mt-5 w-full">
                        <Link :href="index({
                            report: report.id,
                            section: section.id,
                        })">
                            Back to Content Blocks
                        </Link>
                    </Button>
                </div>
            </div>
        </div>
    </div>
</template>