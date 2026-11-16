
<script setup lang="ts">
import { computed } from 'vue'
import {
  blockquoteAlignClasses,
  blockquoteSizeClasses,
  blockquoteSpacingClasses,
  blockquoteVariantClasses,
  type BlockquoteAlign,
  type BlockquoteSize,
  type BlockquoteSpacing,
  type BlockquoteVariant,
} from './variants'

interface Props {
  variant?: BlockquoteVariant
  size?: BlockquoteSize
  align?: BlockquoteAlign
  spacing?: BlockquoteSpacing
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'default',
  size: 'md',
  align: 'left',
  spacing: 'md',
})

const blockquoteClasses = computed(() => [
  'min-w-0',
  blockquoteVariantClasses[props.variant],
  blockquoteSizeClasses[props.size],
  blockquoteAlignClasses[props.align],
  blockquoteSpacingClasses[props.spacing],
  props.class,
])
</script>

<template>
  <blockquote :class="blockquoteClasses">
    <div
      class="font-editorial font-medium leading-relaxed text-foreground"
    >
      <slot />
    </div>

    <slot name="attribution" />
  </blockquote>
</template>
