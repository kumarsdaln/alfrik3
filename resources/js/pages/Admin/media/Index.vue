<script setup lang="ts">
    import { Link, router } from '@inertiajs/vue3'
    import {
        MoreHorizontal,
        Pencil,
        Trash2,
        FileText,
        Image,
        Video,
        Music,
    } from '@lucide/vue'

    import Date from '@/components/datadisplay/Date.vue'
    import AppPagination from '@/components/ui/AppPagination.vue'
    import Button from '@/components/ui/button/Button.vue'
    import MediaUpload from '@/components/media/MediaUpload.vue'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'

    import type { Media, Model, Pagination } from '@/types'
    import { edit } from '@/routes/admin/media'

    interface Props {
        model: Model
        media: Pagination<Media>
    }

    const props = defineProps<Props>()

    const mediaUrl = `/admin/media/${props.model.type}/${props.model.id}`

    const formatSize = (size: number): string => {
        if (size < 1024) {
            return `${size} B`
        }

        if (size < 1024 * 1024) {
            return `${(size / 1024).toFixed(1)} KB`
        }

        if (size < 1024 * 1024 * 1024) {
            return `${(size / (1024 * 1024)).toFixed(1)} MB`
        }

        return `${(size / (1024 * 1024 * 1024)).toFixed(1)} GB`
    }

    const deleteMedia = (media: Media) => {
        if (!confirm(`Are you sure you want to delete "${media.name}"?`)) {
            return
        }

        router.delete(`${mediaUrl}/${media.id}`, {
            preserveScroll: true,
        })
    }

    const isImage = (media: Media): boolean => {
        return media.mime_type.startsWith('image/')
    }

    const isVideo = (media: Media): boolean => {
        return media.mime_type.startsWith('video/')
    }

    const isAudio = (media: Media): boolean => {
        return media.mime_type.startsWith('audio/')
    }
</script>

