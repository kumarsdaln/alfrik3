<script setup lang="ts">
    import { Form, router } from '@inertiajs/vue3'
    import { ArrowLeft, Trash2 } from '@lucide/vue'

    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'

    import {
        edit as mediaEdit,
        update as mediaUpdate,
        destroy as mediaDestroy,
    } from '@/routes/admin/media'

    import type { Media } from '@/types'

    interface Props {
        media: {
            data: Media
        }
    }

    const props = defineProps<Props>()

    function goBack() {
        router.visit(
            mediaEdit(props.media.data.id).url
        )
    }

    function deleteMedia() {
        if (
            !confirm(
                'Are you sure you want to delete this media? This action cannot be undone.'
            )
        ) {
            return
        }

        router.delete(
            mediaDestroy(props.media.data.id).url,
            {
                preserveScroll: true,
                onSuccess: () => {
                    router.visit('/admin/media')
                },
            },
        )
    }
</script>

<template>
    <div class="space-y-8">

        <!-- Header -->
        <div class="flex items-start gap-4">
            <Button type="button" variant="outline" size="icon" class="shrink-0" @click="goBack">
                <ArrowLeft class="size-4" />

                <span class="sr-only">
                    Back
                </span>
            </Button>

            <Heading title="Edit Media" description="Update media information and metadata." />
        </div>

        <!-- Preview / Information -->
        <section class="rounded-xl border bg-card">
            <div class="border-b px-6 py-5">
                <h2 class="font-semibold">
                    Media Information
                </h2>

                <p class="mt-1 text-sm text-muted-foreground">
                    Information about the stored file.
                </p>
            </div>

            <div class="grid gap-6 p-6 sm:grid-cols-2 lg:grid-cols-4">

                <div>
                    <p class="text-xs font-medium text-muted-foreground">
                        File Name
                    </p>

                    <p class="mt-1 truncate text-sm font-medium">
                        {{ media.data.file_name }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium text-muted-foreground">
                        Type
                    </p>

                    <p class="mt-1 text-sm">
                        {{ media.data.mime_type }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium text-muted-foreground">
                        Extension
                    </p>

                    <p class="mt-1 text-sm uppercase">
                        {{ media.data.extension ?? '—' }}
                    </p>
                </div>

                <div>
                    <p class="text-xs font-medium text-muted-foreground">
                        Size
                    </p>

                    <p class="mt-1 text-sm">
                        {{ media.data.size }}
                    </p>
                </div>

            </div>
        </section>

        <!-- Form -->
        <section class="rounded-xl border bg-card">
            <div class="border-b px-6 py-5">
                <h2 class="font-semibold">
                    Media Details
                </h2>

                <p class="mt-1 text-sm text-muted-foreground">
                    Update how this media is identified and organized.
                </p>
            </div>

            <Form v-bind="mediaUpdate(media.data.id).form()" :options="{
                preserveScroll: true,
            }" #default="{ errors, processing }" class="space-y-6 p-6">
                <AppFormControl label="Name" :error="errors.name">
                    <AppInput name="name" :default-value="media.data.name" placeholder="Enter media name" />
                </AppFormControl>

                <AppFormControl label="Collection" :error="errors.collection" required>
                    <AppInput name="collection" :default-value="media.data.collection" placeholder="default" />
                </AppFormControl>

                <AppFormControl label="Alt Text" :error="errors.alt"
                    description="Describe the media for accessibility and SEO.">
                    <AppTextarea name="alt" :default-value="media.data.alt ?? ''" placeholder="Describe this media..."
                        :rows="4" />
                </AppFormControl>

                <div class="flex items-center justify-between gap-3 border-t pt-6">
                    <Button type="button" variant="outline" @click="goBack">
                        Cancel
                    </Button>

                    <Button type="submit" :disabled="processing">
                        {{ processing ? 'Updating...' : 'Update Media' }}
                    </Button>
                </div>
            </Form>
        </section>

        <!-- Danger Zone -->
        <section class="
                rounded-xl
                border border-destructive/30
                bg-destructive/5
            ">
            <div class="flex items-center justify-between gap-6 p-6">
                <div>
                    <h2 class="font-semibold text-destructive">
                        Delete Media
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Permanently delete this media file and its database record.
                    </p>
                </div>

                <Button type="button" variant="destructive" class="shrink-0 gap-2" @click="deleteMedia">
                    <Trash2 class="size-4" />
                    Delete Media
                </Button>
            </div>
        </section>

    </div>
</template>