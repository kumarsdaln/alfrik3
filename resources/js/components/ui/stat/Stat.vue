<script setup lang="ts">
    defineOptions({
        inheritAttrs: false,
    })
</script>

<template>
    <div class="
            bg-surface-light
            p-5
            dark:bg-surface-dark
        " v-bind="$attrs">
        <slot />
    </div>
</template>