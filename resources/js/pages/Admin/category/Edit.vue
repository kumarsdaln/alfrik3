<script setup lang="ts">
    import { Form, router } from '@inertiajs/vue3'

    import Heading from '@/components/Heading.vue'
    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import AppSelect from '@/components/form/AppSelect.vue'
    import Button from '@/components/ui/button/Button.vue'

    import {
        index as categoryIndex,
        update as categoryUpdate,
    } from '@/routes/admin/categories'

    import type { Category } from '@/types'
    import AppToggle from '@/components/form/AppToggle.vue'
    import { ref } from 'vue'

    const props = defineProps<{
        category: {
            data: Category
        }
        parentCategories: {
            data: Category[]
        }
    }>()

    const parentId = ref<number>(
        props.category.data.parent?.id ?? ''
    )

    const description = ref<string>(
        props.category.data.description ?? ''
    )
</script>

<template>
    <div class="space-y-6 mt-4">
        <Heading title="Edit Category" :description="`Edit ${category.data.name}`" />

        <Form v-bind="categoryUpdate.form(category.data.id)" #default="{ errors, processing }"
            class="max-w-3xl space-y-6">
            <AppFormControl label="Name" :error="errors.name" required>
                <AppInput name="name" v-model="category.data.name" />
            </AppFormControl>

            <AppFormControl label="Slug" :error="errors.slug" required>
                <AppInput name="slug" v-model="category.data.slug" />
            </AppFormControl>

            <AppFormControl label="Description" :error="errors.description">
                <AppTextarea name="description" v-model="description" :rows="4" />
            </AppFormControl>

            <AppFormControl label="Parent Category" :error="errors.parent_id">
                <AppSelect name="parent_id" v-model="parentId" placeholder="Select parent category" :options="parentCategories.data.map(parent => ({
                    label: parent.name,
                    value: parent.id,
                }))" />
            </AppFormControl>

            <AppFormControl label="Sort Order" :error="errors.sort_order">
                <AppInput name="sort_order" type="number" min="0" v-model="category.data.sort_order" />
            </AppFormControl>

            <AppFormControl :error="errors.status">
                <AppToggle name="status" v-model="category.data.status" on-value="1" off-value="0"
                    on-label="Active" off-label="Inactive" />
            </AppFormControl>

            <div class="flex justify-end gap-2">
                <Button type="button" variant="outline" @click="router.visit(categoryIndex().url)">
                    Cancel
                </Button>

                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Saving...' : 'Save Changes' }}
                </Button>
            </div>
        </Form>
    </div>
</template>