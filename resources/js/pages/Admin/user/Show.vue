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
    } from '@/routes/admin/users'

    import type { User } from '@/types'

    interface Props {
        user: {
            data: User
        }
    }

    defineProps<Props>()
</script>

<template>

    <Head :title="user.data.name" />

    <div class="space-y-6">
        <!-- Header -->

        <div class="flex items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <BackButton />

                <Heading :title="user.data.name" description="View user profile, roles, and account information." />
            </div>

            <Button as-child>
                <Link :href="edit({
                    user: user.data.id,
                })
                    ">
                    <Pencil class="mr-2 size-4" />
                    Edit User
                </Link>
            </Button>
        </div>

        <!-- Profile -->

        <section class="rounded-lg border">
            <div class="border-b px-6 py-4">
                <h2 class="font-semibold">
                    Profile
                </h2>
            </div>

            <div class="grid gap-6 p-6 md:grid-cols-2">
                <div>
                    <p class="text-sm text-muted-foreground">
                        Name
                    </p>

                    <p class="mt-1 font-medium">
                        {{ user.data.name }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-muted-foreground">
                        Username
                    </p>

                    <p class="mt-1">
                        {{
                            user.data.username
                                ? `@${user.data.username}`
                                : '—'
                        }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-muted-foreground">
                        Email
                    </p>

                    <p class="mt-1">
                        {{ user.data.email }}
                    </p>
                </div>

                <div>
                    <p class="text-sm text-muted-foreground">
                        Headline
                    </p>

                    <p class="mt-1">
                        {{ user.data.headline || '—' }}
                    </p>
                </div>
            </div>
        </section>

        <!-- Account -->

        <section class="rounded-lg border">
            <div class="border-b px-6 py-4">
                <h2 class="font-semibold">
                    Account
                </h2>
            </div>

            <div class="grid gap-6 p-6 md:grid-cols-3">
                <div>
                    <p class="text-sm text-muted-foreground">
                        Status
                    </p>

                    <div class="mt-2">
                        <Badge :color="user.data.status.color">
                            {{ user.data.status.label }}
                        </Badge>
                    </div>
                </div>

                <div>
                    <p class="text-sm text-muted-foreground">
                        Created At
                    </p>

                    <Date v-if="user.data.created_at" :value="user.data.created_at" class="mt-1" />

                    <span v-else>—</span>
                </div>

                <div>
                    <p class="text-sm text-muted-foreground">
                        Updated At
                    </p>

                    <Date v-if="user.data.updated_at" :value="user.data.updated_at" class="mt-1" />

                    <span v-else>—</span>
                </div>
            </div>
        </section>

        <!-- Roles -->

        <section class="rounded-lg border">
            <div class="flex items-center justify-between border-b px-6 py-4">
                <div>
                    <h2 class="font-semibold">
                        Roles
                    </h2>

                    <p class="text-sm text-muted-foreground">
                        Roles assigned to this user.
                    </p>
                </div>

                <span class="text-sm text-muted-foreground">
                    {{ user.data.roles?.length ?? 0 }}
                </span>
            </div>

            <div class="p-6">
                <div v-if="user.data.roles?.length" class="space-y-4">
                    <div v-for="role in user.data.roles" :key="role.id" class="rounded-lg border p-4">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <p class="font-medium">
                                    {{ role.name }}
                                </p>

                                <code class="text-sm text-muted-foreground">
                                    {{ role.slug }}
                                </code>

                                <p v-if="role.description" class="mt-2 text-sm text-muted-foreground">
                                    {{ role.description }}
                                </p>
                            </div>

                            <Badge variant="outline">
                                {{
                                    role.permissions?.length ?? 0
                                }}
                                permissions
                            </Badge>
                        </div>

                        <!-- Permissions -->

                        <div v-if="role.permissions?.length" class="mt-4 flex flex-wrap gap-2">
                            <Badge v-for="permission in role.permissions" :key="permission.id" variant="secondary">
                                {{ permission.name }}
                            </Badge>
                        </div>
                    </div>
                </div>

                <p v-else class="text-sm text-muted-foreground">
                    No roles assigned to this user.
                </p>
            </div>
        </section>
    </div>
</template>