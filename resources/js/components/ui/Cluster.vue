
<script setup lang="ts">
import { computed } from 'vue'

type Gap = 'none' | 'xs' | 'sm' | 'md' | 'lg'
type Align = 'start' | 'center' | 'end' | 'baseline'
type Justify = 'start' | 'center' | 'end' | 'between'

interface Props {
  gap?: Gap
  rowGap?: Gap
  columnGap?: Gap
  align?: Align
  justify?: Justify
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  gap: 'sm',
  align: 'center',
  justify: 'start',
})

const gaps: Record<Gap, string> = {
  none: '0',
  xs: '0.25rem',
  sm: '0.5rem',
  md: '0.75rem',
  lg: '1rem',
}

const alignments: Record<Align, string> = {
  start: 'flex-start',
  center: 'center',
  end: 'flex-end',
  baseline: 'baseline',
}

const justifications: Record<Justify, string> = {
  start: 'flex-start',
  center: 'center',
  end: 'flex-end',
  between: 'space-between',
}

const styles = computed(() => ({
  gap: gaps[props.gap],
  rowGap: props.rowGap ? gaps[props.rowGap] : undefined,
  columnGap: props.columnGap ? gaps[props.columnGap] : undefined,
  alignItems: alignments[props.align],
  justifyContent: justifications[props.justify],
}))
</script>

<template>
  <div
    :class="['flex flex-wrap min-w-0', $props.class]"
    :style="styles"
  >
    <slot />
  </div>
</template>
