
<script setup lang="ts">
import { computed, provide, ref, watch } from 'vue'
import {
  accordionVariantClasses,
  type AccordionType,
  type AccordionVariant,
  type AccordionSize,
} from './variants'

type AccordionValue = string | string[]

interface Props {
  modelValue?: AccordionValue
  defaultValue?: AccordionValue
  type?: AccordionType
  variant?: AccordionVariant
  size?: AccordionSize
  collapsible?: boolean
  disabled?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  type: 'single',
  variant: 'default',
  size: 'md',
  collapsible: true,
  disabled: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: AccordionValue]
  change: [value: AccordionValue]
}>()

const internalValue = ref<AccordionValue>(
  props.defaultValue ?? (props.type === 'multiple' ? [] : ''),
)

const activeValue = computed<AccordionValue>(() =>
  props.modelValue !== undefined
    ? props.modelValue
    : internalValue.value,
)

const isOpen = (value: string) => {
  return props.type === 'multiple'
    ? Array.isArray(activeValue.value) && activeValue.value.includes(value)
    : activeValue.value === value
}

const toggle = (value: string) => {
  if (props.disabled) return

  let nextValue: AccordionValue

  if (props.type === 'multiple') {
    const current = Array.isArray(activeValue.value)
      ? activeValue.value
      : []

    nextValue = current.includes(value)
      ? current.filter(item => item !== value)
      : [...current, value]
  } else {
    nextValue = activeValue.value === value
      ? (props.collapsible ? '' : value)
      : value
  }

  internalValue.value = nextValue
  emit('update:modelValue', nextValue)
  emit('change', nextValue)
}

watch(
  () => props.defaultValue,
  value => {
    if (props.modelValue === undefined && value !== undefined) {
      internalValue.value = value
    }
  },
)

provide('accordion-context', {
  isOpen,
  toggle,
  disabled: computed(() => props.disabled),
  size: computed(() => props.size),
  variant: computed(() => props.variant),
})

const rootClasses = computed(() => [
  'min-w-0',
  accordionVariantClasses[props.variant],
  props.class,
])
</script>

<template>
  <div
    :class="rootClasses"
    data-slot="accordion"
  >
    <slot />
  </div>
</template>
