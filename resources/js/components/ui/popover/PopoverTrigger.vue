<script setup lang="ts">
import { inject } from 'vue'
import { cn } from '@/lib/utils'
import { popoverContextKey } from './variants'

interface Props {
  disabled?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  disabled: false,
})

const context = inject(popoverContextKey)

if (!context) {
  throw new Error('PopoverTrigger must be used inside Popover.')
}

const handleClick = () => {
  if (!props.disabled) context.toggle()
}
</script>

<template>
  <button
    type="button"
    data-slot="popover-trigger"
    :class="cn('inline-flex items-center justify-center', props.class)"
    :disabled="disabled"
    :aria-expanded="context.open.value"
    :aria-controls="context.contentId"
    aria-haspopup="dialog"
    @click="handleClick"
  >
    <slot />
  </button>
</template>
