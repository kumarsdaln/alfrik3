<script setup lang="ts">
  import type { HTMLAttributes } from 'vue'
  import { useVModel } from '@vueuse/core'

  import { cn } from '@/lib/utils'

  const props = defineProps<{
    defaultValue?: string | number
    modelValue?: string | number
    class?: HTMLAttributes['class']
  }>()

  const emits = defineEmits<{
    (e: 'update:modelValue', payload: string | number): void
  }>()

  const modelValue = useVModel(props, 'modelValue', emits, {
    passive: true,
    defaultValue: props.defaultValue,
  })
</script>

<template>
  <input v-model="modelValue" data-slot="input" :class="cn(
    `
                h-10
                w-full
                min-w-0

                border
                border-border-light
                bg-white

                px-3.5
                py-2

                font-redhat
                text-sm
                text-black

                outline-none

                transition-colors
                duration-200

                placeholder:text-black/40

                hover:border-black/35
                focus:border-black/60

                disabled:cursor-not-allowed
                disabled:opacity-50

                dark:border-white/20
                dark:bg-black
                dark:text-white

                dark:placeholder:text-white/40

                dark:hover:border-white/35
                dark:focus:border-white/60
                `,
    props.class,
  )
    " />
</template>