<script setup lang="ts">
    import { Form, Link } from '@inertiajs/vue3'

    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'

    import {
        index,
        store,
    } from '@/routes/admin/magazines/issues/articles'

    import type {
        FormOption,
        Magazine,
        MagazineIssue,
    } from '@/types'
    import BackButton from '@/components/ui/BackButton.vue'

    interface Props {
        magazine: Magazine
        issue: MagazineIssue
        statusOptions: FormOption[]
        typeOptions: FormOption[]
    }

    const props = defineProps<Props>()
</script>

<template>
    <div class="space-y-6 py-4">
        <div class="flex gap-4">
            <BackButton />
            <Heading title="Create Magazine Article" :description="`Create a new article for ${props.issue.title}.`" />
        </div>

        <div class="max-w-5xl">
            <Form v-bind="store.form({
                magazine: props.magazine.id,
                issue: props.issue.id,
            })" class="space-y-6" #default="{ errors, processing }">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <AppFormControl label="Title" :error="errors.title" required>
                        <AppInput name="title" placeholder="Enter article title" />
                    </AppFormControl>

                    <AppFormControl label="Slug" :error="errors.slug">
                        <AppInput name="slug" placeholder="article-slug" />
                    </AppFormControl>
                </div>

                <AppFormControl label="Subtitle" :error="errors.subtitle">
                    <AppInput name="subtitle" placeholder="Enter article subtitle" />
                </AppFormControl>

                <AppFormControl label="Excerpt" :error="errors.excerpt">
                    <AppTextarea name="excerpt" :rows="4" placeholder="Short summary of the article..." />
                </AppFormControl>

                <AppFormControl label="Content" :error="errors.content" required>
                    <AppTextarea name="content" :rows="16" placeholder="Write the article content..." />
                </AppFormControl>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <AppFormControl label="Article Type" :error="errors.type" required>
                        <AppSelect name="type" :options="props.typeOptions" placeholder="Select article type" />
                    </AppFormControl>

                    <AppFormControl label="Status" :error="errors.status" required>
                        <AppSelect name="status" :options="props.statusOptions" placeholder="Select status" />
                    </AppFormControl>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <AppFormControl label="Byline" :error="errors.byline">
                        <AppInput name="byline" placeholder="Article byline" />
                    </AppFormControl>

                    <AppFormControl label="Position" :error="errors.position">
                        <AppInput name="position" type="number" min="0" placeholder="0" />
                    </AppFormControl>
                </div>

                <AppFormControl label="Published At" :error="errors.published_at">
                    <AppInput name="published_at" type="datetime-local" />
                </AppFormControl>

                <div class="flex items-center gap-3">
                    <Button type="submit" :disabled="processing">
                        Create Article
                    </Button>

                    <Button type="button" variant="outline" as-child>
                        <Link :href="index({
                            magazine: props.magazine.id,
                            issue: props.issue.id,
                        }).url">
                            Cancel
                        </Link>
                    </Button>
                </div>
            </Form>
        </div>
    </div>
</template>