<script setup>
    defineProps({
        value: {
            type: [String, Number],
            required: true,
        }
    })
</script>

<template>
    <div class="
            border-b border-neutral-200
            last:border-b-0

            dark:border-neutral-800
        ">
        <slot />
    </div>
</template>