<script setup lang="ts">
    defineOptions({
        inheritAttrs: false,
    })
</script>

<template>
    <tfoot class="
            border-t
            border-border-light
            bg-surface-light/50
            font-medium
            dark:border-border-dark
            dark:bg-surface-dark/50
        " v-bind="$attrs">
        <slot />
    </tfoot>
</template>