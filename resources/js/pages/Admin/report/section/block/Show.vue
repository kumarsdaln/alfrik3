
<script setup lang="ts">
import { computed } from 'vue'
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
import { ReportContentBlock } from '@/types'

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
        data: ReportContentBlock
    }
}

const { report, section, block } = defineProps<Props>()
console.log('block', block)
const blockTitle = computed(
    () => block.data.title || block.data.type.label || 'Untitled Block'
)

const formattedContent = computed(() => {
    return JSON.stringify(block.data.content ?? {}, null, 2)
})

const editUrl = computed(() =>
    edit({
        report: report.id,
        section: section.id,
        block: block.data.id,
    })
)

const indexUrl = computed(() =>
    index({
        report: report.id,
        section: section.id,
    })
)
</script>

<template>
    <Head :title="`${blockTitle} - Content Block`" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between gap-4 border-b py-5">
            <div class="flex items-center gap-4">
                <BackButton />

                <Heading
                    title="Content Block"
                    :description="`${report.title} / ${section.title}`"
                />
            </div>

            <Button as-child class="gap-2">
                <Link :href="editUrl">
                    <Pencil class="size-4" />
                    Edit Block
                </Link>
            </Button>
        </div>

        <div class="grid gap-6 lg:grid-cols-3">
            <!-- Main Content -->
            <div class="space-y-6 lg:col-span-2">
                <section class="border bg-background">
                    <div class="flex items-start justify-between gap-4 border-b p-6">
                        <div class="min-w-0 space-y-2">
                            <h2 class="text-lg font-semibold">
                                {{ blockTitle }}
                            </h2>

                            <p
                                v-if="block.data.description"
                                class="text-sm text-muted-foreground"
                            >
                                {{ block.data.description }}
                            </p>
                        </div>

                        <Badge variant="secondary">
                            {{ block.data.type.label }}
                        </Badge>
                    </div>

                    <div class="space-y-4 p-6">
                        <div>
                            <h3 class="text-sm font-medium">
                                Content
                            </h3>

                            <p class="mt-1 text-sm text-muted-foreground">
                                Structured data stored for this content block.
                            </p>
                        </div>

                        <pre
                            class="overflow-x-auto border bg-muted/30 p-5 text-sm leading-6"
                        ><code>{{ formattedContent }}</code></pre>
                    </div>
                </section>
            </div>

            <!-- Sidebar -->
            <aside class="space-y-6">
                <section class="border bg-background">
                    <div class="border-b p-6">
                        <h2 class="font-semibold">
                            Block Information
                        </h2>
                    </div>

                    <div class="divide-y">
                        <div class="p-5">
                            <p class="text-sm text-muted-foreground">
                                Type
                            </p>

                            <div class="mt-2">
                                <Badge variant="secondary">
                                    {{ block.data.type.label }}
                                </Badge>
                            </div>
                        </div>

                        <div class="p-5">
                            <p class="text-sm text-muted-foreground">
                                Position
                            </p>

                            <p class="mt-1 font-medium">
                                {{ block.data.position }}
                            </p>
                        </div>

                        <div class="p-5">
                            <p class="text-sm text-muted-foreground">
                                Section
                            </p>

                            <p class="mt-1 font-medium">
                                {{ section.title }}
                            </p>
                        </div>

                        <div class="p-5">
                            <p class="text-sm text-muted-foreground">
                                Report
                            </p>

                            <p class="mt-1 font-medium">
                                {{ report.title }}
                            </p>
                        </div>

                        <div class="p-5">
                            <p class="text-sm text-muted-foreground">
                                Block ID
                            </p>

                            <p class="mt-1 font-medium tabular-nums">
                                #{{ block.data.id }}
                            </p>
                        </div>
                    </div>
                </section>

                <!-- Navigation -->
                <section class="border bg-background p-6">
                    <Heading
                        title="Navigation"
                        description="Return to the content blocks for this section."
                    />

                    <Button
                        variant="outline"
                        as-child
                        class="mt-5 w-full"
                    >
                        <Link :href="indexUrl">
                            Back to Content Blocks
                        </Link>
                    </Button>
                </section>
            </aside>
        </div>
    </div>
</template>