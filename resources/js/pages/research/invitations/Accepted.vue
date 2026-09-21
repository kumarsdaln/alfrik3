<script setup lang="ts">
    import Heading from '@/components/Heading.vue'
    import Button from '@/components/ui/button/Button.vue'
    import { Link } from '@inertiajs/vue3'

    interface Props {
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
            <Heading title="Invitation Accepted" description="You are already a member of this research team." />

            <p class="mt-6 text-sm leading-6 text-muted-foreground">
                You have already accepted the invitation to
                <strong>{{ research.title }}</strong>.
            </p>

            <div class="mt-8">
                <Button as-child>
                    <Link :href="`/admin/research/${research.id}`">
                        Open Research
                    </Link>
                </Button>
            </div>
        </div>
    </div>
</template>