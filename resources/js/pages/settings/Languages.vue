<script setup lang="ts">
import { computed, ref } from 'vue'
import { Form, Head } from '@inertiajs/vue3'

import LanguageController from '@/actions/App/Http/Controllers/Settings/LanguageController'

import Heading from '@/components/Heading.vue'
import AppFormControl from '@/components/form/AppFormControl.vue'
import AppMultiSelect from '@/components/form/AppMultiSelect.vue'

import { Button } from '@/components/ui/button'

interface Language {
    id: number
    name: string
    native: string
}

interface Props {
    languages: Language[]
    languageIds: number[]
}

const props = defineProps<Props>()

/*
|--------------------------------------------------------------------------
| Selected Languages
|--------------------------------------------------------------------------
*/

const selectedLanguageIds = ref<number[]>(
    [...(props.languageIds ?? [])],
)

/*
|--------------------------------------------------------------------------
| Language Options
|--------------------------------------------------------------------------
*/

const languageOptions = computed(() =>
    props.languages.map(language => ({
        value: language.id,
        label: `${language.name} (${language.native})`,
    })),
)
</script>

<template>
    <Head title="Languages" />

    <h1 class="sr-only">
        Languages
    </h1>

    <div class="flex flex-col space-y-6">

        <Heading
            variant="small"
            title="Languages"
            description="Select the languages you speak or use professionally"
        />

        <Form
            v-bind="LanguageController.update.form()"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <AppFormControl :error="errors.language_ids">
                <AppMultiSelect
                    v-model="selectedLanguageIds"
                    name="language_ids"
                    placeholder="Select languages"
                    :options="languageOptions"
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