
<script setup lang="ts">
import { computed, inject } from 'vue'
import {
  accordionTriggerSizeVariants,
  type AccordionSize,
} from './variants'

interface AccordionItemContext {
  triggerId: string
  contentId: string
  isOpen: { value: boolean }
  isDisabled: { value: boolean }
  toggle: () => void
}

interface AccordionContext {
  size: { value: AccordionSize }
}

const item = inject<AccordionItemContext>('accordion-item-context')
const accordion = inject<AccordionContext>('accordion-context')

if (!item || !accordion) {
  throw new Error(
    'AccordionTrigger must be used inside AccordionItem.',
  )
}

const triggerClasses = computed(() => [
  'group flex w-full items-center justify-between gap-4',
  'text-left font-medium text-foreground',
  'transition-colors duration-150',
  'hover:text-brand',
  'focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-inset',
  'disabled:cursor-not-allowed disabled:opacity-50',
  accordionTriggerSizeVariants[accordion.size.value],
])

const handleKeydown = (event: KeyboardEvent) => {
  if (event.key === 'Enter' || event.key === ' ') {
    event.preventDefault()
    if (!item.isDisabled.value) item.toggle()
  }
}
</script>

<template>
  <button
    :id="item.triggerId"
    type="button"
    :class="triggerClasses"
    :aria-expanded="item.isOpen.value"
    :aria-controls="item.contentId"
    :disabled="item.isDisabled.value"
    :data-state="item.isOpen.value ? 'open' : 'closed'"
    @click="item.toggle"
    @keydown="handleKeydown"
  >
    <span class="min-w-0 flex-1">
      <slot />
    </span>

    <svg
      class="size-4 shrink-0 text-muted-foreground transition-transform duration-200 group-data-[state=open]:rotate-180"
      :class="{ 'rotate-180': item.isOpen.value }"
      viewBox="0 0 24 24"
      fill="none"
      stroke="currentColor"
      stroke-width="1.75"
      stroke-linecap="round"
      stroke-linejoin="round"
      aria-hidden="true"
    >
      <path d="m6 9 6 6 6-6" />
    </svg>
  </button>
</template>
