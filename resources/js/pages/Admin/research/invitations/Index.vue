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
        ResearchInvitation,
    } from '@/types'

    import { Form } from '@inertiajs/vue3'

    interface Props {
        research: {
            id: number
            title: string
        }

        invitations: {
            data: ResearchInvitation[]
        }

        roleOptions: FormOption[]
    }

    defineProps<Props>()

    const cancelInvitation = (
        researchId: number,
        invitationId: number,
    ) => {
        if (!confirm('Are you sure you want to cancel this invitation?')) {
            return
        }

        router.delete(
            `/admin/research/${researchId}/invitations/${invitationId}`
        )
    }
</script>

<template>
    <TableLayout>

        <template #header>
            <div class="flex gap-4 py-5">
                <BackButton :href="`/admin/research/${research.id}`" />

                <Heading title="Research Invitations" :description="`Invite collaborators to ${research.title}.`" />
            </div>
        </template>

        <!-- Create Invitation -->

        <div class="mb-6 rounded-lg border border-border-light bg-background p-6 dark:border-border-dark">
            <h2 class="font-semibold">
                Invite Collaborator
            </h2>

            <p class="mt-1 text-sm text-muted-foreground">
                Send an invitation to a user to join this research.
            </p>

            <Form v-slot="{ errors, processing }" :action="`/admin/research/${research.id}/invitations`" method="post"
                class="mt-6 space-y-4">
                <AppFormControl label="Email" required :error="errors.email">
                    <AppInput name="email" type="email" placeholder="Enter collaborator email" />
                </AppFormControl>

                <AppFormControl label="Role" required :error="errors.role">
                    <AppSelect name="role" placeholder="Select role" :options="roleOptions" />
                </AppFormControl>

                <AppFormControl label="Expires At" :error="errors.expires_at">
                    <AppInput name="expires_at" type="datetime-local" />
                </AppFormControl>

                <div class="flex justify-end">
                    <Button type="submit" :disabled="processing">
                        {{ processing
                            ? 'Processing...'
                            : 'Create Invitation'
                        }}
                    </Button>
                </div>
            </Form>
        </div>

        <!-- Invitations -->

        <AppTable :data="invitations.data" :columns="[
            {
                key: 'email',
                label: 'Email',
            },
            {
                key: 'role',
                label: 'Role',
            },
            {
                key: 'status',
                label: 'Status',
            },
            {
                key: 'expires_at',
                label: 'Expires',
            },
            {
                key: 'actions',
                label: 'Actions',
                align: 'right',
            },
        ]">
            <template #cell-email="{ row }">
                <span class="font-medium">
                    {{ row.email }}
                </span>
            </template>

            <template #cell-role="{ row }">
                <Badge>
                    {{ row.role.label }}
                </Badge>
            </template>

            <template #cell-status="{ row }">
                <Badge :variant="row.status === 'accepted'
                        ? 'default'
                        : row.status === 'expired'
                            ? 'destructive'
                            : 'secondary'
                    ">
                    {{ row.status }}
                </Badge>
            </template>

            <template #cell-expires_at="{ row }">
                {{ new Date(row.expires_at).toLocaleString() }}
            </template>

            <template #cell-actions="{ row }">
                <AppTableActions :actions="[
                    {
                        label: 'Cancel',
                        icon: Trash2,
                        onClick: () =>
                            cancelInvitation(
                                research.id,
                                row.id
                            ),
                    },
                ]" />
            </template>
        </AppTable>
    </TableLayout>
</template>