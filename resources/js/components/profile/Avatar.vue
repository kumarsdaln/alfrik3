<script setup lang="ts">
import { computed, ref, watch } from 'vue'

interface Props {
    image?: string | null
    name?: string | null
    size?: string
    textSize?: string
    rounded?: 'full' | 'xl' | 'lg' | 'md'
}

const props = withDefaults(
    defineProps<Props>(),
    {
        image: null,
        name: null,
        size: 'h-12 w-12',
        textSize: 'text-sm',
        rounded: 'full',
    },
)

const imageError = ref(false)

const initial = computed(() => {
    const name = props.name?.trim()

    if (!name) {
        return '?'
    }

    return name.charAt(0).toUpperCase()
})

const roundedClass = computed(() => {
    const classes = {
        full: 'rounded-full',
        xl: 'rounded-xl',
        lg: 'rounded-lg',
        md: 'rounded-md',
    }

    return classes[props.rounded]
})

const showImage = computed(() => {
    return Boolean(props.image) && !imageError.value
})

watch(
    () => props.image,
    () => {
        imageError.value = false
    },
)

function handleImageError(): void {
    imageError.value = true
}
</script>

<template>
    <div
        :class="[
            size,
            textSize,
            roundedClass,

            'relative shrink-0 overflow-hidden',
            'flex items-center justify-center',
            'font-semibold uppercase',

            !showImage && [
                'bg-brand',
                'text-white',
            ],
        ]"
    >
        <img
            v-if="showImage"
            :src="image!"
            :alt="name || 'Avatar'"
            class="h-full w-full object-cover"
            loading="lazy"
            @error="handleImageError"
        />

        <span
            v-else
            aria-hidden="true"
            class="select-none"
        >
            {{ initial }}
        </span>
    </div>
</template>