<script setup lang="ts">
    import { Form, Link } from '@inertiajs/vue3'
    import { ArrowLeft } from '@lucide/vue'

    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import Button from '@/components/ui/button/Button.vue'

    import { store } from '@/routes/admin/magazines'

    import type { FormOption, Profile } from '@/types'
    import Heading from '@/components/Heading.vue'
    import AppCheckbox from '@/components/form/AppCheckbox.vue'

    interface Props {
        authors: Profile[]
        statusOptions: FormOption[]
    }

    defineProps<Props>()
</script>

<template>
    <div class="flex items-center justify-between gap-4 py-5">
        <Heading title="Create Magazine" description="Create a new magazine publication." />

        <Button as-child variant="outline" class="gap-2">
            <Link href="/admin/magazines">
                <ArrowLeft class="size-4" />
                Back
            </Link>
        </Button>
    </div>

    <Form v-bind="store.form()" v-slot="{ errors, processing }" class="space-y-6">

        <div class="grid gap-6">
            <AppFormControl label="Title" :error="errors.title" required>
                <AppInput name="title" placeholder="Enter magazine title" />
            </AppFormControl>

            <AppFormControl label="Slug" :error="errors.slug" required>
                <AppInput name="slug" placeholder="magazine-slug" />
            </AppFormControl>

            <AppFormControl label="Subtitle" :error="errors.subtitle">
                <AppInput name="subtitle" placeholder="Enter magazine subtitle" />
            </AppFormControl>

            <AppFormControl label="Description" :error="errors.description">
                <AppTextarea name="description" placeholder="Describe the magazine..." :rows="7" />
            </AppFormControl>
        </div>


        <div class="grid gap-6 md:grid-cols-2">
            <AppFormControl label="Author" :error="errors.author_id">
                <AppSelect name="author_id" placeholder="Select author" :options="authors.map(author => ({
                    label: author.name,
                    value: String(author.id),
                }))
                    " />
            </AppFormControl>

            <AppFormControl label="Status" :error="errors.status" required>
                <AppSelect name="status" placeholder="Select status" :options="statusOptions" />
            </AppFormControl>

            <AppFormControl label="Published At" :error="errors.published_at">
                <AppInput name="published_at" type="datetime-local" />
            </AppFormControl>

            <AppFormControl label="Featured" :error="errors.featured">
                <AppCheckbox name="featured" true-value="1" false-value="0" label="Feature this magazine" />
            </AppFormControl>
        </div>

        <!-- Actions -->
        <div class="flex justify-end gap-3 border-t pt-6">
            <Button as-child variant="outline">
                <Link href="/admin/magazines">
                    Cancel
                </Link>
            </Button>

            <Button type="submit" :disabled="processing">
                {{ processing ? 'Creating...' : 'Create Magazine' }}
            </Button>
        </div>
    </Form>
</template>