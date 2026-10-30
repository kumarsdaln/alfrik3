<script setup lang="ts">
    import { Head, Form, Link } from '@inertiajs/vue3'

    import AppFormControl from '@/components/form/AppFormControl.vue'
    import AppInput from '@/components/form/AppInput.vue'
    import AppTextarea from '@/components/form/AppTextarea.vue'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'

    import {
        index,
        update,
    } from '@/routes/admin/permissions'
    import { Permission } from '@/types'

    interface Props {
        permission: {
            data: Permission
        }
    }

    const props = defineProps<Props>()
</script>

<template>

    <Head :title="`Edit ${permission.data.name}`" />

    <div class="space-y-6">
        <!-- Header -->

        <div class="flex items-center gap-4">
            <BackButton />

            <Heading title="Edit Permission" :description="`Update ${permission.data.name}.`" />
        </div>

        <!-- Form -->

        <Form v-slot="{ errors, processing }" v-bind="update.form({
            permission: permission.data.id,
        })" :options="{
            preserveScroll: true,
        }">
            <div class="space-y-8">
                <!-- Permission Information -->

                <section class="space-y-6">
                    <div>
                        <h2 class="text-base font-semibold">
                            Permission Information
                        </h2>

                        <p class="text-sm text-muted-foreground">
                            Update the permission information.
                        </p>
                    </div>

                    <div class="grid gap-6 md:grid-cols-2">
                        <AppFormControl label="Name" :error="errors.name" required>
                            <AppInput name="name" :default-value="permission.data.name
                                " placeholder="View Users" />
                        </AppFormControl>

                        <AppFormControl label="Slug" :error="errors.slug" required>
                            <AppInput name="slug" :default-value="permission.data.slug
                                " placeholder="users.view" />
                        </AppFormControl>
                    </div>

                    <AppFormControl label="Description" :error="errors.description">
                        <AppTextarea name="description" :default-value="permission.data.description ?? ''
                            " placeholder="Describe what this permission allows..." :rows="4" />
                    </AppFormControl>
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
                                ? 'Updating...'
                                : 'Update Permission'
                        }}
                    </Button>
                </div>
            </div>
        </Form>
    </div>
</template>