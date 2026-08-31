<script setup lang="ts">
import { computed } from 'vue'

interface Media {
    media_type: 'video' | 'audio'
    source_type: 'upload' | 'external'
    file_url?: string | null
    embed_url?: string | null
    thumbnail?: string | null
}

interface Props {
    media?: Media | null
}

const props = defineProps<Props>()


/*
|--------------------------------------------------------------------------
| Media
|--------------------------------------------------------------------------
*/

const media = computed(() => props.media)

const isVideo = computed(
    () => media.value?.media_type === 'video',
)

const isAudio = computed(
    () => media.value?.media_type === 'audio',
)

const isExternal = computed(
    () => media.value?.source_type === 'external',
)

const hasFile = computed(
    () => !!media.value?.file_url,
)


/*
|--------------------------------------------------------------------------
| External Embed
|--------------------------------------------------------------------------
*/

const embedSrc = computed(() => {
    const url = media.value?.embed_url?.trim()

    if (!url) {
        return null
    }

    /*
    |--------------------------------------------------------------------------
    | YouTube
    |--------------------------------------------------------------------------
    */

    const youtube = url.match(
        /(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([\w-]{11})/,
    )

    if (youtube?.[1]) {
        return `https://www.youtube.com/embed/${youtube[1]}`
    }


    /*
    |--------------------------------------------------------------------------
    | Vimeo
    |--------------------------------------------------------------------------
    */

    const vimeo = url.match(
        /(?:vimeo\.com\/(?:video\/)?)(\d+)/,
    )

    if (vimeo?.[1]) {
        return `https://player.vimeo.com/video/${vimeo[1]}`
    }


    /*
    |--------------------------------------------------------------------------
    | Custom Embed
    |--------------------------------------------------------------------------
    */

    return url
})


/*
|--------------------------------------------------------------------------
| Visibility
|--------------------------------------------------------------------------
*/

const hasExternalEmbed = computed(
    () => isExternal.value && !!embedSrc.value,
)

const hasMedia = computed(
    () =>
        hasExternalEmbed.value ||
        hasFile.value,
)
</script>


<template>

    <!-- No media -->

    <div
        v-if="!media || !hasMedia"
        class="
            flex
            aspect-video
            w-full
            items-center
            justify-center
            bg-muted
        "
    >
        <span class="text-sm text-muted-foreground">
            No media available
        </span>
    </div>


    <!-- Video -->

    <div
        v-else-if="isVideo"
        class="
            relative
            aspect-video
            w-full
            overflow-hidden
            bg-black
        "
    >

        <!-- External video -->

        <iframe
            v-if="hasExternalEmbed"
            :src="embedSrc!"
            title="Interview video"
            class="h-full w-full"
            allow="autoplay; fullscreen; picture-in-picture; encrypted-media"
            allowfullscreen
            loading="lazy"
            referrerpolicy="strict-origin-when-cross-origin"
        />


        <!-- Uploaded video -->

        <video
            v-else-if="media.file_url"
            :src="media.file_url"
            :poster="media.thumbnail || undefined"
            class="
                h-full
                w-full
                object-contain
            "
            controls
            playsinline
            preload="metadata"
        />

    </div>


    <!-- Audio -->

    <div
        v-else-if="isAudio"
        class="
            w-full
            overflow-hidden
            border
            border-border
            bg-muted/30
        "
    >

        <div
            class="
                flex
                min-h-48
                flex-col
                items-center
                justify-center
                gap-6
                p-6
                sm:min-h-56
                sm:p-10
            "
        >

            <!-- Thumbnail -->

            <img
                v-if="media.thumbnail"
                :src="media.thumbnail"
                alt=""
                class="
                    size-28
                    rounded-lg
                    object-cover
                    sm:size-32
                "
            />


            <!-- External audio -->

            <iframe
                v-if="hasExternalEmbed"
                :src="embedSrc!"
                title="Interview audio"
                class="
                    h-20
                    w-full
                    max-w-2xl
                "
                allow="autoplay"
                loading="lazy"
                referrerpolicy="strict-origin-when-cross-origin"
            />


            <!-- Uploaded audio -->

            <audio
                v-else-if="media.file_url"
                :src="media.file_url"
                class="w-full max-w-2xl"
                controls
                preload="metadata"
            />

        </div>

    </div>

</template>