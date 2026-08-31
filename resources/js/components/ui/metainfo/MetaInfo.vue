<script setup lang="ts">
    import MetaItem from './MetaItem.vue'

    export interface MetaInfoItem {
        value: string
        label?: string
        icon?: object
        href?: string
    }

    defineProps<{
        items: MetaInfoItem[]
    }>()
</script>

<template>
    <div class="
            flex
            flex-wrap
            items-center
            gap-x-4
            gap-y-2
        ">
        <template v-for="(item, index) in items" :key="index">
            <span v-if="index > 0" class="
                    text-border-light
                    dark:text-border-dark
                " aria-hidden="true">
                /
            </span>

            <MetaItem
                :value="item.value"
                :label="item.label"
                :icon="item.icon"
                :href="item.href"
            />
        </template>
    </div>
</template>