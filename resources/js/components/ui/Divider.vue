
<script setup lang="ts">
import { computed } from 'vue'

type Orientation = 'horizontal' | 'vertical'
type DividerColor =
  | 'default'
  | 'muted'
  | 'primary'
  | 'berry'
  | 'gold'
  | 'dark'
  | 'white'
type DividerSpacing = 'none' | 'sm' | 'md' | 'lg' | 'xl'
type DividerStyle = 'solid' | 'dashed' | 'dotted'

interface Props {
  orientation?: Orientation
  color?: DividerColor
  spacing?: DividerSpacing
  thickness?: 1 | 2 | 3
  variant?: DividerStyle
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  orientation: 'horizontal',
  color: 'default',
  spacing: 'md',
  thickness: 1,
  variant: 'solid',
})

const colors: Record<DividerColor, string> = {
  default: '#E7E2E5',
  muted: '#F0ECEF',
  primary: '#B00050',
  berry: '#782F50',
  gold: '#946F32',
  dark: '#171B2E',
  white: '#FFFFFF',
}

const spacingValues: Record<DividerSpacing, string> = {
  none: '0',
  sm: '0.5rem',
  md: '1rem',
  lg: '1.5rem',
  xl: '2rem',
}

const isHorizontal = computed(
  () => props.orientation === 'horizontal',
)

const dividerStyles = computed(() => ({
  borderColor: colors[props.color],
  borderWidth: `${props.thickness}px`,
  borderStyle: props.variant,
  ...(isHorizontal.value
    ? {
        width: '100%',
        height: '0',
        marginBlock: spacingValues[props.spacing],
      }
    : {
        width: '0',
        height: 'auto',
        alignSelf: 'stretch',
        marginInline: spacingValues[props.spacing],
      }),
}))
</script>

<template>
  <hr
    v-if="isHorizontal"
    :class="['shrink-0', $props.class]"
    :style="dividerStyles"
    aria-hidden="true"
  />

  <span
    v-else
    :class="['shrink-0 border-l', $props.class]"
    :style="dividerStyles"
    role="separator"
    aria-orientation="vertical"
  />
</template>
