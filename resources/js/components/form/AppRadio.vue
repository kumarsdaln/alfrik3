<script setup lang="ts">
    import { computed, useId } from 'vue'
    import { Radio, RadioGroup } from '../ui/radio'

    interface RadioOption {
        value: string | number
        label: string
        description?: string
        disabled?: boolean
    }

    interface Props {
        modelValue?: string | number
        options: RadioOption[]
        name?: string
        required?: boolean
        disabled?: boolean
        orientation?: 'vertical' | 'horizontal'
    }

    const props = withDefaults(defineProps<Props>(), {
        modelValue: '',
        name: undefined,
        required: false,
        disabled: false,
        orientation: 'vertical',
    })

    const emit = defineEmits<{
        'update:modelValue': [value: string | number]
    }>()

    const groupId = useId()

    const value = computed({
        get: () => props.modelValue,
        set: (value) => emit('update:modelValue', value),
    })
</script>

<template>
    <RadioGroup :id="groupId" v-model="value" :name="name" :disabled="disabled" :required="required"
        :orientation="orientation" :class="orientation === 'horizontal'
                ? 'flex flex-wrap gap-x-6 gap-y-3'
                : 'space-y-3'
            ">
        <div v-for="option in options" :key="String(option.value)" class="flex items-start gap-3">
            <Radio :value="option.value" :disabled="disabled || option.disabled" :aria-describedby="option.description
                    ? `${groupId}-${String(option.value)}-description`
                    : undefined
                " class="mt-0.5" />

            <div class="min-w-0">
                <label :for="`${groupId}-${String(option.value)}`" class="
                        cursor-pointer
                        font-redhat
                        text-sm
                        leading-5
                        text-content-light
                        dark:text-content-dark
                    " :class="[
                        disabled || option.disabled
                            ? 'cursor-not-allowed opacity-50'
                            : '',
                    ]">
                    {{ option.label }}
                </label>

                <p v-if="option.description" :id="`${groupId}-${String(option.value)}-description`" class="
                        mt-0.5
                        font-redhat
                        text-xs
                        leading-relaxed
                        text-content-light-muted
                        dark:text-content-dark-muted
                    ">
                    {{ option.description }}
                </p>
            </div>
        </div>
    </RadioGroup>
</template>