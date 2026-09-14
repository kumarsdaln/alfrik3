<script setup lang="ts">
import { Form, router } from '@inertiajs/vue3'

import Heading from '@/components/Heading.vue'
import AppFormControl from '@/components/form/AppFormControl.vue'
import AppInput from '@/components/form/AppInput.vue'
import AppTextarea from '@/components/form/AppTextarea.vue'
import AppToggle from '@/components/form/AppToggle.vue'
import Button from '@/components/ui/button/Button.vue'

import {
    index as tagIndex,
    store as tagStore,
} from '@/routes/admin/tags'
</script>

<template>
    <div class="space-y-6 mt-4">
        <Heading
            title="Create Tag"
            description="Create a new content tag."
        />

        <Form
            v-bind="tagStore.form()"
            #default="{ errors, processing }"
            class="max-w-3xl space-y-6"
        >
            <AppFormControl
                label="Name"
                :error="errors.name"
                required
            >
                <AppInput
                    name="name"
                    placeholder="e.g. Indian Politics"
                />
            </AppFormControl>

            <AppFormControl
                label="Slug"
                :error="errors.slug"
                required
            >
                <AppInput
                    name="slug"
                    placeholder="indian-politics"
                />
            </AppFormControl>

            <AppFormControl
                label="Description"
                :error="errors.description"
            >
                <AppTextarea
                    name="description"
                    placeholder="Describe this tag..."
                    :rows="4"
                />
            </AppFormControl>

            <AppFormControl :error="errors.status">
                <AppToggle name="status" on-value="1" off-value="0"
                    on-label="Active" off-label="Inactive" />
            </AppFormControl>

            <div class="flex justify-end gap-2">
                <Button
                    type="button"
                    variant="outline"
                    @click="router.visit(tagIndex().url)"
                >
                    Cancel
                </Button>

                <Button
                    type="submit"
                    :disabled="processing"
                >
                    {{ processing ? 'Creating...' : 'Create Tag' }}
                </Button>
            </div>
        </Form>
    </div>
</template>