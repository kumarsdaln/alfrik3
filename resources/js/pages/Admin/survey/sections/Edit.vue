<script setup lang="ts">
    import {
        AppFormControl,
        AppInput,
        AppTextarea,
    } from '@/components/form'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'

    import { Form, Link } from '@inertiajs/vue3'

    import type { SurveySection } from '@/types'

    interface Props {
        survey: {
            id: number
            title: string
        }

        section: SurveySection
    }

    const props = defineProps<Props>()
</script>

<template>
    <div class="flex gap-4 py-5">
        <BackButton :href="`/admin/survey/${survey.id}/sections`" />

        <Heading title="Edit Survey Section" :description="`Update the section for ${survey.title}.`" />
    </div>

    <Form v-slot="{ errors, processing }" :action="`/admin/survey/${survey.id}/sections/${section.id}`" method="put"
        class="space-y-4">
        <AppFormControl label="Title" required :error="errors.title">
            <AppInput name="title" :default-value="section.title" placeholder="Enter section title" />
        </AppFormControl>

        <AppFormControl label="Description" :error="errors.description">
            <AppTextarea name="description" :default-value="section.description ?? ''"
                placeholder="Enter section description" />
        </AppFormControl>

        <AppFormControl label="Position" :error="errors.position">
            <AppInput name="position" type="number" min="0" :default-value="section.position" />
        </AppFormControl>

        <div class="flex justify-end gap-3 border-t border-border-light pt-6 dark:border-border-dark">
            <Button type="button" variant="outline" as-child>
                <Link :href="`/admin/survey/${survey.id}/sections`">
                    Cancel
                </Link>
            </Button>

            <Button type="submit" :disabled="processing">
                {{ processing
                    ? 'Processing...'
                    : 'Update Section'
                }}
            </Button>
        </div>
    </Form>
</template>