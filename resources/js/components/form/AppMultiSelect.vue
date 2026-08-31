<script setup lang="ts">
import { computed } from 'vue'
import { X, ChevronDown, Check } from '@lucide/vue'

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
}

const props = withDefaults(
    defineProps<Props>(),
    {
        modelValue: () => [],
        options: () => [],
        placeholder: 'Select options',
    },
)

const emit = defineEmits<{
    'update:modelValue': [value: Array<string | number>]
}>()

const model = computed({
    get: () => props.modelValue,
    set: value => emit('update:modelValue', value),
})

const selectedOptions = computed(() =>
    props.options.filter(option =>
        model.value.includes(option.value)
    )
)

function removeOption(value: string | number) {
    model.value = model.value.filter(item => item !== value)
}
</script>

<template>
    <Combobox
        v-model="model"
        multiple
    >
        <ComboboxAnchor class="w-full">
            <div
                class="
                    flex
                    min-h-10
                    w-full
                    items-center
                    rounded-md
                    border
                    bg-background
                    px-2
                    py-1

                    focus-within:ring-2
                    focus-within:ring-ring/20
                "
            >
                <!-- Selected values -->
                <div
                    v-if="selectedOptions.length"
                    class="
                        flex
                        min-w-0
                        flex-1
                        flex-wrap
                        items-center
                        gap-1
                    "
                >
                    <span
                        v-for="option in selectedOptions"
                        :key="option.value"
                        class="
                            inline-flex
                            max-w-full
                            items-center
                            gap-1
                            rounded-md
                            bg-muted
                            px-2
                            py-1
                            text-xs
                            font-medium
                            text-foreground
                        "
                    >
                        <span class="max-w-[160px] truncate">
                            {{ option.label }}
                        </span>

                        <button
                            type="button"
                            class="
                                shrink-0
                                rounded-sm
                                text-muted-foreground
                                transition-colors
                                hover:text-foreground
                            "
                            :aria-label="`Remove ${option.label}`"
                            @click.stop="removeOption(option.value)"
                        >
                            <X
                                :size="13"
                                :stroke-width="2"
                            />
                        </button>
                    </span>

                    <!-- Search -->
                    <ComboboxInput
                        class="
                            h-7
                            min-w-[80px]
                            flex-1
                            border-0
                            bg-transparent
                            px-1
                            py-1
                            text-sm
                            outline-none
                            focus:ring-0
                        "
                        :placeholder="selectedOptions.length ? '' : placeholder"
                    />
                </div>

                <!-- Empty -->
                <ComboboxInput
                    v-else
                    class="
                        h-8
                        min-w-0
                        flex-1
                        border-0
                        bg-transparent
                        px-1
                        text-sm
                        outline-none
                        placeholder:text-muted-foreground
                        focus:ring-0
                    "
                    :placeholder="placeholder"
                />

                <!-- Trigger -->
                <ComboboxTrigger
                    class="
                        group
                        ml-1
                        flex
                        size-7
                        shrink-0
                        items-center
                        justify-center
                        rounded-md
                        text-muted-foreground
                        transition-colors
                        hover:bg-muted
                        hover:text-foreground
                        focus-visible:outline-none
                        focus-visible:ring-2
                        focus-visible:ring-ring/30
                    "
                    aria-label="Open options"
                >
                    <ChevronDown
                        :size="16"
                        :stroke-width="1.8"
                        class="
                            transition-transform
                            duration-200
                            group-data-[state=open]:rotate-180
                        "
                    />
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
                    rounded-md
                    border
                    bg-popover
                    shadow-md
                "
            >
                <ComboboxViewport class="max-h-60 overflow-y-auto p-1">
                    <ComboboxEmpty>
                        No options found.
                    </ComboboxEmpty>

                    <ComboboxItem
                        v-for="option in props.options"
                        :key="option.value"
                        :value="option.value"
                        :disabled="option.disabled"
                    >
                        <span class="truncate">
                            {{ option.label }}
                        </span>
                    </ComboboxItem>
                </ComboboxViewport>
            </ComboboxContent>
        </ComboboxPortal>
    </Combobox>
</template>