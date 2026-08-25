<script setup lang="ts">
interface Props {
    id?: string
    label?: string
    description?: string
    error?: string
    required?: boolean
}

withDefaults(
    defineProps<Props>(),
    {
        required: false,
    }
)
</script>

<template>
    <div class="space-y-1.5">
        <label
            v-if="label"
            :for="id"
            class="block text-sm font-medium text-zinc-700 dark:text-zinc-300"
        >
            {{ label }}
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <slot />

        <p
            v-if="description && !error"
            class="text-xs text-zinc-500 dark:text-zinc-400"
        >
            {{ description }}
        </p>

        <p
            v-if="error"
            class="text-xs font-medium text-red-500"
        >
            {{ error }}
        </p>
    </div>
</template>
