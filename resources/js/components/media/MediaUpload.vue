<script setup lang="ts">
    import { Form } from '@inertiajs/vue3'
    import { Upload } from '@lucide/vue'

    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import Button from '@/components/ui/button/Button.vue'

    interface Props {
        action: string
        collections?: {
            label: string
            value: string
        }[]
    }

    withDefaults(defineProps<Props>(), {
        collections: () => [
            {
                label: 'Default',
                value: 'default',
            },
        ],
    })
</script>

<template>
    <div class="border bg-card">
        <div class="border-b px-6 py-5">
            <h2 class="font-semibold">
                Upload Media
            </h2>

            <p class="mt-1 text-sm text-muted-foreground">
                Upload an image, video, audio file, or document.
            </p>
        </div>

        <Form :action="action" method="post" enctype="multipart/form-data" #default="{ errors, processing }"
            class="space-y-6 p-6">
            <!-- File -->
            <AppFormControl label="File" :error="errors.file" required>
                <input type="file" name="file" class="
                        block w-full rounded-lg border
                        border-input bg-background
                        px-3 py-2 text-sm
                        file:mr-4 file:rounded-md
                        file:border-0 file:bg-primary
                        file:px-4 file:py-2
                        file:text-sm file:font-medium
                        file:text-primary-foreground
                        hover:file:bg-primary/90
                    " />
            </AppFormControl>

            <!-- Collection -->
            <AppFormControl label="Collection" :error="errors.collection" required>
                <AppSelect name="collection" placeholder="Select collection" :options="collections" />
            </AppFormControl>

            <!-- Name -->
            <AppFormControl label="Name" :error="errors.name">
                <AppInput name="name" placeholder="Enter media name" />
            </AppFormControl>

            <!-- Alt -->
            <AppFormControl label="Alt Text" :error="errors.alt"
                description="Describe the media for accessibility and SEO.">
                <AppInput name="alt" placeholder="Describe this media..." />
            </AppFormControl>

            <!-- Submit -->
            <div class="flex justify-end border-t pt-6">
                <Button type="submit" :disabled="processing" class="gap-2">
                    <Upload class="size-4" />

                    {{ processing ? 'Uploading...' : 'Upload Media' }}
                </Button>
            </div>
        </Form>
        <div class="border-t px-5 py-4">
            <div class="space-y-2 text-xs text-muted-foreground">
                <div class="flex items-center justify-between">
                    <span>Maximum file size</span>
                    <span class="font-medium text-foreground">
                        50 MB
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span>Supported</span>
                    <span class="font-medium text-foreground">
                        Images, Video, Files
                    </span>
                </div>
            </div>
        </div>
    </div>
</template>