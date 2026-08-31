<script setup lang="ts">
defineOptions({
    inheritAttrs: false,
})
</script>

<template>
    <td
        class="
            px-4
            py-4
            align-middle
            font-redhat
            text-sm
            text-content-light
            [&:has([role=checkbox])]:pr-0
            dark:text-content-dark
        "
        v-bind="$attrs"
    >
        <slot />
    </td>
</template>