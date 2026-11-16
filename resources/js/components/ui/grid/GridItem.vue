
<script setup lang="ts">
import { computed } from 'vue'
import type { GridColumns, GridBreakpoint } from './variants'

type GridSpan = GridColumns | 'full' | 'auto'

type GridItemResponsive = Partial<
  Record<GridBreakpoint, GridSpan>
>

interface Props {
  span?: GridSpan
  rowSpan?: GridSpan
  start?: number
  end?: number
  responsive?: GridItemResponsive
  tag?: string
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  span: 1,
  rowSpan: 1,
  tag: 'div',
})

const spanClasses: Record<GridSpan, string> = {
  1: 'col-span-1',
  2: 'col-span-2',
  3: 'col-span-3',
  4: 'col-span-4',
  5: 'col-span-5',
  6: 'col-span-6',
  7: 'col-span-7',
  8: 'col-span-8',
  9: 'col-span-9',
  10: 'col-span-10',
  11: 'col-span-11',
  12: 'col-span-12',
  full: 'col-span-full',
  auto: 'col-auto',
}

const rowSpanClasses: Record<GridSpan, string> = {
  1: 'row-span-1',
  2: 'row-span-2',
  3: 'row-span-3',
  4: 'row-span-4',
  5: 'row-span-5',
  6: 'row-span-6',
  7: 'row-span-7',
  8: 'row-span-8',
  9: 'row-span-9',
  10: 'row-span-10',
  11: 'row-span-11',
  12: 'row-span-12',
  full: 'row-span-full',
  auto: 'row-auto',
}

const breakpoints: GridBreakpoint[] = [
  'sm',
  'md',
  'lg',
  'xl',
  '2xl',
]

const gridItemClasses = computed(() => [
  'min-w-0',
  spanClasses[props.span],
  rowSpanClasses[props.rowSpan],

  props.start && `col-start-${props.start}`,
  props.end && `col-end-${props.end}`,

  ...breakpoints
    .filter((breakpoint) => props.responsive?.[breakpoint] !== undefined)
    .map((breakpoint) => {
      const span = props.responsive![breakpoint]!

      const responsiveClasses: Record<GridSpan, string> = {
        1: `${breakpoint}:col-span-1`,
        2: `${breakpoint}:col-span-2`,
        3: `${breakpoint}:col-span-3`,
        4: `${breakpoint}:col-span-4`,
        5: `${breakpoint}:col-span-5`,
        6: `${breakpoint}:col-span-6`,
        7: `${breakpoint}:col-span-7`,
        8: `${breakpoint}:col-span-8`,
        9: `${breakpoint}:col-span-9`,
        10: `${breakpoint}:col-span-10`,
        11: `${breakpoint}:col-span-11`,
        12: `${breakpoint}:col-span-12`,
        full: `${breakpoint}:col-span-full`,
        auto: `${breakpoint}:col-auto`,
      }

      return responsiveClasses[span]
    }),

  props.class,
])
</script>

<template>
  <component
    :is="tag"
    :class="gridItemClasses"
  >
    <slot />
  </component>
</template>
