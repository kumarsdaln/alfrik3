<script setup lang="ts">
import { useId } from 'vue'
import {
    CheckboxIndicator,
    CheckboxRoot,
} from 'reka-ui'
import { Check } from '@lucide/vue'

interface Props {
    modelValue?: boolean | number | string
    defaultValue?: boolean | number | string
    disabled?: boolean
    required?: boolean
    name?: string
    id?: string
    label?: string
    falseValue?: boolean | number | string
    trueValue?: boolean | number | string
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: undefined,
    defaultValue: false,
    disabled: false,
    required: false,
    trueValue: true,
    falseValue: false,
})

const emit = defineEmits<{
    'update:modelValue': [value: boolean | number | string]
}>()

const generatedId = useId()
const checkboxId = props.id ?? generatedId

function updateValue(value: boolean | number | string) {
    emit('update:modelValue', value)
}
</script>

<template>
    <div class="flex items-center gap-2">
        <CheckboxRoot
            :id="checkboxId"
            :name="props.name"
            :model-value="props.modelValue"
            :default-value="props.defaultValue"
            :true-value="props.trueValue"
            :false-value="props.falseValue"
            :disabled="props.disabled"
            :required="props.required"
            class="
                peer
                flex size-4 shrink-0 items-center justify-center
                border border-input
                bg-background
                outline-none
                transition-colors
                focus-visible:ring-2
                focus-visible:ring-ring
                focus-visible:ring-offset-2
                disabled:cursor-not-allowed
                disabled:opacity-50
                data-[state=checked]:border-primary
                data-[state=checked]:bg-primary
                data-[state=checked]:text-primary-foreground
            "
            @update:model-value="updateValue"
        >
            <CheckboxIndicator>
                <Check class="size-3.5" />
            </CheckboxIndicator>
        </CheckboxRoot>

        <label
            v-if="props.label"
            :for="checkboxId"
            class="
                cursor-pointer
                text-sm font-medium leading-none
                peer-disabled:cursor-not-allowed
                peer-disabled:opacity-70
            "
        >
            {{ props.label }}
        </label>
    </div>
</template>