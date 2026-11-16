<script setup lang="ts">
import { computed, provide, ref, watch } from 'vue'
import {
  stepperOrientationVariants,
  stepperVariantVariants,
  type StepperOrientation,
  type StepperSize,
  type StepperVariant,
} from './variants'

interface Props {
  modelValue?: number
  defaultValue?: number
  orientation?: StepperOrientation
  variant?: StepperVariant
  size?: StepperSize
  clickable?: boolean
  linear?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  defaultValue: 1,
  orientation: 'horizontal',
  variant: 'default',
  size: 'md',
  clickable: false,
  linear: true,
})

const emit = defineEmits<{
  'update:modelValue': [value: number]
  change: [value: number]
}>()

const internalValue = ref(props.defaultValue)

const activeStep = computed(() =>
  props.modelValue ?? internalValue.value,
)

const setActiveStep = (value: number) => {
  if (!Number.isInteger(value) || value < 1) return

  if (props.linear && value > activeStep.value + 1) return

  if (value === activeStep.value) return

  internalValue.value = value

  emit('update:modelValue', value)
  emit('change', value)
}

watch(
  () => props.defaultValue,
  value => {
    if (props.modelValue === undefined) {
      internalValue.value = value
    }
  },
)

provide('stepper-context', {
  activeStep,
  setActiveStep,
  orientation: computed(() => props.orientation),
  size: computed(() => props.size),
  clickable: computed(() => props.clickable),
})

const rootClasses = computed(() => [
  'flex min-w-0',
  stepperOrientationVariants[props.orientation],
  stepperVariantVariants[props.variant],
  props.orientation === 'horizontal'
    ? 'items-start'
    : 'items-stretch',
  props.class,
])
</script>

<template>
  <div
    :class="rootClasses"
    :data-orientation="orientation"
    role="group"
  >
    <slot />
  </div>
</template>
