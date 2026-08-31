<script setup lang="ts">
    import { Form } from '@inertiajs/vue3'

    import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'

    import AppButton from '@/components/ui/AppButton.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppSelect from '@/components/form/AppSelect.vue'

    import type { FormOption } from '@/types/forms'
    import type { Role, User } from '@/types/user'

    import {
        update,
    } from '@/actions/App/Http/Controllers/Admin/User/UserController'


    /*
    |--------------------------------------------------------------------------
    | Props
    |--------------------------------------------------------------------------
    */

    interface Props {
        user: User
        roles: Role[]
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
    <Form :action="update(props.user.id)" method="put">
        <template #default="{
            errors,
            processing,
            isDirty,
        }">
            <AppFormLayout title="Edit User"
                description="Update the user's profile, account details, role, and status.">
                <div class="max-w-5xl space-y-6">

                    <!-- Name -->

                    <AppInput name="name" label="Name" placeholder="Enter user name" :default-value="props.user.name"
                        :error="errors.name" />


                    <!-- Username -->

                    <AppInput name="username" label="Username" placeholder="Enter username"
                        :default-value="props.user.username" :error="errors.username" />

                    <p class="-mt-4 text-xs text-muted-foreground">
                        This username is used for the user's public profile.
                    </p>


                    <!-- Email -->

                    <AppInput name="email" type="email" label="Email" placeholder="Enter email address"
                        :default-value="props.user.email" :error="errors.email" />


                    <!-- Password -->

                    <AppInput name="password" type="password" label="New Password"
                        placeholder="Leave blank to keep the current password" :error="errors.password" />


                    <!-- Password Confirmation -->

                    <AppInput name="password_confirmation" type="password" label="Confirm New Password"
                        placeholder="Confirm new password" :error="errors.password_confirmation" />


                    <!-- Role -->

                    <AppSelect name="role" label="Role" placeholder="Select a role" :options="roleOptions"
                        :default-value="props.user.role_id" :error="errors.role" />


                    <!-- Status -->

                    <AppSelect name="is_active" label="Status" :options="[
                        {
                            value: '0',
                            label: 'Active',
                        },
                        {
                            value: '1',
                            label: 'Inactive',
                        },
                    ]" :default-value="props.user.is_active" :error="errors.is_active" />

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
                            Save Changes
                        </AppButton>
                    </div>
                </template>
            </AppFormLayout>
        </template>
    </Form>
</template>