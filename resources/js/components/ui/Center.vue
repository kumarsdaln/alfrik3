<script setup lang="ts">
import { computed } from 'vue'

type CenterMode = 'both' | 'horizontal' | 'vertical'
type CenterPadding = 'none' | 'sm' | 'md' | 'lg'

interface Props {
  mode?: CenterMode
  padding?: CenterPadding
  fullHeight?: boolean
  fullWidth?: boolean
  inline?: boolean
  class?: string
  tag?: string
}

const props = withDefaults(defineProps<Props>(), {
  mode: 'both',
  padding: 'none',
  fullHeight: false,
  fullWidth: true,
  inline: false,
  tag: 'div',
})

const paddingClasses: Record<CenterPadding, string> = {
  none: 'p-0',
  sm: 'p-2',
  md: 'p-4',
  lg: 'p-6',
}

const alignmentClasses = computed(() => ({
  both: 'items-center justify-center',
  horizontal: 'justify-center',
  vertical: 'items-center',
}[props.mode]))
</script>

<template>
  <component
    :is="tag"
    :class="[
      inline ? 'inline-flex' : 'flex',
      'min-w-0',
      alignmentClasses,
      fullHeight && 'h-full',
      fullWidth && 'w-full',
      paddingClasses[padding],
      $props.class,
    ]"
  >
    <slot />
  </component>
</template>
