<script setup>
    defineProps({
        label: {
            type: String,
            default: '',
        },

        error: {
            type: String,
            default: '',
        },

        required: {
            type: Boolean,
            default: false,
        },

        fullWidth: {
            type: Boolean,
            default: false,
        }
    })
</script>

<template>

    <div :class="[
        'flex flex-col gap-2',

        fullWidth
            ? 'col-span-full'
            : ''
    ]">

        <!-- LABEL -->
        <div v-if="label" class="flex items-center gap-1">

            <label class="text-xs font-semibold

                       uppercase tracking-wider

                       text-gray-500
                       dark:text-gray-400">
                {{ label }}
            </label>

            <!-- REQUIRED -->
            <span v-if="required" class="text-red-500">
                *
            </span>

        </div>

        <!-- FIELD -->
        <div>

            <slot></slot>

        </div>

        <!-- ERROR -->
        <p v-if="error" class="text-xs text-red-500">
            {{ error }}
        </p>

    </div>

</template>