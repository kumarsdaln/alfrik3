
<script setup lang="ts">
import { computed, inject, provide, useId } from 'vue'
import {
  accordionItemVariants,
  type AccordionVariant,
} from './variants'

interface AccordionContext {
  isOpen: (value: string) => boolean
  toggle: (value: string) => void
  disabled: { value: boolean }
  variant: { value: AccordionVariant }
}

interface Props {
  value: string
  disabled?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  disabled: false,
})

const context = inject<AccordionContext>('accordion-context')

if (!context) {
  throw new Error('AccordionItem must be used inside Accordion.')
}

const id = useId()
const triggerId = `${id}-trigger`
const contentId = `${id}-content`

const isOpen = computed(() => context.isOpen(props.value))

const isDisabled = computed(() =>
  props.disabled || context.disabled.value,
)

provide('accordion-item-context', {
  value: props.value,
  triggerId,
  contentId,
  isOpen,
  isDisabled,
  toggle: () => context.toggle(props.value),
})

const itemClasses = computed(() => [
  'min-w-0',
  accordionItemVariants[context.variant.value],
  props.class,
])
</script>

<template>
  <div
    :class="itemClasses"
    :data-state="isOpen ? 'open' : 'closed'"
    :data-disabled="isDisabled || undefined"
  >
    <slot />
  </div>
</template>
