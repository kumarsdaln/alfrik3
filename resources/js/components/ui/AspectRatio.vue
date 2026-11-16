
<script setup lang="ts">
import { computed } from 'vue'

type Preset =
  | 'auto'
  | 'square'
  | 'video'
  | 'standard'
  | 'portrait'
  | 'landscape'
  | 'wide'
  | 'ultrawide'

interface Props {
  ratio?: Preset | number | string
  width?: string | number
  class?: string
  overflow?: 'hidden' | 'visible'
  radius?: 'none' | 'sm' | 'md' | 'lg' | 'xl'
}

const props = withDefaults(defineProps<Props>(), {
  ratio: 'video',
  overflow: 'hidden',
  radius: 'none',
})

const ratios: Record<Exclude<Preset, 'auto'>, string> = {
  square: '1 / 1',
  video: '16 / 9',
  standard: '4 / 3',
  portrait: '3 / 4',
  landscape: '3 / 2',
  wide: '2 / 1',
  ultrawide: '21 / 9',
}

const aspectRatio = computed(() => {
  if (props.ratio === 'auto') return undefined

  if (typeof props.ratio === 'number') {
    if (!Number.isFinite(props.ratio) || props.ratio <= 0) {
      return undefined
    }

    return String(props.ratio)
  }

  return ratios[props.ratio as Exclude<Preset, 'auto'>]
    ?? props.ratio
})

const radiusClasses = {
  none: 'rounded-none',
  sm: 'rounded-sm',
  md: 'rounded-md',
  lg: 'rounded-lg',
  xl: 'rounded-xl',
}
</script>

<template>
  <div
    :class="[
      'relative w-full min-w-0',
      overflow === 'hidden' ? 'overflow-hidden' : 'overflow-visible',
      radiusClasses[radius],
      $props.class,
    ]"
    :style="{
      aspectRatio,
      width: width,
    }"
  >
    <slot />
  </div>
</template>
