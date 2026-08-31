<script setup lang="ts">
import { computed, ref } from 'vue'
import { Form, Head } from '@inertiajs/vue3'

import CountryController from '@/actions/App/Http/Controllers/Settings/CountryController'

import Heading from '@/components/Heading.vue'
import AppFormControl from '@/components/form/AppFormControl.vue'
import AppSelect from '@/components/form/AppSelect.vue'

import { Button } from '@/components/ui/button'

interface Country {
    id: number
    name: string
}

interface Props {
    countries: Country[]
    countryId: number | null
}

const props = defineProps<Props>()

/*
|--------------------------------------------------------------------------
| Selected Country
|--------------------------------------------------------------------------
*/

const selectedCountryId = ref<number | undefined>(
    props.countryId ?? undefined,
)

/*
|--------------------------------------------------------------------------
| Country Options
|--------------------------------------------------------------------------
*/

const countryOptions = computed(() =>
    props.countries.map(country => ({
        value: country.id,
        label: country.name,
    })),
)
</script>

<template>
    <Head title="Country settings" />

    <h1 class="sr-only">
        Country settings
    </h1>

    <div class="flex flex-col space-y-6">

        <Heading
            variant="small"
            title="Country"
            description="Choose the country associated with your profile"
        />

        <Form
            v-bind="CountryController.update.form()"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <AppFormControl
                :error="errors.country_id"
                required
            >
                <AppSelect
                    v-model="selectedCountryId"
                    name="country_id"
                    placeholder="Select your country"
                    :options="countryOptions"
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