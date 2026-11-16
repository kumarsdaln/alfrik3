<script setup lang="ts">
import { computed, inject } from 'vue'
import {
  stepperSizeVariants,
  stepStatusVariants,
  type StepperOrientation,
  type StepperSize,
} from './variants'

interface StepperContext {
  activeStep: { value: number }
  setActiveStep: (value: number) => void
  orientation: { value: StepperOrientation }
  size: { value: StepperSize }
  clickable: { value: boolean }
}

interface Props {
  value: number
  title: string
  description?: string
  disabled?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  disabled: false,
})

const context = inject<StepperContext>('stepper-context')

if (!context) {
  throw new Error('StepperItem must be used inside Stepper.')
}

const status = computed(() => {
  if (props.value < context.activeStep.value) return 'completed'
  if (props.value === context.activeStep.value) return 'active'
  return 'upcoming'
})

const isClickable = computed(() =>
  context.clickable.value && !props.disabled,
)

const itemSize = computed(() =>
  stepperSizeVariants[context.size.value],
)

const statusClasses = computed(() =>
  stepStatusVariants[status.value],
)

const itemClasses = computed(() => [
  'relative min-w-0',
  context.orientation.value === 'horizontal'
    ? 'flex flex-1 flex-col items-center text-center'
    : 'flex items-start gap-4',
  props.class,
])

const indicatorClasses = computed(() => [
  'relative z-10 flex shrink-0 items-center justify-center',
  'rounded-full border-2 font-semibold transition-colors duration-200',
  itemSize.value.indicator,
  statusClasses.value.indicator,
])

const activate = () => {
  if (isClickable.value) {
    context.setActiveStep(props.value)
  }
}

const handleKeydown = (event: KeyboardEvent) => {
  if (
    isClickable.value &&
    (event.key === 'Enter' || event.key === ' ')
  ) {
    event.preventDefault()
    activate()
  }
}
</script>

<template>
  <div
    :class="itemClasses"
    :data-status="status"
  >
    <div
      v-if="context.orientation.value === 'horizontal'"
      class="relative flex w-full justify-center"
    >
      <button
        type="button"
        :class="indicatorClasses"
        :disabled="!isClickable"
        :aria-current="status === 'active' ? 'step' : undefined"
        :aria-label="`Step ${value}: ${title}, ${status}`"
        :aria-disabled="!isClickable || undefined"
        @click="activate"
        @keydown="handleKeydown"
      >
        <svg
          v-if="status === 'completed'"
          class="size-4"
          viewBox="0 0 24 24"
          fill="none"
          stroke="currentColor"
          stroke-width="2.5"
          stroke-linecap="round"
          stroke-linejoin="round"
          aria-hidden="true"
        >
          <path d="m5 12 4 4L19 6" />
        </svg>

        <span v-else>{{ value }}</span>
      </button>
    </div>

    <button
      v-else
      type="button"
      :class="indicatorClasses"
      :disabled="!isClickable"
      :aria-current="status === 'active' ? 'step' : undefined"
      :aria-label="`Step ${value}: ${title}, ${status}`"
      :aria-disabled="!isClickable || undefined"
      @click="activate"
      @keydown="handleKeydown"
    >
      <svg
        v-if="status === 'completed'"
        class="size-4"
        viewBox="0 0 24 24"
        fill="none"
        stroke="currentColor"
        stroke-width="2.5"
        stroke-linecap="round"
        stroke-linejoin="round"
        aria-hidden="true"
      >
        <path d="m5 12 4 4L19 6" />
      </svg>

      <span v-else>{{ value }}</span>
    </button>

    <div
      :class="context.orientation.value === 'horizontal'
        ? 'mt-3 min-w-0 px-2'
        : 'min-w-0 flex-1 pb-6'"
    >
      <p
        :class="[
          'font-medium',
          itemSize.text,
          statusClasses.title,
        ]"
      >
        {{ title }}
      </p>

      <p
        v-if="description"
        :class="[
          'mt-1',
          itemSize.description,
          statusClasses.description,
        ]"
      >
        {{ description }}
      </p>

      <slot
        v-if="status === 'active'"
        name="content"
      />
    </div>
  </div>
</template>
