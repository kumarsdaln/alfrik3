<script setup lang="ts">
import { computed, onBeforeUnmount, onMounted, provide, ref, watch } from 'vue'
import { cn } from '@/lib/utils'
import { popoverContextKey } from './variants'

interface Props {
  modelValue?: boolean
  defaultOpen?: boolean
  disabled?: boolean
  class?: string
}

const props = withDefaults(defineProps<Props>(), {
  defaultOpen: false,
  disabled: false,
})

const emit = defineEmits<{
  'update:modelValue': [value: boolean]
  open: []
  close: []
}>()

const rootRef = ref<HTMLElement | null>(null)
const internalOpen = ref(props.defaultOpen)

const open = computed({
  get: () => props.modelValue ?? internalOpen.value,
  set: (value: boolean) => {
    if (props.disabled && value) return

    internalOpen.value = value
    emit('update:modelValue', value)
      if (value) {
          emit('open')
      } else {
          emit('close')
      }
  },
})

const contentId = `popover-${Math.random().toString(36).slice(2, 10)}`

const toggle = () => {
  if (!props.disabled) open.value = !open.value
}

const close = () => {
  open.value = false
}

const handleOutsideClick = (event: PointerEvent) => {
  if (
    event.target instanceof Node &&
    rootRef.value &&
    !rootRef.value.contains(event.target)
  ) {
    close()
  }
}

const handleKeydown = (event: KeyboardEvent) => {
  if (event.key === 'Escape' && open.value) {
    close()
  }
}

const addListeners = () => {
  document.addEventListener('pointerdown', handleOutsideClick)
  document.addEventListener('keydown', handleKeydown)
}

const removeListeners = () => {
  document.removeEventListener('pointerdown', handleOutsideClick)
  document.removeEventListener('keydown', handleKeydown)
}

watch(
  () => props.defaultOpen,
  value => {
    if (props.modelValue === undefined) {
      internalOpen.value = value
    }
  },
)

watch(open, value => {
  if (typeof document === 'undefined') return

  if (value) addListeners()
  else removeListeners()
})

onMounted(() => {
  if (open.value) addListeners()
})

onBeforeUnmount(removeListeners)

provide(popoverContextKey, {
  open,
  contentId,
  toggle,
  close,
})

const rootClasses = computed(() =>
  cn('relative inline-flex', props.class),
)
</script>

<template>
  <div
    ref="rootRef"
    :class="rootClasses"
    data-slot="popover"
    :data-state="open ? 'open' : 'closed'"
  >
    <slot />
  </div>
</template>
