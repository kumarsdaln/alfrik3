<script setup lang="ts">
import { computed } from 'vue'
import {
  progressSizeVariants,
  progressVariantVariants,
  type ProgressSize,
  type ProgressVariant,
} from './variants'

interface Props {
  modelValue?: number
  value?: number
  max?: number
  size?: ProgressSize
  variant?: ProgressVariant
  showValue?: boolean
  indeterminate?: boolean
  rounded?: boolean
  label?: string
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  max: 100,
  size: 'md',
  variant: 'default',
  showValue: false,
  indeterminate: false,
  rounded: true,
})

const normalizedMax = computed(() =>
  Number.isFinite(props.max) && props.max > 0
    ? props.max
    : 100,
)

const currentValue = computed(() => {
  const rawValue = props.modelValue ?? props.value ?? 0

  if (!Number.isFinite(rawValue)) return 0

  return Math.min(
    Math.max(rawValue, 0),
    normalizedMax.value,
  )
})

const percentage = computed(() =>
  Math.round((currentValue.value / normalizedMax.value) * 100),
)

const trackClasses = computed(() => [
  'relative w-full overflow-hidden bg-muted',
  progressSizeVariants[props.size],
  props.rounded ? 'rounded-full' : 'rounded-sm',
  props.class,
])

const indicatorClasses = computed(() => [
  'h-full transition-[width] duration-300 ease-out',
  progressVariantVariants[props.variant],
  props.rounded ? 'rounded-full' : 'rounded-sm',
  props.indeterminate ? 'w-1/3 animate-progress-indeterminate' : '',
])

const accessibleLabel = computed(() =>
  props.label || 'Progress',
)
</script>

<template>
  <div class="w-full">
    <div
      v-if="showValue"
      class="mb-2 flex items-center justify-between gap-3"
    >
      <span class="text-sm font-medium text-foreground">
        {{ label || 'Progress' }}
      </span>

      <span class="text-sm tabular-nums text-muted-foreground">
        {{ percentage }}%
      </span>
    </div>

    <div
      :class="trackClasses"
      role="progressbar"
      :aria-label="accessibleLabel"
      :aria-valuemin="0"
      :aria-valuemax="normalizedMax"
      :aria-valuenow="indeterminate ? undefined : currentValue"
      :aria-valuetext="indeterminate ? 'Loading' : `${percentage}%`"
    >
      <div
        :class="indicatorClasses"
        :style="indeterminate
          ? undefined
          : { width: `${percentage}%` }"
      />
    </div>
  </div>
</template>
