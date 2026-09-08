<script setup lang="ts">
    import { Switch } from '../ui/switch'

    interface Props {
        modelValue?: boolean
        disabled?: boolean
        required?: boolean
        name?: string
        id?: string
    }

    const props = withDefaults(defineProps<Props>(), {
        modelValue: false,
        disabled: false,
        required: false,
        name: undefined,
        id: undefined,
    })

    const emit = defineEmits<{
        'update:modelValue': [value: boolean]
    }>()
</script>

<template>
    <Switch v-bind="props" @update:model-value="emit('update:modelValue', $event)" />
</template>