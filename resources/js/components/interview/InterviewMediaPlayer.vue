<script setup lang="ts">
import { computed } from 'vue'

interface Media {
    media_type: 'video' | 'audio'
    source_type: 'upload' | 'external'
    file_url?: string | null
    embed_url?: string | null
    thumbnail?: string | null
}

const props = defineProps<{ media: Media | null | undefined }>()

/** Normalize common providers (YouTube / Vimeo) to an embeddable iframe src. */
const embedSrc = computed(() => {
    const url = props.media?.embed_url
    if (!url) return null

    const yt = url.match(/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([\w-]{11})/)
    if (yt) return `https://www.youtube.com/embed/${yt[1]}`

    const vm = url.match(/vimeo\.com\/(?:video\/)?(\d+)/)
    if (vm) return `https://player.vimeo.com/video/${vm[1]}`

    return url
})

const isExternal = computed(() => props.media?.source_type === 'external')
</script>

<template>
    <div class="relative aspect-video w-full overflow-hidden bg-black">
        <!-- External video embed -->
        <iframe
            v-if="media?.media_type === 'video' && isExternal && embedSrc"
            :src="embedSrc"
            class="h-full w-full"
            frameborder="0"
            allow="autoplay; fullscreen; picture-in-picture; encrypted-media"
            allowfullscreen
        />

        <!-- Uploaded video -->
        <video
            v-else-if="media?.media_type === 'video' && media?.file_url"
            :src="media.file_url"
            :poster="media.thumbnail || undefined"
            class="h-full w-full object-contain"
            controls
            playsinline
        />

        <!-- Audio (external or uploaded) -->
        <div
            v-else-if="media?.media_type === 'audio'"
            class="flex h-full w-full flex-col items-center justify-center gap-6 bg-zinc-900 p-6"
        >
            <img
                v-if="media?.thumbnail"
                :src="media.thumbnail"
                alt=""
                class="h-32 w-32 rounded-2xl object-cover"
            />
            <iframe
                v-if="isExternal && embedSrc"
                :src="embedSrc"
                class="h-24 w-full"
                frameborder="0"
                allow="autoplay"
            />
            <audio v-else-if="media?.file_url" :src="media.file_url" class="w-full" controls />
        </div>
    </div>
</template>
