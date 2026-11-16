<script setup lang="ts">
import { computed } from 'vue'

type Position =
  | 'top'
  | 'bottom'
  | 'center'
  | 'top-left'
  | 'top-right'
  | 'bottom-left'
  | 'bottom-right'

type OverlayVariant =
  | 'dark'
  | 'light'
  | 'berry'
  | 'gradient'
  | 'transparent'
  | 'custom'

interface Props {
  position?: Position
  variant?: OverlayVariant
  opacity?: number
  inset?: number
  zIndex?: number
  class?: string
  contentClass?: string
  pointerEvents?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  position: 'bottom',
  variant: 'dark',
  opacity: 70,
  inset: 0,
  zIndex: 10,
  pointerEvents: false,
})

const positions: Record<Position, string> = {
  top: 'inset-x-0 top-0',
  bottom: 'inset-x-0 bottom-0',
  center: 'inset-0 flex items-center justify-center',
  'top-left': 'top-0 left-0',
  'top-right': 'top-0 right-0',
  'bottom-left': 'bottom-0 left-0',
  'bottom-right': 'bottom-0 right-0',
}

const variants: Record<OverlayVariant, string> = {
  dark: 'bg-[#171B2E]',
  light: 'bg-white',
  berry: 'bg-[#782F50]',
  gradient: 'bg-gradient-to-t from-[#171B2E] to-transparent',
  transparent: 'bg-transparent',
  custom: '',
}

const overlayStyle = computed(() => ({
  opacity: props.opacity / 100,
  zIndex: props.zIndex,
  inset: props.inset ? `${props.inset}px` : undefined,
}))
</script>

<template>
  <div
    :class="[
      'absolute',
      positions[position],
      variants[variant],
      pointerEvents ? 'pointer-events-auto' : 'pointer-events-none',
      $props.class,
    ]"
    :style="overlayStyle"
    aria-hidden="true"
  />

  <div
    v-if="$slots.default"
    class="absolute z-20"
    :class="[
      positions[position],
      pointerEvents ? 'pointer-events-auto' : 'pointer-events-none',
      contentClass,
    ]"
  >
    <slot />
  </div>
</template>
