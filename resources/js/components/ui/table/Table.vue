<script setup lang="ts">
    defineOptions({
        inheritAttrs: false,
    })
</script>

<template>
    <div class="w-full overflow-x-auto" v-bind="$attrs">
        <table class="
                w-full
                caption-bottom
                border-collapse
                text-sm
            ">
            <slot />
        </table>
    </div>
</template>