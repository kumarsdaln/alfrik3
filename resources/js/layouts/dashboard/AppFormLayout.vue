<script setup lang="ts">
  import PageHeader from '@/components/Dashboard/PageHeader.vue'

  interface Props {
    title?: string
    description?: string
    showBackButton?: boolean
  }

  withDefaults(
    defineProps<Props>(),
    {
      title: '',
      description: '',
      showBackButton: true,
    },
  )

  function goBack() {
    history.back()
  }
</script>

<template>
  <div
    class="flex h-[calc(100vh-142px)] flex-col overflow-hidden border border-gray-200 bg-white shadow-sm dark:border-zinc-800 dark:bg-zinc-950">
    <!-- Header -->
    <PageHeader :title="title" :description="description" :show-back-button="showBackButton" @go-back="goBack" />

    <!-- Scrollable Body -->
    <div class="flex-1 overflow-y-auto p-6">
      <slot />
    </div>

    <!-- Sticky Footer -->
    <div v-if="$slots.footer" class="border-t border-gray-200 bg-white p-4 dark:border-zinc-800 dark:bg-zinc-950">
      <slot name="footer" />
    </div>
  </div>
</template>