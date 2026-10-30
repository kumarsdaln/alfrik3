<script setup lang="ts">
    import { Head, Form, Link } from '@inertiajs/vue3'

    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppMultiSelect from '@/components/form/AppMultiSelect.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'

    import {
        index,
        store,
    } from '@/routes/admin/roles'

    interface Option {
        value: string | number
        label: string
    }

    interface Props {
        permissionOptions: Option[]
    }

    defineProps<Props>()
</script>

<template>

    <Head title="Create Role" />

    <div class="space-y-6 py-4">
        <!-- Header -->

        <div class="flex items-center gap-4">
            <BackButton />

            <Heading title="Create Role" description="Create a new user role and assign permissions." />
        </div>

        <!-- Form -->

        <Form v-slot="{ errors, processing }" v-bind="store.form()" :options="{
            preserveScroll: true,
        }">
            <div class="space-y-8">
                <!-- Role Information -->

                <section class="space-y-6">
                    <div>
                        <h2 class="text-base font-semibold">
                            Role Information
                        </h2>

                        <p class="text-sm text-muted-foreground">
                            Define the role and its basic information.
                        </p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <AppFormControl label="Name" :error="errors.name" required>
                            <AppInput name="name" placeholder="Administrator" />
                        </AppFormControl>

                        <AppFormControl label="Slug" :error="errors.slug" required>
                            <AppInput name="slug" placeholder="administrator" />
                        </AppFormControl>
                    </div>

                    <AppFormControl label="Description" :error="errors.description">
                        <AppTextarea name="description" placeholder="Describe what this role can do..." :rows="4" />
                    </AppFormControl>
                </section>

                <!-- Permissions -->

                <section class="space-y-6">
                    <div>
                        <h2 class="text-base font-semibold">
                            Permissions
                        </h2>

                        <p class="text-sm text-muted-foreground">
                            Select the permissions that users with this role
                            should have.
                        </p>
                    </div>

                    <AppFormControl label="Permissions" :error="errors.permissions">
                        <AppMultiSelect name="permissions" :options="permissionOptions"
                            placeholder="Select permissions" />
                    </AppFormControl>

                    <div v-if="errors['permissions.0']" class="text-sm text-destructive">
                        {{ errors['permissions.0'] }}
                    </div>
                </section>

                <!-- Actions -->

                <div class="flex items-center justify-end gap-3 border-t pt-6">
                    <Button as-child variant="outline">
                        <Link :href="index()">
                            Cancel
                        </Link>
                    </Button>

                    <Button type="submit" :disabled="processing">
                        {{
                            processing
                                ? 'Creating...'
                                : 'Create Role'
                        }}
                    </Button>
                </div>
            </div>
        </Form>
    </div>
</template>