
<script setup lang="ts">
import { computed } from 'vue'
import {
  gridAlignVariants,
  gridAutoColumnsVariants,
  gridAutoRowsVariants,
  gridColumnVariants,
  gridFlowVariants,
  gridGapVariants,
  gridJustifyVariants,
  gridResponsiveColumnVariants,
  type GridAlign,
  type GridAutoColumns,
  type GridAutoRows,
  type GridBreakpoint,
  type GridColumns,
  type GridFlow,
  type GridGap,
  type GridJustify,
  type GridResponsiveColumns,
} from './variants'

interface Props {
  cols?: GridColumns
  responsive?: GridResponsiveColumns
  gap?: GridGap
  rowGap?: GridGap
  columnGap?: GridGap
  align?: GridAlign
  justify?: GridJustify
  flow?: GridFlow
  autoRows?: GridAutoRows
  autoColumns?: GridAutoColumns
  tag?: string
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  cols: 1,
  gap: 'md',
  align: 'stretch',
  justify: 'stretch',
  flow: 'row',
  autoRows: 'auto',
  autoColumns: 'auto',
  tag: 'div',
})

const breakpoints: GridBreakpoint[] = [
  'sm',
  'md',
  'lg',
  'xl',
  '2xl',
]

const gridClasses = computed(() => [
  'grid min-w-0',

  gridColumnVariants[props.cols],

  ...breakpoints
    .filter((breakpoint) => props.responsive?.[breakpoint] !== undefined)
    .map((breakpoint) =>
      gridResponsiveColumnVariants[breakpoint][
        props.responsive![breakpoint]!
      ],
    ),

  gridGapVariants[props.gap],

  props.rowGap && `row-${gridGapVariants[props.rowGap].slice(4)}`,
  props.columnGap && `column-${gridGapVariants[props.columnGap].slice(4)}`,

  gridAlignVariants[props.align],
  gridJustifyVariants[props.justify],
  gridFlowVariants[props.flow],
  gridAutoRowsVariants[props.autoRows],
  gridAutoColumnsVariants[props.autoColumns],

  props.class,
])
</script>

<template>
  <component
    :is="tag"
    :class="gridClasses"
  >
    <slot />
  </component>
</template>
