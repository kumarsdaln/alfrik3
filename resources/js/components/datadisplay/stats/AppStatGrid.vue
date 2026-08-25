<script setup>
    defineProps({
        columns: {
            type: String,
            default: 'xl:grid-cols-4',
        }
    })
</script>

<template>
    <div class="
            grid gap-4
            grid-cols-1
            sm:grid-cols-2
        " :class="columns">
        <slot />
    </div>
</template>