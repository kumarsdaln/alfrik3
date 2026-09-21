<script setup lang="ts">
    import { Form, Link } from '@inertiajs/vue3'

    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import AppHeading from '@/components/ui/AppHeading.vue'
    import AppText from '@/components/ui/AppText.vue'
    import Button from '@/components/ui/button/Button.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import { index } from '@/routes/admin/magazines/issues/articles'

    import type {
        FormOption,
        Magazine,
        MagazineArticle,
        MagazineIssue,
    } from '@/types'

    interface Props {
        magazine: Magazine
        issue: MagazineIssue
        article: MagazineArticle
        statusOptions: FormOption[]
        typeOptions: FormOption[]
    }

    const props = defineProps<Props>()
</script>

<template>
    <TableLayout>
        <template #header>
            <div>
                <AppHeading>
                    Edit Magazine Article
                </AppHeading>

                <AppText class="mt-1">
                    Update {{ article.title }} in {{ issue.title }}.
                </AppText>
            </div>
        </template>

        <div class="max-w-5xl">
            <Form :action="`/admin/magazines/${magazine.id}/issues/${issue.id}/articles/${article.id}`" method="put"
                class="space-y-6" #default="{ errors, processing }">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <AppFormControl label="Title" :error="errors.title" required>
                        <AppInput name="title" :default-value="article.title" placeholder="Enter article title" />
                    </AppFormControl>

                    <AppFormControl label="Slug" :error="errors.slug">
                        <AppInput name="slug" :default-value="article.slug" placeholder="article-slug" />
                    </AppFormControl>
                </div>

                <AppFormControl label="Subtitle" :error="errors.subtitle">
                    <AppInput name="subtitle" :default-value="article.subtitle ?? ''"
                        placeholder="Enter article subtitle" />
                </AppFormControl>

                <AppFormControl label="Excerpt" :error="errors.excerpt">
                    <AppTextarea name="excerpt" rows="4" :default-value="article.excerpt ?? ''"
                        placeholder="Short summary of the article..." />
                </AppFormControl>

                <AppFormControl label="Content" :error="errors.content" required>
                    <AppTextarea name="content" rows="18" :default-value="article.content ?? ''"
                        placeholder="Write the article content..." />
                </AppFormControl>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <AppFormControl label="Article Type" :error="errors.type" required>
                        <AppSelect name="type" :options="typeOptions" :default-value="article.type"
                            placeholder="Select article type" />
                    </AppFormControl>

                    <AppFormControl label="Status" :error="errors.status" required>
                        <AppSelect name="status" :options="statusOptions" :default-value="article.status"
                            placeholder="Select status" />
                    </AppFormControl>
                </div>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <AppFormControl label="Byline" :error="errors.byline">
                        <AppInput name="byline" :default-value="article.byline ?? ''" placeholder="Article byline" />
                    </AppFormControl>

                    <AppFormControl label="Position" :error="errors.position">
                        <AppInput name="position" type="number" min="0" :default-value="article.position"
                            placeholder="0" />
                    </AppFormControl>
                </div>

                <AppFormControl label="Published At" :error="errors.published_at">
                    <AppInput name="published_at" type="datetime-local" :default-value="article.published_at ?? ''" />
                </AppFormControl>

                <div class="flex items-center gap-3">
                    <Button type="submit" :disabled="processing">
                        Update Article
                    </Button>

                    <Button type="button" variant="outline" as-child>
                        <Link :href="index(props.magazine.id, props.issue.id).url">
                            Cancel
                        </Link>
                    </Button>
                </div>
            </Form>
        </div>
    </TableLayout>
</template>