<script setup lang="ts">
import { computed } from 'vue'

import type {
    FormOption,
    FormOptionInput,
    FormValue,
} from '@/types/forms'

interface Props {
    name?: string
    options?: FormOptionInput[]
    placeholder?: string
    error?: boolean
    disabled?: boolean
    required?: boolean
}

const props = withDefaults(
    defineProps<Props>(),
    {
        options: () => [],
        placeholder: 'Select an option',
        error: false,
        disabled: false,
        required: false,
    },
)

const model = defineModel<FormValue>({
    default: '',
})

const normalizedOptions = computed<FormOption[]>(() =>
    props.options.map((option) =>
        typeof option === 'object'
        && option !== null
        && 'value' in option
            ? (option as FormOption)
            : {
                value: option as FormValue,
                label: String(option ?? ''),
            },
    ),
)

const selectClass = computed(() => [
    // Base
    'w-full min-w-0 rounded-xl border px-3 py-2.5',
    'text-sm outline-none',

    // Appearance
    'bg-white text-zinc-900',
    'dark:bg-zinc-900 dark:text-zinc-100',

    // Interaction
    'transition-[border-color,box-shadow,background-color]',
    'duration-200',

    props.error
        ? [
            'border-red-500',
            'focus:border-red-500',
            'focus:ring-2',
            'focus:ring-red-500/20',
        ]
        : [
            'border-zinc-300',
            'dark:border-zinc-700',

            'hover:border-zinc-400',
            'dark:hover:border-zinc-600',

            'focus:border-brand',
            'focus:ring-2',
            'focus:ring-brand/20',
        ],

    props.disabled && [
        'cursor-not-allowed',
        'bg-zinc-100',
        'opacity-60',
        'dark:bg-zinc-800',
    ],
])
</script>

<template>
    <select
        v-model="model"
        :name="props.name"
        :disabled="props.disabled"
        :required="props.required"
        :aria-invalid="props.error || undefined"
        :class="selectClass"
    >
        <option
            value=""
            disabled
        >
            {{ props.placeholder }}
        </option>

        <option
            v-for="option in normalizedOptions"
            :key="String(option.value)"
            :value="option.value"
            :disabled="option.disabled"
        >
            {{ option.label }}
        </option>
    </select>
</template>