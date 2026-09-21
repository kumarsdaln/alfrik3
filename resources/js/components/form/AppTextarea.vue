<script setup lang="ts">
import { computed } from 'vue'

import { Textarea } from '@/components/ui/textarea'

interface Props {
    defaultValue?: string
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
    rows: 5,
    disabled: false,
    required: false,
    readonly: false,
})

const model = defineModel<string>()

const value = computed(() => model.value ?? props.defaultValue ?? '')

const textareaId = computed(() => props.id ?? props.name)
</script>

<template>
    <Textarea
        :id="textareaId"
        :name="name"
        :value="value"
        :placeholder="placeholder"
        :rows="rows"
        :disabled="disabled"
        :required="required"
        :readonly="readonly"
        :maxlength="maxlength"
        :minlength="minlength"
        :autocomplete="autocomplete"
        @input="model = ($event.target as HTMLTextAreaElement).value"
    />
</template>