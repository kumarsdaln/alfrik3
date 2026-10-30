<script setup lang="ts">
    import {
        AppFormControl,
        AppInput,
        AppTextarea,
        AppSelect,
        AppCheckbox,
    } from '@/components/form'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'

    import { index, store } from '@/routes/admin/users'
    import { Form, Link } from '@inertiajs/vue3'

    import type { FormOption } from '@/types'
import AppMultiSelect from '@/components/form/AppMultiSelect.vue'

    interface Props {
        roleOptions: FormOption[]
        countryOptions: FormOption[]
        positionOptions: FormOption[]
        languageOptions: FormOption[]
        industryOptions: FormOption[]
    }

    defineProps<Props>()
</script>

<template>
    <div class="flex gap-4 py-5">
        <BackButton />
        <Heading title="Create User" description="Create and configure a user profile." />
    </div>

    <Form v-slot="{ errors, processing }" v-bind="store.form()" class="space-y-8">
        <!-- Account -->
        <section class="space-y-4">
            <div>
                <h2 class="text-base font-semibold">
                    Account Information
                </h2>

                <p class="text-sm text-muted-foreground">
                    Basic login and account information.
                </p>
            </div>

            <AppFormControl label="Name" required :error="errors.name">
                <AppInput name="name" placeholder="Enter full name" />
            </AppFormControl>

            <AppFormControl label="Username" :error="errors.username">
                <AppInput name="username" placeholder="Enter username" />
            </AppFormControl>

            <AppFormControl label="Email" required :error="errors.email">
                <AppInput name="email" type="email" placeholder="Enter email address" />
            </AppFormControl>

            <AppFormControl label="Password" required :error="errors.password">
                <AppInput name="password" type="password" placeholder="Enter password" />
            </AppFormControl>

            <AppFormControl label="Confirm Password" required :error="errors.password_confirmation">
                <AppInput name="password_confirmation" type="password" placeholder="Confirm password" />
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
                <AppInput name="headline" placeholder="e.g. Fashion Entrepreneur & Industry Expert" />
            </AppFormControl>

            <AppFormControl label="Country" :error="errors.country_id">
                <AppSelect name="country_id" placeholder="Select country" :options="countryOptions" />
            </AppFormControl>

            <AppFormControl label="Position" :error="errors.position_id">
                <AppSelect name="position_id" placeholder="Select position" :options="positionOptions" />
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
                <AppMultiSelect name="roles" :options="roleOptions" placeholder="Select roles" />
            </AppFormControl>

            <AppCheckbox name="is_active" true-value="1" false-value="0" :default-value="true" label="Active user" />
        </section>

        <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
            <Button type="button" variant="outline" as-child>
                <Link :href="index.url()">
                    Cancel
                </Link>
            </Button>

            <Button type="submit" :disabled="processing">
                {{ processing ? 'Processing...' : 'Create User' }}
            </Button>
        </div>
    </Form>
</template>