
<script setup lang="ts">
import { computed } from 'vue'
import {
  figureCaptionAlignVariants,
  figureSpacingVariants,
  type FigureCaptionAlign,
  type FigureSpacing,
} from './variants'

interface Props {
  align?: FigureCaptionAlign
  spacing?: FigureSpacing
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  align: 'start',
  spacing: 'md',
})

const captionClasses = computed(() => [
  'flex flex-wrap items-baseline gap-x-2 gap-y-1',
  'text-sm leading-relaxed text-muted-foreground',
  figureCaptionAlignVariants[props.align],
  figureSpacingVariants[props.spacing],
  props.class,
])
</script>

<template>
  <figcaption :class="captionClasses">
    <slot />
  </figcaption>
</template>
