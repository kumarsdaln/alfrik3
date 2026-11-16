
<script setup lang="ts">
import { computed } from 'vue'

type SplitItemAlign = 'start' | 'center' | 'end' | 'stretch'

interface Props {
  align?: SplitItemAlign
  class?: string
  tag?: string
}

const props = withDefaults(defineProps<Props>(), {
  align: 'stretch',
  tag: 'div',
})

const alignClasses: Record<SplitItemAlign, string> = {
  start: 'self-start',
  center: 'self-center',
  end: 'self-end',
  stretch: 'self-stretch',
}

const itemClasses = computed(() => [
  'min-w-0',
  alignClasses[props.align],
  props.class,
])
</script>

<template>
  <component
    :is="tag"
    :class="itemClasses"
  >
    <slot />
  </component>
</template>
