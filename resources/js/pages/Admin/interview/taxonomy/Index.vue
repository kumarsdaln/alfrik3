<script setup lang="ts">
    import { Form, router } from '@inertiajs/vue3'
    import { ref } from 'vue'

    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'

    import { edit as interviewEdit } from '@/routes/admin/interviews'
    import { update as taxonomyUpdate } from '@/routes/admin/interviews/taxonomy'

    import type { Category, Tag, Pagination } from '@/types'

    interface Props {
        interview: {
            id: number
            title: string
        }

        categories: Pagination<Category>
        tags: Pagination<Tag>

        selectedCategoryIds: number[]
        selectedTagIds: number[]
    }

    const props = defineProps<Props>()

    const categoryIds = ref([
        ...props.selectedCategoryIds,
    ])

    const tagIds = ref([
        ...props.selectedTagIds,
    ])

    const categorySearch = ref('')
    const tagSearch = ref('')

    const toggleCategory = (id: number) => {
        if (categoryIds.value.includes(id)) {
            categoryIds.value = categoryIds.value.filter(
                value => value !== id
            )

            return
        }

        categoryIds.value.push(id)
    }

    const toggleTag = (id: number) => {
        if (tagIds.value.includes(id)) {
            tagIds.value = tagIds.value.filter(
                value => value !== id
            )

            return
        }

        tagIds.value.push(id)
    }

    const searchCategories = () => {
        router.get(
            window.location.pathname,
            {
                category_search: categorySearch.value || undefined,
                tag_search: tagSearch.value || undefined,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        )
    }

    const searchTags = () => {
        router.get(
            window.location.pathname,
            {
                category_search: categorySearch.value || undefined,
                tag_search: tagSearch.value || undefined,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,
            }
        )
    }

    const backToInterview = () => {
        router.visit(
            interviewEdit(props.interview.id).url
        )
    }
</script>

<template>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
            <Heading title="Categories & Tags" :description="`Manage categories and tags for ${interview.title}`" />

            <Button type="button" variant="outline" @click="backToInterview">
                Back
            </Button>
        </div>

        <Form :action="taxonomyUpdate(interview.id)" method="put" #default="{ processing }">
            <!-- Hidden category IDs -->
            <input v-for="id in categoryIds" :key="`category-${id}`" type="hidden" name="category_ids[]" :value="id" />

            <!-- Hidden tag IDs -->
            <input v-for="id in tagIds" :key="`tag-${id}`" type="hidden" name="tag_ids[]" :value="id" />

            <div class="grid gap-6 lg:grid-cols-2">
                <!-- Categories -->
                <div class="border bg-card">
                    <div class="border-b p-5 sm:p-6">
                        <h2 class="text-lg font-semibold">
                            Categories
                        </h2>

                        <p class="mt-1 text-sm text-muted-foreground">
                            Select categories for this interview.
                        </p>
                    </div>

                    <div class="p-5 sm:p-6">
                        <div class="flex gap-2">
                            <input v-model="categorySearch" type="text"
                                class="flex-1 rounded-md border bg-background px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-ring"
                                placeholder="Search categories..." @keyup.enter="searchCategories" />

                            <Button type="button" variant="outline" @click="searchCategories">
                                Search
                            </Button>
                        </div>

                        <div class="mt-4 divide-y rounded-md border">
                            <button v-for="category in categories.data" :key="category.id" type="button"
                                class="flex w-full items-center gap-3 p-3 text-left hover:bg-muted/50"
                                @click="toggleCategory(category.id)">
                                <span class="flex size-4 shrink-0 items-center justify-center rounded border" :class="{
                                    'border-primary bg-primary text-primary-foreground':
                                        categoryIds.includes(category.id),
                                }">
                                    <svg v-if="categoryIds.includes(category.id)" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                        class="size-3">
                                        <path d="m5 12 4 4L19 7" />
                                    </svg>
                                </span>

                                <span class="truncate text-sm">
                                    {{ category.name }}
                                </span>
                            </button>

                            <div v-if="!categories.data.length" class="p-6 text-center text-sm text-muted-foreground">
                                No categories found.
                            </div>
                        </div>

                        <p class="mt-3 text-xs text-muted-foreground">
                            {{ categoryIds.length }}
                            selected
                        </p>
                    </div>
                </div>

                <!-- Tags -->
                <div class="border bg-card">
                    <div class="border-b p-5 sm:p-6">
                        <h2 class="text-lg font-semibold">
                            Tags
                        </h2>

                        <p class="mt-1 text-sm text-muted-foreground">
                            Select tags for this interview.
                        </p>
                    </div>

                    <div class="p-5 sm:p-6">
                        <div class="flex gap-2">
                            <input v-model="tagSearch" type="text"
                                class="flex-1 rounded-md border bg-background px-3 py-2 text-sm outline-none focus:ring-2 focus:ring-ring"
                                placeholder="Search tags..." @keyup.enter="searchTags" />

                            <Button type="button" variant="outline" @click="searchTags">
                                Search
                            </Button>
                        </div>

                        <div class="mt-4 divide-y rounded-md border">
                            <button v-for="tag in tags.data" :key="tag.id" type="button"
                                class="flex w-full items-center gap-3 p-3 text-left hover:bg-muted/50"
                                @click="toggleTag(tag.id)">
                                <span class="flex size-4 shrink-0 items-center justify-center rounded border" :class="{
                                    'border-primary bg-primary text-primary-foreground':
                                        tagIds.includes(tag.id),
                                }">
                                    <svg v-if="tagIds.includes(tag.id)" xmlns="http://www.w3.org/2000/svg"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3"
                                        class="size-3">
                                        <path d="m5 12 4 4L19 7" />
                                    </svg>
                                </span>

                                <span class="truncate text-sm">
                                    {{ tag.name }}
                                </span>
                            </button>

                            <div v-if="!tags.data.length" class="p-6 text-center text-sm text-muted-foreground">
                                No tags found.
                            </div>
                        </div>

                        <p class="mt-3 text-xs text-muted-foreground">
                            {{ tagIds.length }}
                            selected
                        </p>
                    </div>
                </div>
            </div>

            <!-- Save -->
            <div class="mt-6 flex justify-end">
                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Saving...' : 'Save Changes' }}
                </Button>
            </div>
        </Form>
    </div>
</template>