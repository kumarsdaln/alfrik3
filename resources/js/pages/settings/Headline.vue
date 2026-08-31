<script setup lang="ts">
import { ref } from 'vue'
import { Form, Head } from '@inertiajs/vue3'

import HeadlineController from '@/actions/App/Http/Controllers/Settings/HeadlineController'

import Heading from '@/components/Heading.vue'
import AppFormControl from '@/components/form/AppFormControl.vue'
import AppInput from '@/components/form/AppInput.vue'

import { Button } from '@/components/ui/button'

interface Props {
    headline: string | null
}

const props = defineProps<Props>()

const headline = ref(props.headline ?? '')
</script>

<template>
    <Head title="Headline settings" />

    <h1 class="sr-only">
        Headline settings
    </h1>

    <div class="flex flex-col space-y-6">

        <Heading
            variant="small"
            title="Headline"
            description="Write a short statement that describes who you are and what you do"
        />

        <Form
            v-bind="HeadlineController.update.form()"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <AppFormControl
                label="Headline"
                :error="errors.headline"
            >
                <AppInput
                    v-model="headline"
                    name="headline"
                    placeholder="e.g. Software Engineer | Educator | Founder"
                    required
                />
            </AppFormControl>

            <p class="-mt-4 text-sm text-muted-foreground">
                Your headline appears below your name on your public profile.
            </p>

            <div class="flex items-center gap-4">
                <Button
                    type="submit"
                    :disabled="processing"
                >
                    Save
                </Button>
            </div>
        </Form>
    </div>
</template>