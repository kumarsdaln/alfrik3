<script setup lang="ts">
import { useId } from 'vue'

import AppFormField from '@/Components/Ui/AppFormField.vue'
import AppInputControl from '@/Components/Ui/AppInputControl.vue'

import type { FormValue } from '@/types/forms'

interface Props {
    name?: string
    label?: string
    type?: string
    error?: string
    placeholder?: string
    disabled?: boolean
    required?: boolean
    autocomplete?: string
}

withDefaults(
    defineProps<Props>(),
    {
        type: 'text',
        disabled: false,
        required: false,
    },
)

const model = defineModel<FormValue>({
    default: '',
})

const id = useId()
</script>

<template>
    <AppFormField
        :id="id"
        :label="label"
        :error="error"
        :required="required"
    >
        <AppInputControl
            :id="id"
            v-model="model"
            :name="name"
            :type="type"
            :placeholder="placeholder"
            :disabled="disabled"
            :required="required"
            :autocomplete="autocomplete"
            :error="!!error"
        />
    </AppFormField>
</template>