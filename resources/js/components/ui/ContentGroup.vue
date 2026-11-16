
<script setup lang="ts">
interface Props {
  spacing?: 'none' | 'xs' | 'sm' | 'md' | 'lg' | 'xl'
  divider?: boolean
  dividerColor?: 'default' | 'muted' | 'primary' | 'gold'
  class?: string
}

withDefaults(defineProps<Props>(), {
  spacing: 'md',
  divider: false,
  dividerColor: 'default',
})

const spacingClasses = {
  none: 'space-y-0',
  xs: 'space-y-1',
  sm: 'space-y-2',
  md: 'space-y-4',
  lg: 'space-y-6',
  xl: 'space-y-8',
}

const dividerColors = {
  default: 'divide-[#E7E2E5]',
  muted: 'divide-[#F0ECEF]',
  primary: 'divide-[#B00050]/20',
  gold: 'divide-[#946F32]/25',
}
</script>

<template>
  <div
    :class="[
      'flex min-w-0 flex-col',
      spacingClasses[spacing],
      divider && 'divide-y',
      divider && dividerColors[dividerColor],
      $props.class,
    ]"
  >
    <slot />
  </div>
</template>
