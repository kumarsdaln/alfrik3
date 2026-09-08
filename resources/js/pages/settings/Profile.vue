<script setup lang="ts">
import { computed, ref } from 'vue'
import { Form, Head, Link, usePage } from '@inertiajs/vue3'

import ProfileController from '@/actions/App/Http/Controllers/Settings/ProfileController'

import DeleteUser from '@/components/DeleteUser.vue'
import Heading from '@/components/Heading.vue'
import AppFormControl from '@/components/form/AppFormControl.vue'
import AppInput from '@/components/form/AppInput.vue'

import { Button } from '@/components/ui/button'
import { send } from '@/routes/verification'



const page = usePage()
const user = computed(() => page.props.auth.user)

/*
|--------------------------------------------------------------------------
| Form Values
|--------------------------------------------------------------------------
*/

const name = ref(user.value.name)
const email = ref(user.value.email)
</script>

<template>
    <Head title="Profile settings" />

    <h1 class="sr-only">
        Profile settings
    </h1>

    <div class="flex flex-col space-y-6">

        <!-- Heading -->

        <Heading
            variant="small"
            title="Profile"
            description="Update your name and email address"
        />


        <!-- Profile Form -->

        <Form
            v-bind="ProfileController.update.form()"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >

            <!-- Name -->

            <AppFormControl
                label="Name"
                :error="errors.name"
                required
            >
                <AppInput
                    v-model="name"
                    name="name"
                    autocomplete="name"
                    placeholder="Full name"
                    required
                    :error="!!errors.name"
                />
            </AppFormControl>


            <!-- Email -->

            <AppFormControl
                label="Email address"
                :error="errors.email"
                required
            >
                <AppInput
                    v-model="email"
                    name="email"
                    type="email"
                    autocomplete="email"
                    placeholder="Email address"
                    required
                    :error="!!errors.email"
                />
            </AppFormControl>


            <!-- Email Verification -->

            <div
                v-if="
                    page.props.mustVerifyEmail &&
                    !user.email_verified_at
                "
            >
                <p class="-mt-4 text-sm text-muted-foreground">
                    Your email address is unverified.

                    <Link
                        :href="send()"
                        as="button"
                        class="
                            text-foreground
                            underline
                            decoration-neutral-300
                            underline-offset-4
                            transition-colors
                            duration-300
                            ease-out
                            hover:decoration-current!
                            dark:decoration-neutral-500
                        "
                    >
                        Click here to re-send the verification email.
                    </Link>
                </p>

                <div
                    v-if="
                        page.props.status ===
                        'verification-link-sent'
                    "
                    class="
                        mt-2
                        text-sm
                        font-medium
                        text-green-600
                    "
                >
                    A new verification link has been sent
                    to your email address.
                </div>
            </div>


            <!-- Actions -->
            <div class="flex items-center gap-4">
                <Button
                    :disabled="processing"
                    data-test="update-profile-button"
                >
                    Save
                </Button>
            </div>

        </Form>
    </div>


    <!-- Delete Account -->
    <DeleteUser />
</template>