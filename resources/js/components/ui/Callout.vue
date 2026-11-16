
<script setup lang="ts">
import { computed } from 'vue'

type CalloutVariant =
  | 'info'
  | 'success'
  | 'warning'
  | 'danger'
  | 'finding'
  | 'note'

interface Props {
  variant?: CalloutVariant
  title?: string
  icon?: string
  bordered?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'info',
  bordered: true,
})

const variants: Record<CalloutVariant, {
  background: string
  border: string
  accent: string
  icon: string
}> = {
  info: {
    background: 'bg-[#FBF3F6]',
    border: 'border-[#E7C6D5]',
    accent: 'text-[#B00050]',
    icon: 'info',
  },
  success: {
    background: 'bg-[#EDF5EF]',
    border: 'border-[#C9DFCE]',
    accent: 'text-[#356B46]',
    icon: 'check-circle',
  },
  warning: {
    background: 'bg-[#FAF4E8]',
    border: 'border-[#E9D9B5]',
    accent: 'text-[#946F32]',
    icon: 'alert-triangle',
  },
  danger: {
    background: 'bg-[#FCEFED]',
    border: 'border-[#F0D0CA]',
    accent: 'text-[#A33B32]',
    icon: 'alert-circle',
  },
  finding: {
    background: 'bg-[#F7E6EE]',
    border: 'border-[#E7C6D5]',
    accent: 'text-[#8F0041]',
    icon: 'lightbulb',
  },
  note: {
    background: 'bg-[#F8F6F2]',
    border: 'border-[#E7E2E5]',
    accent: 'text-[#687080]',
    icon: 'notebook-text',
  },
}

const currentVariant = computed(() => variants[props.variant])
</script>

<template>
  <aside
    :class="[
      'flex gap-3 rounded-lg p-4 sm:p-5',
      currentVariant.background,
      bordered && `border ${currentVariant.border}`,
      $props.class,
    ]"
    :aria-label="title || variant"
  >
    <div class="shrink-0 pt-0.5">
      <slot name="icon">
        <icon
          :name="icon || currentVariant.icon"
          :class="currentVariant.accent"
          size="20"
        />
      </slot>
    </div>

    <div class="min-w-0 flex-1 space-y-1">
      <p
        v-if="title"
        class="font-semibold text-[#171B2E]"
      >
        {{ title }}
      </p>

      <div class="text-sm leading-6 text-[#687080]">
        <slot />
      </div>
    </div>
  </aside>
</template>
