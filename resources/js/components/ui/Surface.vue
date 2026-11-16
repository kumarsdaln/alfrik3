
<script setup lang="ts">
interface Props {
  id?: string
  class?: string

  variant?:
    | 'default'
    | 'paper'
    | 'muted'
    | 'soft'
    | 'wash'
    | 'primary'
    | 'primary-soft'
    | 'berry'
    | 'gold'
    | 'dark'
    | 'transparent'

  border?: 'none' | 'default' | 'strong' | 'primary' | 'gold'

  padding?: 'none' | 'sm' | 'md' | 'lg' | 'xl'

  radius?: 'none' | 'sm' | 'md' | 'lg' | 'xl'

  shadow?: 'none' | 'sm' | 'md' | 'lg'

  interactive?: boolean
}

withDefaults(defineProps<Props>(), {
  variant: 'default',
  border: 'default',
  padding: 'md',
  radius: 'lg',
  shadow: 'none',
  interactive: false,
})

const variants: Record<string, string> = {
  default: 'bg-white text-[#171B2E]',
  paper: 'bg-[#F8F6F2] text-[#171B2E]',
  muted: 'bg-[#F1EFF0] text-[#171B2E]',
  soft: 'bg-[#F7E6EE] text-[#171B2E]',
  wash: 'bg-[#FBF3F6] text-[#171B2E]',
  primary: 'bg-[#B00050] text-white',
  'primary-soft': 'bg-[#F7E6EE] text-[#8F0041]',
  berry: 'bg-[#782F50] text-white',
  gold: 'bg-[#946F32] text-white',
  dark: 'bg-[#171B2E] text-white',
  transparent: 'bg-transparent text-[#171B2E]',
}

const borders: Record<string, string> = {
  none: 'border-0',
  default: 'border border-[#E7E2E5]',
  strong: 'border border-[#D8C3CE]',
  primary: 'border border-[#B00050]/30',
  gold: 'border border-[#946F32]/30',
}

const paddings: Record<string, string> = {
  none: 'p-0',
  sm: 'p-3',
  md: 'p-5',
  lg: 'p-6 sm:p-8',
  xl: 'p-8 sm:p-10',
}

const radiuses: Record<string, string> = {
  none: 'rounded-none',
  sm: 'rounded-sm',
  md: 'rounded-md',
  lg: 'rounded-lg',
  xl: 'rounded-xl',
}

const shadows: Record<string, string> = {
  none: '',
  sm: 'shadow-sm shadow-[#171B2E]/5',
  md: 'shadow-md shadow-[#171B2E]/5',
  lg: 'shadow-lg shadow-[#171B2E]/10',
}
</script>

<template>
  <div
    :id="id"
    :class="[
      'relative min-w-0',
      variants[variant],
      borders[border],
      paddings[padding],
      radiuses[radius],
      shadows[shadow],
      interactive && 'transition-colors duration-200 hover:border-[#B00050]/30',
      $props.class,
    ]"
  >
    <slot />
  </div>
</template>
