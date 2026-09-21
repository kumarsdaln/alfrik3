<script setup lang="ts">
    import { Form } from '@inertiajs/vue3'
    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'

    interface Props {
        invitation: {
            email: string
            role: {
                value: string
                label: string
            }
            expires_at: string
        }

        research: {
            id: number
            title: string
        }
    }

    defineProps<Props>()
</script>

<template>
    <div class="mx-auto max-w-2xl px-6 py-16">
        <div class="rounded-lg border border-border-light bg-background p-8 dark:border-border-dark">
            <Heading title="Research Team Invitation" description="You have been invited to join a research project." />

            <div class="mt-8 space-y-6">
                <div>
                    <div class="text-sm text-muted-foreground">
                        Research
                    </div>

                    <div class="mt-1 text-lg font-semibold">
                        {{ research.title }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-muted-foreground">
                        Invited Email
                    </div>

                    <div class="mt-1 font-medium">
                        {{ invitation.email }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-muted-foreground">
                        Role
                    </div>

                    <div class="mt-1 font-medium">
                        {{ invitation.role.label }}
                    </div>
                </div>

                <div>
                    <div class="text-sm text-muted-foreground">
                        Invitation Expires
                    </div>

                    <div class="mt-1 font-medium">
                        {{ new Date(invitation.expires_at).toLocaleString() }}
                    </div>
                </div>
            </div>

            <Form method="post" :action="`/research/invitations/${$page.url.split('/').pop()}/accept`" class="mt-8">
                <Button type="submit">
                    Accept Invitation
                </Button>
            </Form>
        </div>
    </div>
</template>