
<script setup lang="ts">
import { computed } from 'vue'

type Gap = 'none' | 'xs' | 'sm' | 'md' | 'lg' | 'xl'
type Align = 'start' | 'center' | 'end' | 'baseline' | 'stretch'
type Justify = 'start' | 'center' | 'end' | 'between' | 'around'

interface Props {
  gap?: Gap
  align?: Align
  justify?: Justify
  wrap?: boolean
  divider?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  gap: 'sm',
  align: 'center',
  justify: 'start',
  wrap: true,
  divider: false,
})

const gaps: Record<Gap, string> = {
  none: '0',
  xs: '0.25rem',
  sm: '0.5rem',
  md: '1rem',
  lg: '1.5rem',
  xl: '2rem',
}

const alignments: Record<Align, string> = {
  start: 'flex-start',
  center: 'center',
  end: 'flex-end',
  baseline: 'baseline',
  stretch: 'stretch',
}

const justifications: Record<Justify, string> = {
  start: 'flex-start',
  center: 'center',
  end: 'flex-end',
  between: 'space-between',
  around: 'space-around',
}

const styles = computed(() => ({
  gap: gaps[props.gap],
  alignItems: alignments[props.align],
  justifyContent: justifications[props.justify],
}))

const classes = computed(() => [
  'flex min-w-0',
  props.wrap ? 'flex-wrap' : 'flex-nowrap',
  props.divider && 'divide-x divide-[#E7E2E5]',
  props.class,
])
</script>

<template>
  <div
    :class="classes"
    :style="styles"
  >
    <slot />
  </div>
</template>
