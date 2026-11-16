
<script setup lang="ts">
import { computed } from 'vue'

type Size = 'sm' | 'md' | 'lg'
type Width = 'narrow' | 'normal' | 'wide' | 'full'
type Theme = 'default' | 'muted' | 'dark'

interface Props {
  size?: Size
  width?: Width
  theme?: Theme
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
  width: 'normal',
  theme: 'default',
})

const sizes: Record<Size, string> = {
  sm: 'text-sm leading-6',
  md: 'text-base leading-7',
  lg: 'text-lg leading-8',
}

const widths: Record<Width, string> = {
  narrow: 'max-w-2xl',
  normal: 'max-w-3xl',
  wide: 'max-w-5xl',
  full: 'max-w-none',
}

const themes: Record<Theme, string> = {
  default: 'text-[#171B2E]',
  muted: 'text-[#687080]',
  dark: 'text-white',
}

const proseClasses = computed(() => [
  'prose max-w-none',
  'prose-headings:font-semibold',
  'prose-headings:tracking-tight',
  'prose-headings:text-current',
  'prose-p:text-current',
  'prose-strong:text-current',
  'prose-li:text-current',
  'prose-blockquote:border-l-[#B00050]',
  'prose-blockquote:text-[#687080]',
  'prose-a:text-[#B00050]',
  'prose-a:decoration-[#B00050]/30',
  'prose-a:underline-offset-4',
  'prose-hr:border-[#E7E2E5]',
  'prose-img:rounded-lg',
  sizes[props.size],
  widths[props.width],
  themes[props.theme],
  props.class,
])
</script>

<template>
  <div :class="proseClasses">
    <slot />
  </div>
</template>
