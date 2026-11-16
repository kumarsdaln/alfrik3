<script setup lang="ts">
import { computed } from 'vue'
import {
  descriptionListTermSizeVariants,
  type DescriptionListSize,
} from './variants'

interface Props {
  term?: string
  size?: DescriptionListSize
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
})

const termClasses = computed(() => [
  'mb-1 font-medium text-muted-foreground',
  descriptionListTermSizeVariants[props.size],
])
</script>

<template>
  <div :class="['min-w-0', props.class]">
    <dt :class="termClasses">
      <slot name="term">
        {{ term }}
      </slot>
    </dt>

    <dd class="min-w-0 break-words text-foreground">
      <slot />
    </dd>
  </div>
</template>
