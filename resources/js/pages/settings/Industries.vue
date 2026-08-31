<script setup lang="ts">
import { computed, ref } from 'vue'
import { Form, Head } from '@inertiajs/vue3'

import IndustryController from '@/actions/App/Http/Controllers/Settings/IndustryController'

import Heading from '@/components/Heading.vue'
import AppFormControl from '@/components/form/AppFormControl.vue'
import AppMultiSelect from '@/components/form/AppMultiSelect.vue'

import { Button } from '@/components/ui/button'

interface Industry {
    id: number
    name: string
}

interface Props {
    industries: Industry[]
    industryIds: number[]
}

const props = defineProps<Props>()

/*
|--------------------------------------------------------------------------
| Selected Industries
|--------------------------------------------------------------------------
*/

const selectedIndustryIds = ref<number[]>(
    [...(props.industryIds ?? [])],
)

/*
|--------------------------------------------------------------------------
| Industry Options
|--------------------------------------------------------------------------
*/

const industryOptions = computed(() =>
    props.industries.map(industry => ({
        value: industry.id,
        label: industry.name,
    })),
)
</script>

<template>
    <Head title="Industries" />

    <h1 class="sr-only">
        Industries
    </h1>

    <div class="flex flex-col space-y-6">

        <Heading
            variant="small"
            title="Industries"
            description="Select the industries that represent your work and interests"
        />

        <Form
            v-bind="IndustryController.update.form()"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <AppFormControl :error="errors.industry_ids">
                <AppMultiSelect
                    v-model="selectedIndustryIds"
                    name="industry_ids"
                    placeholder="Select industries"
                    :options="industryOptions"
                />
            </AppFormControl>

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