<script setup lang="ts">
  import {
    ref,
    computed,
    useId,
    onMounted,
    onBeforeUnmount,
  } from 'vue'

  import AppFormField from '@/components/ui/AppFormField.vue'
  import DownAngle from '@/icons/DownAngle.vue'
  import type {
    FormOption,
    FormOptionInput,
    FormValue,
  } from '@/types/forms'

  interface Props {
    modelValue?: FormValue
    label?: string
    placeholder?: string
    error?: string
    disabled?: boolean
    loading?: boolean
    options?: FormOptionInput[]
  }

  const props = withDefaults(
    defineProps<Props>(),
    {
      modelValue: null,
      placeholder: 'Search...',
      disabled: false,
      loading: false,
      options: () => [],
    }
  )

  const emit = defineEmits<{
    (e: 'update:modelValue', value: FormValue): void
  }>()

  const id = useId()

  const wrapperRef = ref<HTMLElement | null>(null)

  const open = ref(false)

  const search = ref('')

  const normalizedOptions = computed<FormOption[]>(() =>
    props.options.map(option =>
      typeof option === 'object' && option !== null && 'value' in option
        ? option as FormOption
        : {
          value: option as FormValue,
          label: String(option ?? ''),
        }
    )
  )

  const selectedOption = computed(() =>
    normalizedOptions.value.find(
      option =>
        option.value ===
        props.modelValue
    )
  )

  const filteredOptions = computed(() => {

    const term =
      search.value.toLowerCase()

    return normalizedOptions.value.filter(
      option =>
        option.label
          .toLowerCase()
          .includes(term)
    )
  })

  function openDropdown() {

    if (props.disabled) {
      return
    }

    open.value = true

    search.value = ''
  }

  function select(option: FormOption) {

    emit(
      'update:modelValue',
      option.value
    )

    open.value = false

    search.value = ''
  }

  function closeDropdown(event: MouseEvent) {

    if (
      wrapperRef.value &&
      !wrapperRef.value.contains(
        event.target
      )
    ) {
      open.value = false

      search.value = ''
    }
  }

  onMounted(() => {

    document.addEventListener(
      'click',
      closeDropdown
    )
  })

  onBeforeUnmount(() => {

    document.removeEventListener(
      'click',
      closeDropdown
    )
  })
</script>

<template>
  <div ref="wrapperRef">
    <AppFormField
      :id="id"
      :label="label"
      :error="error"
    >
      <!-- Control -->

      <div class="
                relative
                rounded-xl
                border
                bg-white
                dark:bg-zinc-900
                transition-all
            " :class="[
              error
                ? 'border-red-500'
                : 'border-zinc-300 dark:border-zinc-700',

              'focus-within:border-brand focus-within:ring-2 focus-within:ring-brand/20',

              disabled &&
              'opacity-60 cursor-not-allowed'
            ]">
        <input :id="id" :value="open
          ? search
          : selectedOption?.label
        " :disabled="disabled" :placeholder="placeholder
                  " @focus="openDropdown" @input="
                  search =
                  ($event.target as HTMLInputElement).value
                  " class="
                    w-full
                    rounded-xl
                    bg-transparent
                    px-4
                    py-2.5
                    pr-10
                    text-sm
                    text-zinc-900
                    dark:text-zinc-100
                    placeholder:text-zinc-400
                    dark:placeholder:text-zinc-500
                    outline-none
                ">

        <button type="button" tabindex="-1" class="
                    absolute
                    right-3
                    top-1/2
                    -translate-y-1/2
                " @click="
                  open = !open
                  ">
        <DownAngle class="
                        h-4
                        w-4
                        text-zinc-400
                        transition-transform
                    " :class="{
                      'rotate-180':
                        open
                    }" />
        </button>
      </div>

      <!-- Dropdown -->

      <div v-if="open" class="
                overflow-hidden
                rounded-xl
                border
                border-zinc-200
                bg-white
                shadow-lg
                dark:border-zinc-700
                dark:bg-zinc-900
            ">
        <!-- Loading -->

        <div v-if="loading" class="
                    px-4
                    py-3
                    text-sm
                    text-zinc-500
                ">
          Searching...
        </div>

        <!-- Empty -->

        <div v-else-if="
        !filteredOptions.length
      " class="
                    px-4
                    py-3
                    text-sm
                    text-zinc-500
                ">
          No results found
        </div>

        <!-- Options -->

        <div v-else class="
                    max-h-60
                    overflow-y-auto
                ">
          <button v-for="
option
                          in filteredOptions
                    " :key="option.value
                      " type="button" @click="
                      select(option)
                      " class="
                        block
                        w-full
                        px-4
                        py-2.5
                        text-left
                        text-sm
                        text-zinc-700
                        dark:text-zinc-200
                        hover:bg-zinc-100
                        dark:hover:bg-zinc-800
                    ">
            {{ option.label }}
          </button>
        </div>
      </div>
    </AppFormField>
  </div>
</template>
