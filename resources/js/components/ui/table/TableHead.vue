<script setup lang="ts">
    defineOptions({
        inheritAttrs: false,
    })
</script>

<template>
    <th class="
            h-11
            px-4
            text-left
            align-middle
            font-redhat
            text-xs
            font-semibold
            uppercase
            tracking-wide
            text-content-light-muted
            [&:has([role=checkbox])]:pr-0
            dark:text-content-dark-muted
        " v-bind="$attrs">
        <slot />
    </th>
</template>