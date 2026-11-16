
<script setup lang="ts">
import { computed } from 'vue'
import {
  attributionSizeClasses,
  type BlockquoteSize,
} from './variants'

interface Props {
  author?: string
  role?: string
  source?: string
  size?: BlockquoteSize
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
})

const attributionClasses = computed(() => [
  'mt-4 flex flex-wrap items-center gap-x-2 gap-y-1',
  'font-sans text-muted-foreground',
  attributionSizeClasses[props.size],
  props.class,
])
</script>

<template>
  <footer :class="attributionClasses">
    <span
      v-if="author"
      class="font-semibold text-foreground"
    >
      {{ author }}
    </span>

    <span v-if="role">
      {{ role }}
    </span>

    <span
      v-if="source"
      class="italic"
    >
      {{ source }}
    </span>

    <slot />
  </footer>
</template>
