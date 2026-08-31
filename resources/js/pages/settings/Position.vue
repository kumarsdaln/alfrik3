<script setup lang="ts">
import { computed, ref } from 'vue'
import { Form, Head } from '@inertiajs/vue3'

import PositionController from '@/actions/App/Http/Controllers/Settings/PositionController'

import Heading from '@/components/Heading.vue'
import AppFormControl from '@/components/form/AppFormControl.vue'
import AppSelect from '@/components/form/AppSelect.vue'

import { Button } from '@/components/ui/button'

interface Position {
    id: number
    name: string
}

interface Props {
    positions: Position[]
    positionId: number | null
}

const props = defineProps<Props>()

/*
|--------------------------------------------------------------------------
| Selected Position
|--------------------------------------------------------------------------
*/

const selectedPositionId = ref<number | undefined>(
    props.positionId ?? undefined,
)

/*
|--------------------------------------------------------------------------
| Position Options
|--------------------------------------------------------------------------
*/

const positionOptions = computed(() =>
    props.positions.map(position => ({
        value: position.id,
        label: position.name,
    })),
)
</script>

<template>
    <Head title="Position settings" />

    <h1 class="sr-only">
        Position settings
    </h1>

    <div class="flex flex-col space-y-6">

        <Heading
            variant="small"
            title="Position"
            description="Choose the position that best describes what you do"
        />

        <Form
            v-bind="PositionController.update.form()"
            class="space-y-6"
            v-slot="{ errors, processing }"
        >
            <AppFormControl :error="errors.position_id">
                <AppSelect
                    v-model="selectedPositionId"
                    name="position_id"
                    placeholder="Select your position"
                    :options="positionOptions"
                    :error="!!errors.position_id"
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