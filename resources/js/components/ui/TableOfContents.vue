
<script setup lang="ts">
import type { PropType } from 'vue'

interface TocItem {
  id: string
  title: string
  children?: TocItem[]
}

defineProps({
  items: {
    type: Array as PropType<TocItem[]>,
    required: true,
  },
  title: {
    type: String,
    default: 'Table of Contents',
  },
  numbered: {
    type: Boolean,
    default: true,
  },
  nested: {
    type: Boolean,
    default: true,
  },
  variant: {
    type: String as PropType<'default' | 'bordered' | 'soft'>,
    default: 'default',
  },
})

function navigateTo(id: string) {
  document.getElementById(id)?.scrollIntoView({
    behavior: 'smooth',
    block: 'start',
  })

  history.replaceState(null, '', `#${encodeURIComponent(id)}`)
}
</script>

<template>
  <nav
    aria-label="Table of contents"
    :class="[
      'w-full min-w-0',
      variant === 'bordered' && 'rounded-lg border border-[#E7E2E5] p-5',
      variant === 'soft' && 'rounded-lg bg-[#FBF3F6] p-5',
    ]"
  >
    <h2 class="mb-4 text-base font-semibold text-[#171B2E]">
      {{ title }}
    </h2>

    <ol class="space-y-3">
      <li v-for="(item, index) in items" :key="item.id">
        <button
          type="button"
          class="group flex w-full items-start gap-3 text-left text-sm text-[#171B2E] transition-colors hover:text-[#B00050]"
          @click="navigateTo(item.id)"
        >
          <span
            v-if="numbered"
            class="shrink-0 font-medium tabular-nums text-[#B00050]"
          >
            {{ String(index + 1).padStart(2, '0') }}.
          </span>

          <span class="min-w-0">{{ item.title }}</span>
        </button>

        <ol
          v-if="nested && item.children?.length"
          class="ml-8 mt-3 space-y-2 border-l border-[#E7E2E5] pl-4"
        >
          <li
            v-for="(child, childIndex) in item.children"
            :key="child.id"
          >
            <button
              type="button"
              class="text-left text-sm text-[#687080] transition-colors hover:text-[#B00050]"
              @click="navigateTo(child.id)"
            >
              {{ index + 1 }}.{{ childIndex + 1 }}
              {{ child.title }}
            </button>
          </li>
        </ol>
      </li>
    </ol>
  </nav>
</template>
