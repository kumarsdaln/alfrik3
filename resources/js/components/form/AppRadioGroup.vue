<script setup lang="ts">
    import { computed, useId } from 'vue'
    import AppFormField from '@/components/ui/AppFormField.vue'
    import type {
        FormOption,
        FormOptionInput,
        FormValue,
    } from '@/types/forms'

    interface Props {
        name?: string
        label?: string
        error?: string
        options?: FormOptionInput[]
        disabled?: boolean
        inline?: boolean
    }

    const props = withDefaults(
        defineProps<Props>(),
        {
            options: () => [],
            disabled: false,
            inline: false,
        }
    )

    const model = defineModel<FormValue>({
        default: '',
    })

    const id = useId()

    const normalizedOptions = computed<FormOption[]>(() =>
        props.options.map(option =>
            typeof option === 'object' && option !== null && 'value' in option
                ? option as FormOption
                : {
                    value: option as FormValue,
                    label: String(option ?? ''),
                }
        )
    )
</script>

<template>
    <AppFormField
        :label="label"
        :error="error"
    >
        <div :class="inline ? 'flex flex-wrap gap-4' : 'space-y-2'">
            <label
                v-for="option in normalizedOptions"
                :key="String(option.value)"
                class="flex items-center gap-2 text-sm text-zinc-700 dark:text-zinc-300"
            >
                <input
                    v-model="model"
                    type="radio"
                    :name="name"
                    :value="option.value"
                    :disabled="disabled || option.disabled"
                    class="border-zinc-300 text-brand focus:ring-brand dark:border-zinc-700"
                >
                {{ option.label }}
            </label>
        </div>
    </AppFormField>
</template>
