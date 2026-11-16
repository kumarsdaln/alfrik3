
<script setup lang="ts">
import { computed } from 'vue'

interface Props {
  tag?: string
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  tag: 'div',
})

const itemClasses = computed(() => [
  'mb-4 inline-block w-full align-top break-inside-avoid',
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
