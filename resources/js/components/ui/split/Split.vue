
<script setup lang="ts">
import { computed } from 'vue'
import {
  splitGapVariants,
  splitRatioVariants,
  splitResponsiveRatioVariants,
  splitStackVariants,
  type SplitBreakpoint,
  type SplitGap,
  type SplitRatio,
  type SplitStack,
} from './variants'

interface Props {
  ratio?: SplitRatio
  gap?: SplitGap
  stack?: SplitStack
  reverse?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  ratio: 'equal',
  gap: 'lg',
  stack: 'md',
  reverse: false,
})

const breakpoints: SplitBreakpoint[] = ['sm', 'md', 'lg', 'xl']

const layoutClasses = computed(() => {
  const responsiveClass =
    props.stack === 'never'
      ? splitRatioVariants[props.ratio]
      : props.stack === 'always'
        ? 'grid-cols-1'
        : [
            splitStackVariants[props.stack],
            splitResponsiveRatioVariants[props.stack][props.ratio],
          ]

  return [
    'grid min-w-0',
    responsiveClass,
    splitGapVariants[props.gap],
    props.reverse && 'direction-rtl',
    props.class,
  ]
})
</script>

<template>
  <div :class="layoutClasses">
    <slot />
  </div>
</template>
