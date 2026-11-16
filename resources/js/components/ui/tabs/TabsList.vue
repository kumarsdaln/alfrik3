
<script setup lang="ts">
import { computed, inject } from 'vue'
import {
  tabsVariantVariants,
  type TabsOrientation,
  type TabsVariant,
} from './variants'

interface TabsContext {
  orientation: { value: TabsOrientation }
  variant: { value: TabsVariant }
  disabled: { value: boolean }
}

interface Props {
  class?: string
}

const props = defineProps<Props>()

const context = inject<TabsContext>('tabs-context')

if (!context) {
  throw new Error('TabsList must be used inside Tabs.')
}

const listClasses = computed(() => [
  'flex shrink-0',
  context.orientation.value === 'horizontal'
    ? 'flex-row items-center'
    : 'flex-col items-stretch',
  tabsVariantVariants[context.variant.value],
  props.class,
])
</script>

<template>
  <div
    :class="listClasses"
    role="tablist"
    :aria-orientation="context.orientation.value"
    :aria-disabled="context.disabled.value"
  >
    <slot />
  </div>
</template>
