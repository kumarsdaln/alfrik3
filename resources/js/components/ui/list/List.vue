<script setup lang="ts">
import { computed } from 'vue'
import {
  listColorVariants,
  listMarkerVariants,
  listSizeVariants,
  listSpacingVariants,
  type ListColor,
  type ListMarker,
  type ListSize,
  type ListSpacing,
  type ListType,
} from './variants'

interface Props {
  type?: ListType
  marker?: ListMarker
  size?: ListSize
  spacing?: ListSpacing
  color?: ListColor
  nested?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  type: 'unordered',
  marker: 'bullet',
  size: 'md',
  spacing: 'md',
  color: 'default',
  nested: false,
})

const tag = computed(() => {
  if (props.type === 'ordered') return 'ol'
  if (props.type === 'unordered') return 'ul'

  return 'div'
})

const listClasses = computed(() => [
  'min-w-0',
  'list-outside',
  props.type === 'none'
    ? 'list-none pl-0'
    : [
        listMarkerVariants[props.marker],
        'pl-5',
      ],
  listSizeVariants[props.size],
  listSpacingVariants[props.spacing],
  listColorVariants[props.color],
  props.nested && 'ml-4',
  props.class,
])
</script>

<template>
  <component
    :is="tag"
    :class="listClasses"
    :role="type === 'none' ? 'list' : undefined"
  >
    <slot />
  </component>
</template>
