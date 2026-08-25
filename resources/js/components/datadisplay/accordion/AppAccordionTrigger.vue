<script setup>
    import {
        computed,
        inject,
    } from 'vue'

    const props = defineProps({
        value: {
            type: [String, Number],
            required: true,
        }
    })

    const accordion = inject('accordion')

    const isOpen = computed(() => {
        return accordion.activeItems.value.includes(
            props.value
        )
    })
</script>

<template>
    <button class="
            flex w-full items-center justify-between
            gap-4
            px-6 py-5
            text-left
            transition-colors

            hover:bg-neutral-50
            dark:hover:bg-neutral-900
        " @click="
            accordion.toggleItem(value)
            ">
        <div class="flex-1">
            <slot />
        </div>

        <div class="
                text-neutral-400
                transition-transform
                duration-200
            " :class="[
                isOpen &&
                'rotate-180'
            ]">
            ⌄
        </div>
    </button>
</template>