<script setup lang="ts">
    import { Form, Link } from '@inertiajs/vue3'
    import {
        FileText,
        Music,
    } from '@lucide/vue'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Input from '@/components/ui/input/Input.vue'
    import Label from '@/components/ui/label/Label.vue'

    import type { Media, Model } from '@/types'
    import { update } from '@/routes/admin/media'

    interface Props {
        model: Model
        media: {
            data: Media
        }
    }

    const props = defineProps<Props>()

    const indexUrl = `/admin/media/${props.model.type}/${props.model.id}`

    const isImage = (media: Media): boolean => {
        return media.mime_type?.startsWith('image/') ?? false
    }

    const isVideo = (media: Media): boolean => {
        return media.mime_type?.startsWith('video/') ?? false
    }

    const isAudio = (media: Media): boolean => {
        return media.mime_type?.startsWith('audio/') ?? false
    }

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
</script>

<template>
    <div class="min-h-[calc(100vh-64px)]">

        <!-- Header -->
        <div class="border-b py-4">
            <div class="flex items-center gap-3 sm:gap-4">
                <BackButton />

                <Heading title="Edit Media" :description="`Edit media for ${props.model.title}`" />
            </div>
        </div>

        <!-- Content -->
        <div class="py-4">
            <div class="max-w-3xl">

                <Form v-bind="update.form({ type: model.type, id: model.id, mediaId: media.data.id })"
                    #default="{ processing, errors }">
                    <div class="space-y-6">

                        <!-- Preview -->
                        <div class="overflow-hidden border bg-card">

                            <div class="flex min-h-[240px] items-center justify-center bg-muted sm:min-h-[360px]">

                                <!-- Image -->
                                <img v-if="isImage(media.data)" :src="media.data.url"
                                    :alt="media.data.alt ?? media.data.name"
                                    class="max-h-[360px] max-w-full object-contain" />

                                <!-- Video -->
                                <video v-else-if="isVideo(media.data)" :src="media.data.url" controls
                                    class="max-h-[360px] max-w-full" />

                                <!-- Audio -->
                                <div v-else-if="isAudio(media.data)"
                                    class="flex w-full flex-col items-center gap-5 px-4">
                                    <div class="flex size-16 items-center justify-center border bg-background">
                                        <Music class="size-7 text-muted-foreground" />
                                    </div>

                                    <audio :src="media.data.url" controls class="w-full max-w-md" />
                                </div>

                                <!-- Other File -->
                                <div v-else class="flex flex-col items-center gap-3 px-4 text-center">
                                    <div class="flex size-16 items-center justify-center border bg-background">
                                        <FileText class="size-7 text-muted-foreground" />
                                    </div>

                                    <div class="min-w-0">
                                        <p class="break-all font-medium">
                                            {{ media.data.file_name }}
                                        </p>

                                        <p v-if="media.data.mime_type" class="mt-1 text-sm text-muted-foreground">
                                            {{ media.data.mime_type }}
                                        </p>
                                    </div>
                                </div>

                            </div>

                            <!-- File Information -->
                            <div class="grid grid-cols-2 border-t sm:grid-cols-4">

                                <!-- File -->
                                <div class="min-w-0 border-r px-4 py-3">
                                    <p class="text-xs text-muted-foreground">
                                        File
                                    </p>

                                    <p class="mt-1 truncate text-sm font-medium" :title="media.data.file_name">
                                        {{ media.data.file_name }}
                                    </p>
                                </div>

                                <!-- Type -->
                                <div class="min-w-0 px-4 py-3 sm:border-r">
                                    <p class="text-xs text-muted-foreground">
                                        Type
                                    </p>

                                    <p class="mt-1 text-sm font-medium">
                                        {{
                                            media.data.extension
                                                ?.toUpperCase()
                                            || 'FILE'
                                        }}
                                    </p>
                                </div>

                                <!-- Size -->
                                <div class="border-r border-t px-4 py-3 sm:border-t-0">
                                    <p class="text-xs text-muted-foreground">
                                        Size
                                    </p>

                                    <p class="mt-1 text-sm font-medium">
                                        {{ formatSize(media.data.size) }}
                                    </p>
                                </div>

                                <!-- Collection -->
                                <div class="border-t px-4 py-3 sm:border-t-0">
                                    <p class="text-xs text-muted-foreground">
                                        Collection
                                    </p>

                                    <p class="mt-1 truncate text-sm font-medium" :title="media.data.collection">
                                        {{ media.data.collection }}
                                    </p>
                                </div>

                            </div>
                        </div>

                        <!-- Media Information -->
                        <div class="border bg-card">

                            <!-- Section Header -->
                            <div class="border-b px-4 py-4 sm:px-5">
                                <h2 class="font-medium">
                                    Media Information
                                </h2>

                                <p class="mt-1 text-sm text-muted-foreground">
                                    Update the metadata associated with this
                                    media file.
                                </p>
                            </div>

                            <!-- Form Fields -->
                            <div class="space-y-5 p-4 sm:p-5">

                                <!-- Name -->
                                <div class="space-y-2">
                                    <Label for="name">
                                        Name
                                    </Label>

                                    <Input id="name" name="name" :default-value="media.data.name" />

                                    <p v-if="errors.name" class="text-sm text-destructive">
                                        {{ errors.name }}
                                    </p>
                                </div>

                                <!-- Collection -->
                                <div class="space-y-2">
                                    <Label for="collection">
                                        Collection
                                    </Label>

                                    <Input id="collection" name="collection" :default-value="media.data.collection" />

                                    <p v-if="errors.collection" class="text-sm text-destructive">
                                        {{ errors.collection }}
                                    </p>

                                    <p class="text-xs text-muted-foreground">
                                        Use a collection name to organize
                                        related media files.
                                    </p>
                                </div>

                                <!-- Alt Text -->
                                <div class="space-y-2">
                                    <Label for="alt">
                                        Alt Text
                                    </Label>

                                    <Input id="alt" name="alt" :default-value="media.data.alt ?? ''" />

                                    <p v-if="errors.alt" class="text-sm text-destructive">
                                        {{ errors.alt }}
                                    </p>

                                    <p class="text-xs text-muted-foreground">
                                        Describe the image for accessibility
                                        and SEO.
                                    </p>
                                </div>

                            </div>

                            <!-- Actions -->
                            <div
                                class="flex flex-col-reverse gap-3 border-t px-4 py-4 sm:flex-row sm:justify-end sm:px-5">
                                <Link :href="indexUrl">
                                    <Button type="button" variant="outline" class="w-full sm:w-auto">
                                        Cancel
                                    </Button>
                                </Link>

                                <Button type="submit" :disabled="processing" class="w-full sm:w-auto">
                                    {{
                                        processing
                                            ? 'Saving...'
                                            : 'Save Changes'
                                    }}
                                </Button>
                            </div>

                        </div>
                    </div>
                </Form>
            </div>
        </div>
    </div>
</template>