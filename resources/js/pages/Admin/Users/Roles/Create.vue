<script setup lang="ts">
    import { Form } from '@inertiajs/vue3'

    import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'

    import AppButton from '@/components/ui/AppButton.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'

    import type { FormOption } from '@/types/forms'

    import {
        store,
    } from '@/actions/App/Http/Controllers/Admin/User/UserController'
    import { Permission } from '@/types/user'
    import AppTextarea from '@/components/form/AppTextarea.vue'
    import AppMultiSelectSearch from '@/components/form/AppMultiSelectSearch.vue'


    /*
    |--------------------------------------------------------------------------
    | Props
    |--------------------------------------------------------------------------
    */

    interface Props {
        permissions: Permission[]
    }

    const props = defineProps<Props>()


    /*
    |--------------------------------------------------------------------------
    | permission Options
    |--------------------------------------------------------------------------
    */

    const permissionOptions: FormOption[] = props.permissions.map(
        permission => ({
            value: permission.id,
            label: permission.name,
        }),
    )


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
    <Form :action="store()">
        <template #default="{
            errors,
            processing,
            isDirty,
        }">
            <AppFormLayout title="Create User" description="Create a new user and assign their permission.">
                <div class="max-w-5xl space-y-6">

                    <!-- Name -->
                    <AppInput name="name" label="Name" placeholder="Enter role name" :error="errors.name" />


                    <!-- Email -->
                    <AppInput name="slug" type="slug" label="Slug" placeholder="Enter role slug"
                        :error="errors.email" />

                    <AppTextarea name="description" label="Description" :error="errors.description" />


                    <!-- permission -->
                    <AppMultiSelectSearch name="permissions" label="Permission" :options="permissionOptions"
                        :error="errors.permissions" />
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