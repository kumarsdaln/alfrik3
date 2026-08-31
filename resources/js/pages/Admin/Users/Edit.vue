<script setup lang="ts">
import { ref } from 'vue'
import { Form } from '@inertiajs/vue3'

import AppFormLayout from '@/layouts/dashboard/AppFormLayout.vue'

import AppButton from '@/components/ui/AppButton.vue'
import AppInput from '@/components/form/AppInput.vue'
import AppSelect from '@/components/form/AppSelect.vue'
import AppToggle from '@/components/form/AppToggle.vue'

import type { FormOption, FormValue } from '@/types/forms'
import type { Role, User } from '@/types/user'

import {
    update,
} from '@/actions/App/Http/Controllers/Admin/User/UserController'


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
| Form Values
|--------------------------------------------------------------------------
*/

const name = ref(props.user.name)

const username = ref(props.user.username)

const email = ref(props.user.email)

const role = ref<FormValue>(
    props.user.roles?.[0]?.id ?? '',
)

const isActive = ref<boolean>(
    props.user.is_active,
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
    <Form
        :action="update(props.user.id)"
        method="put"
    >
        <template #default="{
            errors,
            processing,
            isDirty,
        }">
            <AppFormLayout
                title="Edit User"
                description="Update the user's profile, account details, role, and status."
            >
                <div class="max-w-5xl space-y-6">

                    <!-- Name -->

                    <AppInput
                        v-model="name"
                        name="name"
                        label="Name"
                        placeholder="Enter user name"
                        :error="errors.name"
                        required
                    />


                    <!-- Username -->

                    <AppInput
                        v-model="username"
                        name="username"
                        label="Username"
                        placeholder="Enter username"
                        :error="errors.username"
                        required
                    />

                    <p class="-mt-4 text-xs text-muted-foreground">
                        This username is used for the user's public profile.
                    </p>


                    <!-- Email -->

                    <AppInput
                        v-model="email"
                        name="email"
                        type="email"
                        label="Email"
                        placeholder="Enter email address"
                        :error="errors.email"
                        required
                    />


                    <!-- Password -->

                    <AppInput
                        name="password"
                        type="password"
                        label="New Password"
                        placeholder="Leave blank to keep the current password"
                        :error="errors.password"
                    />


                    <!-- Password Confirmation -->

                    <AppInput
                        name="password_confirmation"
                        type="password"
                        label="Confirm New Password"
                        placeholder="Confirm new password"
                        :error="errors.password_confirmation"
                    />


                    <!-- Role -->

                    <AppSelect
                        v-model="role"
                        name="role"
                        label="Role"
                        placeholder="Select a role"
                        :options="roleOptions"
                        :error="errors.role"
                        required
                    />


                    <!-- Active Status -->

                    <AppToggle
                        v-model="isActive"
                        name="is_active"
                        label="Active"
                        description="Allow this user to access their account and appear as an active user."
                        :error="errors.is_active"
                    />

                </div>


                <!-- Footer -->

                <template #footer>
                    <div
                        class="
                            flex flex-col-reverse gap-3
                            sm:flex-row
                            sm:items-center
                            sm:justify-end
                        "
                    >
                        <AppButton
                            variant="cancel"
                            type="button"
                            :disabled="processing"
                            @click="goBack"
                        >
                            Cancel
                        </AppButton>

                        <AppButton
                            variant="submit"
                            type="submit"
                            :loading="processing"
                            :disabled="!isDirty || processing"
                        >
                            Save Changes
                        </AppButton>
                    </div>
                </template>
            </AppFormLayout>
        </template>
    </Form>
</template>