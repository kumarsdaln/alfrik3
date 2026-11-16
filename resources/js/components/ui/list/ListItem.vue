<script setup lang="ts">
import { computed } from 'vue'
import {
  listItemSizeVariants,
  type ListColor,
  type ListSize,
} from './variants'

interface Props {
  size?: ListSize
  color?: ListColor
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
  color: 'default',
})

const colorClasses: Record<ListColor, string> = {
  default: 'text-foreground',
  muted: 'text-muted-foreground',
  primary: 'text-primary',
  berry: 'text-brand',
  gold: 'text-gold',
}

const itemClasses = computed(() => [
  'min-w-0',
  listItemSizeVariants[props.size],
  colorClasses[props.color],
  'marker:text-brand',
  props.class,
])
</script>

<template>
  <li :class="itemClasses">
    <slot />
  </li>
</template>