<template>
    <div class="flex min-h-[calc(100vh-64px)] flex-col">

        <!-- Header -->
        <div class="shrink-0 border-b px-4 py-4 sm:px-6">
            <div class="flex items-start gap-3 sm:items-center sm:gap-4">
                <BackButton />

                <Heading title="Media" :description="`Media files for ${props.model.title}`" />
            </div>
        </div>

        <!-- Content -->
        <div class="flex-1 py-4 sm:py-6">

            <div class="grid gap-4 lg:grid-cols-[320px_minmax(0,1fr)] lg:gap-6">

                <!-- Upload Panel -->
                <aside>
                    <div class="bg-card lg:sticky lg:top-4">
                        <MediaUpload :action="mediaUrl" />
                    </div>
                </aside>

                <!-- Media Library -->
                <section class="flex min-w-0 flex-col border bg-card">

                    <!-- Library Header -->
                    <div
                        class="flex flex-col gap-3 border-b px-4 py-3 sm:flex-row sm:items-center sm:justify-between sm:px-5 sm:py-4">
                        <div>
                            <h2 class="font-medium">
                                Media Library
                            </h2>

                            <p class="mt-1 text-sm text-muted-foreground">
                                {{ props.media.meta.total }}
                                {{
                                    props.media.meta.total === 1
                                        ? 'file'
                                        : 'files'
                                }}
                            </p>
                        </div>

                        <div v-if="props.media.data.length" class="text-xs text-muted-foreground sm:text-sm">
                            Page {{ props.media.meta.current_page }}
                            of {{ props.media.meta.last_page }}
                        </div>
                    </div>

                    <!-- Media Grid -->
                    <div class="p-4 sm:p-5">

                        <div v-if="props.media.data.length"
                            class="grid grid-cols-2 gap-3 sm:grid-cols-3 sm:gap-4 xl:grid-cols-4 2xl:grid-cols-5">

                            <!-- Media Item -->
                            <div v-for="item in props.media.data" :key="item.id"
                                class="group min-w-0 overflow-hidden border bg-background">

                                <!-- Preview -->
                                <div class="relative aspect-square overflow-hidden bg-muted">

                                    <!-- Image -->
                                    <img v-if="isImage(item)" :src="item.url" :alt="item.alt ?? item.name"
                                        class="h-full w-full object-cover transition-transform duration-200 sm:group-hover:scale-105" />

                                    <!-- Video -->
                                    <video v-else-if="isVideo(item)" :src="item.url" class="h-full w-full object-cover"
                                        muted preload="metadata" />

                                    <!-- Audio -->
                                    <div v-else-if="isAudio(item)"
                                        class="flex h-full w-full flex-col items-center justify-center gap-2">
                                        <div
                                            class="flex size-12 items-center justify-center rounded-lg border bg-background sm:size-16">
                                            <Music class="size-5 text-muted-foreground sm:size-7" />
                                        </div>

                                        <span class="text-xs text-muted-foreground">
                                            Audio
                                        </span>
                                    </div>

                                    <!-- Other File -->
                                    <div v-else class="flex h-full w-full flex-col items-center justify-center gap-2">
                                        <div
                                            class="flex size-12 items-center justify-center rounded-lg border bg-background sm:size-16">
                                            <FileText class="size-5 text-muted-foreground sm:size-7" />
                                        </div>

                                        <span class="text-xs text-muted-foreground">
                                            {{
                                                item.extension?.toUpperCase()
                                                || 'FILE'
                                            }}
                                        </span>
                                    </div>

                                    <!-- Actions -->
                                    <div
                                        class="absolute right-2 top-2 opacity-100 sm:opacity-0 sm:transition-opacity sm:group-hover:opacity-100">
                                        <div class="flex items-center border bg-background/95 shadow-sm">
                                            <Button variant="ghost" size="icon" class="size-8">
                                                <Link :href="edit.url({
                                                    type: model.type,
                                                    id: model.id,
                                                    mediaId: item.id,
                                                })">
                                                    <Pencil class="size-4" />
                                                </Link>
                                            </Button>

                                            <Button variant="ghost" size="icon"
                                                class="size-8 text-destructive hover:text-destructive"
                                                @click="deleteMedia(item)">
                                                <Trash2 class="size-4" />
                                            </Button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Details -->
                                <div class="space-y-1.5 p-2.5 sm:space-y-2 sm:p-3">

                                    <div class="min-w-0">
                                        <p class="truncate text-xs font-medium sm:text-sm" :title="item.name">
                                            {{ item.name }}
                                        </p>

                                        <p class="truncate text-[11px] text-muted-foreground sm:text-xs"
                                            :title="item.file_name">
                                            {{ item.file_name }}
                                        </p>
                                    </div>

                                    <div
                                        class="flex items-center justify-between gap-2 text-[11px] text-muted-foreground sm:text-xs">
                                        <span>
                                            {{ formatSize(item.size) }}
                                        </span>

                                        <span class="shrink-0">
                                            {{ item.extension?.toUpperCase() }}
                                        </span>
                                    </div>

                                    <div class="truncate text-[11px] text-muted-foreground sm:text-xs">
                                        <Date :value="item.created_at" />
                                    </div>

                                </div>
                            </div>
                        </div>

                        <!-- Empty -->
                        <div v-else
                            class="flex min-h-[300px] items-center justify-center border border-dashed px-4 sm:min-h-[400px]">
                            <div class="text-center">
                                <div
                                    class="mx-auto mb-4 flex size-12 items-center justify-center border bg-muted sm:size-14">
                                    <Image class="size-5 text-muted-foreground sm:size-6" />
                                </div>

                                <h3 class="font-medium">
                                    No media yet
                                </h3>

                                <p class="mt-1 text-sm text-muted-foreground">
                                    Upload your first media file using the
                                    upload area.
                                </p>
                            </div>
                        </div>

                    </div>

                    <!-- Pagination -->
                    <div v-if="props.media.data.length" class="border-t px-4 py-3 sm:px-5">
                        <AppPagination :meta="props.media.meta" />
                    </div>

                </section>
            </div>
        </div>
    </div>
</template>