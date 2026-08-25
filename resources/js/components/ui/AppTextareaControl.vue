<script setup lang="ts">
import { computed, ref, watch } from 'vue'

interface Props {
    modelValue?: string
    defaultValue?: string
    rows?: number
    error?: boolean
    disabled?: boolean
}

const props = withDefaults(
    defineProps<Props>(),
    {
        defaultValue: '',
        rows: 4,
        error: false,
        disabled: false,
    },
)

const emit = defineEmits<{
    'update:modelValue': [value: string]
}>()

const internalValue = ref(
    props.modelValue ?? props.defaultValue,
)

watch(
    () => props.modelValue,
    (value) => {
        if (value !== undefined) {
            internalValue.value = value
        }
    },
)

const value = computed({
    get: () => {
        return props.modelValue !== undefined
            ? props.modelValue
            : internalValue.value
    },

    set: (newValue: string) => {
        internalValue.value = newValue

        emit('update:modelValue', newValue)
    },
})

const textareaClass = computed(() => [
    'w-full min-w-0 rounded-xl border px-3 py-2.5',
    'text-sm leading-relaxed outline-none',
    'resize-y',

    'bg-white text-zinc-900',
    'dark:bg-zinc-900 dark:text-zinc-100',

    'placeholder:text-zinc-400',
    'dark:placeholder:text-zinc-500',

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
    <textarea
        v-model="value"
        :rows="rows"
        :disabled="disabled"
        :aria-invalid="error || undefined"
        :class="textareaClass"
    />
</template>