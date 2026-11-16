
<script setup lang="ts">
import { computed } from 'vue'
import {
  descriptionListGapVariants,
  descriptionListLayoutVariants,
  descriptionListResponsiveVariants,
  descriptionListSizeVariants,
  type DescriptionListBreakpoint,
  type DescriptionListGap,
  type DescriptionListLayout,
  type DescriptionListResponsive,
  type DescriptionListSize,
} from './variants'

interface Props {
  layout?: DescriptionListLayout
  responsive?: DescriptionListResponsive
  gap?: DescriptionListGap
  size?: DescriptionListSize
  divided?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  layout: 'vertical',
  gap: 'md',
  size: 'md',
  divided: false,
})

const breakpoints: DescriptionListBreakpoint[] = [
  'sm',
  'md',
  'lg',
]

const listClasses = computed(() => [
  'grid min-w-0',
  descriptionListLayoutVariants[props.layout],
  descriptionListGapVariants[props.gap],
  descriptionListSizeVariants[props.size],

  ...breakpoints
    .filter((breakpoint) => props.responsive?.[breakpoint] !== undefined)
    .map((breakpoint) =>
      descriptionListResponsiveVariants[breakpoint][
        props.responsive![breakpoint]!
      ],
    ),

  props.divided && '[&>*+*]:border-t [&>*+*]:border-border [&>*+*]:pt-4',

  props.class,
])
</script>

<template>
  <dl :class="listClasses">
    <slot />
  </dl>
</template>
