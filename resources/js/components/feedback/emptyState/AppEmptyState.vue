<script setup>
    import { computed } from 'vue'

    import {
        emptyStateSizes,
    } from './empty-state.variants'
    import AppHeading from '@/Components/Ui/AppHeading.vue'
    import AppText from '@/Components/Ui/AppText.vue'

    const props = defineProps({
        title: {
            type: String,
            default: 'Nothing here yet',
        },

        description: {
            type: String,
            default: '',
        },

        size: {
            type: String,
            default: 'md',
        },

        centered: {
            type: Boolean,
            default: true,
        }
    })

    const emptyClass = computed(() => {
        return [
            'flex flex-col',
            'items-center',
            'justify-center',
            'text-center',

            emptyStateSizes[props.size],

            props.centered &&
            'mx-auto',
        ]
    })
</script>

<template>
    <div :class="emptyClass">
        <!-- Icon -->
        <slot name="icon" />

        <!-- Content -->
        <div class="mt-5 space-y-2">
            <AppHeading size="xl" weight="semibold">
                {{ title }}
            </AppHeading>

            <AppText v-if="description" color="muted" class="max-w-md">
                {{ description }}
            </AppText>
        </div>

        <!-- Actions -->
        <div v-if="$slots.actions" class="
                mt-6
                flex flex-wrap
                items-center justify-center
                gap-3
            ">
            <slot name="actions" />
        </div>
    </div>
</template>