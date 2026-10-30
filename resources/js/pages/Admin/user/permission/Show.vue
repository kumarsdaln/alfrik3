<script setup lang="ts">
    import { Head, Link } from '@inertiajs/vue3'
    import { Pencil } from '@lucide/vue'

    import Date from '@/components/datadisplay/Date.vue'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import {
        edit,
        index,
    } from '@/routes/admin/permissions'
    import { Permission } from '@/types'

    interface Props {
        permission: {
            data: Permission
        }
    }

    defineProps<Props>()
</script>

<template>

    <Head :title="permission.data.name" />

    <div class="space-y-6">
        <!-- Header -->

        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <BackButton />

                <Heading :title="permission.data.name" description="View permission details and assigned roles." />
            </div>

            <Button as-child>
                <Link :href="edit({
                    permission: permission.data.id,
                })
                    ">
                    <Pencil class="mr-2 size-4" />
                    Edit Permission
                </Link>
            </Button>
        </div>

        <!-- Permission Information -->

        <section class="rounded-lg border">
            <div class="border-b px-6 py-4">
                <h2 class="font-semibold">
                    Permission Information
                </h2>
            </div>

            <div class="grid gap-6 p-6 md:grid-cols-2">
                <div>
                    <p class="text-sm text-muted-foreground">
                        Name
                    </p>

                    <p class="mt-1 font-medium">
                        {{ permission.data.name }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-muted-foreground">
                        Slug
                    </p>

                    <code class="mt-1 block text-sm">
                        {{ permission.data.slug }}
                    </code>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-muted-foreground">
                        Description
                    </p>

                    <p class="mt-1">
                        {{ permission.data.description || '—' }}
                    </p>
                </div>

                <div v-if="permission.data.created_at">
                    <p class="text-sm text-muted-foreground">
                        Created At
                    </p>

                    <Date :value="permission.data.created_at
                        " class="mt-1" />
                </div>

                <div v-if="permission.data.updated_at">
                    <p class="text-sm text-muted-foreground">
                        Updated At
                    </p>

                    <Date :value="permission.data.updated_at
                        " class="mt-1" />
                </div>
            </div>
        </section>

        <!-- Assigned Roles -->

        <section class="rounded-lg border">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <div>
                    <h2 class="font-semibold">
                        Assigned Roles
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Roles that currently have this permission.
                    </p>
                </div>

                <span class="text-sm text-muted-foreground">
                    {{ permission.data.roles?.length ?? 0 }}
                </span>
            </div>

            <div class="p-6">
                <div v-if="permission.data.roles?.length" class="flex flex-wrap gap-2">
                    <Badge v-for="role in permission.data.roles" :key="role.id" variant="outline">
                        {{ role.name }}
                    </Badge>
                </div>

                <p v-else class="text-sm text-muted-foreground">
                    This permission is not assigned to any role.
                </p>
            </div>
        </section>
    </div>
</template>