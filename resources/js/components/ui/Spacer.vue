<script setup lang="ts">
import { computed } from 'vue'

type SpacerSize = 'xs' | 'sm' | 'md' | 'lg' | 'xl' | '2xl'
type SpacerAxis = 'vertical' | 'horizontal'

interface Props {
  size?: SpacerSize | number | string
  axis?: SpacerAxis
  grow?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
  axis: 'vertical',
  grow: false,
})

const sizes: Record<SpacerSize, string> = {
  xs: '0.25rem',
  sm: '0.5rem',
  md: '1rem',
  lg: '1.5rem',
  xl: '2rem',
  '2xl': '3rem',
}

const dimension = computed(() => {
  if (typeof props.size === 'number') {
    return `${Math.max(0, props.size)}px`
  }

  return sizes[props.size as SpacerSize] ?? props.size
})

const spacerStyles = computed(() => {
  if (props.grow) {
    return {
      flex: '1 1 auto',
      minWidth: '0',
      minHeight: '0',
    }
  }

  return props.axis === 'vertical'
    ? { height: dimension.value, width: '100%' }
    : { width: dimension.value, height: 'auto' }
})
</script>

<template>
  <div
    :class="[
      'shrink-0',
      grow && 'min-w-0',
      $props.class,
    ]"
    :style="spacerStyles"
    aria-hidden="true"
  />
</template>
