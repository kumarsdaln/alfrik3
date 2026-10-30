<script setup lang="ts">
    import {
        AppFormControl,
        AppInput,
        AppSelect,
        AppCheckbox,
    } from '@/components/form'
    import AppMultiSelect from '@/components/form/AppMultiSelect.vue'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'

    import {
        index,
        show,
        update,
    } from '@/routes/admin/users'

    import type {
        FormOption,
        User,
    } from '@/types'

    import { Form, Link } from '@inertiajs/vue3'

    interface Props {
        user: {
            data: User
        }
        roleOptions: FormOption[]
        countryOptions: FormOption[]
        positionOptions: FormOption[]
        languageOptions: FormOption[]
        industryOptions: FormOption[]
    }

    const props = defineProps<Props>()
</script>

<template>
    <div class="flex gap-4 py-5">
        <BackButton />

        <Heading title="Edit User" description="Update the user's account and professional profile." />
    </div>

    <Form v-slot="{ errors, processing }" v-bind="update.form({
        user: user.data.id,
    })" :options="{ preserveScroll: true }" class="space-y-8">
        <!-- Account -->
        <section class="space-y-4">
            <Heading title="Account Information" description="Basic login and account information." />

            <AppFormControl label="Name" required :error="errors.name">
                <AppInput name="name" :default-value="user.data.name" placeholder="Enter full name" />
            </AppFormControl>

            <AppFormControl label="Username" :error="errors.username">
                <AppInput name="username" :default-value="user.data.username??''" placeholder="Enter username" />
            </AppFormControl>

            <AppFormControl label="Email" required :error="errors.email">
                <AppInput name="email" type="email" :default-value="user.data.email"
                    placeholder="Enter email address" />
            </AppFormControl>

            <AppFormControl label="New Password" :error="errors.password">
                <AppInput name="password" type="password" placeholder="Leave blank to keep current password" />
            </AppFormControl>

            <AppFormControl label="Confirm New Password" :error="errors.password_confirmation">
                <AppInput name="password_confirmation" type="password" placeholder="Confirm new password" />
            </AppFormControl>
        </section>

        <!-- Profile -->
        <section class="space-y-4">
            <div>
                <h2 class="text-base font-semibold">
                    Profile
                </h2>

                <p class="text-sm text-muted-foreground">
                    Professional information shown on the user's profile.
                </p>
            </div>

            <AppFormControl label="Headline" :error="errors.headline">
                <AppInput name="headline" :default-value="user.data.headline??''"
                    placeholder="e.g. Fashion Entrepreneur & Industry Expert" />
            </AppFormControl>

            <AppFormControl label="Country" :error="errors.country_id">
                <AppSelect name="country_id" :options="countryOptions" :default-value="user.data.country_id"
                    placeholder="Select country" />
            </AppFormControl>

            <AppFormControl label="Position" :error="errors.position_id">
                <AppSelect name="position_id" :options="positionOptions" :default-value="user.data.position_id"
                    placeholder="Select position" />
            </AppFormControl>
        </section>

        <!-- Access -->
        <section class="space-y-4">
            <div>
                <h2 class="text-base font-semibold">
                    Access & Roles
                </h2>

                <p class="text-sm text-muted-foreground">
                    Configure the user's account status and permissions.
                </p>
            </div>

            <AppFormControl label="Roles" :error="errors.roles">
                <AppMultiSelect name="roles" :options="roleOptions"
                    :default-value="user.data.roles?.map(role => role.id) ?? []" placeholder="Select roles" />
            </AppFormControl>

            <AppCheckbox name="is_active" :default-value="user.data.is_active" true-value="1" false-value="0"
                label="Active user" />
        </section>

        <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
            <Button type="button" variant="outline" as-child>
                <Link :href="show(user.data.id)">
                    Cancel
                </Link>
            </Button>

            <Button type="submit" :disabled="processing">
                {{ processing ? 'Processing...' : 'Update User' }}
            </Button>
        </div>
    </Form>
</template>