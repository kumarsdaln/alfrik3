<script setup lang="ts">
    import { router } from '@inertiajs/vue3'
    import { Trash2 } from '@lucide/vue'

    import {
        AppFormControl,
        AppInput,
        AppSelect,
    } from '@/components/form'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import Badge from '@/components/ui/badge/Badge.vue'

    import AppTable from '@/components/ui/AppTable.vue'
    import AppTableActions from '@/components/ui/AppTableActions.vue'
    import TableLayout from '@/layouts/table/TableLayout.vue'

    import type {
        FormOption,
        ResearchMember,
    } from '@/types'

    import { Form } from '@inertiajs/vue3'

    interface Props {
        research: {
            id: number
            title: string
        }

        members: {
            data: ResearchMember[]
        }

        users: {
            id: number
            name: string
            email: string
        }[]

        roleOptions: FormOption[]
    }

    defineProps<Props>()

    const removeMember = (
        researchId: number,
        memberId: number,
    ) => {
        if (!confirm('Are you sure you want to remove this member?')) {
            return
        }

        router.delete(
            `/admin/research/${researchId}/team/${memberId}`
        )
    }
</script>

<template>
    <TableLayout>

        <!-- Header -->

        <template #header>
            <div class="flex gap-4 py-5">
                <BackButton :href="`/admin/research/${research.id}`" />

                <Heading title="Research Team" :description="`Manage the team working on ${research.title}.`" />
            </div>
        </template>

        <!-- Add Member -->

        <div class="mb-6 rounded-lg border border-border-light bg-background p-6 dark:border-border-dark">
            <h2 class="font-semibold">
                Add Team Member
            </h2>

            <p class="mt-1 text-sm text-muted-foreground">
                Add a user to the research team and assign their role.
            </p>

            <Form v-slot="{ errors, processing }" :action="`/admin/research/${research.id}/team`" method="post"
                class="mt-6 space-y-4">
                <AppFormControl label="User" required :error="errors.user_id">
                    <AppInput name="user_id" type="number" placeholder="Enter user ID" />
                </AppFormControl>

                <AppFormControl label="Role" required :error="errors.role">
                    <AppSelect name="role" placeholder="Select team role" :options="roleOptions" />
                </AppFormControl>

                <div class="flex justify-end">
                    <Button type="submit" :disabled="processing">
                        {{ processing ? 'Processing...' : 'Add Member' }}
                    </Button>
                </div>
            </Form>
        </div>

        <!-- Members -->

        <AppTable :data="members.data" :columns="[
            {
                key: 'user',
                label: 'Member',
            },
            {
                key: 'role',
                label: 'Role',
            },
            {
                key: 'joined_at',
                label: 'Joined',
            },
            {
                key: 'actions',
                label: 'Actions',
                align: 'right',
            },
        ]">
            <template #cell-user="{ row }">
                <div>
                    <div class="font-medium">
                        {{ row.user?.name ?? 'Unknown User' }}
                    </div>

                    <div class="text-sm text-muted-foreground">
                        {{ row.user?.email ?? '—' }}
                    </div>
                </div>
            </template>

            <template #cell-role="{ row }">
                <Badge>
                    {{ row.role.label }}
                </Badge>
            </template>

            <template #cell-joined_at="{ row }">
                {{ row.joined_at
                    ? new Date(row.joined_at).toLocaleDateString()
                    : '—'
                }}
            </template>

            <template #cell-actions="{ row }">
                <AppTableActions :actions="[
                    {
                        label: 'Remove',
                        icon: Trash2,
                        onClick: () =>
                            removeMember(
                                research.id,
                                row.id
                            ),
                    },
                ]" />
            </template>
        </AppTable>
    </TableLayout>
</template>