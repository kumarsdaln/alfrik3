<script setup lang="ts">
    import { Link } from '@inertiajs/vue3';
    import PlusIcon from '@/Icons/PlusIcon.vue';
    import AppSelect from './AppSelect.vue';
    import type {
        FormOptionInput,
        FormValue,
    } from '@/types/forms'

    interface Props {
        modelValue?: FormValue
        label?: string
        placeholder?: string
        options?: FormOptionInput[]
        error?: string
        href: string
    }

    withDefaults(
        defineProps<Props>(),
        {
            modelValue: null,
            options: () => [],
        }
    )

    defineEmits<{
        (e: 'update:modelValue', value: FormValue): void
    }>()
</script>

<template>
    <div class="space-y-1">
        <label v-if="label" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
            {{ label }}
        </label>

        <div class="flex">
            <div class="flex-1">
                <AppSelect 
                    :model-value="modelValue" 
                    :placeholder="placeholder"
                    :options="options"
                    @update:model-value="$emit('update:modelValue', $event)" />
            </div>

            <Link :href="href" class="flex shrink-0 w-14 items-center justify-center self-stretch
                border border-l-0 rounded-r-md border-gray-300
                hover:bg-gray-100
                dark:border-gray-700
                dark:hover:bg-gray-700
                transition-all duration-200">
                <PlusIcon class="h-5 w-5" />
            </Link>
        </div>

        <p v-if="error" class="text-sm text-red-500">
            {{ error }}
        </p>
    </div>
</template>
