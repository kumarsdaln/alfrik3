<script setup lang="ts">
    import { computed, ref } from 'vue'

    interface Props {
        src: string
        alt: string
        caption?: string | null
        aspectRatio?: 'auto' | '16/9' | '4/3' | '3/2' | '1/1'
        fit?: 'cover' | 'contain'
        position?: string
        loading?: 'lazy' | 'eager'
    }

    const props = withDefaults(defineProps<Props>(), {
        caption: null,
        aspectRatio: 'auto',
        fit: 'cover',
        position: 'center',
        loading: 'lazy',
    })

    const hasError = ref(false)

    const aspectRatioClass = computed(() => ({
        auto: '',
        '16/9': 'aspect-video',
        '4/3': 'aspect-[4/3]',
        '3/2': 'aspect-[3/2]',
        '1/1': 'aspect-square',
    })[props.aspectRatio])
</script>

<template>
    <figure class="my-8 w-full">
        <div v-if="!hasError" class="relative w-full overflow-hidden bg-muted/40" :class="aspectRatioClass">
            <img :src="src" :alt="alt" :loading="loading" :class="[
                'block h-full w-full',
                fit === 'cover' ? 'object-cover' : 'object-contain',
            ]" :style="{ objectPosition: position }" @error="hasError = true" />
        </div>

        <div v-else
            class="flex aspect-video w-full items-center justify-center bg-muted/40 text-sm text-muted-foreground">
            Image unavailable
        </div>

        <figcaption v-if="caption" class="mt-3 text-sm leading-relaxed text-muted-foreground">
            {{ caption }}
        </figcaption>
    </figure>
</template>
