<script setup lang="ts">
    import {
        AppFormControl,
        AppInput,
        AppTextarea,
        AppSelect,
    } from '@/components/form'
    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'
    import { Form, Link } from '@inertiajs/vue3'

    import type { FormOption } from '@/types'

    interface Props {
        research: {
            id: number
            title: string
        }

        source: {
            id: number
            title: string
            source_type: {
                value: string
                label: string
            }
            author: string | null
            publisher: string | null
            url: string | null
            published_at: string | null
            citation: string | null
            description: string | null
            position: number
        }

        sourceTypeOptions: FormOption[]
    }

    const props = defineProps<Props>()
</script>

<template>
    <div class="flex gap-4 py-5">
        <BackButton />

        <Heading title="Edit Research Source" :description="`Update source information for ${research.title}.`" />
    </div>

    <Form v-slot="{ errors, processing }" :action="`/admin/research/${research.id}/sources/${source.id}`" method="put"
        class="space-y-4">
        <AppFormControl label="Title" required :error="errors.title">
            <AppInput name="title" placeholder="Enter source title" :default-value="source.title" />
        </AppFormControl>

        <AppFormControl label="Source Type" required :error="errors.source_type">
            <AppSelect name="source_type" placeholder="Select source type" :options="sourceTypeOptions"
                :default-value="source.source_type.value" />
        </AppFormControl>

        <AppFormControl label="Author" :error="errors.author">
            <AppInput name="author" placeholder="Enter author" :default-value="source.author ?? ''" />
        </AppFormControl>

        <AppFormControl label="Publisher" :error="errors.publisher">
            <AppInput name="publisher" placeholder="Enter publisher" :default-value="source.publisher ?? ''" />
        </AppFormControl>

        <AppFormControl label="URL" :error="errors.url">
            <AppInput name="url" placeholder="https://example.com/source" :default-value="source.url ?? ''" />
        </AppFormControl>

        <AppFormControl label="Published At" :error="errors.published_at">
            <AppInput name="published_at" type="date" :default-value="source.published_at ?? ''" />
        </AppFormControl>

        <AppFormControl label="Citation" :error="errors.citation">
            <AppTextarea name="citation" placeholder="Enter citation" :default-value="source.citation ?? ''" />
        </AppFormControl>

        <AppFormControl label="Description" :error="errors.description">
            <AppTextarea name="description" placeholder="Describe this source..."
                :default-value="source.description ?? ''" />
        </AppFormControl>

        <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
            <Button type="button" variant="outline" as-child>
                <Link :href="`/admin/research/${research.id}/sources`">
                    Cancel
                </Link>
            </Button>

            <Button type="submit" :disabled="processing">
                {{ processing ? 'Processing...' : 'Update Source' }}
            </Button>
        </div>
    </Form>
</template>