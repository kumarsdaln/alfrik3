
<script setup lang="ts">
import { computed, provide, ref, watch } from 'vue'
import {
  tabsOrientationVariants,
  type TabsOrientation,
  type TabsSize,
  type TabsVariant,
} from './variants'

interface Props {
  modelValue?: string
  defaultValue?: string
  orientation?: TabsOrientation
  variant?: TabsVariant
  size?: TabsSize
  disabled?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  orientation: 'horizontal',
  variant: 'underline',
  size: 'md',
  disabled: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: string]
  change: [value: string]
}>()

const internalValue = ref(props.defaultValue ?? '')

const activeValue = computed(() =>
  props.modelValue !== undefined
    ? props.modelValue
    : internalValue.value,
)

const setActiveValue = (value: string) => {
  if (props.disabled) return

  internalValue.value = value
  emit('update:modelValue', value)
  emit('change', value)
}

watch(
  () => props.defaultValue,
  (value) => {
    if (props.modelValue === undefined && value !== undefined) {
      internalValue.value = value
    }
  },
)

provide('tabs-context', {
  activeValue,
  setActiveValue,
  orientation: computed(() => props.orientation),
  variant: computed(() => props.variant),
  size: computed(() => props.size),
  disabled: computed(() => props.disabled),
})

const rootClasses = computed(() => [
  'min-w-0',
  'flex',
  props.orientation === 'horizontal' ? 'flex-col' : 'flex-row gap-6',
  tabsOrientationVariants[props.orientation],
  props.class,
])
</script>

<template>
  <div
    :class="rootClasses"
    :data-orientation="orientation"
  >
    <slot />
  </div>
</template>
