
<script setup lang="ts">
import { computed } from 'vue'

type ContainerSize =
  | 'xs'
  | 'sm'
  | 'md'
  | 'lg'
  | 'xl'
  | '2xl'
  | 'full'

type ContainerPadding = 'none' | 'sm' | 'md' | 'lg'

interface Props {
  id?: string
  size?: ContainerSize
  padding?: ContainerPadding
  centered?: boolean
  fluid?: boolean
  tag?: string
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  size: 'xl',
  padding: 'md',
  centered: true,
  fluid: false,
  tag: 'div',
})

const maxWidths: Record<ContainerSize, string> = {
  xs: '36rem',
  sm: '40rem',
  md: '48rem',
  lg: '64rem',
  xl: '80rem',
  '2xl': '96rem',
  full: '100%',
}

const paddingClasses: Record<ContainerPadding, string> = {
  none: 'px-0',
  sm: 'px-3 sm:px-4',
  md: 'px-4 sm:px-6',
  lg: 'px-5 sm:px-8 lg:px-10',
}

const containerStyles = computed(() => ({
  maxWidth: props.fluid ? '100%' : maxWidths[props.size],
}))
</script>

<template>
  <component
    :is="tag"
    :id="id"
    :class="[
      'w-full min-w-0',
      paddingClasses[padding],
      centered && 'mx-auto',
      $props.class,
    ]"
    :style="containerStyles"
  >
    <slot />
  </component>
</template>
