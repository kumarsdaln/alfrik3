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
    <Transition name="accordion">
        <div v-if="isOpen" class="
                overflow-hidden
            ">
            <div class="
                    px-6 pb-5
                ">
                <slot />
            </div>
        </div>
    </Transition>
</template>

<style scoped>

    .accordion-enter-active,
    .accordion-leave-active {
        transition: all 0.2s ease;
    }

    .accordion-enter-from,
    .accordion-leave-to {
        opacity: 0;
        transform: translateY(-6px);
    }
</style>