<script setup lang="ts">
    import { Textarea } from '@/components/ui/textarea'

    interface Props {
        modelValue?: string
        name?: string
        placeholder?: string
        rows?: number
        disabled?: boolean
        required?: boolean
        readonly?: boolean
        maxlength?: number
        minlength?: number
        autocomplete?: string
        id?: string
    }

    const props = withDefaults(defineProps<Props>(), {
        modelValue: '',
        rows: 5,
        disabled: false,
        required: false,
        readonly: false,
    })

    const emit = defineEmits<{
        'update:modelValue': [value: string]
    }>()
</script>

<template>
    <Textarea
        :id="id ?? name"
        :name="name"
        :value="modelValue"
        :placeholder="placeholder"
        :rows="rows"
        :disabled="disabled"
        :required="required"
        :readonly="readonly"
        :maxlength="maxlength"
        :minlength="minlength"
        :autocomplete="autocomplete"
        @input="
            emit(
                'update:modelValue',
                ($event.target as HTMLTextAreaElement).value,
            )
        "
    />
</template>