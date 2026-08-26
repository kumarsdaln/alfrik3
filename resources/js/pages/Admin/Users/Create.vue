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
import { UserRole } from '@/types/user'


    /*
    |--------------------------------------------------------------------------
    | Props
    |--------------------------------------------------------------------------
    */

    interface Props {
        roles: UserRole[]
    }

    const props = defineProps<Props>()


    /*
    |--------------------------------------------------------------------------
    | Role Options
    |--------------------------------------------------------------------------
    */

    const roleOptions: FormOption[] = props.roles.map(
        role => ({
            value: role.id,
            label: role.name,
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
            <AppFormLayout title="Create User" description="Create a new user and assign their role.">
                <div class="max-w-5xl space-y-6">

                    <!-- Name -->

                    <AppInput name="name" label="Name" placeholder="Enter user name" :error="errors.name" />


                    <!-- Email -->

                    <AppInput name="email" type="email" label="Email" placeholder="Enter email address"
                        :error="errors.email" />


                    <!-- Password -->

                    <AppInput name="password" type="password" label="Password" placeholder="Enter password"
                        :error="errors.password" />


                    <!-- Password Confirmation -->

                    <AppInput name="password_confirmation" type="password" label="Confirm Password"
                        placeholder="Confirm password" :error="errors.password_confirmation" />


                    <!-- Role -->

                    <AppSelect name="role" label="Role" placeholder="Select a role" :options="roleOptions"
                        :error="errors.role" />


                    <!-- Status -->

                    <AppSelect name="status" label="Status" :options="[
                        {
                            value: 'active',
                            label: 'Active',
                        },
                        {
                            value: 'inactive',
                            label: 'Inactive',
                        },
                    ]" :error="errors.status" />

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