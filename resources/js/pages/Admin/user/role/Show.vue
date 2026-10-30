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
    } from '@/routes/admin/roles'
    import { Role } from '@/types'


    interface Props {
        role: {
            data: Role
        }
    }

    defineProps<Props>()
</script>

<template>

    <Head :title="role.data.name" />

    <div class="space-y-6">
        <!-- Header -->

        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <BackButton :href="index()" />

                <Heading :title="role.data.name" description="View role details, permissions, and assigned users." />
            </div>

            <Button as-child>
                <Link :href="edit({
                    role: role.data.id,
                })
                    ">
                    <Pencil class="mr-2 size-4" />
                    Edit Role
                </Link>
            </Button>
        </div>

        <!-- Role Information -->

        <section class="rounded-lg border">
            <div class="border-b px-6 py-4">
                <h2 class="font-semibold">
                    Role Information
                </h2>
            </div>

            <div class="grid gap-6 p-6 md:grid-cols-2">
                <div>
                    <p class="text-sm text-muted-foreground">
                        Name
                    </p>

                    <p class="mt-1 font-medium">
                        {{ role.data.name }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-muted-foreground">
                        Slug
                    </p>

                    <code class="mt-1 block text-sm">
                        {{ role.data.slug }}
                    </code>
                </div>

                <div class="md:col-span-2">
                    <p class="text-sm text-muted-foreground">
                        Description
                    </p>

                    <p class="mt-1">
                        {{ role.data.description || '—' }}
                    </p>
                </div>

                <div v-if="role.data.created_at">
                    <p class="text-sm text-muted-foreground">
                        Created At
                    </p>

                    <Date :value="role.data.created_at" class="mt-1" />
                </div>

                <div v-if="role.data.updated_at">
                    <p class="text-sm text-muted-foreground">
                        Updated At
                    </p>

                    <Date :value="role.data.updated_at" class="mt-1" />
                </div>
            </div>
        </section>

        <!-- Permissions -->

        <section class="rounded-lg border">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <div>
                    <h2 class="font-semibold">
                        Permissions
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Permissions assigned to this role.
                    </p>
                </div>

                <span class="text-sm text-muted-foreground">
                    {{ role.data.permissions?.length ?? 0 }}
                </span>
            </div>

            <div class="p-6">
                <div v-if="role.data.permissions?.length" class="flex flex-wrap gap-2">
                    <Badge v-for="permission in role.data.permissions" :key="permission.id" variant="outline">
                        {{ permission.name }}
                    </Badge>
                </div>

                <p v-else class="text-sm text-muted-foreground">
                    No permissions assigned.
                </p>
            </div>
        </section>

        <!-- Users -->

        <section class="rounded-lg border">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <div>
                    <h2 class="font-semibold">
                        Users
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Users assigned to this role.
                    </p>
                </div>

                <span class="text-sm text-muted-foreground">
                    {{ role.data.users?.length ?? 0 }}
                </span>
            </div>

            <div class="p-6">
                <div v-if="role.data.users?.length" class="space-y-3">
                    <div v-for="user in role.data.users" :key="user.id"
                        class="flex items-center justify-between rounded-md border p-3">
                        <div>
                            <p class="font-medium">
                                {{ user.name }}
                            </p>

                            <p v-if="user.username" class="text-sm text-muted-foreground">
                                @{{ user.username }}
                            </p>
                        </div>

                        <p class="text-sm text-muted-foreground">
                            {{ user.email }}
                        </p>
                    </div>
                </div>

                <p v-else class="text-sm text-muted-foreground">
                    No users assigned to this role.
                </p>
            </div>
        </section>
    </div>
</template>