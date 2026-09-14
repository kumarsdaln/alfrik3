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
        store as categoryStore,
    } from '@/routes/admin/categories'

    import type { Category } from '@/types'
    import AppToggle from '@/components/form/AppToggle.vue'
import { ref } from 'vue'

    defineProps<{
        parentCategories: {
            data: Category[]
        }
    }>()

    const status = ref(false);
</script>

<template>
    <div class="space-y-6 mt-4">
        <Heading title="Create Category" description="Create a new content category." />

        <Form v-bind="categoryStore.form()" #default="{ errors, processing }" class="max-w-3xl space-y-6">
            <AppFormControl label="Name" :error="errors.name" required>
                <AppInput name="name" placeholder="e.g. Indian Politics" />
            </AppFormControl>

            <AppFormControl label="Slug" :error="errors.slug" required>
                <AppInput name="slug" placeholder="indian-politics" />
            </AppFormControl>

            <AppFormControl label="Description" :error="errors.description">
                <AppTextarea name="description" placeholder="Describe this category..." :rows="4" />
            </AppFormControl>

            <AppFormControl label="Parent Category" :error="errors.parent_id">
                <AppSelect name="parent_id" placeholder="Select parent category" :options="parentCategories.data.map(category => ({
                    label: category.name,
                    value: category.id,
                }))" />
            </AppFormControl>

            <AppFormControl label="Sort Order" :error="errors.sort_order">
                <AppInput name="sort_order" type="number" min="0" value="0" />
            </AppFormControl>

           <AppFormControl label="Status" :error="errors.status">
                <AppToggle name="status" on-value="1" off-value="0"
                    on-label="Active" off-label="Inactive" />
            </AppFormControl>

            <div class="flex justify-end gap-2">
                <Button type="button" variant="outline" @click="router.visit(categoryIndex().url)">
                    Cancel
                </Button>

                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Creating...' : 'Create Category' }}
                </Button>
            </div>
        </Form>
    </div>
</template>