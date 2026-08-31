<script setup lang="ts">
import { useId } from 'vue'

import { Input } from '@/components/ui/input'

interface Props {
    name?: string
    type?: string
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

const model = defineModel<string | number>({
    default: '',
})

const generatedId = useId()

const inputId = props.id ?? generatedId
</script>

<template>
    <Input
        :id="inputId"
        v-model="model"
        :name="name"
        :type="type"
        :placeholder="placeholder"
        :disabled="disabled"
        :required="required"
        :autocomplete="autocomplete"
        :aria-invalid="error || undefined"
    />
</template>