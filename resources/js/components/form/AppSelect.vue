<script setup lang="ts">
import { useId } from 'vue'

import AppFormField from '@/components/ui/AppFormField.vue'
import AppSelectControl from '@/components/ui/AppSelectControl.vue'

import type {
    FormOptionInput,
    FormValue,
} from '@/types/forms'

interface Props {
    name?: string
    label?: string
    placeholder?: string
    error?: string
    disabled?: boolean
    options?: FormOptionInput[]
    required?: boolean
}

const model = defineModel<FormValue>({
    default: '',
})

withDefaults(
    defineProps<Props>(),
    {
        placeholder: 'Select an option',
        disabled: false,
        options: () => [],
        required: false,
    },
)

const id = useId()
</script>

<template>
    <AppFormField
        :id="id"
        :label="label"
        :error="error"
        :required="required"
    >
        <AppSelectControl
            :id="id"
            v-model="model"
            :name="name"
            :placeholder="placeholder"
            :options="options"
            :disabled="disabled"
            :required="required"
            :error="!!error"
        />
    </AppFormField>
</template>