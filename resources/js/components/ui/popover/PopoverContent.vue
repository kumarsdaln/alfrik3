<script setup lang="ts">
import { computed, inject } from 'vue'
import { cn } from '@/lib/utils'
import {
  popoverAlignVariants,
  popoverContextKey,
  popoverSideVariants,
  popoverSizeVariants,
  type PopoverAlign,
  type PopoverSide,
  type PopoverSize,
} from './variants'

interface Props {
  side?: PopoverSide
  align?: PopoverAlign
  size?: PopoverSize
  sideOffset?: number
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  side: 'bottom',
  align: 'center',
  size: 'md',
  sideOffset: 8,
})

const context = inject(popoverContextKey)

if (!context) {
  throw new Error('PopoverContent must be used inside Popover.')
}

const contentClasses = computed(() =>
  cn(
    'absolute z-50',
    'rounded-lg border border-border bg-card text-card-foreground',
    'shadow-lg shadow-foreground/5 outline-none',
    'transition-transform duration-150',
    popoverSizeVariants[props.size],
    popoverSideVariants[props.side],
    popoverAlignVariants[props.side][props.align],
    props.class,
  ),
)

const offsetStyle = computed(() => {
  const offset = `${props.sideOffset}px`

  return {
    top: { marginBottom: offset },
    bottom: { marginTop: offset },
    left: { marginRight: offset },
    right: { marginLeft: offset },
  }[props.side]
})
</script>

<template>
  <Transition
    enter-active-class="transition duration-150 ease-out"
    enter-from-class="opacity-0 scale-95"
    enter-to-class="opacity-100 scale-100"
    leave-active-class="transition duration-100 ease-in"
    leave-from-class="opacity-100 scale-100"
    leave-to-class="opacity-0 scale-95"
  >
    <div
      v-if="context.open.value"
      :id="context.contentId"
      data-slot="popover-content"
      :class="contentClasses"
      :style="offsetStyle"
      role="dialog"
      tabindex="-1"
    >
      <slot />
    </div>
  </Transition>
</template>
