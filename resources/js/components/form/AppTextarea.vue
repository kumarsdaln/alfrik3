<script setup lang="ts">
import { useId } from 'vue'

import AppFormField from '@/Components/Ui/AppFormField.vue'
import AppTextareaControl from '@/Components/Ui/AppTextareaControl.vue'

interface Props {
    name?: string
    label?: string
    placeholder?: string
    rows?: number
    error?: string
    disabled?: boolean
    required?: boolean
    defaultValue?: string
    autocomplete?: string
}

withDefaults(
    defineProps<Props>(),
    {
        rows: 4,
        disabled: false,
        required: false,
        defaultValue: '',
    },
)

const model = defineModel<string>()

const id = useId()
</script>

<template>
    <AppFormField
        :id="id"
        :label="label"
        :error="error"
        :required="required"
    >
        <AppTextareaControl
            :id="id"
            v-model="model"
            :name="name"
            :default-value="defaultValue"
            :rows="rows"
            :placeholder="placeholder"
            :disabled="disabled"
            :required="required"
            :autocomplete="autocomplete"
            :error="!!error"
            :aria-describedby="error ? `${id}-error` : undefined"
        />
    </AppFormField>
</template>