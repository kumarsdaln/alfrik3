<script setup lang="ts">
    defineOptions({
        inheritAttrs: false,
    })
</script>

<template>
    <tr class="
            border-b
            border-border-light
            transition-colors
            hover:bg-surface-light/50
            dark:border-border-dark
            dark:hover:bg-surface-dark/50
        " v-bind="$attrs">
        <slot />
    </tr>
</template>