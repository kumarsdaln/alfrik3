
<script setup lang="ts">
import { computed, inject, useId } from 'vue'
import {
  tabsSizeVariants,
  tabsTriggerVariants,
  type TabsOrientation,
  type TabsSize,
  type TabsVariant,
} from './variants'

interface TabsContext {
  activeValue: { value: string }
  setActiveValue: (value: string) => void
  orientation: { value: TabsOrientation }
  variant: { value: TabsVariant }
  size: { value: TabsSize }
  disabled: { value: boolean }
}

interface Props {
  value: string
  disabled?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  disabled: false,
})

const context = inject<TabsContext>('tabs-context')

if (!context) {
  throw new Error('TabsTrigger must be used inside Tabs.')
}

const id = useId()
const panelId = `${id}-panel`

const isActive = computed(
  () => context.activeValue.value === props.value,
)

const isDisabled = computed(
  () => props.disabled || context.disabled.value,
)

const triggerClasses = computed(() => [
  'inline-flex shrink-0 items-center justify-center whitespace-nowrap',
  'font-medium transition-colors duration-150',
  'outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2',
  'disabled:pointer-events-none disabled:opacity-50',
  tabsSizeVariants[context.size.value],
  tabsTriggerVariants[context.variant.value],
  props.class,
])

const activate = () => {
  if (!isDisabled.value) {
    context.setActiveValue(props.value)
  }
}

const handleKeydown = (event: KeyboardEvent) => {
  const previous = context.orientation.value === 'horizontal'
    ? 'ArrowLeft'
    : 'ArrowUp'

  const next = context.orientation.value === 'horizontal'
    ? 'ArrowRight'
    : 'ArrowDown'

  if (event.key === 'Enter' || event.key === ' ') {
    event.preventDefault()
    activate()
    return
  }

  if (event.key === previous || event.key === next) {
    event.preventDefault()

    const list = (event.currentTarget as HTMLElement).parentElement
    const triggers = Array.from(
      list?.querySelectorAll<HTMLButtonElement>('[role="tab"]:not(:disabled)') ?? [],
    )

    const currentIndex = triggers.indexOf(
      event.currentTarget as HTMLButtonElement,
    )

    const direction = event.key === next ? 1 : -1
    const nextIndex = (currentIndex + direction + triggers.length) % triggers.length

    triggers[nextIndex]?.focus()
  }

  if (event.key === 'Home' || event.key === 'End') {
    event.preventDefault()

    const list = (event.currentTarget as HTMLElement).parentElement
    const triggers = Array.from(
      list?.querySelectorAll<HTMLButtonElement>('[role="tab"]:not(:disabled)') ?? [],
    )

    const target = event.key === 'Home'
      ? triggers[0]
      : triggers[triggers.length - 1]

    target?.focus()
  }
}
</script>

<template>
  <button
    :id="`${id}-trigger`"
    type="button"
    role="tab"
    :class="triggerClasses"
    :data-state="isActive ? 'active' : 'inactive'"
    :aria-selected="isActive"
    :aria-controls="panelId"
    :tabindex="isActive ? 0 : -1"
    :disabled="isDisabled"
    @click="activate"
    @keydown="handleKeydown"
  >
    <slot />
  </button>
</template>
