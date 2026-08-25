<script setup lang="ts">
import { computed } from 'vue'
import type { FormValue } from '@/types/forms'

interface Props {
    type?: string
    error?: boolean
    disabled?: boolean
}

const props = withDefaults(
    defineProps<Props>(),
    {
        type: 'text',
        error: false,
        disabled: false,
    },
)

const model = defineModel<FormValue>({
    default: '',
})

const inputClass = computed(() => [
    // Base
    'w-full min-w-0 rounded-xl border px-3 py-2.5',
    'text-sm outline-none',

    // Appearance
    'bg-white text-zinc-900',
    'dark:bg-zinc-900 dark:text-zinc-100',

    // Placeholder
    'placeholder:text-zinc-400',
    'dark:placeholder:text-zinc-500',

    // File input
    'file:mr-3 file:border-0 file:bg-transparent',
    'file:text-sm file:font-medium file:text-zinc-700',
    'dark:file:text-zinc-300',

    // Transition
    'transition-[border-color,box-shadow,background-color]',
    'duration-200',

    // Error / Normal state
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

    // Disabled
    props.disabled && [
        'cursor-not-allowed',
        'bg-zinc-100',
        'opacity-60',
        'dark:bg-zinc-800',
    ],
])
</script>

<template>
    <input
        v-model="model"
        :type="type"
        :disabled="disabled"
        :aria-invalid="error || undefined"
        :class="inputClass"
    />
</template>