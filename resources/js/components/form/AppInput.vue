<script setup lang="ts">
import { useId } from 'vue'

import { Input } from '@/components/ui/input'

interface Props {
    name?: string
    type?: string
    defaultValue?: string | number
    placeholder?: string
    disabled?: boolean
    required?: boolean
    autocomplete?: string
    id?: string
    error?: boolean
}

const props = withDefaults(
    defineProps<Props>(),
    {
        type: 'text',
        disabled: false,
        required: false,
        error: false,
    },
)

const model = defineModel<string | number>()

const generatedId = useId()

const inputId = props.id ?? generatedId
</script>

<template>
    <Input
        :id="inputId"
        v-model="model"
        :default-value="props.defaultValue"
        :name="props.name"
        :type="props.type"
        :placeholder="props.placeholder"
        :disabled="props.disabled"
        :required="props.required"
        :autocomplete="props.autocomplete"
        :aria-invalid="props.error || undefined"
    />
</template>