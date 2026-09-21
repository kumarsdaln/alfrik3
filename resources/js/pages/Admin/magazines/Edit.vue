```vue
<script setup lang="ts">
    import { computed, ref } from 'vue'
    import { Form, Link } from '@inertiajs/vue3'

    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import { update } from '@/routes/admin/magazines'

    import type {
        FormOption,
        Magazine,
        Profile,
    } from '@/types'

    interface Props {
        magazine: Magazine
        authors: Profile[]
        statusOptions: FormOption[]
    }

    const props = defineProps<Props>()

    const selectedAuthor = ref<string | number>(
        props.magazine.author_id
            ? String(props.magazine.author_id)
            : '',
    )

    const authorOptions = computed<FormOption[]>(() => {
        return props.authors.map((author) => ({
            label: author.name,
            value: String(author.id),
        }))
    })

    const publishedAt = computed(() => {
        if (!props.magazine.published_at) {
            return ''
        }

        return props.magazine.published_at.slice(0, 16)
    })
</script>

<template>
    <!-- Header -->
    <div class="flex items-center gap-4 py-5">
        <BackButton />
        <Heading title="Edit Magazine" description="Update magazine information and publishing settings." />
    </div>

    <div class="py-6">
        <Form v-bind="update.form(props.magazine.id)" :options="{
            preserveScroll: true,
        }" #default="{ errors, processing }" class="space-y-6">
            <!-- Basic Information -->
            <div>
                <div class="border-b py-5">
                    <h2 class="font-semibold">
                        Basic Information
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Update the magazine's basic information.
                    </p>
                </div>

                <div class="grid gap-6 py-6">
                    <AppFormControl label="Title" :error="errors.title" required>
                        <AppInput name="title" :default-value="props.magazine.title"
                            placeholder="Enter magazine title" />
                    </AppFormControl>

                    <AppFormControl label="Slug" :error="errors.slug" required>
                        <AppInput name="slug" :default-value="props.magazine.slug" placeholder="magazine-slug" />
                    </AppFormControl>

                    <AppFormControl label="Subtitle" :error="errors.subtitle">
                        <AppInput name="subtitle" :default-value="props.magazine.subtitle ?? ''"
                            placeholder="Enter magazine subtitle" />
                    </AppFormControl>

                    <AppFormControl label="Description" :error="errors.description">
                        <AppTextarea name="description" :default-value="props.magazine.description ?? ''"
                            placeholder="Describe the magazine..." :rows="7" />
                    </AppFormControl>
                </div>
            </div>

            <!-- Publishing -->
            <div>
                <div class="border-b py-5">
                    <h2 class="font-semibold">
                        Publishing
                    </h2>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Update ownership and publication settings.
                    </p>
                </div>

                <div class="grid gap-6 py-6 md:grid-cols-2">
                    <AppFormControl label="Author" :error="errors.author_id">
                        <AppSelect v-model="selectedAuthor" name="author_id" placeholder="Select author"
                            :options="authorOptions" />
                    </AppFormControl>

                    <AppFormControl label="Status" :error="errors.status" required>
                        <AppSelect name="status" placeholder="Select status"
                            :default-value="props.magazine.status.value" :options="statusOptions" />
                    </AppFormControl>

                    <AppFormControl label="Published At" :error="errors.published_at">
                        <AppInput name="published_at" type="datetime-local" :default-value="publishedAt" />
                    </AppFormControl>

                    <AppFormControl label="Featured" :error="errors.featured">
                        <label class="flex items-center gap-3 pt-2 text-sm">
                            <input type="checkbox" name="featured" value="1" :checked="props.magazine.featured"
                                class="size-4 rounded border-input" />

                            <span>
                                Feature this magazine
                            </span>
                        </label>
                    </AppFormControl>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col-reverse gap-3 border-t pt-6 sm:flex-row sm:justify-end">
                <Button as-child variant="outline">
                    <Link href="/admin/magazines">
                        Cancel
                    </Link>
                </Button>

                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Saving...' : 'Save Changes' }}
                </Button>
            </div>
        </Form>
    </div>
</template>