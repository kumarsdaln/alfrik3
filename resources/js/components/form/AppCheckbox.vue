<script setup lang="ts">
    import AppFormField from '@/components/ui/AppFormField.vue'

    interface Props {
        modelValue?: boolean
        label?: string
        error?: string
        disabled?: boolean
    }

    withDefaults(
        defineProps<Props>(),
        {
            modelValue: false,
            disabled: false,
        }
    )

    defineEmits<{
        (e: 'update:modelValue', value: boolean): void
    }>()
</script>

<template>
    <AppFormField :error="error">
        <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
            <input type="checkbox" :checked="modelValue" :disabled="disabled"
                @change="$emit('update:modelValue', ($event.target as HTMLInputElement).checked)"
                class="rounded border-gray-300 dark:border-gray-600
               text-brand focus:ring-brand" />
            {{ label }}
        </label>
    </AppFormField>
</template>
