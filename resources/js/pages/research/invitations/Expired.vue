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
            <Heading title="Invitation Expired" description="This research invitation is no longer valid." />

            <p class="mt-6 text-sm leading-6 text-muted-foreground">
                The invitation to join
                <strong>{{ research.title }}</strong>
                has expired. Please ask the research owner to send you a
                new invitation.
            </p>

            <div class="mt-8">
                <Button as-child>
                    <Link href="/">
                        Return Home
                    </Link>
                </Button>
            </div>
        </div>
    </div>
</template>