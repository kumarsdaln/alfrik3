<script setup lang="ts">
    import { Form, router } from '@inertiajs/vue3'

    import Heading from '@/components/Heading.vue'
    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import AppToggle from '@/components/form/AppToggle.vue'
    import Button from '@/components/ui/button/Button.vue'

    import {
        index as participantRoleIndex,
        store as participantRoleStore,
    } from '@/routes/admin/participants/roles'

</script>

<template>
    <div class="mt-4 space-y-6">
        <Heading title="Create Participant Role"
            description="Create a role for people contributing to research, reports, interviews, magazines, surveys, and events." />

        <Form v-bind="participantRoleStore.form()" #default="{ errors, processing }" class="max-w-3xl space-y-6">
            <AppFormControl label="Name" :error="errors.name" required>
                <AppInput name="name" placeholder="e.g. Researcher" />
            </AppFormControl>

            <AppFormControl label="Slug" :error="errors.slug" required>
                <AppInput name="slug" placeholder="e.g. researcher" />
            </AppFormControl>

            <AppFormControl label="Description" :error="errors.description">
                <AppTextarea name="description" placeholder="Describe the responsibilities of this role..." :rows="4" />
            </AppFormControl>

            <AppFormControl label="Sort Order" :error="errors.position">
                <AppInput name="position" type="number" min="0" value="0" />
            </AppFormControl>

            <AppFormControl label="Status" :error="errors.is_active">
                <AppToggle name="is_active" on-value="1" off-value="0" on-label="Active" off-label="Inactive" />
            </AppFormControl>

            <div class="flex justify-end gap-2">
                <Button type="button" variant="outline" @click="router.visit(participantRoleIndex().url)">
                    Cancel
                </Button>

                <Button type="submit" :disabled="processing">
                    {{ processing ? 'Creating...' : 'Create Role' }}
                </Button>
            </div>
        </Form>
    </div>
</template>
