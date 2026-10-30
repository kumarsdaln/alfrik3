<script setup lang="ts">
    import {
        AppFormControl,
        AppInput,
        AppTextarea,
    } from '@/components/form'

    import Heading from '@/components/Heading.vue'
    import BackButton from '@/components/ui/BackButton.vue'
    import Button from '@/components/ui/button/Button.vue'

    import { store} from '@/routes/admin/survey/sections'

    import { Form } from '@inertiajs/vue3'

    interface Props {
        survey: {
            id: number
            title: string
        }
    }

    defineProps<Props>()
</script>

<template>
    <div class="flex gap-4 py-5">
        <BackButton :href="`/admin/survey/${survey.id}`" />
        <Heading title="Add Survey Sections" :description="`Organize ${survey.title} into sections.`" />
    </div>

    <Form v-slot="{ errors, processing }" v-bind="store.form(survey.id)" class="mt-6 space-y-4">
        <AppFormControl label="Title" required :error="errors.title">
            <AppInput name="title" placeholder="Enter section title" />
        </AppFormControl>

        <AppFormControl label="Description" :error="errors.description">
            <AppTextarea name="description" placeholder="Enter section description" />
        </AppFormControl>

        <AppFormControl label="Position" :error="errors.position">
            <AppInput name="position" type="number" min="0" value="0" />
        </AppFormControl>

        <div class="flex justify-end">
            <Button type="submit" :disabled="processing">
                {{ processing
                    ? 'Processing...'
                    : 'Add Section'
                }}
            </Button>
        </div>
    </Form>
</template>