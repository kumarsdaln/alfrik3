<script setup lang="ts">
import { computed } from 'vue'

type Direction = 'vertical' | 'horizontal'
type Gap = 'none' | 'xs' | 'sm' | 'md' | 'lg' | 'xl'
type Align = 'start' | 'center' | 'end' | 'stretch'
type Justify =
  | 'start'
  | 'center'
  | 'end'
  | 'between'
  | 'around'
  | 'evenly'

interface Props {
  direction?: Direction
  gap?: Gap
  align?: Align
  justify?: Justify
  wrap?: boolean
  inline?: boolean
  tag?: string
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  direction: 'vertical',
  gap: 'md',
  align: 'stretch',
  justify: 'start',
  wrap: false,
  inline: false,
  tag: 'div',
})

const gapValues: Record<Gap, string> = {
  none: '0',
  xs: '0.25rem',
  sm: '0.5rem',
  md: '1rem',
  lg: '1.5rem',
  xl: '2rem',
}

const alignValues: Record<Align, string> = {
  start: 'flex-start',
  center: 'center',
  end: 'flex-end',
  stretch: 'stretch',
}

const justifyValues: Record<Justify, string> = {
  start: 'flex-start',
  center: 'center',
  end: 'flex-end',
  between: 'space-between',
  around: 'space-around',
  evenly: 'space-evenly',
}

const stackClasses = computed(() => [
  props.inline ? 'inline-flex' : 'flex',
  props.direction === 'vertical' ? 'flex-col' : 'flex-row',
  props.wrap ? 'flex-wrap' : 'flex-nowrap',
  'min-w-0',
  props.class,
])

const stackStyles = computed(() => ({
  gap: gapValues[props.gap],
  alignItems: alignValues[props.align],
  justifyContent: justifyValues[props.justify],
}))
</script>

<template>
  <component
    :is="tag"
    :class="stackClasses"
    :style="stackStyles"
  >
    <slot />
  </component>
</template>
