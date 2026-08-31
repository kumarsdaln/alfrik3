<script setup lang="ts">
    defineOptions({
        inheritAttrs: false,
    })
</script>

<template>
    <caption class="
            mt-4
            text-left
            font-redhat
            text-sm
            text-content-light-muted
            dark:text-content-dark-muted
        " v-bind="$attrs">
        <slot />
    </caption>
</template>