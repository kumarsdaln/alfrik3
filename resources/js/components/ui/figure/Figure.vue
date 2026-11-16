
<script setup lang="ts">
import { computed } from 'vue'
import {
  figureAlignVariants,
  figureWidthVariants,
  type FigureAlign,
  type FigureWidth,
} from './variants'

interface Props {
  align?: FigureAlign
  width?: FigureWidth
  class?: string
  tag?: string
}

const props = withDefaults(defineProps<Props>(), {
  align: 'center',
  width: 'full',
  tag: 'figure',
})

const figureClasses = computed(() => [
  'min-w-0',
  figureWidthVariants[props.width],
  figureAlignVariants[props.align],
  props.class,
])
</script>

<template>
  <component
    :is="tag"
    :class="figureClasses"
  >
    <slot />
  </component>
</template>
