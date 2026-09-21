<script setup lang="ts">
    import { computed, ref } from 'vue'
    import { Form, InfiniteScroll, Link } from '@inertiajs/vue3'

    import AppInput from '@/components/form/AppInput.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Checkbox from '@/components/ui/checkbox/Checkbox.vue'
    import Heading from '@/components/Heading.vue'

    import type { Category } from '@/types'

    import { index, update } from '@/routes/admin/categories/assignment'
    import { useInfiniteFilters } from '@/composables/useInfiniteFilters'
    import BackButton from '@/components/ui/BackButton.vue'

    interface Model {
        type: string | number
        id: number
        title: string
    }

    interface Props {
        model: Model

        categories: {
            assigned: {
                data: Category[]
            }

            options: {
                data: Category[]
            }
        }
    }

    const props = defineProps<Props>()

    const indexUrl = index({
        type: props.model.type,
        id: props.model.id,
    }).url

    const selectedIds = ref<number[]>(
        props.categories.assigned.data.map(
            category => category.id
        )
    )

    const {
        filters,
    } = useInfiniteFilters({
        url: indexUrl,

        dataKey: 'categories.options',

        initialFilters: {
            search: '',
        },

        debounce: 300,
    })

    const isSelected = (id: number) => {
        return selectedIds.value.includes(id)
    }

    const toggleCategory = (id: number) => {
        if (isSelected(id)) {
            selectedIds.value = selectedIds.value.filter(
                categoryId => categoryId !== id
            )

            return
        }

        selectedIds.value.push(id)
    }

    const selectedCount = computed(
        () => selectedIds.value.length
    )
</script>

<template>
    <div class="space-y-6 py-6">
        <!-- Heading -->
        <div class="flex items-center gap-4">
            <BackButton />
            <Heading title="Assign Categories" :description="`Manage categories for ${props.model.title}`" />
        </div>

        <div class="flex max-w-3xl flex-col overflow-hidden">
            <!-- Header -->
            <div class="shrink-0 border-b py-4">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-muted-foreground">
                        {{ selectedCount }} selected
                    </span>
                </div>
            </div>

            <!-- Search -->
            <div class="shrink-0 border-b py-4">
                <AppInput v-model="filters.search" placeholder="Search categories..." />
            </div>

            <Form v-bind="update.form({
                type: props.model.type,
                id: props.model.id,
            })" method="put" v-slot="{ processing }" class="flex min-h-0 flex-col">
                <!-- Scroll area -->
                <div v-if="categories.options.data.length" class="max-h-[500px] overflow-y-auto">
                    <InfiniteScroll data="categories.options" only-next :buffer="200" preserve-url>
                        <div class="divide-y">
                            <label v-for="category in categories.options.data" :key="category.id"
                                class="flex cursor-pointer items-center gap-3 px-6 py-4 transition-colors hover:bg-muted/50">
                                <Checkbox :model-value="isSelected(category.id)"
                                    @update:model-value="toggleCategory(category.id)" />

                                <input type="hidden" name="category_ids[]" :value="category.id"
                                    :disabled="!isSelected(category.id)">

                                <div class="min-w-0">
                                    <p class="font-medium">
                                        {{ category.name }}
                                    </p>

                                    <p class="text-sm text-muted-foreground">
                                        {{ category.slug }}
                                    </p>
                                </div>
                            </label>
                        </div>

                        <template #loading>
                            <div class="px-6 py-4 text-center text-sm text-muted-foreground">
                                Loading more categories...
                            </div>
                        </template>
                    </InfiniteScroll>
                </div>

                <!-- Empty -->
                <div v-else class="px-6 py-12 text-center">
                    <p class="font-medium">
                        No categories found
                    </p>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Try a different search term.
                    </p>
                </div>

                <!-- Actions -->
                <div class="shrink-0 border-t bg-card px-6 py-4">
                    <div class="flex justify-end gap-3">
                        <Link :href="indexUrl">
                            <Button type="button" variant="outline">
                                Cancel
                            </Button>
                        </Link>

                        <Button type="submit" :disabled="processing">
                            {{ processing ? 'Saving...' : 'Save Categories' }}
                        </Button>
                    </div>
                </div>
            </Form>
        </div>
    </div>
</template>