<script setup>
    const props = defineProps({
        size: {
            type: String,
            default: 'w-5 h-5 sm:w-6 sm:h-6'
        },
        showLabel: {
            type: Boolean,
            default: false
        }
    })
</script>

<template>
    <div class="inline-flex items-center gap-1">
        <img src="/frontend/images/verified.png" alt="verified" :class="size" />

        <!-- Optional Label -->
        <span v-if="showLabel" class="text-xs text-brand dark:text-brand font-medium">
            Verified
        </span>
    </div>
</template>