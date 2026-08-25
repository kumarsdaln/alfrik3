<script setup lang="ts">
import { computed, ref } from 'vue'
import { Form, router } from '@inertiajs/vue3'

import AppInput from '@/components/form/AppInput.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import AppFileUpload from '@/components/form/AppFileUpload.vue'
import AppButton from '@/Components/Ui/AppButton.vue'
import InterviewMediaPlayer from '@/components/interview/InterviewMediaPlayer.vue'

import { store as mediaStore, destroy as mediaDestroy } from '@/routes/admin/interviews/media'

interface Media {
    id: number
    media_type: 'video' | 'audio'
    source_type: 'upload' | 'external'
    file_url?: string | null
    embed_url?: string | null
    thumbnail?: string | null
    duration?: number | null
}

const props = defineProps<{ interview: { id: number; media?: Media[] } }>()

const current = computed<Media | null>(() => props.interview.media?.[0] ?? null)

const mediaType = ref<'video' | 'audio'>(current.value?.media_type ?? 'video')
const sourceType = ref<'external' | 'upload'>(current.value?.source_type ?? 'external')

function removeMedia() {
    if (!current.value) return
    router.delete(mediaDestroy([props.interview.id, current.value.id]).url, { preserveScroll: true })
}
</script>

<template>
    <div class="rounded-2xl border border-gray-200 bg-white p-6 dark:border-zinc-800 dark:bg-zinc-900">
        <h2 class="mb-5 text-xl font-semibold text-zinc-900 dark:text-zinc-100">Interview Media</h2>

        <!-- Current media preview -->
        <div v-if="current" class="mb-6 space-y-3">
            <InterviewMediaPlayer :media="current" />
            <div class="flex items-center justify-between text-sm text-zinc-500 dark:text-zinc-400">
                <span class="capitalize">{{ current.media_type }} &middot; {{ current.source_type }}</span>
                <button type="button" class="font-medium text-red-500 hover:text-red-600" @click="removeMedia">
                    Remove
                </button>
            </div>
        </div>

        <!-- Add / replace media -->
        <Form :action="mediaStore(interview.id)" enctype="multipart/form-data">
            <template #default="{ errors, processing }">
                <div class="space-y-5">
                    <AppSelect
                        name="media_type"
                        label="Media Type"
                        v-model="mediaType"
                        :options="[
                            { value: 'video', label: 'Video' },
                            { value: 'audio', label: 'Audio' },
                        ]"
                        :error="errors.media_type"
                    />

                    <div>
                        <span class="mb-1.5 block text-sm font-medium text-zinc-700 dark:text-zinc-300">Source</span>
                        <div class="flex gap-6">
                            <label class="flex items-center gap-2 text-sm">
                                <input type="radio" name="source_type" value="external" v-model="sourceType" class="text-brand focus:ring-brand/20" />
                                External embed (YouTube / Vimeo)
                            </label>
                            <label class="flex items-center gap-2 text-sm">
                                <input type="radio" name="source_type" value="upload" v-model="sourceType" class="text-brand focus:ring-brand/20" />
                                Upload file
                            </label>
                        </div>
                    </div>

                    <AppInput
                        v-if="sourceType === 'external'"
                        name="embed_url"
                        label="Embed URL"
                        placeholder="https://www.youtube.com/watch?v=…"
                        :model-value="current?.embed_url ?? ''"
                        :error="errors.embed_url"
                    />
                    <AppFileUpload
                        v-else
                        name="file"
                        label="Media File"
                        description="MP4/WebM/MOV for video, MP3/WAV/M4A for audio (max 100MB)."
                        :error="errors.file"
                    />

                    <AppFileUpload
                        name="thumbnail"
                        label="Thumbnail (optional)"
                        :existing-image="current?.thumbnail"
                        :error="errors.thumbnail"
                    />

                    <AppInput
                        name="duration"
                        type="number"
                        label="Duration (seconds, optional)"
                        :model-value="current?.duration ?? ''"
                        :error="errors.duration"
                    />

                    <AppButton variant="submit" type="submit" :loading="processing" :disabled="processing">
                        {{ current ? 'Update Media' : 'Add Media' }}
                    </AppButton>
                </div>
            </template>
        </Form>
    </div>
</template>
