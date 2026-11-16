
<script setup lang="ts">
import { computed } from 'vue'
import {
  masonryColumnVariants,
  masonryGapVariants,
  masonryResponsiveColumnVariants,
  type MasonryBreakpoint,
  type MasonryColumns,
  type MasonryGap,
  type MasonryResponsiveColumns,
} from './variants'

interface Props {
  columns?: MasonryColumns
  responsive?: MasonryResponsiveColumns
  gap?: MasonryGap
  tag?: string
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  columns: 1,
  gap: 'md',
  tag: 'div',
})

const breakpoints: MasonryBreakpoint[] = [
  'sm',
  'md',
  'lg',
  'xl',
  '2xl',
]

const masonryClasses = computed(() => [
  'min-w-0',
  masonryColumnVariants[props.columns],
  masonryGapVariants[props.gap],

  ...breakpoints
    .filter((breakpoint) => props.responsive?.[breakpoint] !== undefined)
    .map((breakpoint) =>
      masonryResponsiveColumnVariants[breakpoint][
        props.responsive![breakpoint]!
      ],
    ),

  props.class,
])
</script>

<template>
  <component
    :is="tag"
    :class="masonryClasses"
  >
    <slot />
  </component>
</template>
