<script setup lang="ts">
import { useId } from 'vue'

import { Checkbox } from '../ui/checkbox'

interface Props {
    modelValue?: boolean
    defaultChecked?: boolean
    disabled?: boolean
    required?: boolean
    name?: string
    id?: string
    label?: string
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: undefined,
    defaultChecked: false,
    disabled: false,
    required: false,
    name: undefined,
    id: undefined,
    label: undefined,
})

const emit = defineEmits<{
    'update:modelValue': [value: boolean]
}>()

const generatedId = useId()
const checkboxId = props.id ?? generatedId

function updateValue(value: boolean | 'indeterminate') {
    if (value === 'indeterminate') {
        return
    }

    emit('update:modelValue', value)
}
</script>

<template>
    <div class="flex items-center gap-2">
        <Checkbox
            :id="checkboxId"
            :name="props.name"
            :model-value="props.modelValue"
            :default-value="props.defaultChecked"
            :disabled="props.disabled"
            :required="props.required"
            @update:model-value="updateValue"
        />

        <label
            v-if="props.label"
            :for="checkboxId"
            class="cursor-pointer text-sm font-medium leading-none peer-disabled:cursor-not-allowed peer-disabled:opacity-70"
        >
            {{ props.label }}
        </label>
    </div>
</template>