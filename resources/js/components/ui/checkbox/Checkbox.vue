<script setup lang="ts">
  import type {
    CheckboxRootEmits,
    CheckboxRootProps,
  } from 'reka-ui'

  import type { HTMLAttributes } from 'vue'

  import { Check } from '@lucide/vue'

  import { reactiveOmit } from '@vueuse/core'

  import {
    CheckboxIndicator,
    CheckboxRoot,
    useForwardPropsEmits,
  } from 'reka-ui'

  import { cn } from '@/lib/utils'

  const props = defineProps<
    CheckboxRootProps & {
      class?: HTMLAttributes['class']
    }
  >()

  const emits = defineEmits<CheckboxRootEmits>()

  const delegatedProps = reactiveOmit(props, 'class')

  const forwarded = useForwardPropsEmits(
    delegatedProps,
    emits,
  )
</script>

<template>
  <CheckboxRoot v-slot="slotProps" data-slot="checkbox" v-bind="forwarded" :class="cn(
    `
                peer
                size-4
                shrink-0
                cursor-pointer
                border
                border-border-light
                bg-transparent
                outline-none
                transition-colors
                duration-200

                focus-visible:border-primary
                focus-visible:ring-2
                focus-visible:ring-primary/30

                data-[state=checked]:border-primary
                data-[state=checked]:bg-primary
                data-[state=checked]:text-white

                disabled:cursor-not-allowed
                disabled:opacity-50

                dark:border-border-dark
                `,
    props.class,
  )
    ">
    <CheckboxIndicator data-slot="checkbox-indicator" class="grid place-content-center">
      <slot v-bind="slotProps">
        <Check class="size-3" />
      </slot>
    </CheckboxIndicator>
  </CheckboxRoot>
</template>