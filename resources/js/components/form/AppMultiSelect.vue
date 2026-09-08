<script setup lang="ts">
import { ref, watch, computed } from 'vue'
import { ChevronDown, X } from '@lucide/vue'

import {
    Combobox,
    ComboboxAnchor,
    ComboboxInput,
    ComboboxTrigger,
    ComboboxPortal,
    ComboboxContent,
    ComboboxViewport,
    ComboboxEmpty,
    ComboboxItem,
} from '@/components/ui/combobox'

interface Option {
    value: string | number
    label: string
    disabled?: boolean
}

interface Props {
    modelValue?: Array<string | number>
    options?: Option[]
    placeholder?: string
    name?: string
    disabled?: boolean
    required?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: () => [],
    options: () => [],
    placeholder: 'Select options',
    name: undefined,
    disabled: false,
    required: false,
})

const emit = defineEmits<{
    'update:modelValue': [value: Array<string | number>]
}>()

const model = ref<Array<string | number>>([...props.modelValue])

watch(
    () => props.modelValue,
    value => {
        model.value = [...(value ?? [])]
    },
    { deep: true },
)

watch(
    model,
    value => {
        emit('update:modelValue', [...value])
    },
    { deep: true },
)

const selectedOptions = computed(() =>
    props.options.filter(option =>
        model.value.includes(option.value),
    ),
)

function removeOption(value: string | number) {
    model.value = model.value.filter(item => item !== value)
}
</script>

<template>
    <Combobox
        v-model="model"
        :name="name"
        :disabled="disabled"
        :required="required"
        multiple
    >
        <ComboboxAnchor class="w-full">
            <div
                class="
                    flex min-h-10 w-full items-center
                    border border-border
                    bg-surface
                    px-3 py-1.5

                    transition-colors

                    focus-within:border-primary
                "
            >
                <div
                    class="
                        flex min-w-0 flex-1
                        flex-wrap
                        items-center
                        gap-x-1.5
                        gap-y-1
                    "
                >
                    <span
                        v-for="option in selectedOptions"
                        :key="String(option.value)"
                        class="
                            inline-flex
                            max-w-full
                            items-center
                            gap-1
                            border
                            border-border
                            bg-secondary/10
                            px-2
                            py-1
                            text-xs
                            font-medium
                            text-content
                        "
                    >
                        <span class="max-w-[180px] truncate">
                            {{ option.label }}
                        </span>

                        <button
                            type="button"
                            class="
                                flex
                                size-4
                                shrink-0
                                items-center
                                justify-center
                                text-content/50
                                transition-colors

                                hover:text-content

                                focus:outline-none
                                focus-visible:text-primary
                            "
                            :disabled="disabled"
                            :aria-label="`Remove ${option.label}`"
                            @click.stop="removeOption(option.value)"
                        >
                            <X :size="13" />
                        </button>
                    </span>

                    <ComboboxInput
                        class="
                            h-7
                            min-w-[120px]
                            flex-1
                            border-0
                            bg-transparent
                            px-0
                            text-sm
                            text-content
                            outline-none

                            placeholder:text-content/50

                            focus:outline-none
                            focus:ring-0
                        "
                        :placeholder="
                            selectedOptions.length
                                ? ''
                                : placeholder
                        "
                    />
                </div>

                <ComboboxTrigger
                    class="
                        ml-2
                        flex
                        size-7
                        shrink-0
                        items-center
                        justify-center
                        text-content/50

                        transition-colors

                        hover:text-content

                        focus:outline-none
                        focus-visible:text-primary

                        disabled:pointer-events-none
                        disabled:opacity-40
                    "
                    :disabled="disabled"
                    aria-label="Open options"
                >
                    <ChevronDown :size="16" />
                </ComboboxTrigger>
            </div>
        </ComboboxAnchor>

        <ComboboxPortal>
            <ComboboxContent
                position="popper"
                class="
                    z-50
                    mt-1
                    w-[var(--reka-combobox-trigger-width)]
                    overflow-hidden
                    border
                    border-border
                    bg-surface
                    text-content
                    shadow-sm
                "
            >
                <ComboboxViewport class="
                        max-h-64
                        overflow-y-auto
                        p-1
                    ">
                    <ComboboxEmpty
                        class="
                            px-3
                            py-7
                            text-center
                            text-sm
                            text-content/50
                        "
                    >
                        No options found.
                    </ComboboxEmpty>

                    <ComboboxItem
                        v-for="option in options"
                        :key="String(option.value)"
                        :value="option.value"
                        :disabled="option.disabled"
                    >
                        {{ option.label }}
                    </ComboboxItem>
                </ComboboxViewport>
            </ComboboxContent>
        </ComboboxPortal>
    </Combobox>
</template>