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
    import { edit, update } from '@/routes/admin/research/findings'
    import type { FormOption, ResearchFinding } from '@/types'
    import { Form, Link } from '@inertiajs/vue3'

    interface Props {
        research: {
            id: number
            title: string
        }
        finding: ResearchFinding
        findingTypeOptions: FormOption[]
    }

    const props = defineProps<Props>()
</script>

<template>
    <div class="flex gap-4 py-5">
        <BackButton :href="`/admin/research/${research.id}/findings`" />

        <Heading title="Edit Finding" :description="`Update the finding for ${research.title}.`" />
    </div>

    <Form v-slot="{ errors, processing }" v-bind="update(research.id, finding.id).form()" class="space-y-4">
        <AppFormControl label="Title" required :error="errors.title">
            <AppInput name="title" :default-value="finding.title" placeholder="Enter finding title" />
        </AppFormControl>

        <AppFormControl label="Summary" :error="errors.summary">
            <AppTextarea name="summary" :default-value="finding.summary ?? ''" placeholder="Enter finding summary" />
        </AppFormControl>

        <AppFormControl label="Description" :error="errors.description">
            <AppTextarea name="description" :default-value="finding.description ?? ''"
                placeholder="Enter detailed finding description" />
        </AppFormControl>

        <AppFormControl label="Finding Type" required :error="errors.type">
            <AppSelect name="type" :default-value="finding.type.value" placeholder="Select finding type"
                :options="findingTypeOptions" />
        </AppFormControl>

        <AppFormControl label="Confidence" :error="errors.confidence">
            <AppInput name="confidence" type="number" min="0" max="100" step="0.01"
                :default-value="finding.confidence ?? ''" placeholder="Enter confidence percentage" />
        </AppFormControl>

        <AppFormControl label="Position" :error="errors.position">
            <AppInput name="position" type="number" min="0" :default-value="finding.position ?? 0"
                placeholder="Enter display position" />
        </AppFormControl>

        <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
            <Button type="button" variant="outline" as-child>
                <Link :href="`/admin/research/${research.id}/findings`">
                    Cancel
                </Link>
            </Button>

            <Button type="submit" :disabled="processing">
                {{ processing ? 'Processing...' : 'Update Finding' }}
            </Button>
        </div>
    </Form>
</template>