
<script setup lang="ts">
import { computed } from 'vue'

type ListType = 'unordered' | 'ordered' | 'none'
type ListMarker = 'bullet' | 'number' | 'decimal' | 'lower-alpha' | 'upper-alpha' | 'lower-roman' | 'upper-roman' | 'disc' | 'circle' | 'square'
type ListSize = 'sm' | 'md' | 'lg'
type ListSpacing = 'none' | 'sm' | 'md' | 'lg'
type ListColor = 'default' | 'muted' | 'primary' | 'berry' | 'gold'

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

const tag = computed(() =>
  props.type === 'ordered' ? 'ol' : 'ul',
)

const markerClasses: Record<ListMarker, string> = {
  bullet: 'list-disc',
  number: 'list-decimal',
  decimal: 'list-decimal',
  'lower-alpha': 'list-[lower-alpha]',
  'upper-alpha': 'list-[upper-alpha]',
  'lower-roman': 'list-[lower-roman]',
  'upper-roman': 'list-[upper-roman]',
  disc: 'list-disc',
  circle: 'list-[circle]',
  square: 'list-[square]',
}

const sizeClasses: Record<ListSize, string> = {
  sm: 'text-xs leading-5',
  md: 'text-sm leading-6',
  lg: 'text-base leading-7',
}

const spacingClasses: Record<ListSpacing, string> = {
  none: 'space-y-0',
  sm: 'space-y-1',
  md: 'space-y-2',
  lg: 'space-y-4',
}

const colorClasses: Record<ListColor, string> = {
  default: 'text-[#171B2E]',
  muted: 'text-[#687080]',
  primary: 'text-[#B00050]',
  berry: 'text-[#782F50]',
  gold: 'text-[#946F32]',
}

const listClasses = computed(() => [
  'min-w-0',
  props.type === 'none'
    ? 'list-none pl-0'
    : [
        markerClasses[props.marker],
        'pl-5',
      ],
  sizeClasses[props.size],
  spacingClasses[props.spacing],
  colorClasses[props.color],
  props.nested && 'ml-4',
  props.class,
])
</script>

<template>
  <component
    :is="tag"
    v-if="type !== 'none'"
    :class="listClasses"
  >
    <slot />
  </component>

  <div
    v-else
    :class="listClasses"
  >
    <slot />
  </div>
</template>
