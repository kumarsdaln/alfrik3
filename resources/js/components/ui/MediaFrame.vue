
<script setup lang="ts">
import { computed } from 'vue'

type Ratio =
  | 'auto'
  | 'square'
  | 'video'
  | 'landscape'
  | 'portrait'
  | 'wide'

type Fit = 'cover' | 'contain' | 'fill'

type Background =
  | 'transparent'
  | 'white'
  | 'paper'
  | 'soft'
  | 'dark'

type Radius = 'none' | 'sm' | 'md' | 'lg' | 'xl'

interface Props {
  ratio?: Ratio | number | string
  fit?: Fit
  background?: Background
  radius?: Radius
  bordered?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  ratio: 'video',
  fit: 'cover',
  background: 'soft',
  radius: 'lg',
  bordered: false,
})

const ratios: Record<Exclude<Ratio, 'auto'>, string> = {
  square: '1 / 1',
  video: '16 / 9',
  landscape: '3 / 2',
  portrait: '3 / 4',
  wide: '2 / 1',
}

const backgrounds: Record<Background, string> = {
  transparent: 'bg-transparent',
  white: 'bg-white',
  paper: 'bg-[#F8F6F2]',
  soft: 'bg-[#FBF3F6]',
  dark: 'bg-[#171B2E]',
}

const radiuses: Record<Radius, string> = {
  none: 'rounded-none',
  sm: 'rounded-sm',
  md: 'rounded-md',
  lg: 'rounded-lg',
  xl: 'rounded-xl',
}

const aspectRatio = computed(() => {
  if (props.ratio === 'auto') return undefined

  if (typeof props.ratio === 'number') {
    return Number.isFinite(props.ratio) && props.ratio > 0
      ? String(props.ratio)
      : undefined
  }

  return ratios[props.ratio as Exclude<Ratio, 'auto'>]
    ?? props.ratio
})

const frameStyle = computed(() => ({
  aspectRatio: aspectRatio.value,
}))

const fitClasses: Record<Fit, string> = {
  cover: 'object-cover',
  contain: 'object-contain',
  fill: 'object-fill',
}
</script>

<template>
  <div
    :class="[
      'relative w-full min-w-0 overflow-hidden',
      backgrounds[background],
      radiuses[radius],
      bordered && 'border border-[#E7E2E5]',
      $props.class,
    ]"
    :style="frameStyle"
  >
    <slot :fit-class="fitClasses[fit]" />
  </div>
</template>
