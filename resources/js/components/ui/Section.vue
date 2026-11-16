<script setup lang="ts">
interface Props {
  id?: string
  class?: string

  background?:
    | 'transparent'
    | 'paper'
    | 'surface'
    | 'soft'
    | 'wash'
    | 'primary'
    | 'primary-soft'
    | 'berry'
    | 'sage'
    | 'gold'
    | 'dark'

  border?: 'none' | 'default' | 'strong' | 'primary' | 'gold'

  padding?: 'none' | 'sm' | 'md' | 'lg' | 'xl'

  spacing?: 'none' | 'sm' | 'md' | 'lg'

  radius?: 'none' | 'sm' | 'md' | 'lg' | 'xl'

  shadow?: 'none' | 'sm' | 'md' | 'lg'
}

withDefaults(defineProps<Props>(), {
  background: 'transparent',
  border: 'none',
  padding: 'none',
  spacing: 'md',
  radius: 'none',
  shadow: 'none',
})

const backgrounds: Record<string, string> = {
  transparent: 'bg-transparent text-[#171B2E]',
  paper: 'bg-[#F8F6F2] text-[#171B2E]',
  surface: 'bg-white text-[#171B2E]',
  soft: 'bg-[#F7E6EE] text-[#171B2E]',
  wash: 'bg-[#FBF3F6] text-[#171B2E]',
  primary: 'bg-[#B00050] text-white',
  'primary-soft': 'bg-[#F7E6EE] text-[#8F0041]',
  berry: 'bg-[#782F50] text-white',
  sage: 'bg-[#77304F] text-white',
  gold: 'bg-[#946F32] text-white',
  dark: 'bg-[#171B2E] text-white',
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

const spacings: Record<string, string> = {
  none: 'space-y-0',
  sm: 'space-y-3',
  md: 'space-y-5',
  lg: 'space-y-8',
}

const radiuses: Record<string, string> = {
  none: 'rounded-none',
  sm: 'rounded-sm',
  md: 'rounded-md',
  lg: 'rounded-lg',
  xl: 'rounded-xl',
}

const shadows: Record<string, string> = {
  none: 'shadow-none',
  sm: 'shadow-sm shadow-[#171B2E]/5',
  md: 'shadow-md shadow-[#171B2E]/5',
  lg: 'shadow-lg shadow-[#171B2E]/10',
}
</script>

<template>
  <section
    :id="id"
    :class="[
      'relative w-full min-w-0',
      backgrounds[background],
      borders[border],
      paddings[padding],
      spacings[spacing],
      radiuses[radius],
      shadows[shadow],
      $props.class,
    ]"
  >
    <slot />
  </section>
</template>
