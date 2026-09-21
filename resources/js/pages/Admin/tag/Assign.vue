<script setup lang="ts">
    import { computed, ref } from 'vue'
    import { Form, InfiniteScroll, Link } from '@inertiajs/vue3'

    import Button from '@/components/ui/button/Button.vue'
    import { Model, Pagination, Tag } from '@/types'
    import Heading from '@/components/Heading.vue'
    import { AppInput, AppCheckbox } from '@/components/form'
    import { update } from '@/routes/admin/tags/assignment'
    import BackButton from '@/components/ui/BackButton.vue'

    interface Props {
        model: Model
        tags: {
            assigned: {
                data: Tag[]
            }
            options: Pagination<Tag>
        }
    }

    const props = defineProps<Props>()

    const selectedIds = ref<number[]>(
        props.tags.assigned.data.map(tag => tag.id)
    )

    const search = ref('')

    const isSelected = (id: number): boolean => {
        return selectedIds.value.includes(id)
    }

    const toggleTag = (id: number): void => {
        if (isSelected(id)) {
            selectedIds.value = selectedIds.value.filter(
                tagId => tagId !== id
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
        <div class="flex items-center gap-4">
            <BackButton />
            <Heading title="Tags" :description="`Manage tags for ${model.title}`" />
        </div>

        <div class="max-w-3xl">
            <div class="border-b py-4">
                <div class="flex items-center justify-between gap-4">
                    <span class="shrink-0 text-sm text-muted-foreground">
                        {{ selectedCount }} selected
                    </span>
                </div>

                <div class="mt-4">
                    <AppInput v-model="search" placeholder="Search tags..." />
                </div>
            </div>

            <Form v-bind="update.form({type:model.type, id:model.id})" #default="{ processing }">
                <div v-if="tags.options.data.length" class="max-h-[500px] overflow-y-auto divide-y">
                    <InfiniteScroll data="tags.options" only-next :buffer="200" preserve-url>
                        <label v-for="tag in tags.options.data" :key="tag.id"
                            class="flex cursor-pointer items-center gap-3 px-6 py-4 transition-colors hover:bg-muted/50">
                            <AppCheckbox :model-value="isSelected(tag.id)" @update:model-value="toggleTag(tag.id)" />

                            <input type="hidden" name="tag_ids[]" :value="tag.id" :disabled="!isSelected(tag.id)" />

                            <div class="min-w-0">
                                <p class="font-medium">
                                    {{ tag.name }}
                                </p>

                                <p class="text-sm text-muted-foreground">
                                    {{ tag.slug }}
                                </p>
                            </div>
                        </label>
                    </InfiniteScroll>
                </div>

                <div v-else class="px-6 py-12 text-center">
                    <p class="font-medium">
                        No tags available
                    </p>

                    <p class="mt-1 text-sm text-muted-foreground">
                        Create an active tag before assigning it.
                    </p>
                </div>

                <div class="flex justify-end gap-3 border-t px-6 py-4">
                    <Link :href="indexUrl">
                        <Button type="button" variant="outline">
                            Cancel
                        </Button>
                    </Link>

                    <Button type="submit" :disabled="processing">
                        {{ processing ? 'Saving...' : 'Save Tags' }}
                    </Button>
                </div>
            </Form>
        </div>
    </div>
</template>