<script setup lang="ts">
    import { Form } from '@inertiajs/vue3'

    import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'

    import AppButton from '@/components/ui/AppButton.vue'
    import AppInput from '@/components/form/AppInput.vue'

    import {
        update,
    } from '@/actions/App/Http/Controllers/Admin/User/PermissionController'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import { Permission } from '@/types/user'


    interface Props {
        permission: Permission
    }
    const props = defineProps<Props>()
    /*
    |--------------------------------------------------------------------------
    | Navigation
    |--------------------------------------------------------------------------
    */
    function goBack(): void {
        history.back()
    }
</script>


<template>
    <Form :action="update(props.permission.id)">
        <template #default="{
            errors,
            processing,
            isDirty,
        }">
            <AppFormLayout title="Create User" description="Create a new user and assign their permission.">
                <div class="max-w-5xl space-y-6">

                    <!-- Name -->
                    <AppInput name="name" v-model="permission.name" label="Name" placeholder="Enter role name" :error="errors.name" />


                    <!-- Email -->
                    <AppInput name="slug" v-model="permission.slug" label="Slug" placeholder="Enter role slug"
                        :error="errors.slug" />

                    <AppTextarea name="description" v-model="permission.description" label="Description" :error="errors.description" />
                </div>


                <!-- Footer -->

                <template #footer>
                    <div class="
                            flex flex-col-reverse gap-3
                            sm:flex-row
                            sm:items-center
                            sm:justify-end
                        ">
                        <AppButton variant="cancel" type="button" :disabled="processing" @click="goBack">
                            Cancel
                        </AppButton>

                        <AppButton variant="submit" type="submit" :loading="processing"
                            :disabled="!isDirty || processing">
                            Create User
                        </AppButton>
                    </div>
                </template>
            </AppFormLayout>
        </template>
    </Form>
</template>