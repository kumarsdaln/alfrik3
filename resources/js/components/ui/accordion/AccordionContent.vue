
<script setup lang="ts">
import { computed, inject } from 'vue'
import {
  accordionContentSizeVariants,
  type AccordionSize,
} from './variants'

interface AccordionItemContext {
  triggerId: string
  contentId: string
  isOpen: { value: boolean }
}

interface AccordionContext {
  size: { value: AccordionSize }
}

const item = inject<AccordionItemContext>('accordion-item-context')
const accordion = inject<AccordionContext>('accordion-context')

if (!item || !accordion) {
  throw new Error(
    'AccordionContent must be used inside AccordionItem.',
  )
}

const contentClasses = computed(() => [
  'min-w-0 overflow-hidden text-muted-foreground',
  accordionContentSizeVariants[accordion.size.value],
])
</script>

<template>
  <div
    :id="item.contentId"
    v-show="item.isOpen.value"
    :class="contentClasses"
    role="region"
    :aria-labelledby="item.triggerId"
    :aria-hidden="!item.isOpen.value"
    :data-state="item.isOpen.value ? 'open' : 'closed'"
  >
    <slot />
  </div>
</template>
