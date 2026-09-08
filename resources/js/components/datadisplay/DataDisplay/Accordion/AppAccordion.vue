<script setup>
    import {
        provide,
        ref,
    } from 'vue'

    const props = defineProps({
        type: {
            type: String,
            default: 'single',
        }
    })

    const activeItems = ref([])

    const toggleItem = (value) => {
        if (props.type === 'single') {
            activeItems.value =
                activeItems.value[0] === value
                    ? []
                    : [value]

            return
        }

        if (activeItems.value.includes(value)) {
            activeItems.value =
                activeItems.value.filter(
                    item => item !== value
                )

            return
        }

        activeItems.value.push(value)
    }

    provide('accordion', {
        activeItems,
        toggleItem,
    })
</script>

<template>
    <div class="
            rounded-3xl
            border border-neutral-200
            bg-white

            dark:border-neutral-800
            dark:bg-neutral-950
        ">
        <slot />
    </div>
</template>