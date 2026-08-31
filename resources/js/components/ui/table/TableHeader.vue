<script setup lang="ts">
    defineOptions({
        inheritAttrs: false,
    })
</script>

<template>
    <thead class="
            [&_tr]:border-b
            [&_tr]:border-border-light
            dark:[&_tr]:border-border-dark
        " v-bind="$attrs">
        <slot />
    </thead>
</template>