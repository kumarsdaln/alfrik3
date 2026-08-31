<script setup lang="ts">
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/components/ui/select'

import type {
    FormOptionInput,
    FormSelectValue,
} from '@/types/forms'

interface Props {
    name?: string
    placeholder?: string
    disabled?: boolean
    options?: FormOptionInput[]
    required?: boolean
    id?: string
    error?: boolean
}

const props = withDefaults(
    defineProps<Props>(),
    {
        placeholder: 'Select an option',
        disabled: false,
        options: () => [],
        required: false,
        error: false,
    },
)

const model = defineModel<FormSelectValue | undefined>({
    default: undefined,
})

function getValue(option: FormOptionInput): FormSelectValue {
    return typeof option === 'object'
        ? option.value
        : option
}

function getLabel(option: FormOptionInput): string {
    return typeof option === 'object'
        ? option.label
        : String(option)
}

function isDisabled(option: FormOptionInput): boolean {
    return typeof option === 'object'
        ? Boolean(option.disabled)
        : false
}
</script>

<template>
    <Select
        v-model="model"
        :name="name"
        :disabled="disabled"
        :required="required"
    >
        <SelectTrigger
            :id="id"
            class="w-full"
            :aria-invalid="error || undefined"
        >
            <SelectValue :placeholder="placeholder" />
        </SelectTrigger>

        <SelectContent>
            <SelectItem
                v-for="option in options"
                :key="String(getValue(option))"
                :value="getValue(option)"
                :disabled="isDisabled(option)"
            >
                {{ getLabel(option) }}
            </SelectItem>
        </SelectContent>
    </Select>
</template>