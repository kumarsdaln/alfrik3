
<script setup lang="ts">
import { computed, inject, useId } from 'vue'
import {
  type TabsOrientation,
  type TabsSize,
  type TabsVariant,
} from './variants'

interface TabsContext {
  activeValue: { value: string }
  orientation: { value: TabsOrientation }
  variant: { value: TabsVariant }
  size: { value: TabsSize }
}

interface Props {
  value: string
  forceMount?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  forceMount: false,
})

const context = inject<TabsContext>('tabs-context')

if (!context) {
  throw new Error('TabsContent must be used inside Tabs.')
}

const id = useId()

const isActive = computed(
  () => context.activeValue.value === props.value,
)

const panelClasses = computed(() => [
  'min-w-0 outline-none',
  'focus-visible:ring-2 focus-visible:ring-ring',
  props.class,
])
</script>

<template>
  <div
    v-if="isActive || forceMount"
    :id="`${id}-panel`"
    :class="panelClasses"
    role="tabpanel"
    :aria-hidden="!isActive"
    :tabindex="isActive ? 0 : -1"
    :hidden="!isActive"
  >
    <slot />
  </div>
</template>
