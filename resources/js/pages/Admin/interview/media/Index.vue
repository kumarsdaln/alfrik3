<script setup lang="ts">
import { Link } from '@inertiajs/vue3'
import { ArrowLeft } from '@lucide/vue'

import MediaUpload from '@/components/media/MediaUpload.vue'
import Button from '@/components/ui/button/Button.vue'
import { Media } from '@/types/media'
import { store as mediaStore } from '@/routes/admin/interviews/media';
import { index as interviewIndex } from '@/routes/interviews'

interface Interview {
    id: number
    title: string
}

interface Props {
    interview: Interview
    media: {
        data: Media[]
    }
}

defineProps<Props>()
</script>

<template>
    <div class="space-y-6 p-6">
        <div class="flex items-center gap-3">
            <Link :href="interviewIndex()">
                <Button variant="outline" size="icon">
                    <ArrowLeft class="size-4" />
                </Button>
            </Link>

            <div>
                <h1 class="text-2xl font-semibold">
                    Interview Media
                </h1>

                <p class="text-sm text-muted-foreground">
                    {{ interview.title }}
                </p>
            </div>
        </div>

        <MediaUpload :action="mediaStore(interview.id).url" />

        <div class="rounded-xl border bg-card">
            <div class="border-b px-6 py-5">
                <h2 class="font-semibold">
                    Uploaded Media
                </h2>

                <p class="mt-1 text-sm text-muted-foreground">
                    Media attached to this interview.
                </p>
            </div>

            <div v-if="media.data.length" class="divide-y">
                <div v-for="item in media.data" :key="item.id" class="flex items-center justify-between px-6 py-4">
                    <div class="min-w-0">
                        <p class="truncate font-medium">
                            {{ item.name }}
                        </p>

                        <p class="text-sm text-muted-foreground">
                            {{ item.file_name }}
                        </p>
                    </div>

                    <div class="text-right text-sm text-muted-foreground">
                        <p>
                            {{ item.collection }}
                        </p>

                        <p>
                            {{ item.mime_type }}
                        </p>
                    </div>
                </div>
            </div>

            <div v-else class="px-6 py-12 text-center text-sm text-muted-foreground">
                No media uploaded yet.
            </div>
        </div>
    </div>
</template>